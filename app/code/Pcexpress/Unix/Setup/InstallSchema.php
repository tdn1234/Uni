<?php

namespace Pcexpress\Unix\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

//		$installer->run('SET NAMES utf8');
//$installer->run('SET time_zone = \'+00:00\'');
//$installer->run('SET foreign_key_checks = 0');
//$installer->run('SET sql_mode = \'NO_AUTO_VALUE_ON_ZERO\'');
//$installer->run('CREATE TABLE `unifaun_labels` (
//  `label_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//  `shipment_id` int(10) unsigned NOT NULL,
//  `status` int(1) NOT NULL,
//  `printed_at` datetime DEFAULT NULL,
//  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`label_id`),
//  KEY `UNLBLSHPMTFK` (`shipment_id`) USING BTREE,
//  CONSTRAINT `UNLBLSHPMTFK` FOREIGN KEY (`shipment_id`) REFERENCES `sales_flat_shipment` (`entity_id`) ON DELETE CASCADE ON UPDATE NO ACTION
//) ENGINE=InnoDB DEFAULT CHARSET=utf8');


		//demo
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/updaterates.log');
//$logger = new \Zend\Log\Logger();
//$logger->addWriter($writer);
//$logger->info('updaterates');
//demo 

		}

        $installer->endSetup();

    }
}