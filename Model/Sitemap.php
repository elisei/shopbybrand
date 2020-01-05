<?php
namespace O2TI\ShopByBrand\Model;

use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\DataObject;

class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    public function _initSitemapItems()
    {
        $helper = $this->_sitemapData;
        $storeId = $this->getStoreId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_sitemapItems[] = new DataObject([
            'changefreq' => $helper->getCategoryChangefreq($storeId),
            'priority' => $helper->getCategoryPriority($storeId),
            'collection' => $objectManager->get('\O2TI\ShopByBrand\Model\ResourceModel\Sitemap\BrandFactory')->create()->getCollection($storeId),
        ]);
        parent::_initSitemapItems();
    }
}
