<?php

if (true === version_compare(TYPO3_version, '7.5', '>=')) {
    $iconFile = 'EXT:dpn_glossary/Resources/Public/Icons/description.png';
} else {
    $iconFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('dpn_glossary') . 'Resources/Public/Icons/description.png	';
}

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description',
        'label' => 'meaning',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'hideTable' => true,
        'versioningWS' => 2,
        'versioning_followPages' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'searchFields' => 'meaning,text,',
        'iconfile' => $iconFile,
    ),
    'interface' => array(
        'showRecordFieldList' => 'l10n_diffsource, meaning, text',
    ),
    'types' => array(
        '1' => array('showitem' => 'l10n_diffsource, meaning, text;;;richtext:rte_transform[mode=ts_links]'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'default' => 0,
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                ),
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_dpnglossary_domain_model_description',
                'foreign_table_where' => 'AND tx_dpnglossary_domain_model_description.pid=###CURRENT_PID### AND tx_dpnglossary_domain_model_description.sys_language_uid IN (-1,0)',
                'showIconTable' => false,
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        't3ver_label' => array(
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ),
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'term' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'meaning' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description.meaning',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ),
        ),
        'text' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description.text',
            'defaultExtras' => 'richtext[]',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rich_text_editor',
                            'urlParameters' => array(
                                'mode' => 'wizard',
                                'act' => 'wizard_rte.php',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
