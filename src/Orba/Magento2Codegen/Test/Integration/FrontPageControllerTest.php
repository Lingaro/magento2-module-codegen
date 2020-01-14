<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\FrontPageController;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @see FrontPageController
 */
class FrontPageControllerTest extends TestCase
{
    public function testGeneratedPageIsAccessibleAndContainsCorrectElements(): void
    {
        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        //frontName controllerName actionName
        $response = $client->request('GET', $_ENV['MAGENTO_BASE_URL'] . 'foo_bar/foobarpage/foo');
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        $this->assertSame(200, $statusCode);
        $this->assertStringContainsString('page-layout-1column', $content);
        $this->assertStringContainsString('<p id="front-page-test">bar</p>', $content);
    }
}