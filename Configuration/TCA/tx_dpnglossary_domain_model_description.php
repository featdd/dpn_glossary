<?php

if (TRUE === version_compare(TYPO3_version, '7.5', '>=')) {
    $iconFile = 'EXT:dpn_glossary/Resources/Public/Icons/description.png';
} else {
    $iconFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/description.png	';
}

return array(
    'ctrl'      => array(
        'title'                  => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description',
        'label'                  => 'meaning',
        'sortby'                 => 'sorting',
        'tstamp'                 => 'tstamp',
        'crdate'                 => 'crdate',
        'cruser_id'              => 'cruser_id',
        'dividers2tabs'          => TRUE,
        'hideTable'              => TRUE,
        'versioningWS'           => 2,
        'versioning_followPages' => TRUE,
        'origUid'                => 't3_origuid',
        'languageField'          => 'sys_language_uid',
        'delete'                 => 'deleted',
        'enablecolumns'          => array(
            'disabled' => 'hidden',
        ),
        'searchFields'           => 'meaning,text,',
        'iconfile'               => $iconFile
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, meaning, text',
    ),
    'types'     => array(
        '1' => array('showitem' => 'meaning, text;;;richtext:rte_transform[mode=ts_links]'),
    ),
    'palettes'  => array(
        '1' => array('showitem' => ''),
    ),
    'columns'   => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config'  => array(
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'special'    => 'languages',
                'items'      => array(
                    array(
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ),
                ),
                'default'    => 0,
            ),
        ),
        't3ver_label'      => array(
            'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            )
        ),
        'hidden'           => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => array(
                'type' => 'check',
            ),
        ),
        'term'             => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'meaning'          => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description.meaning',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ),
        ),
        'text'             => array(
            'exclude'       => 1,
            'label'         => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_description.text',
            'defaultExtras' => 'richtext[]',
            'config'        => array(
                'type'    => 'text',
                'cols'    => 40,
                'rows'    => 15,
                'eval'    => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'title'         => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type'          => 'script',
                        'icon'          => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords' => 1,
                        'RTEonly'       => 1,
                        'module'        => array(
                            'name'          => 'wizard_rich_text_editor',
                            'urlParameters' => array(
                                'mode' => 'wizard',
                                'act'  => 'wizard_rte.php'
                            )
                        )
                    )
                )
            ),
        ),
    ),
);
