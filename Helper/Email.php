<?php

/**
 * Storebase data helper
 */
namespace Excellence\Storebase\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    const EMAIL_TEMPLATE = 'dealerlocator/template/';

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $inlineTranslation;

    protected $transportBuilder;
    
    /** @var Excellence\Storebase\Helper\Data\Data */
    protected $_dataHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Excellence\Storebase\Helper\Data $dataHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->_dataHelper = $dataHelper;
        $this->_scopeConfigObject = $context->getscopeConfig();
        parent::__construct($context);
    }

    // Store Approval Email to Dealer
    public function sendApprovalNotification($data) {
        $url = $this->_storeManager->getStore()->getUrl('storelocator/index');
        $customerData = $this->_dataHelper->getStoreLocationsById($data['storebase_id']);
        $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->_storeManager->getStore()->getId());
        $transport = array (
            'store' => $this->_storeManager->getStore()->getName(),
            'customer_name' => $customerData['firstname'].' '.$customerData['lastname'],
            'store_name' => $data['store_name'],
            'dealer_locator' => $url,
        );
        
        $transport = new \Magento\Framework\DataObject($transport);
        $from = array('email' => $this->_dataHelper->getSenderEmailId(), 'name' => $this->_dataHelper->getSenderName());
        $this->inlineTranslation->suspend();
        $to = $customerData['email'];
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $templateId = $this->_scopeConfigObject->getValue(self::EMAIL_TEMPLATE.'store_approval', $storeScope);
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($transport->getData())
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}