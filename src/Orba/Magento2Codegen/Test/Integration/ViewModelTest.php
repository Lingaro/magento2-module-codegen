<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\ViewModel;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @see ViewModel
 */
class ViewModelTest extends TestCase
{
    public function testGeneratedViewModelCanBeUsedInLayout(): void
    {
        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $response = $client->request('GET', $_ENV['MAGENTO_BASE_URL']);
        $content = $response->getContent();
        $this->assertStringContainsString('<p id="test-view-model">bar</p>', $content);
    }
}