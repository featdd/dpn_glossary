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
    'Glossarypreview',
    array(
        'Term' => 'previewNewest, previewRandom, previewSelected',
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
        'Term' => 'show',
    )
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
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['dpn_glossary'] =
        \Featdd\DpnGlossary\Hook\RealurlHook::class . '->addConfig';
}
