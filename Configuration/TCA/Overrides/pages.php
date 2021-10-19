<?php
defined('TYPO3_MODE') || die();

call_user_func(function() {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', [
        'tx_dpnglossary_parsing_settings' => [
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.glossarySettings',
            'exclude' => true,
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.parseForGlossary',
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
        'tx_dpnglossary_parsing_settings',
        '',
        'after:php_tree_stop'
    );
});
