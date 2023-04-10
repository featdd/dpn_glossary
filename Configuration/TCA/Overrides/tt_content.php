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
            'Glossarypreview',
            'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.wizard_preview_title',
            'ext-dpn_glossary-preview-wizard-icon',
            'special'
        );

        $flexforms = [
            'dpnglossary_glossary' => null,
            'dpnglossary_glossarypreview' => '/Configuration/FlexForms/Preview.xml',
        ];

        foreach ($flexforms as $cType => $flexform) {
            if ($flexform !== null) {
                ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:dpn_glossary' . $flexform, $cType);

                ExtensionManagementUtility::addToAllTCAtypes(
                    'tt_content',
                    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform',
                    $cType,
                    'after:header'
                );
            }
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
            'tx_dpnglossary_disable_parser' => [
                'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tt_content.glossary_settings',
                'exclude' => true,
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'items' => [
                        [
                            0 => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tt_content.parse_for_glossary',
                            1 => '',
                            'labelChecked' => 'Enabled',
                            'labelUnchecked' => 'Disabled',
                            'invertStateDisplay' => true,
                        ],
                    ],
                ],
            ],
        ]);

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'tx_dpnglossary_disable_parser',
            '',
            'after:linkToTop'
        );

    }
);
