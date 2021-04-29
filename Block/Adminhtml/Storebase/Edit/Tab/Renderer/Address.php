<?php

namespace Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer;

use Magento\Framework\DataObject;

class Address extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
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
            if($storeData['street_two']) {
                $street = $storeData['street_one'].'<br />'.$storeData['street_two'];
            } else {
                $street = $storeData['street_one'];
            }
            $storeAddress = $street.'<br />'.$storeData['city'].'<br />'.$storeData['region_id'].' - '.$storeData['zipcode'].'<br />'.$this->_dataHelper->getCountryname($storeData['country']);
            return $storeAddress;
        } else {
            return false;
        }
    }
}
