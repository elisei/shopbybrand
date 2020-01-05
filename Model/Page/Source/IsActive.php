<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Model\Page\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \O2TI\ShopByBrand\Model\Page
     */
    protected $brandPage;

    /**
     * Constructor
     *
     * @param \O2TI\ShopByBrand\Model\Page $brandPage
     */
    public function __construct(\O2TI\ShopByBrand\Model\Page $brandPage)
    {
        $this->brandPage = $brandPage;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->brandPage->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
