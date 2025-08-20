<?php
declare(strict_types=1);

use Featdd\DpnGlossary\Controller\TermController;
use Featdd\DpnGlossary\Routing\Aspect\StaticMultiRangeMapper;
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

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['StaticMultiRangeMapper'] = StaticMultiRangeMapper::class;

        if (false === isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dpnglossary_termscache'] = [];
        }
    }
);
