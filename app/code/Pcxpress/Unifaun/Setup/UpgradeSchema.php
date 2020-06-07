<?php

namespace Pcxpress\Unifaun\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if(version_compare($context->getVersion(), '1.0.3', '<')) {

            if (!$installer->tableExists('unifaun_shippingrates')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('unifaun_shippingrates')
                )
                    ->addColumn(
                        'shippingrate_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'identity' => true,
                            'primary' => true,
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'shippingrate_id'
                    )
                    ->addColumn(
                        'shippingmethod_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'shippingmethod_id'
                    )
                    ->addColumn(
                        'max_weight',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'max_weight'
                    )
                    ->addColumn(
                        'max_width',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'max_width'
                    )
                    ->addColumn(
                        'max_height',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'max_height'
                    )
                    ->addColumn(
                        'max_depth',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'max_depth'
                    )
                    ->addColumn(
                        'shipping_fee',
                        \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                        10,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'shipping_fee'
                    )
                    ->addColumn(
                        'zipcode_range',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'zipcode_range'
                    )
                    ->addColumn(
                        'countries',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'countries'
                    )
                    ->addColumn(
                        'website_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'website_id'
                    );

                $installer->getConnection()->createTable($table);
            }
        }

        $installer->endSetup();
    }
}