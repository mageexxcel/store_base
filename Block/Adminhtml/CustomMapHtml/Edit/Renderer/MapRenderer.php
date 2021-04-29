<?php
namespace Excellence\Storebase\Block\Adminhtml\CustomMapHtml\Edit\Renderer;

use Magento\Framework\App\ObjectManager;
/**
* CustomFormField Customformfield field renderer
*/
class MapRenderer extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_element;
    /**
     * @var string
     */
    protected $_template = 'form/map/element.phtml';
    /**
     * Retrieve an element
     *
     * @return \Magento\Framework\Data\Form\Element\Fieldset
     */
    public function getElement() {
        return $this->_element;
    }
    /**
     * Render element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
        $this->_element = $element;
        return $this->toHtml();
    }
    public function getApi() {
        $scopeConfigObject = ObjectManager::getInstance()->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $apiKey = $scopeConfigObject->getValue(\Excellence\Storebase\Model\Storebase::GOOGLE_SETTING.'/api');
        return $apiKey;
    }
}