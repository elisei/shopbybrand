<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Controller\Adminhtml\Page;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use O2TI\ShopByBrand\Model\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save CMS page action.
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'O2TI_ShopByBrand::save';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

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


    private $imageUpload;
    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \O2TI\ShopByBrand\Model\PageFactory|null $pageFactory
     * @param \O2TI\ShopByBrand\Api\PageRepositoryInterface|null $pageRepository
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \O2TI\ShopByBrand\Model\PageFactory $pageFactory = null,
        \O2TI\ShopByBrand\Api\PageRepositoryInterface $pageRepository = null,
        \O2TI\ShopByBrand\Model\Page\Source\Image $imageUpload = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->pageFactory = $pageFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\O2TI\ShopByBrand\Model\PageFactory::class);
        $this->pageRepository = $pageRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\O2TI\ShopByBrand\Api\PageRepositoryInterface::class);
        $this->imageUpload = $imageUpload ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\O2TI\ShopByBrand\Model\Page\Source\Image::class);
        parent::__construct($context);
    }

    /**
     * Filter category data
     *
     * @deprecated 101.0.8
     * @param array $rawData
     * @return array
     */
    protected function _filteBrandPostData(array $rawData)
    {
        $data = $rawData;
        if (isset($data['image'])) {
            if (!empty($data['image']['delete'])) {
                $data['image'] = null;
            } else {
                if (isset($data['image'][0]['name'])) {
                    $data['image'] = $data['image'][0]['name'];
                } else {
                    unset($data['image']);
                }
            }
        }

        return $data;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->imagePreprocessing($data);
            $data = $this->_filteBrandPostData($data);
            $data = $this->dataProcessor->filter($data);

            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Page::STATUS_ENABLED;
            }
            if (empty($data['page_id'])) {
                $data['page_id'] = null;
            }

            /** @var \O2TI\ShopByBrand\Model\Page $model */
            $model = $this->pageFactory->create();

            $id = $this->getRequest()->getParam('page_id');
            if ($id) {
                try {
                    $model = $this->pageRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This page no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'brand_page_prepare_save',
                ['page' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->pageRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the page.'));
                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
            }

            $this->dataPersistor->set('brand_page', $data);
            return $resultRedirect->setPath('*/*/edit', ['page_id' => $this->getRequest()->getParam('page_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }


    /**
    * Sets image attribute data to false if image was removed
    *
    * @param array $data
    * @return array
    */
    

    public function imagePreprocessing($data)
    {
        if (isset($data['image']) && isset($data['image'][0]['tmp_name'])) {
            $data['image'] = $this->imageUpload->beforeSave($data);
        }
        return $data;
    }

    /**
     * Process result redirect
     *
     * @param \O2TI\ShopByBrand\Api\Data\PageInterface $model
     * @param \Magento\Backend\Model\View\Result\Redirect $resultRedirect
     * @param array $data
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws LocalizedException
     */
    private function processResultRedirect($model, $resultRedirect, $data)
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newPage = $this->pageFactory->create(['data' => $data]);
            $newPage->setId(null);
            $identifier = $model->getIdentifier() . '-' . uniqid();
            $newPage->setIdentifier($identifier);
            $newPage->setIsActive(false);
            $this->pageRepository->save($newPage);
            $this->messageManager->addSuccessMessage(__('You duplicated the page.'));
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'page_id' => $newPage->getId(),
                    '_current' => true
                ]
            );
        }
        $this->dataPersistor->clear('brand_page');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['page_id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
