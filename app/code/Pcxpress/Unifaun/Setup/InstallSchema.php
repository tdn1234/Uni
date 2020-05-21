<?php

namespace Pcxpress\Unifaun\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('unifaun_shippingmethods')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('unifaun_shippingmethods')
            )
                ->addColumn(
                    'shippingmethod_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'shippingmethod_id'
                )
                ->addColumn(
                    'title',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'title'
                )
                ->addColumn(
                    'template_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'template_name'
                )
                ->addColumn(
                    'carrier',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    [],
                    'carrier'
                )
                ->addColumn(
                    'carrier_method',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    [],
                    'carrier_method'
                )
                ->addColumn(
                    'last_booking_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    1,
                    [],
                    'last_booking_time'
                )
                ->addColumn(
                    'featured_image',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Post Featured Image'
                )
                ->addColumn(
                    'description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'description'
                )->addColumn(
                    'email_confirmation_text',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'email_confirmation_text')
                ->addColumn(
                    'email_confirmation_text',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'email_confirmation_text')
                ->addColumn(
                    'free_shipping_enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 0],
                    'free_shipping_enable')
                ->addColumn(
                    'free_shipping_subtotal',
                    \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    null,
                    ['nullable' => true],
                    'free_shipping_subtotal')
                ->addColumn(
                    'handling_fee',
                    \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    null,
                    ['nullable' => true],
                    'handling_fee')
                ->addColumn(
                    'frontend_visibility',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 1],
                    'frontend_visibility')
                ->addColumn(
                    'shipping_group',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'shipping_group')
                ->addColumn(
                    'no_booking',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 0],
                    'no_booking')
                ->addColumn(
                    'label_only',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 0],
                    'label_only')
                ->addColumn(
                    'active',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 1],
                    'active')
                ->addColumn(
                    'min_consignment_weight',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true, 'default' => 0],
                    'min_consignment_weight')
                ->addColumn(
                    'max_consignment_weight',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true, 'default' => 999999],
                    'max_consignment_weight')
                ->addColumn(
                    'unification_enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    11,
                    ['nullable' => true, 'default' => 0],
                    'unification_enable')
                ->addColumn(
                    'unification_priority',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    11,
                    ['nullable' => true, 'default' => 0],
                    'unification_priority')
                ->addColumn(
                    'unification_product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    11,
                    ['nullable' => true, 'default' => 0],
                    'unification_product_id')
                ->addColumn(
                    'multiple_parcels',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    11,
                    ['nullable' => true, 'default' => 0],
                    'multiple_parcels')
                ->addColumn(
                    'advice_default',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    20,
                    ['nullable' => true],
                    'advice_default')
                ->addColumn(
                    'insurance_enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 0],
                    'insurance_enable')
                ->addColumn(
                    'shipping_service',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    3,
                    ['nullable' => true, 'default' => 0],
                    'shipping_service')
                ->setComment('Shipping Table');
            $installer->getConnection()->createTable($table);

//            $installer->getConnection()->addIndex(
//                $installer->getTable('mageplaza_helloworld_post'),
//                $setup->getIdxName(
//                    $installer->getTable('mageplaza_helloworld_post'),
//                    ['name', 'url_key', 'post_content', 'tags', 'featured_image'],
//                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
//                ),
//                ['name', 'url_key', 'post_content', 'tags', 'featured_image'],
//                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
//            );
        }
        $installer->endSetup();
    }
}