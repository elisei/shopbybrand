<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Controller\Adminhtml\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Create CMS page action.
 */
class NewAction extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'O2TI_ShopByBrand::save';

    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;



    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \O2TI\ShopByBrand\Model\PageFactory
     */
    private $pageFactory;

    /**
     * @var \O2TI\ShopByBrand\Api\PageRepositoryInterface
     */
    private $pageRepository;

    private $_removeAccents;
    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \O2TI\ShopByBrand\Model\PageFactory|null $pageFactory
     * @param \O2TI\ShopByBrand\Api\PageRepositoryInterface|null $pageRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \O2TI\ShopByBrand\Model\PageFactory $pageFactory = null,
        \O2TI\ShopByBrand\Api\PageRepositoryInterface $pageRepository = null,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filter\RemoveAccents $removeAccents,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->pageFactory = $pageFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\O2TI\ShopByBrand\Model\PageFactory::class);
        $this->_removeAccents = $removeAccents;
        $this->pageRepository = $pageRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\O2TI\ShopByBrand\Api\PageRepositoryInterface::class);
        parent::__construct($context);
    }


    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $model = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute')->setEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $model->loadByCode(\Magento\Catalog\Model\Product::ENTITY, 'manufacturer');
 
        foreach ($model->getOptions() as $option) {
            if ($option->getValue()) {
                $id = (int)$option->getValue();
                if ($this->pageRepository->getByAttributeId($id)) {
                    continue;
                } else {
                    $brandItem = $this->pageFactory->create();
                    $data = array(
                            'title' => $option->getLabel(),
                            'identifier' => $this->createUrlKey($option->getLabel()),
                            'content' =>  $option->getLabel(),
                            'attribute_id' => $option->getValue(),
                            'is_active' => 1,
                            'store_id' => 0,
                            'page_layout' => '2columns-left'
                        );
                    $brandItem->setData($data);
                    try {
                        $this->pageRepository->save($brandItem);
                        $this->messageManager->addSuccess(__('New Brand "%1" Create', $option->getLabel()));
                    } catch (LocalizedException $e) {
                        $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
                    } catch (\Exception $e) {
                        $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
                    }
                }
            }
        }
        
        $this->_redirect('*/*/');
    }


  

    public function createUrlKey($title)
    {
        $url = $this->_removeAccents->filter($title);
        $url = preg_replace('#[^0-9a-z]+#i', '-', $url);
        $urlKey = strtolower($url);
        $storeId = (int) $this->_storeManager->getStore()->getStoreId();
        return $urlKey;
    }
}
