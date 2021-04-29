<?php

namespace Excellence\Storebase\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
	
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Excellence_Storebase::storebase_manage');
    }

    /**
     * Storebase List action
     *
     * @return void
     */
    public function execute() {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(
            'Excellence_Storebase::storebase_manage'
        )->addBreadcrumb(
            __('Storebase'),
            __('Storebase')
        )->addBreadcrumb(
            __('Manage Storebase'),
            __('Manage Storebase')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Storebase'));
        return $resultPage;
    }
}
