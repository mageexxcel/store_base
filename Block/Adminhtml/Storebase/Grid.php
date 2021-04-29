<?php
namespace Excellence\Storebase\Block\Adminhtml\Storebase;

/**
 * Adminhtml Storebase grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Excellence\Storebase\Model\ResourceModel\Storebase\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Excellence\Storebase\Model\Storebase
     */
    protected $_storebase;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Excellence\Storebase\Model\Storebase $storebasePage
     * @param \Excellence\Storebase\Model\ResourceModel\Storebase\CollectionFactory $collectionFactory
     * @param \Magento\Core\Model\PageLayout\Config\Builder $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Excellence\Storebase\Model\Storebase $storebase,
        \Excellence\Storebase\Model\ResourceModel\Storebase\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_storebase = $storebase;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('storebaseGrid');
        $this->setDefaultSort('storebase_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection() {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Excellence\Storebase\Model\ResourceModel\Storebase\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {
        $this->addColumn(
            'store_name', 
            array(
                'header' => __('Store Name'), 
                'index' => 'store_name',
                'filter' => false,
                'sortable' => false,
            )
        );
        $this->addColumn(
            'store_address', 
            array(
                'header' => __('Store Address'),
                'renderer'  => 'Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer\Address',
                'filter' => false,
                'sortable' => false,
            )
        );
        $this->addColumn(
            'Number', 
            array(
                'header' => __('Phone Number'), 
                'index' => 'number',
                'filter' => false,
                'sortable' => false,
            )
        );
        $this->addColumn(
            'status', 
            array(
                'header' => __('Status'),
                'renderer'  => 'Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer\Status',
                'filter' => false,
                'sortable' => false,
            )
        );
        $this->addColumn(
            'action', 
            array(
                'header' => __('Action'),
                'renderer'  => 'Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab\Renderer\Action',
                'filter' => false,
                'sortable' => false,
                'filter' => false,
                'sortable' => false,
            )
        );
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        

        return parent::_prepareColumns();
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}
