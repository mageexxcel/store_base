<?php

namespace Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer;

use Magento\Framework\DataObject;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_backendHelper;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Helper\Data $backendHelper
    ) {
        $this->_backendHelper = $backendHelper;
        parent::__construct($context);
    }
    public function render(DataObject $row)
    {
        $storebaseId = $row->getId();
        $storebaseUrl =  $this->_backendHelper->getUrl('storebase/index/edit', ['storebase_id' => $storebaseId]);

        if (!empty($storebaseId)) {
            $link = __('Edit');
            return '<a href="' . $storebaseUrl . '">' . $link . '</a>';
        } else {
            return false;
        }
    }
}
