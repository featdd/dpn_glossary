<?php

if (TRUE === version_compare(TYPO3_version, '7.5', '>=')) {
    $iconFile = 'EXT:dpn_glossary/Resources/Public/Icons/term.png';
} else {
    $iconFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/term.png';
}

return array(
    'ctrl'      => array(
        'title'          => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term',
        'label'          => 'name',
        'tstamp'         => 'tstamp',
        'crdate'         => 'crdate',
        'cruser_id'      => 'cruser_id',
        'dividers2tabs'  => TRUE,
        'default_sortby' => 'ORDER BY name ASC',

        'versioningWS'           => 2,
        'versioning_followPages' => TRUE,
        'origUid'                => 't3_origuid',
        'languageField'          => 'sys_language_uid',
        'delete'                 => 'deleted',
        'enablecolumns'          => array(
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ),
        'searchFields'           => 'name,tooltiptext,descriptions,synonyms,term_type,term_lang,images,',
        'iconfile'               => $iconFile
    ),
    'interface' => array(
        'showRecordFieldList' => 'sys_language_uid, hidden, name, tooltiptext, synonyms, descriptions, term_type, term_lang, media, starttime, endtime',
    ),
    'types'     => array(
        '1' => array('showitem' => 'sys_language_uid, hidden;;1, name, tooltiptext, synonyms, descriptions, term_type, term_lang, media, --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
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
        'starttime'        => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config'  => array(
                'type'     => 'input',
                'size'     => 13,
                'max'      => 20,
                'eval'     => 'datetime',
                'checkbox' => 0,
                'default'  => 0,
                'range'    => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime'          => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config'  => array(
                'type'     => 'input',
                'size'     => 13,
                'max'      => 20,
                'eval'     => 'datetime',
                'checkbox' => 0,
                'default'  => 0,
                'range'    => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'hidden'           => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => array(
                'type' => 'check',
            ),
        ),
        'name'             => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.name',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'tooltiptext'      => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.tooltiptext',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'descriptions'     => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.descriptions',
            'config'  => array(
                'type'          => 'inline',
                'foreign_table' => 'tx_dpnglossary_domain_model_description',
                'foreign_field' => 'term',
                'foreign_label' => 'meaning',
                'minitems'      => 1,
                'maxitems'      => 9999,
                'appearance'    => array(
                    'newRecordLinkTitle' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.new_description',
                    'useSortable'        => 1,
                ),
                'behaviour'     => array(
                    'localizationMode'                     => 'select',
                    'localizeChildrenAtParentLocalization' => TRUE,
                ),
            ),
        ),
        'synonyms'         => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.synonyms',
            'config'  => array(
                'type'          => 'inline',
                'foreign_table' => 'tx_dpnglossary_domain_model_synonym',
                'foreign_field' => 'term',
                'foreign_label' => 'name',
                'minitems'      => 1,
                'maxitems'      => 9999,
                'appearance'    => array(
                    'newRecordLinkTitle' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.new_synonym',
                    'useSortable'        => 1,
                ),
                'behaviour'     => array(
                    'localizationMode'                     => 'select',
                    'localizeChildrenAtParentLocalization' => TRUE,
                ),
            ),
        ),
        'term_type'        => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type',
            'config'  => array(
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => array(
                    array(
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_none',
                        ''
                    ),
                    array(
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_definition',
                        'definition'
                    ),
                    array(
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_abbreviation',
                        'abbreviation'
                    ),
                    array(
                        'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_acronym',
                        'acronym'
                    ),
                ),
                'default'    => ''
            ),
        ),
        'term_lang'        => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_lang',
            'config'  => array(
                'type' => 'input',
                'size' => 2,
                'max'  => 2,
                'eval' => 'trim,lower,nospace,alpha'
            ),
        ),
        'media'            => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.media',
            'config'  => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'media',
                array(
                    'appearance'    => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                    ),
                    'foreign_types' => array(
                        '0'                                                 => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT        => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE       => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO       => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO       => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => array(
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        )
                    ),
                    'maxitems'      => 999
                ),
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
        ),
    ),
);
