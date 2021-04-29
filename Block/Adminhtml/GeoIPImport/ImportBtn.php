<?php
/**
 * Adminhtml orderexport list block
 *
 */
namespace Excellence\Storebase\Block\Adminhtml\GeoIPImport;

class ImportBtn extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
 
    protected function _prepareLayout() {
        $addButtonProps = [
            'id' => 'Geo_IP_Import',
            'label' => __('GeoIPImport'),
            'class' => 'add',
            'button_class' => '',
            'onclick' => "setLocation(window.location.href = '".$this->getUrl('*/geoipimport/import')."')"
        ];
        $this->buttonList->add('geo_ip', $addButtonProps);
        return parent::_prepareLayout();
    }
}