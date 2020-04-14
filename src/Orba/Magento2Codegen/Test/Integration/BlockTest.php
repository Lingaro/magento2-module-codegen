<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\Block;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @see Block
 */
class BlockTest extends TestCase
{
    public function testGeneratedBlockOutputsContentWhenUsedInLayout(): void
    {
        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $response = $client->request('GET', $_ENV['MAGENTO_BASE_URL']);
        $content = $response->getContent();
        $this->assertStringContainsString('<p id="test-block">bar</p>', $content);
    }
}