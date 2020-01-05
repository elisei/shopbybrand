<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * brand page content block
 *
 * @api
 * @since 100.0.2
 */
class Page extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{

   

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \O2TI\ShopByBrand\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \O2TI\ShopByBrand\Model\Page
     */
    protected $_page;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Page factory
     *
     * @var \O2TI\ShopByBrand\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \O2TI\ShopByBrand\Model\Page $page
     * @param \O2TI\ShopByBrand\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \O2TI\ShopByBrand\Model\PageFactory $pageFactory
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \O2TI\ShopByBrand\Model\Page $page,
        \O2TI\ShopByBrand\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \O2TI\ShopByBrand\Model\PageFactory $pageFactory,
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
       
        // used singleton (instead factory) because there exist dependencies on \O2TI\ShopByBrand\Helper\Page
        $this->_page = $page;
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_pageFactory = $pageFactory;
        $this->pageConfig = $pageConfig;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Page instance
     *
     * @return \O2TI\ShopByBrand\Model\Page
     */
    public function getPage()
    {
        if (!$this->hasData('page')) {
            if ($this->getPageId()) {
                /** @var \O2TI\ShopByBrand\Model\Page $page */
                $page = $this->_pageFactory->create();
                $page->setStoreId($this->_storeManager->getStore()->getId())->load($this->getPageId(), 'identifier');
            } else {
                $page = $this->_page;
            }
            $this->setData('page', $page);
        }
        return $this->getData('page');
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);
        $page = $this->getPage();
        $this->_addBreadcrumbs($page);
        $this->pageConfig->addBodyClass('brand-' . $page->getIdentifier());
        $metaTitle = $page->getMetaTitle();
        $this->pageConfig->getTitle()->set($metaTitle ? $metaTitle : $page->getTitle());
        $this->pageConfig->setKeywords($page->getMetaKeywords());
        $this->pageConfig->setDescription($page->getMetaDescription());

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            // Setting empty page title if content heading is absent
            $brandTitle = $page->getTitle() ?: ' ';
            $pageMainTitle->setPageTitle($this->escapeHtml($brandTitle));
        }
        $this->pageConfig->addRemotePageAsset(
            $this->_storeManager->getStore()->getUrl($page->getIdentifier()),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
        return $this;
    }

    /**
     * Prepare breadcrumbs
     *
     * @param \O2TI\ShopByBrand\Model\Page $page
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs(\O2TI\ShopByBrand\Model\Page $page)
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb('brand_page', ['label' => $page->getTitle(), 'title' => $page->getTitle()]);
        }
    }

    /**
     * @return string
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }


    /**
     * Retrieve current category model object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCurrentBrand()
    {
        if (!$this->hasData('current_brand')) {
            $this->setData('current_brand', $this->_coreRegistry->registry('current_brand'));
        }
        return $this->getData('current_brand');
    }

    /**
     * Prepare HTML content
     *
     * @return string
     */
    // protected function _toHtml()
    // {
    //     $html = $this->getChildHtml();
    //     // $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());
    //     return $html;
    // }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\O2TI\ShopByBrand\Model\Page::CACHE_TAG . '_' . $this->getPage()->getId()];
    }
}
