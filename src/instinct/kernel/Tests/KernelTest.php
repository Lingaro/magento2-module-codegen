<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instinct\Component\Kernel\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Instinct\Component\Kernel\Bundle\BundleInterface;
use Instinct\Component\Kernel\DependencyInjection\ResettableServicePass;
use Instinct\Component\Kernel\DependencyInjection\ServicesResetter;
use Instinct\Component\Kernel\Kernel;
use Instinct\Component\Kernel\Tests\Fixtures\KernelForOverrideName;
use Instinct\Component\Kernel\Tests\Fixtures\KernelForTest;
use Instinct\Component\Kernel\Tests\Fixtures\KernelWithoutBundles;
use Instinct\Component\Kernel\Tests\Fixtures\ResettableService;

class KernelTest extends TestCase
{
    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/Fixtures/var');
    }

    public function testConstructor()
    {
        $env = 'test_env';
        $debug = true;
        $kernel = new KernelForTest($env, $debug);

        $this->assertEquals($env, $kernel->getEnvironment());
        $this->assertEquals($debug, $kernel->isDebug());
        $this->assertFalse($kernel->isBooted());
        $this->assertLessThanOrEqual(microtime(true), $kernel->getStartTime());
        $this->assertNull($kernel->getContainer());
    }

    public function testClone()
    {
        $env = 'test_env';
        $debug = true;
        $kernel = new KernelForTest($env, $debug);

        $clone = clone $kernel;

        $this->assertEquals($env, $clone->getEnvironment());
        $this->assertEquals($debug, $clone->isDebug());
        $this->assertFalse($clone->isBooted());
        $this->assertLessThanOrEqual(microtime(true), $clone->getStartTime());
        $this->assertNull($clone->getContainer());
    }

    public function testInitializeContainerClearsOldContainers()
    {
        $fs = new Filesystem();
        $legacyContainerDir = __DIR__.'/Fixtures/var/cache/custom/ContainerA123456';
        $fs->mkdir($legacyContainerDir);
        touch($legacyContainerDir.'.legacy');

        $kernel = new CustomProjectDirKernel();
        $kernel->boot();

        $containerDir = __DIR__.'/Fixtures/var/cache/custom/'.substr(\get_class($kernel->getContainer()), 0, 16);
        $this->assertTrue(unlink(__DIR__.'/Fixtures/var/cache/custom/TestsInstinct_Component_Kernel_Tests_CustomProjectDirKernelCustomDebugContainer.php.meta'));
        $this->assertFileExists($containerDir);
        $this->assertFileNotExists($containerDir.'.legacy');

        $kernel = new CustomProjectDirKernel(function ($container) { $container->register('foo', 'stdClass')->setPublic(true); });
        $kernel->boot();

        $this->assertFileExists($containerDir);
        $this->assertFileExists($containerDir.'.legacy');

        $this->assertFileNotExists($legacyContainerDir);
        $this->assertFileNotExists($legacyContainerDir.'.legacy');
    }

    public function testBootInitializesBundlesAndContainer()
    {
        $kernel = $this->getKernel(['initializeBundles', 'initializeContainer']);
        $kernel->expects($this->once())
            ->method('initializeBundles');
        $kernel->expects($this->once())
            ->method('initializeContainer');

        $kernel->boot();
    }

    public function testBootSetsTheContainerToTheBundles()
    {
        $bundle = $this->getMockBuilder('Instinct\Component\Kernel\Bundle\Bundle')->getMock();
        $bundle->expects($this->once())
            ->method('setContainer');

        $kernel = $this->getKernel(['initializeBundles', 'initializeContainer', 'getBundles']);
        $kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnValue([$bundle]));

        $kernel->boot();
    }

    public function testBootSetsTheBootedFlagToTrue()
    {
        // use test kernel to access isBooted()
        $kernel = $this->getKernelForTest(['initializeBundles', 'initializeContainer']);
        $kernel->boot();

        $this->assertTrue($kernel->isBooted());
    }

    public function testClassCacheIsNotLoadedByDefault()
    {
        $kernel = $this->getKernel(['initializeBundles', 'initializeContainer', 'doLoadClassCache']);
        $kernel->expects($this->never())
            ->method('doLoadClassCache');

        $kernel->boot();
    }

    public function testBootKernelSeveralTimesOnlyInitializesBundlesOnce()
    {
        $kernel = $this->getKernel(['initializeBundles', 'initializeContainer']);
        $kernel->expects($this->once())
            ->method('initializeBundles');

        $kernel->boot();
        $kernel->boot();
    }

    public function testShutdownCallsShutdownOnAllBundles()
    {
        $bundle = $this->getMockBuilder('Instinct\Component\Kernel\Bundle\Bundle')->getMock();
        $bundle->expects($this->once())
            ->method('shutdown');

        $kernel = $this->getKernel([], [$bundle]);

        $kernel->boot();
        $kernel->shutdown();
    }

    public function testShutdownGivesNullContainerToAllBundles()
    {
        $bundle = $this->getMockBuilder('Instinct\Component\Kernel\Bundle\Bundle')->getMock();
        $bundle->expects($this->at(3))
            ->method('setContainer')
            ->with(null);

        $kernel = $this->getKernel(['getBundles']);
        $kernel->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue([$bundle]));

        $kernel->boot();
        $kernel->shutdown();
    }

    public function testStripComments()
    {
        $source = <<<'EOF'
<?php

$string = 'string should not be   modified';

$string = 'string should not be

modified';


$heredoc = <<<HD


Heredoc should not be   modified {$a[1+$b]}


HD;

$nowdoc = <<<'ND'


Nowdoc should not be   modified


ND;

/**
 * some class comments to strip
 */
class TestClass
{
    /**
     * some method comments to strip
     */
    public function doStuff()
    {
        // inline comment
    }
}
EOF;
        $expected = <<<'EOF'
<?php
$string = 'string should not be   modified';
$string = 'string should not be

modified';
$heredoc = <<<HD


Heredoc should not be   modified {$a[1+$b]}


HD;
$nowdoc = <<<'ND'


Nowdoc should not be   modified


ND;
class TestClass
{
    public function doStuff()
    {
        }
}
EOF;

        $output = Kernel::stripComments($source);

        // Heredocs are preserved, making the output mixing Unix and Windows line
        // endings, switching to "\n" everywhere on Windows to avoid failure.
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $expected = str_replace("\r\n", "\n", $expected);
            $output = str_replace("\r\n", "\n", $output);
        }

        $this->assertEquals($expected, $output);
    }

    /**
     * @group legacy
     */
    public function testGetRootDir()
    {
        $kernel = new KernelForTest('test', true);

        $this->assertEquals(__DIR__.\DIRECTORY_SEPARATOR.'Fixtures', realpath($kernel->getRootDir()));
    }

    /**
     * @group legacy
     */
    public function testGetName()
    {
        $kernel = new KernelForTest('test', true);

        $this->assertEquals('Fixtures', $kernel->getName());
    }

    /**
     * @group legacy
     */
    public function testOverrideGetName()
    {
        $kernel = new KernelForOverrideName('test', true);

        $this->assertEquals('overridden', $kernel->getName());
    }

    public function testSerialize()
    {
        $env = 'test_env';
        $debug = true;
        $kernel = new KernelForTest($env, $debug);

        $expected = 'O:54:"Instinct\\Component\\Kernel\\Tests\\Fixtures\\KernelForTest":2:{s:14:"'."\0".'*'."\0".'environment";s:8:"test_env";s:8:"'."\0".'*'."\0".'debug";b:1;}';
        $this->assertEquals($expected, serialize($kernel));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLocateResourceThrowsExceptionWhenNameIsNotValid()
    {
        $this->getKernel()->locateResource('Foo');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testLocateResourceThrowsExceptionWhenNameIsUnsafe()
    {
        $this->getKernel()->locateResource('@FooBundle/../bar');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLocateResourceThrowsExceptionWhenBundleDoesNotExist()
    {
        $this->getKernel()->locateResource('@FooBundle/config/routing.xml');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLocateResourceThrowsExceptionWhenResourceDoesNotExist()
    {
        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/Bundle1Bundle')))
        ;

        $kernel->locateResource('@Bundle1Bundle/config/routing.xml');
    }

    public function testLocateResourceReturnsTheFirstThatMatches()
    {
        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/Bundle1Bundle')))
        ;

        $this->assertEquals(__DIR__.'/Fixtures/Bundle1Bundle/foo.txt', $kernel->locateResource('@Bundle1Bundle/foo.txt'));
    }

    public function testLocateResourceIgnoresDirOnNonResource()
    {
        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/Bundle1Bundle')))
        ;

        $this->assertEquals(
            __DIR__.'/Fixtures/Bundle1Bundle/foo.txt',
            $kernel->locateResource('@Bundle1Bundle/foo.txt', __DIR__.'/Fixtures')
        );
    }

    public function testLocateResourceReturnsTheDirOneForResources()
    {
        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/FooBundle', null, null, 'FooBundle')))
        ;

        $this->assertEquals(
            __DIR__.'/Fixtures/Resources/FooBundle/foo.txt',
            $kernel->locateResource('@FooBundle/Resources/foo.txt', __DIR__.'/Fixtures/Resources')
        );
    }

    public function testLocateResourceOnDirectories()
    {
        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->exactly(2))
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/FooBundle', null, null, 'FooBundle')))
        ;

        $this->assertEquals(
            __DIR__.'/Fixtures/Resources/FooBundle/',
            $kernel->locateResource('@FooBundle/Resources/', __DIR__.'/Fixtures/Resources')
        );
        $this->assertEquals(
            __DIR__.'/Fixtures/Resources/FooBundle',
            $kernel->locateResource('@FooBundle/Resources', __DIR__.'/Fixtures/Resources')
        );

        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->exactly(2))
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/Bundle1Bundle', null, null, 'Bundle1Bundle')))
        ;

        $this->assertEquals(
            __DIR__.'/Fixtures/Bundle1Bundle/Resources/',
            $kernel->locateResource('@Bundle1Bundle/Resources/')
        );
        $this->assertEquals(
            __DIR__.'/Fixtures/Bundle1Bundle/Resources',
            $kernel->locateResource('@Bundle1Bundle/Resources')
        );
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to register two bundles with the same name "DuplicateName"
     */
    public function testInitializeBundleThrowsExceptionWhenRegisteringTwoBundlesWithTheSameName()
    {
        $fooBundle = $this->getBundle(null, null, 'FooBundle', 'DuplicateName');
        $barBundle = $this->getBundle(null, null, 'BarBundle', 'DuplicateName');

        $kernel = $this->getKernel([], [$fooBundle, $barBundle]);
        $kernel->boot();
    }

    public function testKernelWithoutBundles()
    {
        $kernel = new KernelWithoutBundles('test', true);
        $kernel->boot();

        $this->assertTrue($kernel->getContainer()->getParameter('test_executed'));
    }

    public function testProjectDirExtension()
    {
        $kernel = new CustomProjectDirKernel();
        $kernel->boot();

        $this->assertSame(__DIR__.'/Fixtures', $kernel->getProjectDir());
        $this->assertSame(__DIR__.\DIRECTORY_SEPARATOR.'Fixtures', $kernel->getContainer()->getParameter('kernel.project_dir'));
    }

    public function testKernelReset()
    {
        (new Filesystem())->remove(__DIR__.'/Fixtures/var/cache');

        $kernel = new CustomProjectDirKernel();
        $kernel->boot();

        $containerClass = \get_class($kernel->getContainer());
        $containerFile = (new \ReflectionClass($kernel->getContainer()))->getFileName();
        unlink(__DIR__.'/Fixtures/var/cache/custom/TestsInstinct_Component_Kernel_Tests_CustomProjectDirKernelCustomDebugContainer.php.meta');

        $kernel = new CustomProjectDirKernel();
        $kernel->boot();

        $this->assertInstanceOf($containerClass, $kernel->getContainer());
        $this->assertFileExists($containerFile);
        unlink(__DIR__.'/Fixtures/var/cache/custom/TestsInstinct_Component_Kernel_Tests_CustomProjectDirKernelCustomDebugContainer.php.meta');

        $kernel = new CustomProjectDirKernel(function ($container) { $container->register('foo', 'stdClass')->setPublic(true); });
        $kernel->boot();

        $this->assertNotInstanceOf($containerClass, $kernel->getContainer());
        $this->assertFileExists($containerFile);
        $this->assertFileExists(\dirname($containerFile).'.legacy');
    }

    public function testKernelPass()
    {
        $kernel = new PassKernel();
        $kernel->boot();

        $this->assertTrue($kernel->getContainer()->getParameter('test.processed'));
    }

    public function testServicesResetter()
    {
        $kernel = new CustomProjectDirKernel(function ($container) {
            $container->addCompilerPass(new ResettableServicePass());
            $container->register('one', ResettableService::class)
                ->setPublic(true)
                ->addTag('kernel.reset', ['method' => 'reset']);
            $container->register('services_resetter', ServicesResetter::class)->setPublic(true);
        }, 'resetting');

        ResettableService::$counter = 0;

        $kernel->boot();
        $kernel->executeScope();
        $kernel->getContainer()->get('one');

        $this->assertEquals(0, ResettableService::$counter);
        $this->assertFalse($kernel->getContainer()->initialized('services_resetter'));

        $kernel->boot();
        $kernel->executeScope();

        $this->assertEquals(1, ResettableService::$counter);
    }

    /**
     * @group time-sensitive
     */
    public function testKernelStartTimeIsResetWhileBootingAlreadyBootedKernel()
    {
        $kernel = $this->getKernelForTest(['initializeBundles'], true);
        $kernel->boot();
        $preReBoot = $kernel->getStartTime();

        sleep(3600); //Intentionally large value to detect if ClockMock ever breaks
        $kernel->reboot(null);

        $this->assertGreaterThan($preReBoot, $kernel->getStartTime());
    }

    /**
     * Returns a mock for the BundleInterface.
     *
     * @return BundleInterface
     */
    protected function getBundle($dir = null, $parent = null, $className = null, $bundleName = null)
    {
        $bundle = $this
            ->getMockBuilder('Instinct\Component\Kernel\Bundle\BundleInterface')
            ->setMethods(['getPath', 'getParent', 'getName'])
            ->disableOriginalConstructor()
        ;

        if ($className) {
            $bundle->setMockClassName($className);
        }

        $bundle = $bundle->getMockForAbstractClass();

        $bundle
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(null === $bundleName ? \get_class($bundle) : $bundleName))
        ;

        $bundle
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($dir))
        ;

        $bundle
            ->expects($this->any())
            ->method('getParent')
            ->will($this->returnValue($parent))
        ;

        return $bundle;
    }

    /**
     * Returns a mock for the abstract kernel.
     *
     * @param array $methods Additional methods to mock (besides the abstract ones)
     * @param array $bundles Bundles to register
     *
     * @return Kernel
     */
    protected function getKernel(array $methods = [], array $bundles = [])
    {
        $methods[] = 'registerBundles';

        $kernel = $this
            ->getMockBuilder('Instinct\Component\Kernel\Kernel')
            ->setMethods($methods)
            ->setConstructorArgs(['test', false])
            ->getMockForAbstractClass()
        ;
        $kernel->expects($this->any())
            ->method('registerBundles')
            ->will($this->returnValue($bundles))
        ;
        $p = new \ReflectionProperty($kernel, 'rootDir');
        $p->setAccessible(true);
        $p->setValue($kernel, __DIR__.'/Fixtures');

        return $kernel;
    }

    protected function getKernelForTest(array $methods = [], $debug = false)
    {
        $kernel = $this->getMockBuilder('Instinct\Component\Kernel\Tests\Fixtures\KernelForTest')
            ->setConstructorArgs(['test', $debug])
            ->setMethods($methods)
            ->getMock();
        $p = new \ReflectionProperty($kernel, 'rootDir');
        $p->setAccessible(true);
        $p->setValue($kernel, __DIR__.'/Fixtures');

        return $kernel;
    }
}

class CustomProjectDirKernel extends Kernel
{
    private $baseDir;
    private $buildContainer;

    public function __construct(\Closure $buildContainer = null, $env = 'custom')
    {
        parent::__construct($env, true);

        $this->buildContainer = $buildContainer;
    }

    public function registerBundles()
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }

    public function getProjectDir()
    {
        return __DIR__.'/Fixtures';
    }

    public function executeScope()
    {
        $this->enterScope();

        $this->leaveScope();
    }

    protected function build(ContainerBuilder $container)
    {
        if ($build = $this->buildContainer) {
            $build($container);
        }
    }
}

class PassKernel extends CustomProjectDirKernel implements CompilerPassInterface
{
    public function __construct()
    {
        parent::__construct();
        Kernel::__construct('pass', true);
    }

    public function process(ContainerBuilder $container)
    {
        $container->setParameter('test.processed', true);
    }
}
