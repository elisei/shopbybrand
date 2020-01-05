<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace O2TI\ShopByBrand\Model;

use O2TI\ShopByBrand\Api\Data\PageInterface;
use O2TI\ShopByBrand\Api\GetPageByIdentifierInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GetPageByIdentifier
 */
class GetPageByIdentifier implements GetPageByIdentifierInterface
{
    /**
     * @var \O2TI\ShopByBrand\Model\PageFactory
     */
    private $pageFactory;

    /**
     * @var ResourceModel\Page
     */
    private $pageResource;

    /**
     * @param PageFactory $pageFactory
     * @param ResourceModel\Page $pageResource
     */
    public function __construct(
        \O2TI\ShopByBrand\Model\PageFactory $pageFactory,
        \O2TI\ShopByBrand\Model\ResourceModel\Page $pageResource
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $identifier, int $storeId) : PageInterface
    {
        $page = $this->pageFactory->create();
        $page->setStoreId($storeId);
        $this->pageResource->load($page, $identifier, PageInterface::IDENTIFIER);

        if (!$page->getId()) {
            throw new NoSuchEntityException(__('The CMS page with the "%1" ID doesn\'t exist.', $identifier));
        }

        return $page;
    }
}
