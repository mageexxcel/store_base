<?php
/**
 * Adminhtml storebase list block
 *
 */
namespace Excellence\Storebase\Block\Adminhtml;

class Storebase extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct() {
        $this->_controller = 'adminhtml_storebase';
        $this->_blockGroup = 'Excellence_Storebase';
        $this->_headerText = __('Storebase');
        $this->_addButtonLabel = __('Add Store Location');
        parent::_construct();
        if ($this->_isAllowedAction('Excellence_Storebase::save')) {
            $this->buttonList->update('add', 'label', __('Add Store Location'));
        } else {
            $this->buttonList->remove('add');
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

    
}
