<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Dpn.' . $_EXTKEY,
	'Glossary',
	array(
		'Term' => 'list, character, show'
	),
	// non-cacheable actions
	array(
		'Term' => ''
	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'Dpn\DpnGlossary\Service\WrapperService->contentParser';