<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Block\Adminhtml;

/**
 * Adminhtml cms pages content block
 */
class Page extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_page';
        $this->_blockGroup = 'O2TI_ShopByBrand';
        $this->_headerText = __('Manage Brand\'s');

        parent::_construct();

        if ($this->_isAllowedAction('O2TI_ShopByBrand::save')) {
            $this->buttonList->update('add', 'label', __('Re-sync Manufacture Attribute'));
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
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
