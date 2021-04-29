<?php

namespace Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer;

use Magento\Framework\DataObject;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_dataHelper;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Excellence\Storebase\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context);
    }
    public function render(DataObject $row)
    {
        $storebaseId = $row->getId();
        if (!empty($storebaseId)) {
            $storeData = $this->_dataHelper->getStoresByStoreId($storebaseId);
            if ($storeData['status'] == 1) {
                $label = __('Enabled');
                return $label;
            } else {
                $label = __('Disabled');
                return $label;
            }
        } else {
            return false;
        }
    }
}
