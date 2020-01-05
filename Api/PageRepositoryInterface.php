<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS page CRUD interface.
 * @api
 * @since 100.0.2
 */
interface PageRepositoryInterface
{
    /**
     * Save page.
     *
     * @param \O2TI\ShopByBrand\Api\Data\PageInterface $page
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\O2TI\ShopByBrand\Api\Data\PageInterface $page);

    /**
     * Retrieve page.
     *
     * @param int $pageId
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($pageId);

    /**
     * Retrieve page.
     *
     * @param int $attributeId
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByAttributeId($attributeId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \O2TI\ShopByBrand\Api\Data\PageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete page.
     *
     * @param \O2TI\ShopByBrand\Api\Data\PageInterface $page
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\O2TI\ShopByBrand\Api\Data\PageInterface $page);

    /**
     * Delete page by ID.
     *
     * @param int $pageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($pageId);
}
