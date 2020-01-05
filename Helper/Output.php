<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace O2TI\ShopByBrand\Helper;

use O2TI\ShopByBrand\Model\Page as Modelbrand;
use Magento\Framework\Filter\Template;

class Output extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Array of existing handlers
     *
     * @var array
     */
    protected $_handlers;

    /**
     * Template processor instance
     *
     * @var Template
     */
    protected $_templateProcessor = null;

    /**
     * Catalog data
     *
     * @var Data
     */
    protected $_filterProvider = null;

    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var array
     */
    private $directivePatterns;

    protected $_storeManager;

    protected $_brandImage;

    /**
     * Output constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param Data $filterProvider
     * @param \Magento\Framework\Escaper $escaper
     * @param array $directivePatterns
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \O2TI\ShopByBrand\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Escaper $escaper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \O2TI\ShopByBrand\Model\ImageUploader $_brandImage,
        $directivePatterns = []
    ) {
        $this->_eavConfig = $eavConfig;
        $this->_filterProvider = $filterProvider;
        $this->_escaper = $escaper;
        $this->directivePatterns = $directivePatterns;
        $this->_storeManager = $storeManager;
        $this->_brandImage = $_brandImage;
        parent::__construct($context);
    }

    /**
     * @return Template
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = $this->_filterProvider->getPageFilter();
        }

        return $this->_templateProcessor;
    }

    /**
     * Adding method handler
     *
     * @param string $method
     * @param object $handler
     * @return $this
     */
    public function addHandler($method, $handler)
    {
        if (!is_object($handler)) {
            return $this;
        }
        $method = strtolower($method);

        if (!isset($this->_handlers[$method])) {
            $this->_handlers[$method] = [];
        }

        $this->_handlers[$method][] = $handler;
        return $this;
    }

    /**
     * Get all handlers for some method
     *
     * @param string $method
     * @return array
     */
    public function getHandlers($method)
    {
        $method = strtolower($method);
        return $this->_handlers[$method] ?? [];
    }

    /**
     * Process all method handlers
     *
     * @param string $method
     * @param mixed $result
     * @param array $params
     * @return mixed
     */
    public function process($method, $result, $params)
    {
        foreach ($this->getHandlers($method) as $handler) {
            if (method_exists($handler, $method)) {
                $result = $handler->{$method}($this, $result, $params);
            }
        }
        return $result;
    }

   
    /**
     * Prepare brand attribute html output
     *
     * @param Modelbrand $brand
     * @param string $attributeHtml
     * @param string $attributeName
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function brandAttribute($brand, $attributeHtml, $attributeName)
    {
        $attributeType = $this->_getTemplateProcessor()->filter($attributeName);
      

        // if ($brand &&
        //     $attributeType != 'image'
        // ) {
        //     $attributeHtml = $this->_escaper->escapeHtml($attributeHtml);
        // }

        $attributeHtml = $this->process(
            'brandAttribute',
            $attributeHtml,
            ['brand' => $brand, 'attribute' => $attributeName]
        );

        

        return $attributeHtml;
    }

    /**
     * Check if string has directives
     *
     * @param string $attributeHtml
     * @return bool
     */
    public function isDirectivesExists($attributeHtml)
    {
        $matches = false;
        foreach ($this->directivePatterns as $pattern) {
            if (preg_match($pattern, $attributeHtml)) {
                $matches = true;
                break;
            }
        }
        return $matches;
    }

    public function getImageUrl($image)
    {
        $mediaUrl = $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $absolutePath = $this->_brandImage->getBasePath();
        return $mediaUrl.$absolutePath.'/'.$image;
    }
}
