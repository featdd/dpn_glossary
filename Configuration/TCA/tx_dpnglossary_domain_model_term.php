<?php

use Featdd\DpnGlossary\UserFunc\SlugPreviewUserFunc;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
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
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'searchFields' => 'name,tooltiptext,descriptions,synonyms,term_type,term_lang,images,',
        'iconfile' => 'EXT:dpn_glossary/Resources/Public/Icons/Term.png',
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
                'type' => 'language',
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
                        'label' => '',
                        'value' => 0,
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
                'type' => 'datetime',
                'size' => 13,
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
                'type' => 'datetime',
                'size' => 13,
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
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'url_segment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.url_segment',
            'description' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.url_segment.description',
            'config' => [
                'type' => 'slug',
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
                'appearance' => [
                    'prefix' => SlugPreviewUserFunc::class . '->slugPrefixUserFunc',
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
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_none',
                        'value' => '',
                    ],
                    [
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_definition',
                        'value' => 'definition',
                    ],
                    [
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_abbreviation',
                        'value' => 'abbreviation',
                    ],
                    [
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_acronym',
                        'value' => 'acronym',
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
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_mode_normal',
                        'value' => '',
                    ],
                    [
                        'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_mode_link',
                        'value' => 'link',
                    ],
                ],
            ],
        ],
        'term_link' => [
            'exclude' => true,
            'displayCond' => 'FIELD:term_mode:=:link',
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_link',
            'config' => [
                'type' => 'link',
                'size' => 30,
                'max' => 255,
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
                        'label' => '',
                        'value' => 0,
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
                        'label' => '',
                        'value' => 0,
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ],
        'max_replacements' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.max_replacements',
            'config' => [
                'type' => 'number',
                'default' => -1,
                'size' => 10,
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
            'config' => [
                'type' => 'file',
                'allowed' => 'common-media-types',
            ],
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
