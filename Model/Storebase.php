<?php

namespace Excellence\Storebase\Model;

/**
 * Storebase Model
 *
 * @method \Excellence\Storebase\Model\Resource\Page _getResource()
 * @method \Excellence\Storebase\Model\Resource\Page getResource()
 */
class Storebase extends \Magento\Framework\Model\AbstractModel 
{
    protected $_collectionFactory;
    const CACHE_TAG = 'excellence_storebase';
    const GOOGLE_SEO = 'googleMap/seo';
    const GOOGLE_SETTING = 'googleMap/setting';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Excellence\Storebase\Model\ResourceModel\Storebase');
    }
    
    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
     public function saveEdit($post,$nid) {
        $model = $this->load($nid);
            $model->setStoreName($post['Store_Name']);
            $model->setStoreAddress($post['Address']);
            $model->setStreetOne($post['street_one']);
            if ($post['street_two']) {
                $model->setStreetTwo($post['street_two']);
            }
            $model->setNumber($post['Number']);
            $model->setTime($post['Time']);
            $model->setCity($post['City']);
            $model->setZipcode($post['Zipcode']);
            $model->setCountry($post['Country']);
            $model->setDefaultLatitude($post['Default_Latitude']);
            $model->setDefaultLongitude($post['Default_Longitude']);
            $model->setLatitude($post['Latitude']);
            $model->setLongitude($post['Longitude']);
            $model->setPosition($post['Position']);
            $model->setStatus($post['status']);
            if(array_key_exists('is_approved',$post)) {
                $model->setIsApproved($post['is_approved']);
            }
            return $model->save();
    }
    public function pickupDetails($storeId) {
        return $this->load($storeId)->getData();
    }
}
