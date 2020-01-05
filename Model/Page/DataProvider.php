<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Model\Page;

use O2TI\ShopByBrand\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Framework\AuthorizationInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \O2TI\ShopByBrand\Model\ResourceModel\Page\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var AuthorizationInterface
     */
    private $auth;

    private $fileInfo;

    protected $_storeManager;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @param AuthorizationInterface|null $auth
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ?AuthorizationInterface $auth = null
    ) {
        $this->_storeManager = $storeManager;
        $this->collection = $pageCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->auth = $auth ?? ObjectManager::getInstance()->get(AuthorizationInterface::class);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $page \O2TI\ShopByBrand\Model\Page */
        foreach ($items as $page) {
            $this->loadedData[$page->getId()] = $page->getData();
            if ($fileName = $page->getImage()) {
                $fileInfo = $this->getFileInfo();

                if ($fileInfo->isExist($fileName)) {
                    $stat = $fileInfo->getStat($fileName);
                    $mime = $fileInfo->getMimeType($fileName);

                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    $brandImage['image'][0]['name'] = basename($fileName);

                    if ($fileInfo->isBeginsWithMediaDirectoryPath($fileName)) {
                        $brandImage['image'][0]['url'] =  $this->getMediaUrl().$fileInfo::ENTITY_MEDIA_PATH.'/'.$fileName;
                    } else {
                        $brandImage['image'][0]['url'] = $this->getMediaUrl().$fileInfo::ENTITY_MEDIA_PATH.'/'.$fileName;
                    }

                    $brandImage['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
                    $brandImage['image'][0]['type'] = $mime;
                    
                    $fullData = $this->loadedData;
                    $this->loadedData[$page->getId()] = array_merge($fullData[$page->getId()], $brandImage);
                }
            }
        }

        $data = $this->dataPersistor->get('brand_page');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('brand_page');
        }

        return $this->loadedData;
    }


    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    /**
     * @inheritDoc
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        if (!$this->auth->isAllowed('O2TI_ShopByBrand::save_design')) {
            $designMeta = [
                'design' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'disabled' => true
                            ]
                        ]
                    ]
                ],
                'custom_design_update' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'disabled' => true
                            ]
                        ]
                    ]
                ]
            ];
            $meta = array_merge_recursive($meta, $designMeta);
        }

        return $meta;
    }

    /**
    * Get FileInfo instance
    *
    * @return FileInfo
    *
    * @deprecated 102.0.0
    */
    private function getFileInfo()
    {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }
}
