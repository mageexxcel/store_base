<?php

/**
 * Storebase data helper
 */
namespace Excellence\Storebase\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

use Magento\Framework\App\Config\ScopeConfigInterface;

use Excellence\Storebase\Model\Storebase;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Path to store config where count of storebase posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE     = 'storebase/view/items_per_page';
    
    const XML_PATH_EMAIL_SENDER_NAME = 'trans_email/ident_general/name';
    
    const XML_PATH_EMAIL_SENDER = 'trans_email/ident_general/email';

    const XML_PATH_GOOGLEMAP = 'googleMap/setting/active';
    /**
     * Media path to extension images
     *
     * @var string
     */
    const MEDIA_PATH    = 'Storebase';

    /**
     * Maximum size for image in bytes
     * Default value is 1M
     *
     * @var int
     */
    const MAX_FILE_SIZE = 1048576;

    /**
     * Manimum image height in pixels
     *
     * @var int
     */
    const MIN_HEIGHT = 50;

    /**
     * Maximum image height in pixels
     *
     * @var int
     */
    const MAX_HEIGHT = 800;

    /**
     * Manimum image width in pixels
     *
     * @var int
     */
    const MIN_WIDTH = 50;

    /**
     * Maximum image width in pixels
     *
     * @var int
     */
    const MAX_WIDTH = 1024;

    /**
     * Array of image size limitation
     *
     * @var array
     */
    protected $_imageSize   = array(
        'minheight'     => self::MIN_HEIGHT,
        'minwidth'      => self::MIN_WIDTH,
        'maxheight'     => self::MAX_HEIGHT,
        'maxwidth'      => self::MAX_WIDTH,
    );
    
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\HTTP\Adapter\FileTransferFactory
     */
    protected $httpFactory;
    
    /**
     * File Uploader factory
     *
     * @var \Magento\Core\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    
    /**
     * File Uploader factory
     *
     * @var \Magento\Framework\Io\File
     */
    protected $_ioFile;
    
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Http Request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    protected $_scopeConfigObject;

    protected $storeCollection;

    protected $_customerSession;

    /**
     * Customer Group
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroup;

    protected $_customerFactory;

    protected $_storebase;

    protected $_countryCollectionFactory;

    protected $_countryFactory;

    protected $scopeConfig;
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod,
        \Excellence\Storebase\Model\ResourceModel\Storebase\Collection $storeCollection,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Excellence\Storebase\Model\Storebase $storebase,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $context->getscopeConfig();
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
        $this->_request = $request;
        $this->shippingMethod = $shippingMethod;
        $this->storeCollection = $storeCollection;
        $this->_customerSession = $customerSession;
        $this->_customerGroup = $customerGroup;
        $this->_customerFactory = $customerFactory;
        $this->_storebase = $storebase;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_countryFactory = $countryFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }
    
    /**
     * Remove Storebase item image by image filename
     *
     * @param string $imageFile
     * @return bool
     */
    public function removeImage($imageFile) {
        $io = $this->_ioFile;
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }
    
    /**
     * Return URL for resized Storebase Item Image
     *
     * @param Excellence\Storebase\Model\Storebase $item
     * @param integer $width
     * @param integer $height
     * @return bool|string
     */
    public function resize(Storebase $item, $width, $height = null) {
        if (!$item->getImage()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }
        $width = (int)$width;

        if (!is_null($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }
            $height = (int)$height;
        }

        $imageFile = $item->getImage();
        $cacheDir  = $this->getBaseDir() . '/' . 'cache' . '/' . $width;
        $cacheUrl  = $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';

        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(array('path' => $cacheDir));
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = $this->_imageFactory->create($this->getBaseDir() . '/' . $imageFile);
            $image->resize($width, $height);
            $image->save($cacheDir . '/' . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Upload image and return uploaded image file name or false
     *
     * @throws Mage_Core_Exception
     * @param string $scope the request key for file
     * @return bool|string
     */
    public function uploadImage($scope) {
        $adapter = $this->httpFactory->create();
        $adapter->addValidator(new \Zend_Validate_File_ImageSize($this->_imageSize));
        $adapter->addValidator(
            new \Zend_Validate_File_FilesSize(['max' => self::MAX_FILE_SIZE])
        );
        
        if ($adapter->isUploaded($scope)) {
            // validate image
            if (!$adapter->isValid($scope)) {
                throw new \Magento\Framework\Model\Exception(__('Uploaded image is not valid.'));
            }
            
            $uploader = $this->_fileUploaderFactory->create(['fileId' => $scope]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->setAllowCreateFolders(true);
            
            if ($uploader->save($this->getBaseDir())) {
                return $uploader->getUploadedFileName();
            }
        }
        return false;
    }
    
    /**
     * Return the base media directory for Storebase Item images
     *
     * @return string
     */
    public function getBaseDir() {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(self::MEDIA_PATH);
        return $path;
    }
    
    /**
     * Return the Base URL for Storebase Item images
     *
     * @return string
     */
    public function getBaseUrl() { 
        return $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . '/' . self::MEDIA_PATH;
    }
    
    /**
     * Return the number of items per page
     * @return int
     */
    public function getStorebasePerPage() {
        return abs((int)$this->_scopeConfig->getValue(self::XML_PATH_ITEMS_PER_PAGE, ScopeInterface::SCOPE_STORE));
    }

    public function getStoreName() {
        return $this->_storeManager->getStore()->getName();
    }

    public function getStoreId() {
        return $this->_storeManager->getStore()->getStoreId();
    }

    public function getStoreLatLong() {
        return $this->_request->getParams();
    }

    public function getDlat(){
        $configValueDlat = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'/latitude', ScopeInterface::SCOPE_STORE);
        return  $configValueDlat;
    }

    public function getDlong(){
        $configValueDlong = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'/longitude', ScopeInterface::SCOPE_STORE);
        return  $configValueDlong;
    }

    public function getApi(){
        $apiKey = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'/api', ScopeInterface::SCOPE_STORE);
        if($this->getConfig(self::XML_PATH_GOOGLEMAP)){
            return  $apiKey;
        }
    }

    public function getDistanceUnit(){
        $unit = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'/distance', ScopeInterface::SCOPE_STORE);
        return $unit;
    }

    public function getRadius(){
        $radius = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'/radius', ScopeInterface::SCOPE_STORE);
        return $radius;
    }

    public function getCurrentShippingMethod() {
        $methodTitle = $this->shippingMethod->getMethodTitle();
        return $methodTitle;
    }

    public function getPageTitile() {
        $pageTitle = $this->_scopeConfig->getValue(Storebase::GOOGLE_SETTING.'store_title', ScopeInterface::SCOPE_STORE);
        return $pageTitle;
    }

    // Get the storename for specific storelocator page on the basis of lat long
    public function getCurrentStoreName() {
        $currentStoreName = '';
        $allStores = $this->storeCollection->getData();
        $paramsLatLong = $this->getStoreLatLong();
        // Filter the store data of the selected storepickup on the basis of lat long
        foreach ($allStores as $data) {
            if (($data['latitude'] == $paramsLatLong["lat"]) && ($data['longitude'] == $paramsLatLong["long"])) {
                // Assign the name of selected storepickup to show on the top of the page
                $currentStoreName = $data["store_name"];
            }
        }
        // return current storepickup name
        return $currentStoreName;
    }

    public function isCustomerLoggedIn() {
        return $this->_customerSession->isLoggedIn();
    }

    public function getCurrentCustomerId() {
        if ($this->isCustomerLoggedIn()) {
            return $this->_customerSession->getCustomer()->getId();
        }
    }

    public function getAvailableStores() {
        $allStores = $this->storeCollection->getData();
        $availableStores = [];
        foreach($allStores as $stores) {
            if ($stores['status'] == 1) {
                if ((array_key_exists("is_dealer",$stores) && ($stores['is_dealer'] == 1))) {
                    if ($stores['is_approved'] == 1) {
                        $availableStores[] = $stores;
                    }
                } else {
                    $availableStores[] = $stores;
                }
            }
        }
        return $availableStores;
    }

    public function getCustomerStoreData() {
        $allStores = $this->storeCollection->getData();
        $customerId = $this->getCurrentCustomerId();
        $customerStores = [];
        foreach ($allStores as $stores) {
            if($stores['customer_id'] == $customerId) {
                $customerStores[] = $stores;
            }
        }
        return $customerStores;
    }

    public function getEditStorebaseId() {
        $params = $this->_request->getParams();
        if ($params) {
            return $params['store'];
        }
    }

    public function getStoreDataById() {
        $allStores = $this->storeCollection->getData();
        $customerStores = [];
        foreach ($allStores as $stores) {
            if($stores['storebase_id'] == $this->getEditStorebaseId()) {
                $customerStores = $stores;
            }
        }
        return $customerStores;
    }

    public function getDealerGroupId() {
        $customerGroups = $this->_customerGroup->toOptionArray();
        foreach ($customerGroups as $groups) {
            if($groups['label'] == 'Dealer')
            return $groups['value'];
        }
    }

    public function getCurrentCustomerGroupId() {
        if ($this->isCustomerLoggedIn()) {
            return $this->_customerSession->getCustomer()->getGroupId();
        }
    }

    public function getSenderName($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER_NAME,
            $scope
        );
    }

    public function getSenderEmailId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            $scope
        );
    }

    public function getStoreLocationsById($id) {
        $allStores = $this->storeCollection->getData();
        $customerData = [];
        foreach($allStores as $stores) {
            if ($stores['storebase_id'] == $id) {
                $cusotomerId = $stores['customer_id'];
                $customerData = $this->getCustomerDataById($cusotomerId);
            }
        }
        return $customerData;
    }

    public function getCustomerDataById($cusotomerId) {
        $customerData = $this->_customerFactory->create()->load($cusotomerId);
        $customerDataForMail = [];
        $customerDataForMail['email'] = $customerData['email'];
        $customerDataForMail['firstname'] = $customerData['firstname'];
        $customerDataForMail['lastname'] = $customerData['lastname'];
        return $customerDataForMail;
    }

    public function getStoreDetails($storebaseId) {
        return $this->_storebase->pickupDetails($storebaseId);
    }

    public function getStoreBaseUrl() {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getCountryCollection() {
        $collection = $this->_countryCollectionFactory->create();
        return $collection->toOptionArray();
    }

    public function getCountryname($countryCode) {
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    public function getStoresByStoreId($storebaseId) {
        $allStores = $this->storeCollection->getData();
        $customerStores = [];
        foreach ($allStores as $stores) {
            if($stores['storebase_id'] == $storebaseId) {
                $customerStores = $stores;
            }
        }
        return $customerStores;
    }

    public function getConfig($configVal) {
        return $this->scopeConfig->getValue(
            $configVal,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getLoginCustomerDetails() {
        $customerDetails = [];
        if ($this->isCustomerLoggedIn()) {
            $customerData = $this->_customerSession->getCustomerData();
            $customerDetails['email'] = $customerData->getEmail();
            $customerDetails['name'] = $customerData->getFirstname()." ".$customerData->getLastname();
            return $customerDetails;
        }
    }
}