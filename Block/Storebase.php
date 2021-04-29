<?php

namespace Excellence\Storebase\Block;

/**
 * Storebase content block
 */
class Storebase extends \Magento\Framework\View\Element\Template
{
    /**
     * Storebase collection
     *
     * @var Excellence\Storebase\Model\ResourceModel\Storebase\Collection
     */
    protected $_storebaseCollection = null;
    
    /**
     * Storebase factory
     *
     * @var \Excellence\Storebase\Model\StorebaseFactory
     */
    protected $_storebaseCollectionFactory;
    
    /** @var \Excellence\Storebase\Helper\Data */
    protected $_dataHelper;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Excellence\Storebase\Model\ResourceModel\Storebase\CollectionFactory $storebaseCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Excellence\Storebase\Model\ResourceModel\Storebase\CollectionFactory $storebaseCollectionFactory,
        \Excellence\Storebase\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_storebaseCollectionFactory = $storebaseCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct(
            $context,
            $data
        );
    }
    
    /**
     * Retrieve storebase collection
     *
     * @return Excellence\Storebase\Model\ResourceModel\Storebase\Collection
     */
    protected function _getCollection()
    {
        $collection = $this->_storebaseCollectionFactory->create();
        return $collection;
    }
    
    /**
     * Retrieve prepared storebase collection
     *
     * @return Excellence\Storebase\Model\ResourceModel\Storebase\Collection
     */
    public function getCollection()
    {
        if (is_null($this->_storebaseCollection)) {
            $this->_storebaseCollection = $this->_getCollection();
            $this->_storebaseCollection->setCurPage($this->getCurrentPage());
            $this->_storebaseCollection->setPageSize($this->_dataHelper->getStorebasePerPage());
            $this->_storebaseCollection->setOrder('published_at','asc');
        }

        return $this->_storebaseCollection;
    }
    
    /**
     * Fetch the current page for the storebase list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }
    
    /**
     * Return URL to item's view page
     *
     * @param Excellence\Storebase\Model\Storebase $storebaseItem
     * @return string
     */
    public function getItemUrl($storebaseItem)
    {
        return $this->getUrl('*/*/view', array('id' => $storebaseItem->getId()));
    }
    
    /**
     * Return URL for resized Storebase Item image
     *
     * @param Excellence\Storebase\Model\Storebase $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return $this->_dataHelper->resize($item, $width);
    }
    
    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
        $pager = $this->getChildBlock('storebase_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $storebasePerPage = $this->_dataHelper->getStorebasePerPage();

            $pager->setAvailableLimit([$storebasePerPage => $storebasePerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(TRUE);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );

            return $pager->toHtml();
        }

        return NULL;
    }
}
