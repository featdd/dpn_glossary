<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionUtility::registerPlugin(
            'DpnGlossary',
            'Glossary',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_glossary_title',
            'ext-dpn_glossary-wizard-icon',
            'special'
        );

        ExtensionUtility::registerPlugin(
            'DpnGlossary',
            'Glossarypreviewnewest',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_preview_newest_title',
            'ext-dpn_glossary-preview-wizard-icon',
            'special'
        );

        ExtensionUtility::registerPlugin(
            'DpnGlossary',
            'Glossarypreviewrandom',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_preview_random_title',
            'ext-dpn_glossary-preview-wizard-icon',
            'special'
        );

        ExtensionUtility::registerPlugin(
            'DpnGlossary',
            'Glossarypreviewselected',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_preview_selected_title',
            'ext-dpn_glossary-preview-wizard-icon',
            'special'
        );

        // Add FlexForm for main glossary plugin
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:dpn_glossary/Configuration/FlexForms/List.xml',
            'dpnglossary_glossary'
        );
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform',
            'dpnglossary_glossary',
            'after:header'
        );

        $flexforms = [
            'dpnglossary_glossarypreviewnewest' => '/Configuration/FlexForms/PreviewNewest.xml',
            'dpnglossary_glossarypreviewrandom' => '/Configuration/FlexForms/PreviewRandom.xml',
            'dpnglossary_glossarypreviewselected' => '/Configuration/FlexForms/PreviewSelected.xml',
        ];

        foreach ($flexforms as $cType => $flexform) {
            ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:dpn_glossary' . $flexform, $cType);

            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform',
                $cType,
                'after:header'
            );
        }

        // Hide preview plugins from CType selector
        $pluginsToHide = [
            'dpnglossary_glossarypreviewnewest',
            'dpnglossary_glossarypreviewrandom',
            'dpnglossary_glossarypreviewselected',
        ];

        foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $key => $item) {
            if (isset($item['value']) && in_array($item['value'], $pluginsToHide, true)) {
                unset($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][$key]);
            }
        }

        ExtensionManagementUtility::addTCAcolumns('tt_content', [
            'tx_dpnglossary_disable_parser' => [
                'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tt_content.glossary_settings',
                'exclude' => true,
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'items' => [
                        [
                            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tt_content.parse_for_glossary',
                            'labelChecked' => 'Enabled',
                            'labelUnchecked' => 'Disabled',
                            'invertStateDisplay' => true,
                        ],
                    ],
                ],
            ],
        ]);

        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'tx_dpnglossary_disable_parser',
            '',
            'after:linkToTop'
        );

    }
);
