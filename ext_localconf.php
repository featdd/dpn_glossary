<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Featdd.' . $extKey,
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
            'Featdd.' . $extKey,
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
            'Featdd.' . $extKey,
            'Glossarydetail',
            [
                'Term' => 'show',
            ],
            // non-cacheable actions
            [
                'Term' => 'show',
            ]
        );

        if (50400 <= PHP_VERSION_ID) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = \Featdd\DpnGlossary\Hook\ContentPostProcHook::class . '->main';
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-cached'][] = \Featdd\DpnGlossary\Hook\ContentPostProcHook::class . '->main';
        }

        if (true === version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), '7.5.0', '>=')) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Featdd\DpnGlossary\Hook\RenderPreProcessHook::class . '->main';

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

        if (false === \is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
        }
    },
    $_EXTKEY
);
