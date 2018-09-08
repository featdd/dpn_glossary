<?php
defined('TYPO3_MODE') || die();

call_user_func(
    function () {
        $flexforms = [
            'dpnglossary_glossarypreview' => '/Configuration/FlexForms/Preview.xml',
        ];

        foreach ($flexforms as $plugin => $flexform) {
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$plugin] = 'recursive,select_key,pages';
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$plugin] = 'pi_flexform';

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($plugin, 'FILE:EXT:dpn_glossary' . $flexform);
        }
    }
);
