<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Api;

/**
 * Command to load the page data by specified identifier
 * @api
 * @since 103.0.0
 */
interface GetPageByIdentifierInterface
{
    /**
     * Load page data by given page identifier.
     *
     * @param string $identifier
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     * @since 103.0.0
     */
    public function execute(string $identifier, int $storeId) : \O2TI\ShopByBrand\Api\Data\PageInterface;
}
