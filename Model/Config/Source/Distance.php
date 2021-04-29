<?php
namespace Excellence\Storebase\Model\Config\Source;

class Distance implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray() {
        return [
            [
                'value' => '0',
                'label' => __('Kilometers'),
            ],
            [
                'value' => 'mi',
                'label' => __('Miles'),
            ],
        ];
    }
}