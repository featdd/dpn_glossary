<?php
defined('TYPO3_MODE') || die();

call_user_func(function() {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', [
        'tx_dpnglossary_disable_parser' => [
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.glossary_settings',
            'exclude' => true,
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.parse_for_glossary',
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
        'pages',
        'tx_dpnglossary_disable_parser',
        '',
        'after:php_tree_stop'
    );
});
