<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            'Glossarylist',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_list_title'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            'Glossarypreview',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_preview_title'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            'Glossarydetail',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_detail_title'
        );

        $flexforms = [
            'dpnglossary_glossarypreview' => '/Configuration/FlexForms/Preview.xml',
        ];

        foreach ($flexforms as $plugin => $flexform) {
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$plugin] = 'recursive,select_key,pages';
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$plugin] = 'pi_flexform';

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($plugin, 'FILE:EXT:' . $extKey . $flexform);
        }

        if (version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), '7.5.0', '>=')) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:dpn_glossary/Configuration/TSconfig/ContentElementWizard.t3s">');
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $extKey,
            'Configuration/TypoScript',
            'dreipunktnull Glossar'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpnglossary_domain_model_term');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpnglossary_domain_model_description');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_dpnglossary_domain_model_synonym');
    },
    $_EXTKEY
);
