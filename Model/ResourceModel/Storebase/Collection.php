<?php

/**
 * Storebase Resource Collection
 */
namespace Excellence\Storebase\Model\ResourceModel\Storebase;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Excellence\Storebase\Model\Storebase', 'Excellence\Storebase\Model\ResourceModel\Storebase');
    }
}
