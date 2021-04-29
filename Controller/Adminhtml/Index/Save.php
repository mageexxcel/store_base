<?php

namespace Excellence\Storebase\Controller\Adminhtml\Index;

class Save extends \Magento\Backend\App\Action
{
    /**
    * @var \Magento\Framework\Image\AdapterFactory
    */
    protected $adapterFactory;
    /**
    * @var \Magento\MediaStorage\Model\File\UploaderFactory
    */
   
    /**
    * @var \Magento\Framework\Filesystem
    */
    protected $storeManager;
    protected $resultPageFactory = false;
    protected $resultRedirectFactory = false;
    protected $_storebaseFactory;
    protected $_emailHelper;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Excellence\Storebase\Helper\Email $emailHelper,
        \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory)
         {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_storebaseFactory = $storebaseFactory;
        $this->storeManager = $storeManager;
        $this->_emailHelper = $emailHelper;
    }

    public function execute() {
        $post = $this->getRequest()->getPostValue();
        if(isset($post)){
            $isEdit = false;
            $model = $this->_storebaseFactory->create();
            if(!empty($post['storebase_id'])){
                $model->load($post['storebase_id']);
                $isEdit = true;
            }
            $storeView = implode(",", $post['stores']);
            $timeRange = $post['rangetime'];
            $timeString = '';
            $dayOpenTimeRange = array();
            if($post['sunday'] == 2){
             $timeString .= 'SUNDAY=> '.$timeRange[0].'<br>'.' ';
             $dayOpenTimeRange['sunday'] = $timeRange[0];
            }else{
                $timeString .= 'SUNDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['sunday'] = 'Closed';
            }
            
            if($post['monday'] == 2){
             $timeString .= 'MONDAY=> '.$timeRange[1].'<br>'.' ';
              $dayOpenTimeRange['monday'] = $timeRange[1];
            }else{
                $timeString .= 'MONDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['monday'] = 'Closed';
            }
           
             if($post['tuesday'] == 2){
             $timeString .= 'TUESDAY=> '.$timeRange[2].'<br>'.' ';
             $dayOpenTimeRange['tuesday'] = $timeRange[2];
            }
            else{
                $timeString .= 'TUESDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['tuesday'] = 'Closed';
            }
            if($post['wednesday'] == 2){
             $timeString .= 'WEDNESDAY=> '.$timeRange[3].'<br>'.' ';
             $dayOpenTimeRange['wednesday'] = $timeRange[3];
            }else{
                $timeString .= 'WEDNESDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['wednesday'] = 'Closed';
            }
           if($post['thursday'] == 2){
             $timeString .= 'THURSDAY=> '.$timeRange[4].'<br>'.' ';
             $dayOpenTimeRange['thursday'] = $timeRange[4];
            }
            else{
                $timeString .= 'THURSDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['thursday'] = 'Closed';
            }
             if($post['friday'] == 2){
             $timeString .= 'FRIDAY=> '.$timeRange[5].'<br>'.' ';
             $dayOpenTimeRange['friday'] = $timeRange[5];
            }else{
                $timeString .= 'FRIDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['friday'] = 'Closed';
            }
            if($post['saturday'] == 2){
             $timeString .= 'SATURDAY=> '.$timeRange[6].'<br>'.' ';
              $dayOpenTimeRange['saturday'] = $timeRange[6];
            }else{
                $timeString .= 'SATURDAY=> '.'Closed'.'<br>'.' ';
                $dayOpenTimeRange['saturday'] = 'Closed';
            }
            $daytimeRange = json_encode($dayOpenTimeRange);
            $model->setStoreView($storeView);
            $model->setStoreName($post['store_name']);
            $model->setStreetOne($post['street_one']);
            if ($post['street_two']) {
                $model->setStreetTwo($post['street_two']);
            } else {
                $model->setStreetTwo('');
            }
            $model->setNumber($post['number']);
            $model->setTime($timeString);
            $model->setCity($post['city']);
            $model->setRegionId($post['region_id']);
            $model->setZipcode($post['zipcode']);
            $model->setCountry($post['country']);
            $model->setLatitude($post['latitude']);
            $model->setLongitude($post['longitude']);
            $model->setPosition($post['position']);
            $model->setStatus($post['status']);
            if(array_key_exists('is_approved',$post)) {
                $model->setIsApproved($post['is_approved']);
                if (($post['is_approved'] == 1) && ($model->getIsDealer() == 1)) {
                    $this->_emailHelper->sendApprovalNotification($post);
                }
            }
            if(array_key_exists('is_storepickup',$post)) {
                $model->setIsStorepickup($post['is_storepickup']);
            }
            $model->setSunday(intval($post['sunday']));
            $model->setMonday($post['monday']);
            $model->setTuesday(intval($post['tuesday']));
            $model->setWednesday($post['wednesday']);
            $model->setThursday($post['thursday']);
            $model->setFriday($post['friday']);
            $model->setSaturday($post['saturday']);
            $model->setTimeRange($daytimeRange);

            if($model->save()){
                if ($isEdit) {
                    $this->messageManager->addSuccess(__("Store %1 Has Been Updated.", $model->getStoreName()));
                } else {
                    $this->messageManager->addSuccess(__('Store Has Been Saved.'));
                }
            }
            else{
                $this->messageManager->addError(__('Some error occured while saving the Store. Please try again.'));
            }
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', ['storebase_id' => $model->getId(), '_current' => true]);
                return;
            }
        }
        


        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}