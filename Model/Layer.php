<?php

namespace O2TI\ShopByBrand\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Layer class
 */
class Layer extends \Magento\Catalog\Model\Layer
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Layer constructor.
     * @param \Magento\Catalog\Model\Layer\ContextInterface $context
     * @param \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $catalogProduct
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\ContextInterface $context,
        \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $catalogProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
    }

    /**
     * Get special product collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws \Zend_Date_Exception
     */
    public function getProductCollection()
    {
        $brand =  $this->registry->registry('current_brand');
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
            $collection->addStoreFilter()->addAttributeToFilter('manufacturer', $brand->getAttributeId());
        } else {
            $collection = $this->collectionProvider->getCollection($this->getCurrentCategory());
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
            $collection->addAttributeToFilter('manufacturer', $brand->getAttributeId());
        }
        return $collection;
    }
}
