<?php
declare(strict_types=1);

use Featdd\DpnGlossary\Controller\TermController;
use Featdd\DpnGlossary\Hook\ContentPostProcHook;
use Featdd\DpnGlossary\Routing\Aspect\StaticMultiRangeMapper;
use Featdd\DpnGlossary\Updates\PluginCTypeMigrationUpdateWizard;
use Featdd\DpnGlossary\Updates\PluginSwitchableControllerMigrationUpdateWizard;
use Featdd\DpnGlossary\Updates\SlugUpdateWizard;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionUtility::configurePlugin(
            'DpnGlossary',
            'Glossary',
            [TermController::class => 'list, show'],
            [TermController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            'DpnGlossary',
            'Glossarypreviewnewest',
            [TermController::class => 'previewNewest'],
            [TermController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            'DpnGlossary',
            'Glossarypreviewrandom',
            [TermController::class => 'previewRandom'],
            [TermController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            'DpnGlossary',
            'Glossarypreviewselected',
            [TermController::class => 'previewSelected'],
            [TermController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        // These hooks were removed in v12 but still needed in v11
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = ContentPostProcHook::class . '->all';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-cached'][] = ContentPostProcHook::class . '->cached';

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][SlugUpdateWizard::class] = SlugUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][PluginCTypeMigrationUpdateWizard::class] = PluginCTypeMigrationUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][PluginSwitchableControllerMigrationUpdateWizard::class] = PluginSwitchableControllerMigrationUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['StaticMultiRangeMapper'] = StaticMultiRangeMapper::class;

        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

        $iconRegistry->registerIcon(
            'ext-dpn_glossary-wizard-icon',
            SvgIconProvider::class,
            ['source' => 'EXT:dpn_glossary/Resources/Public/Icons/Plugin_Glossary.svg']
        );

        $iconRegistry->registerIcon(
            'ext-dpn_glossary-preview-wizard-icon',
            SvgIconProvider::class,
            ['source' => 'EXT:dpn_glossary/Resources/Public/Icons/Plugin_Glossarypreview.svg']
        );

        if (false === isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
        }

        ExtensionManagementUtility::addPageTSConfig('@import \'EXT:dpn_glossary/Configuration/TSconfig/*.tsconfig\'');
    }
);
