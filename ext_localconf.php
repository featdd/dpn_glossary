<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Featdd.' . $_EXTKEY,
    'Glossarylist',
    array(
        'Term' => 'list',
    ),
    // non-cacheable actions
    array(
        'Term' => '',
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Featdd.' . $_EXTKEY,
    'Glossarydetail',
    array(
        'Term' => 'show',
    ),
    // non-cacheable actions
    array(
        'Term' => '',
    )
);

if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = \Featdd\DpnGlossary\Hook\ContentPostProcAllHook::class . '->main';
}

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'ext-dpn_glossary-list-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:dpn_glossary/Resources/Public/Icons/wizard_list.png']
);

$iconRegistry->registerIcon(
    'ext-dpn_glossary-detail-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:dpn_glossary/Resources/Public/Icons/wizard_detail.png']
);

if (
    TRUE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']) &&
    TRUE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'])
) {
    if (FALSE === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = array();
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] += array(
        $_EXTKEY . '_list_RealUrlConfig'   => array(
            array(
                'GETvar'  => 'tx_dpnglossary_glossarylist[controller]',
                'noMatch' => 'bypass'
            ),
            array(
                'GETvar'  => 'tx_dpnglossary_glossarylist[action]',
                'noMatch' => 'bypass'
            ),
            array(
                'GETvar' => 'tx_dpnglossary_glossarylist[@widget_0][character]',
            ),
        ),
        $_EXTKEY . '_detail_RealUrlConfig' => array(
            array(
                'GETvar'  => 'tx_dpnglossary_glossarydetail[controller]',
                'noMatch' => 'bypass'
            ),
            array(
                'GETvar'  => 'tx_dpnglossary_glossarydetail[action]',
                'noMatch' => 'bypass'
            ),
            array(
                'GETvar'      => 'tx_dpnglossary_glossarydetail[term]',
                'lookUpTable' => array(
                    'table'                 => 'tx_dpnglossary_domain_model_term',
                    'id_field'              => 'uid',
                    'alias_field'           => 'name',
                    'addWhereClause'        => ' AND NOT deleted',
                    'useUniqueCache'        => 1,
                    'useUniqueCache_conf'   => array(
                        'strtolower'     => 1,
                        'spaceCharacter' => '-'
                    ),
                    'languageGetVar'        => 'L',
                    'languageExceptionUids' => '',
                    'languageField'         => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                    'autoUpdate'            => 1,
                    'expireDays'            => 180
                ),
            ),
            array(
                'GETvar' => 'tx_dpnglossary_glossarydetail[pageUid]'
            ),
        ),
    );
}
