<?php
namespace Excellence\Storebase\Block\Adminhtml\Storebase\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $countryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Directory\Model\Config\Source\Country $countryFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_countryFactory = $countryFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm() {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('storebase');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Excellence_Storebase::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('storebase_main_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Store Pickup Information')]);

        if ($model->getId()) {
            $fieldset->addField('storebase_id', 'hidden', ['name' => 'storebase_id']);
        }
        $storeStatus = array (
            array('value' =>'' , 'label' =>  __('--Please Select--')),
            array('value' =>2, 'label' => __('Open')),
            array('value' =>1, 'label' => __('Close')),
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
        $field = $fieldset->addField(
            'store_view',
            'multiselect',
            [
                'name' => 'stores[]',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_view',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }


        $fieldset->addField(
            'store_name',
            'text',
            [
                'name' => 'store_name',
                'label' => __('Store Name'),
                'title' => __('Store Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'street_one',
            'text',
            [
                'name' => 'street_one',
                'label' => __('Street'),
                'title' => __('Street'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'street_two',
            'text',
            [
                'name' => 'street_two',
                'label' => __('(Line 2)'),
                'title' => __('Street'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'number',
            'text',
            [
                'name' => 'number',
                'label' => __('Phone Number'),
                'title' => __('Number'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'time',
            'hidden',
            [
                'name' => 'time',
                'label' => __('Time'),
                'title' => __('Time'),
                'required' => true,
                'disabled' => true
                
            ]
        );
        // set time range values 
        $timeRange = $fieldset->addField(
            'time_range',
            'hidden',
            [
                'name' => 'time_range',
                'label' => __('Time Range'),
                'title' => __('Time Range'),
                'value' => '0',
                'disabled' => true
            ]
        );
        $timeRange->setAfterElementHtml("   
            <script type=\"text/javascript\">
                    require([
                    'jquery',
                    'jquery/ui',
                    'mage/translate'
                ],
                function(jQuery) {
                 jQuery(document).ready(function(){

                          var timesIntervalArray = jQuery('#storebase_main_time_range').val();
                          if(timesIntervalArray){

                      var timeIntObject = JSON.parse(timesIntervalArray); 
                      console.log(timeIntObject);
                        if(timeIntObject.sunday){
                        jQuery('#storebase_main_sunday').siblings('.time-range:first').find('.range-value').text(timeIntObject.sunday);
                        jQuery('#storebase_main_sunday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.sunday);
                       }
                       if(timeIntObject.monday){
                        jQuery('#storebase_main_monday').siblings('.time-range:first').find('.range-value').text(timeIntObject.monday);
                        jQuery('#storebase_main_monday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.monday);
                       }
                       if(timeIntObject.tuesday){
                        jQuery('#storebase_main_tuesday').siblings('.time-range:first').find('.range-value').text(timeIntObject.tuesday);
                        jQuery('#storebase_main_tuesday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.tuesday);
                       }
                       if(timeIntObject.wednesday){
                        jQuery('#storebase_main_wednesday').siblings('.time-range:first').find('.range-value').text(timeIntObject.wednesday);
                        jQuery('#storebase_main_wednesday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.wednesday);
                       }
                       if(timeIntObject.thursday){
                        jQuery('#storebase_main_thursday').siblings('.time-range:first').find('.range-value').text(timeIntObject.thursday);
                        jQuery('#storebase_main_thursday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.thursday);
                       }
                        if(timeIntObject.friday){
                        jQuery('#storebase_main_friday').siblings('.time-range:first').find('.range-value').text(timeIntObject.friday);
                        jQuery('#storebase_main_friday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.friday);
                       }
                       if(timeIntObject.saturaday){
                         jQuery('#storebase_main_saturday').siblings('.time-range:first').find('.range-value').text(timeIntObject.saturday);
                         jQuery('#storebase_main_saturday').siblings('.time-range:first').find('.rangetime').val(timeIntObject.saturday);
                       }
                      
                   }
                }); 
              
            });
            </script>"
        );

        $fieldSub =$fieldset->addField(
            'sunday',
            'select',
            [
                'name' => 'sunday',
                'label' => __('Sunday'),
                'title' => __('Sunday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);

        $fieldSub =$fieldset->addField(
            'monday',
            'select',
            [
                'name' => 'monday',
                'label' => __('Monday'),
                'title' => __('Monday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);    

         $fieldSub =$fieldset->addField(
            'tuesday',
            'select',
            [
                'name' => 'tuesday',
                'label' => __('Tuesday'),
                'title' => __('Tuesday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);    
           

          $fieldSub =$fieldset->addField(
            'wednesday',
            'select',
            [
                'name' => 'wednesday',
                'label' => __('Wednesday'),
                'title' => __('Wednesday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);    
           
          $fieldSub =$fieldset->addField(
            'thursday',
            'select',
            [
                'name' => 'thursday',
                'label' => __('Thursday'),
                'title' => __('Thursday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);    
           
          $fieldSub =$fieldset->addField(
            'friday',
            'select',
            [
                'name' => 'friday',
                'label' => __('Friday'),
                'title' => __('Friday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);    
           
          $fieldSub =$fieldset->addField(
            'saturday',
            'select',
            [
                'name' => 'saturday',
                'label' => __('saturday'),
                'title' => __('Saturday'),
                'class' => 'required-entry storestatus',
                'required' => true,
                'values' => $storeStatus,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
               'Excellence\Storebase\Block\Store\Form\Renderer\Fieldset\Element'
           );
           $fieldSub->setRenderer($renderer);        

      
        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'region_id',
            'text',
            [
                'name' => 'region_id',
                'label' => __('State / Province'),
                'title' => __('State / Province'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'zipcode',
            'text',
            [
                'name' => 'zipcode',
                'label' => __('Zipcode'),
                'title' => __('Zipcode'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $optionsCountry = $this->_countryFactory->toOptionArray();
        $fieldset->addField(
            'country',
            'select',
            [
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                'required' => true,
                'values' => $optionsCountry,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldMap = $fieldset->addField(
            'map',
            'text',
            [
                'name' => 'map',
                'label' => __('Map'),
                'title' => __('Map'),
                'required' => false
            ]
        );
        $fieldset->addField(
            'latitude',
            'text',
            [
                'name' => 'latitude',
                'label' => __('Latitude'),
                'title' => __('Latitude'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'after_element_html' =>'<small>Click on link to find Latitude <a href="https://support.google.com/maps/answer/18539" target="_blank">https://support.google.com/maps/answer/18539</a> <small>or</small> <a href="http://www.latlong.net" target="_blank">http://www.latlong.net</a> </small>'
            ]
        );
        $fieldset->addField(
            'longitude',
            'text',
            [
                'name' => 'longitude',
                'label' => __('Longitude'),
                'title' => __('Longitude'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'after_element_html' => '<small>Click on link to find Longitude <a href="https://support.google.com/maps/answer/18539" target="_blank">https://support.google.com/maps/answer/18539</a> <small>or</small> <a href="http://www.latlong.net" target="_blank">http://www.latlong.net</a> </small> '
            ]
        );

        $renderer = $this->getLayout()->createBlock(
                        'Excellence\Storebase\Block\Adminhtml\CustomMapHtml\Edit\Renderer\MapRenderer'
                    );
        $fieldMap->setRenderer($renderer);

        $fieldset->addField(
            'position',
            'text',
            [
                'name' => 'position',
                'label' => __('Position'),
                'title' => __('Position'),
                'value' => '0',
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'status', 
            'select', 
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name'  => 'status',
                'required' => true,
                'values'=> array('1'=>__('Enabled'), '0'=>__('Disabled'))
            ]
        );

        $this->_eventManager->dispatch('adminhtml_storebase_edit_tab_main_prepare_form', ['form' => $form]);
        if (!isset($form['position'])) {
            $form['position'] = '0';
        }
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return __('Store Pickup Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Store Pickup Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}
