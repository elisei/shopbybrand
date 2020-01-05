<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace O2TI\ShopByBrand\Api\Data;

/**
 * CMS page interface.
 * @api
 * @since 100.0.2
 */
interface PageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PAGE_ID                  = 'page_id';
    const IDENTIFIER               = 'identifier';
    const TITLE                    = 'title';
    const PAGE_LAYOUT              = 'page_layout';
    const META_TITLE               = 'meta_title';
    const META_KEYWORDS            = 'meta_keywords';
    const META_DESCRIPTION         = 'meta_description';
    const CONTENT                  = 'content';
    const CREATION_TIME            = 'creation_time';
    const UPDATE_TIME              = 'update_time';
    const LAYOUT_UPDATE_XML        = 'layout_update_xml';
    const CUSTOM_THEME             = 'custom_theme';
    const CUSTOM_ROOT_TEMPLATE     = 'custom_root_template';
    const CUSTOM_LAYOUT_UPDATE_XML = 'custom_layout_update_xml';
    const CUSTOM_THEME_FROM        = 'custom_theme_from';
    const CUSTOM_THEME_TO          = 'custom_theme_to';
    const IS_ACTIVE                = 'is_active';

    const ATTRIBUTE_ID             = 'attribute_id';
    const IMAGE                    = 'image';
    const IS_FEATURED              = 'is_featured';
    

    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get page layout
     *
     * @return string|null
     */
    public function getPageLayout();

    /**
     * Get meta title
     *
     * @return string|null
     * @since 101.0.0
     */
    public function getMetaTitle();

    /**
     * Get meta keywords
     *
     * @return string|null
     */
    public function getMetaKeywords();

    /**
     * Get meta description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Get layout update xml
     *
     * @return string|null
     */
    public function getLayoutUpdateXml();

    /**
     * Get custom theme
     *
     * @return string|null
     */
    public function getCustomTheme();

    /**
     * Get custom root template
     *
     * @return string|null
     */
    public function getCustomRootTemplate();

    /**
     * Get custom layout update xml
     *
     * @return string|null
     */
    public function getCustomLayoutUpdateXml();

    /**
     * Get custom theme from
     *
     * @return string|null
     */
    public function getCustomThemeFrom();

    /**
     * Get custom theme to
     *
     * @return string|null
     */
    public function getCustomThemeTo();

    /**
     * Get attribute id to manufecture
     *
     * @return string
     */
    public function getAttributeId();

    public function getImage();

    public function getIsFeatured();
    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set title
     *
     * @param string $title
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setTitle($title);

    /**
     * Set page layout
     *
     * @param string $pageLayout
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setPageLayout($pageLayout);

    /**
     * Set meta title
     *
     * @param string $metaTitle
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     * @since 101.0.0
     */
    public function setMetaTitle($metaTitle);

    /**
     * Set meta keywords
     *
     * @param string $metaKeywords
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Set meta description
     *
     * @param string $metaDescription
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * Set content
     *
     * @param string $content
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setContent($content);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set layout update xml
     *
     * @param string $layoutUpdateXml
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setLayoutUpdateXml($layoutUpdateXml);

    /**
     * Set custom theme
     *
     * @param string $customTheme
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCustomTheme($customTheme);

    /**
     * Set custom root template
     *
     * @param string $customRootTemplate
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCustomRootTemplate($customRootTemplate);

    /**
     * Set custom layout update xml
     *
     * @param string $customLayoutUpdateXml
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCustomLayoutUpdateXml($customLayoutUpdateXml);

    /**
     * Set custom theme from
     *
     * @param string $customThemeFrom
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCustomThemeFrom($customThemeFrom);

    /**
     * Set custom theme to
     *
     * @param string $customThemeTo
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setCustomThemeTo($customThemeTo);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \O2TI\ShopByBrand\Api\Data\PageInterface
     */
    public function setIsActive($isActive);

    /**
    * Set is active
    *
    * @param int|bool $isActive
    * @return \O2TI\ShopByBrand\Api\Data\PageInterface
    */
    public function setAttributeId($attributeId);

    public function setImage($image);

    public function setIsFeatured($isFeatured);
}
