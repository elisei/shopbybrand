<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms page search results.
 * @api
 * @since 100.0.2
 */
interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \O2TI\ShopByBrand\Api\Data\PageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
