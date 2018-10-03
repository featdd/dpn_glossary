<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Featdd.' . $extKey,
            'Glossary',
            [
                'Term' => 'list, show',
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

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = \Featdd\DpnGlossary\Hook\ContentPostProcHook::class . '->main';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-cached'][] = \Featdd\DpnGlossary\Hook\ContentPostProcHook::class . '->main';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][\Featdd\DpnGlossary\Updates\SlugUpdateWizard::class] = \Featdd\DpnGlossary\Updates\SlugUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['StaticMultiRangeMapper'] = \Featdd\DpnGlossary\Routing\Aspect\StaticMultiRangeMapper::class;

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'ext-dpn_glossary-wizard-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'list']
        );

        $iconRegistry->registerIcon(
            'ext-dpn_glossary-preview-wizard-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'external-link-square']
        );

        if (false === \is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
        }
    },
    $_EXTKEY
);
