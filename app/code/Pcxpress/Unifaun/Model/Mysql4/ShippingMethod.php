<?php
/**
 * @category   PC  xpressPCXpress AB
 * @package    Pcxpress_Unifaun
 * @copyright  Copyright (c) 2017 PCXpress AB
 * @author     PCXpress AB Developer <info@pcxpress.se>
 * @license    http://pcxpress.se/magento/license.txt
 */
namespace Pcxpress\Unifaun\Model\Mysql4;

class ShippingMethod extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct(
            $context,
            $connectionName
        );
    }


	public function _construct()
	{
		$this->_init('unifaun/shippingMethod', \Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID);
	}
	
	/**
	 * @param \Magento\Framework\Model\AbstractModel $object
	 * @return \Magento\Framework\Model\ModelResource\Db\AbstractDb
	 */
	protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
	{
		// Store
		$table = $this->getTable('unifaun/shippingMethods_has_stores');
		$select = $this->_getReadAdapter()->select()
							->from($table, '*')
							->where(\Pcxpress\Unifaun\Helper\Data::SHIPPINGMETHOD_ID . ' = ?', $object->getId());

		if ($data = $this->_getReadAdapter()->fetchAll($select)) {
			$stores = array();
			
			foreach ($data as $row) {
					$stores[] = $row['store_id'];
			}
			
			$object->setData('store_ids', $stores);
		}

		return parent::_afterLoad($object);
	}

	/*protected function _beforeDelete(Mage_Core_Model_Abstract $object)
	{
			parent::_beforeDelete($object);

			$condition = $this->_getWriteAdapter()->quoteInto('shippingmethod_id = ?', $object->getId());

			// Store
			$this->_getWriteAdapter()->delete($this->getTable('unifaun/shippingMethod_store'), $condition);

			// Price
			$this->_getWriteAdapter()->delete($this->getTable('unifaun/shippingRate'), $condition);
	}*/

	protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
	{
		$table = $this->getTable('unifaun/shippingMethods_has_stores');
		
		$condition = $this->_getWriteAdapter()->quoteInto('shippingmethod_id = ?', $object->getId());
		
		$this->_getWriteAdapter()->delete($table, $condition);

		foreach ((array) $object->getData('store_ids') as $store) {
			$stores = array();
			$stores['shippingmethod_id'] = $object->getId();
			$stores['store_id'] = $store;
			$this->_getWriteAdapter()->insert($table, $stores);
		}

		return parent::_afterSave($object);
	}

	/**
	 * Retrieve select object for load object data
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param \Magento\Framework\Model\AbstractModel $object
	 * @return \Zend_Db_Select
	 */
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = parent::_getLoadSelect($field, $value, $object);

		$table = $this->getTable('unifaun/shippingMethods_has_stores');
		$mainTable = $this->getMainTable();
		if ($object->getStoreId()) {
			$select->join(array('unifaun_method_has_store' => $table), $mainTable . '.shippingmethod_id = unifaun_method_has_store.shippingmethod_id')
				->where('active = 1 AND unifaun_method_has_store.store_id in (0, ?) ', $object->getStoreIds())
				->order('store_id DESC')
				->limit(1);
		}

		return $select;
	}
}