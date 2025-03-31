<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {

    ExtensionManagementUtility::addTCAcolumns('pages', [
        'tx_dpnglossary_disable_parser' => [
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.glossary_settings',
            'exclude' => true,
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:pages.parse_for_glossary',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
    ]);

    ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_dpnglossary_disable_parser',
        '',
        'after:php_tree_stop'
    );
});
