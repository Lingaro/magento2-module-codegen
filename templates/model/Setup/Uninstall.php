<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        $setup->startSetup();

        if ($setup->tableExists('{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}')) {
            $setup->getConnection()->dropTable($setup->getTable('{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}'));
        }

        $setup->endSetup();
    }
}
