<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY name ASC',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,tooltiptext,descriptions,synonyms,term_type,term_lang,images,',
        'iconfile' => 'EXT:dpn_glossary/Resources/Public/Icons/term.png',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, --palette--;;hidden_exludefromparsing_casesensitive, name, url_segment, tooltiptext, term_mode, term_link, synonyms, descriptions, term_type, term_lang, media, --div--;LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.divider.seo, seo_title, meta_description, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'palettes' => [
        'hidden_exludefromparsing_casesensitive' => ['showitem' => 'hidden, case_sensitive, --linebreak--, max_replacements, exclude_from_parsing'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple',
                    ],
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_dpnglossary_domain_model_term',
                'foreign_table_where' => 'AND tx_dpnglossary_domain_model_term.pid=###CURRENT_PID### AND tx_dpnglossary_domain_model_term.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],

        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,required',
            ],
        ],
        'url_segment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.url_segment',
            'config' => [
                'type' => 'slug',
                'renderType' => 'recordPreview',
                'size' => 30,
                'max' => 255,
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'generatorOptions' => [
                    'fields' => ['name'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
            ],
        ],
        'tooltiptext' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.tooltiptext',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'term_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'maxitems' => 1,
                'items' => [
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_none',
                        '',
                    ],
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_definition',
                        'definition',
                    ],
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_abbreviation',
                        'abbreviation',
                    ],
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_acronym',
                        'acronym',
                    ],
                ],
            ],
        ],
        'term_lang' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_lang',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'max' => 2,
                'eval' => 'trim,lower,nospace,alpha',
            ],
        ],
        'term_mode' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_mode',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'maxitems' => 1,
                'items' => [
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_mode_normal',
                        '',
                    ],
                    [
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_mode_link',
                        'link',
                    ],
                ],
            ],
        ],
        'term_link' => [
            'exclude' => true,
            'displayCond' => 'FIELD:term_mode:=:link',
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'softref' => 'typolink',
            ],
        ],
        'exclude_from_parsing' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.exclude_from_parsing',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ],
        'case_sensitive' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.case_sensitive',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ],
        'max_replacements' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.max_replacements',
            'config' => [
                'type' => 'input',
                'default' => -1,
                'size' => 10,
                'eval' => 'int',
                'range' => [
                    'lower' => -1,
                ],
            ],
        ],
        'descriptions' => [
            'exclude' => true,
            'displayCond' => 'FIELD:term_mode:!=:link',
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.descriptions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_dpnglossary_domain_model_description',
                'foreign_field' => 'term',
                'foreign_label' => 'meaning',
                'foreign_sortby' => 'sorting',
                'minitems' => 1,
                'maxitems' => 9999,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.new_description',
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'collapseAll' => true,
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],
        'synonyms' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.synonyms',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_dpnglossary_domain_model_synonym',
                'foreign_field' => 'term',
                'foreign_label' => 'name',
                'foreign_sortby' => 'sorting',
                'minitems' => 0,
                'maxitems' => 9999,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.new_synonym',
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'collapseAll' => true,
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],
        'media' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.media',
            'displayCond' => 'FIELD:term_mode:!=:link',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'media',
                [
                    'maxitems' => 999,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette',
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
        ],
        'seo_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.seo_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'meta_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.meta_description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 10,
                'eval' => 'trim',
            ],
        ],
    ],
];
