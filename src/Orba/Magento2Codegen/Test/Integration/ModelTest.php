<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\Model;

/**
 * @see Model
 */
class ModelTest extends TestCase
{
    public function testGeneratedDbSchemaCreatesDatabaseTableWithCorrectColumns(): void
    {
        $result = MysqlConnection::getInstance()->query('DESCRIBE codegen_model_llama')->fetchAll();

        $this->assertSame('entity_id', $result[0]['Field']);
        $this->assertSame('int(10) unsigned', $result[0]['Type']);
        $this->assertSame('NO', $result[0]['Null']);
        $this->assertSame('PRI', $result[0]['Key']);
        $this->assertSame('auto_increment', $result[0]['Extra']);

        $this->assertSame('name', $result[1]['Field']);
        $this->assertSame('varchar(32)', $result[1]['Type']);
        $this->assertSame('NO', $result[1]['Null']);

        $this->assertSame('points', $result[2]['Field']);
        $this->assertSame('decimal(6,3) unsigned', $result[2]['Type']);
        $this->assertSame('YES', $result[2]['Null']);
    }

    public function testGeneratedRepositoryWorksCorrectly()
    {
        $result = MysqlConnection::getInstance()->query('SELECT * FROM codegen_model_llama')->fetchAll();

        $this->assertCount(1, $result);
        $this->assertSame('Bar', $result[0]['name']);
        $this->assertSame('654.321', $result[0]['points']);
    }
}