<?php
namespace Excellence\Storebase\Block;

class Check extends \Magento\Framework\View\Element\Template
{    
  protected $_scopeConfigObject;
  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\Registry $registry,
    \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory)
  {
    $this->registry = $registry;  
    $this->storebaseFactory = $storebaseFactory;
    $this->_scopeConfigObject = $context->getscopeConfig();
    $this->pageConfig = $context->getPageConfig();
    parent::__construct($context);
  }
  protected function _prepareLayout()
  {
    if(!empty($this->_scopeConfigObject->getValue('googleMap/seo/metaTitle'))){
      $this->pageConfig->getTitle()->set($this->_scopeConfigObject->getValue('googleMap/seo/metaTitle'));
    }
    if(!empty($this->_scopeConfigObject->getValue('googleMap/seo/metaKeywords'))){
      $this->pageConfig->setKeywords($this->_scopeConfigObject->getValue('googleMap/seo/metaKeywords'));
    }
    if(!empty($this->_scopeConfigObject->getValue('googleMap/seo/metaDescription'))){
      $this->pageConfig->setDescription($this->_scopeConfigObject->getValue('googleMap/seo/metaDescription'));
    }
    parent::_prepareLayout();
  }
  public function getValue()
  {
   $position=$this->registry->registry('info_val');
   
   return $position;
 }
 
 public function getStorebase(){
  $strId = $this->_storeManager->getStore()->getId();
  $model=$this->storebaseFactory->create();
  $var_data=$model->getCollection()->addFieldToFilter('status','Enable');
  $collectionData = array();
  foreach ($var_data as $item) {
    $strexp = explode(',',$item['store_view']); 
    if(in_array($strId, $strexp) || in_array(0, $strexp)){
      $collectionData[] = $item->getData();
    }
  }

  return $collectionData;
}

public function getDlat(){

 $configValueDlat = $this->_scopeConfigObject->getValue('googleMap/setting/latitude');
 return  $configValueDlat;
}
public function getDlong(){

 $configValueDlong = $this->_scopeConfigObject->getValue('googleMap/setting/longitude');
 return  $configValueDlong;
}
public function getApi(){
  $apiKey = $this->_scopeConfigObject->getValue('googleMap/setting/api');
  return  $apiKey;
}
public function getDistanceUnit(){
  $unit = $this->_scopeConfigObject->getValue('googleMap/setting/distance');
  return $unit;
}
public function getRadius(){
  $radius = $this->_scopeConfigObject->getValue('googleMap/setting/radius');
  return $radius;
}
}
