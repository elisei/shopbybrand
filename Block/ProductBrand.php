<?php
namespace O2TI\ShopByBrand\Block;

class ProductBrand extends \Magento\Framework\View\Element\Template
{
    /**
     * @var _brandFactory
     */
    protected $_page;
    /**
     * @var Registry
     */
    private $registry;
    
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \O2TI\ShopByBrand\Model\PageRepository $page,
    \Magento\Framework\Registry $registry
) {
        $this->_page = $page;
        $this->registry = $registry;
        parent::__construct($context);
    }
    
    public function getBrand()
    {
        $product = $this->registry->registry('current_product');
        if ($manufacturer = $product->getManufacturer()) {
            return  $this->_page->getByAttributeId($manufacturer);
        } else {
            return $this;
        }
    }
}
