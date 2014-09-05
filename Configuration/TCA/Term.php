<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_dpnglossary_domain_model_term'] = array(
	'ctrl' => $TCA['tx_dpnglossary_domain_model_term']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, name_alternative, tooltiptext, description, term_type, term_lang, images, related',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name, name_alternative, tooltiptext, description, term_type, term_lang, images, related,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
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
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_dpnglossary_domain_model_term',
				'foreign_table_where' => 'AND tx_dpnglossary_domain_model_term.pid=###CURRENT_PID### AND tx_dpnglossary_domain_model_term.sys_language_uid IN (-1,0)',
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
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'tooltiptext' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.tooltiptext',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim,required'
			),
		),
		'name_alternative' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.name_alternative',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'term_type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_type_none', ''),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_type_definition', 'definition'),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_type_abbreviation', 'abbreviation'),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_type_acronym', 'acronym'),
				),
				'default' => ''
			),
		),
		'term_lang' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.term_lang',
			'config' => array(
				'type' => 'input',
				'size' => 2,
				'max' => 2,
				'eval' => 'trim'
			),
		),
		'images' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang_db.xlf:tx_dpnglossary_domain_model_term.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('images', array(
				'appearance' => array(
					'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
				),
				'minitems' => 0,
				'maxitems' => 10,
			), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
		),
	),
);