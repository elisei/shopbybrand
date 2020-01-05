<?php
/**
 * Application config file resolver
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Model\Page;

/**
 * Class DomValidationState
 * @package O2TI\ShopByBrand\Model\Page
 */
class DomValidationState implements \Magento\Framework\Config\ValidationStateInterface
{
    /**
     * Retrieve validation state
     * Used in cms page post processor to force validate layout update xml
     *
     * @return boolean
     */
    public function isValidationRequired()
    {
        return true;
    }
}
