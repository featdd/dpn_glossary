<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Featdd.' . $_EXTKEY,
    'Glossarylist',
    [
        'Term' => 'list',
    ],
    // non-cacheable actions
    [
        'Term' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Featdd.' . $_EXTKEY,
    'Glossarypreview',
    [
        'Term' => 'previewNewest, previewRandom, previewSelected',
    ],
    // non-cacheable actions
    [
        'Term' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Featdd.' . $_EXTKEY,
    'Glossarydetail',
    [
        'Term' => 'show',
    ],
    // non-cacheable actions
    [
        'Term' => 'show',
    ]
);

if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = \Featdd\DpnGlossary\Hook\ContentPostProcAllHook::class . '->main';
}

if (true === version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), '7.5.0', '>=')) {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Featdd\DpnGlossary\Hook\RenderPreProcessHook::class . '->main';
    }

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    $iconRegistry->registerIcon(
        'ext-dpn_glossary-list-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
        ['name' => 'list']
    );

    $iconRegistry->registerIcon(
        'ext-dpn_glossary-preview-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
        ['name' => 'external-link-square']
    );

    $iconRegistry->registerIcon(
        'ext-dpn_glossary-detail-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
        ['name' => 'search']
    );
}

if (
    true === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']) &&
    true === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'])
) {
    if (false === is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = [];
    }

    $realurlConfig = [
        $_EXTKEY . '_list_RealUrlConfig' => [
            [
                'GETvar' => 'tx_dpnglossary_glossarylist[controller]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_dpnglossary_glossarylist[action]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_dpnglossary_glossarylist[@widget_0][character]',
            ],
        ],
        $_EXTKEY . '_detail_RealUrlConfig' => [
            [
                'GETvar' => 'tx_dpnglossary_glossarydetail[controller]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_dpnglossary_glossarydetail[action]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_dpnglossary_glossarydetail[term]',
                'lookUpTable' => [
                    'table' => 'tx_dpnglossary_domain_model_term',
                    'id_field' => 'uid',
                    'alias_field' => 'name',
                    'addWhereClause' => ' AND NOT deleted',
                    'useUniqueCache' => 1,
                    'useUniqueCache_conf' => [
                        'strtolower' => 1,
                        'spaceCharacter' => '-',
                    ],
                    'languageGetVar' => 'L',
                    'languageExceptionUids' => '',
                    'languageField' => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                    'autoUpdate' => 1,
                    'expireDays' => 180,
                ],
            ],
            [
                'GETvar' => 'tx_dpnglossary_glossarydetail[pageUid]',
                'optional' => true,
            ],
        ],
    ];

    $realurlConfig += $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = $realurlConfig;
}

if (false === is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
}
