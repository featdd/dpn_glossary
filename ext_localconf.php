<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Dpn.' . $_EXTKEY,
	'Glossarylist',
	array(
		'Term' => 'list'
	),
	// non-cacheable actions
	array(
		'Term' => ''
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Dpn.' . $_EXTKEY,
	'Glossarydetail',
	array(
		'Term' => 'show'
	),
	// non-cacheable actions
	array(
		'Term' => ''
	)
);

$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
if (array_key_exists('contentProc', $configuration) && $configuration['contentProc']) {
  $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'Dpn\DpnGlossary\Service\ParserService->pageParser';
}

if (TRUE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
	if (TRUE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'])) {
		if (FALSE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'])) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = array();
		}

		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] += array(
			$_EXTKEY . '_list_RealUrlConfig' => array(
				array(
					'GETvar' => 'tx_dpnglossary_glossarylist[controller]',
					'noMatch' => 'bypass'
				),
				array(
					'GETvar' => 'tx_dpnglossary_glossarylist[action]',
					'noMatch' => 'bypass'
				),
				array(
					'GETvar' => 'tx_dpnglossary_glossarylist[@widget_0][character]',
				),
			),
			$_EXTKEY . '_detail_RealUrlConfig' => array(
				array(
					'GETvar' => 'tx_dpnglossary_glossarydetail[controller]',
					'noMatch' => 'bypass'
				),
				array(
					'GETvar' => 'tx_dpnglossary_glossarydetail[action]',
					'noMatch' => 'bypass'
				),
				array(
					'GETvar' => 'tx_dpnglossary_glossarydetail[term]',
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
					'GETvar' => 'tx_dpnglossary_glossarydetail[pageUid]'
				),
			),
		);
	}
}
