<?php
namespace Excellence\Storebase\Block\Adminhtml\Storebase;

/**
 * Admin Storebase page
 *
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct() {
        $this->_objectId = 'storebase_id';
        $this->_blockGroup = 'Excellence_Storebase';
        $this->_controller = 'adminhtml_storebase';

        parent::_construct();

        if ($this->_isAllowedAction('Excellence_Storebase::save')) {
            $this->buttonList->update('save', 'label', __('Save Store Pickup'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Excellence_Storebase::storebase_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Store Pickup'));
        } else {
            $this->buttonList->remove('delete');
        }
        //  if ($this->_isAllowedAction('Excellence_Storebase::storebase_reset')) {
        //     $this->buttonList->update('reset', 'label', __('Reset'));
        // } else {
        //     $this->buttonList->remove('reset');
        // }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText() {
        if ($this->_coreRegistry->registry('storebase')->getId()) {
            return __("Edit Storebase '%1'", $this->escapeHtml($this->_coreRegistry->registry('storebase')->getTitle()));
        } else {
            return __('Store Location');
        }
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

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl() {
        return $this->getUrl('storebase/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout() {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
