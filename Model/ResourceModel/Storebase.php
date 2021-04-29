<?php

namespace Excellence\Storebase\Model\ResourceModel;

/**
 * Storebase Resource Model
 */
class Storebase extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('excellence_storebase', 'storebase_id');
    }
}
