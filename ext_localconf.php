<?php
declare(strict_types=1);

use Featdd\DpnGlossary\Controller\TermController;
use Featdd\DpnGlossary\Routing\Aspect\StaticMultiRangeMapper;
use Featdd\DpnGlossary\Updates\PluginCTypeMigrationUpdateWizard;
use Featdd\DpnGlossary\Updates\PluginSwitchableControllerMigrationUpdateWizard;
use Featdd\DpnGlossary\Updates\SlugUpdateWizard;
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

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][SlugUpdateWizard::class] = SlugUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][PluginCTypeMigrationUpdateWizard::class] = PluginCTypeMigrationUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][PluginSwitchableControllerMigrationUpdateWizard::class] = PluginSwitchableControllerMigrationUpdateWizard::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['StaticMultiRangeMapper'] = StaticMultiRangeMapper::class;

        if (false === isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
        }
    }
);
