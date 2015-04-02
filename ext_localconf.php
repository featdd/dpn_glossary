<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Dpn.' . $_EXTKEY,
	'Glossary',
	array(
		'Term' => 'list, show'
	),
	// non-cacheable actions
	array(
		'Term' => ''
	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'Dpn\DpnGlossary\Service\WrapperService->contentParser';

if (TRUE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {

	if (FALSE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'])) {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = array();
	}

	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] += array(
		$_EXTKEY . '_RealUrlConfig' => array(
			array(
				'GETvar' => 'tx_dpnglossary_glossary[controller]',
				'noMatch' => 'bypass'
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[action]',
				'valueMap' => array(
					'detail' => 'show',
				),
				'valueDefault' => 'list',
				'noMatch' => 'bypass'
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[term]',
				'lookUpTable' => array(
					'table' => 'tx_dpnglossary_domain_model_term',
					'id_field' => 'uid',
					'alias_field' => 'name',
					'addWhereClause' => ' AND NOT deleted',
					'useUniqueCache' => 1,
					'useUniqueCache_conf' => array(
						'strtolower' => 1,
						'spaceCharacter' => '-'
					),
					'languageGetVar' => 'L',
					'languageExceptionUids' => '',
					'languageField' => 'sys_language_uid',
					'transOrigPointerField' => 'l10n_parent',
					'autoUpdate' => 1,
					'expireDays' => 180
				),
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[@widget_0][character]',
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[pageUid]'
			),
		),
	);
}
