<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_dpnglossary_domain_model_term'] = array(
	'ctrl' => $TCA['tx_dpnglossary_domain_model_term']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_diffsource, hidden, name, name_alternative, tooltiptext, descriptions, term_type, term_lang, images',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden;;1, name, name_alternative, tooltiptext, descriptions, term_type, term_lang, images'),
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
		'name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'tooltiptext' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.tooltiptext',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			),
		),
		'descriptions' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.descriptions',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_dpnglossary_domain_model_description',
				'foreign_field' => 'term',
				'foreign_label' => 'meaning',
				'minitems'		=> 1,
				'maxitems'      => 9999,
				'appearance' => array(
					'newRecordLinkTitle' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary.new_description',
				),
				'behaviour' => array(
					'localizationMode' => 'select',
					'localizeChildrenAtParentLocalization' => TRUE,
				),
			),
		),
		'name_alternative' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.name_alternative',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'term_type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_none', ''),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_definition', 'definition'),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_abbreviation', 'abbreviation'),
					array('LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_type_acronym', 'acronym'),
				),
				'default' => ''
			),
		),
		'term_lang' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.term_lang',
			'config' => array(
				'type' => 'input',
				'size' => 2,
				'max' => 2,
				'eval' => 'trim'
			),
		),
		'images' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dpn_glossary/Resources/Private/Language/locallang.xlf:tx_dpnglossary_domain_model_term.images',
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
