<?php

namespace Pcxpress\Unifaun\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.3', '<')) {

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

            if (!$installer->tableExists('unifaun_pickuplocation')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('unifaun_pickuplocation')
                )
                    ->addColumn(
                        'pickuplocation_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'identity' => true,
                            'primary' => true,
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'pickuplocation_id'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'name'
                    )
                    ->addColumn(
                        'address',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'address'
                    )
                    ->addColumn(
                        'postcode',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'postcode'
                    )
                    ->addColumn(
                        'city',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'city'
                    )
                    ->addColumn(
                        'state',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'state'
                    )
                    ->addColumn(
                        'countrycode',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'countrycode'
                    )
                    ->addColumn(
                        'contact_person',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'contact_person'
                    )
                    ->addColumn(
                        'phone',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'phone'
                    )
                    ->addColumn(
                        'mobile',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'mobile'
                    )
                    ->addColumn(
                        'fax',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'fax'
                    )
                    ->addColumn(
                        'email',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => true],
                        'email'
                    );

                $installer->getConnection()->createTable($table);
            }


            if (!$installer->tableExists('unifaun_labels')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('unifaun_labels')
                )
                    ->addColumn(
                        'label_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        [
                            'identity' => true,
                            'primary' => true,
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'label_id'
                    )
                    ->addColumn(
                        'shipment_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'shipment_id'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        2,
                        ['nullable' => true],
                        'status'
                    )
                    ->addColumn(
                        'printed_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true],
                        'printed_at'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        [
                            'nullable' => true,
//                            'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                        ]
                    );

                $installer->getConnection()->createTable($table);
            }

        }

        $installer->endSetup();
    }
}