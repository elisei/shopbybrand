<?php
/**
 * Cms Template Filter Provider
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Model\Template;

/**
 * Filter provider model
 */
class FilterProvider
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var string
     */
    protected $_pageFilter;

    /**
     * @var array
     */
    protected $_instanceList;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $pageFilter
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $pageFilter = \O2TI\ShopByBrand\Model\Template\Filter::class
    ) {
        $this->_objectManager = $objectManager;
        $this->_pageFilter = $pageFilter;
    }

    /**
     * @param string $instanceName
     * @return \Magento\Framework\Filter\Template
     * @throws \Exception
     */
    protected function _getFilterInstance($instanceName)
    {
        if (!isset($this->_instanceList[$instanceName])) {
            $instance = $this->_objectManager->get($instanceName);

            if (!$instance instanceof \Magento\Framework\Filter\Template) {
                throw new \Exception('Template filter ' . $instanceName . ' does not implement required interface');
            }
            $this->_instanceList[$instanceName] = $instance;
        }

        return $this->_instanceList[$instanceName];
    }

    /**
     * @return \Magento\Framework\Filter\Template
     */
    public function getPageFilter()
    {
        return $this->_getFilterInstance($this->_pageFilter);
    }
}
