<?php

namespace Excellence\Storebase\Block;

class View extends \Magento\Framework\View\Element\Template
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /** @var \Excellence\Storebase\Helper\Data */
    protected $_dataHelper;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Excellence\Storebase\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->httpContext = $httpContext;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _prepareLayout() {
        $this->pageConfig->getTitle()->set($this->getStorebase()->getTitle());
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Excellence\Storebase\Model\Storebase
     */
    public function getStorebase() {
        return $this->_coreRegistry->registry('current_storebase');
    }

    /**
     * Return back url for logged in and guest users
     *
     * @return string
     */
    public function getBackUrl() {
        return $this->getUrl('storebase/index/index');
    }

    /**
     * Return back title for logged in and guest users
     *
     * @return string
     */
    public function getBackTitle() {
        if ($this->httpContext->getValue(Context::CONTEXT_AUTH)) {
            return __('Back to My Orders');
        }
        return __('View Another Order');
    }
    
    /**
     * Return URL for resized Storebase Item image
     *
     * @param \Excellence\Storebase\Model\Storebase $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width) {
        return $this->_dataHelper->resize($item, $width);
    }
}
