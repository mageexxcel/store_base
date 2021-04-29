<?php
namespace Excellence\Storebase\Model\Config\Source;

class Handling implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray() {
        return [
            [
                'value' => '0',
                'label' => __('Fixed'),
            ],
            [
                'value' => '1',
                'label' => __('Percent'),
            ],
        ];
    }
}