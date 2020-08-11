<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('my_product', 'Configuration/TypoScript', 'Products');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_myproduct_domain_model_product', 'EXT:my_product/Resources/Private/Language/locallang_csh_tx_myproduct_domain_model_product.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_myproduct_domain_model_product');

    }
);
