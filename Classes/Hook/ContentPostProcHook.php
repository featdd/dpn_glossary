<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Hook;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\ParserService;
use Featdd\DpnGlossary\Utility\ObjectUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package Featdd\DpnGlossary\Hook
 */
class ContentPostProcHook
{
    /**
     * @var \Featdd\DpnGlossary\Service\ParserService
     */
    protected $parserService;

    public function __construct()
    {
        $this->parserService = ObjectUtility::makeInstance(ParserService::class);
    }

    /**
     * @param array $params
     * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function all(array &$params, TypoScriptFrontendController $typoScriptFrontendController): void
    {
        if (true === $typoScriptFrontendController->no_cache) {
            $this->parseHtml($typoScriptFrontendController);
        }
    }

    /**
     * @param array $params
     * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function cached(array &$params, TypoScriptFrontendController $typoScriptFrontendController): void
    {
        $this->parseHtml($typoScriptFrontendController);
    }

    /**
     * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    protected function parseHtml(TypoScriptFrontendController $typoScriptFrontendController): void
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = ObjectUtility::makeInstance(ConfigurationManager::class);

        try {
            $settings = $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                'dpnglossary'
            );

            $isDisableParser = (bool) $settings['disableParser'];
        } catch (InvalidConfigurationTypeException $exception) {
            $isDisableParser = true;
        }

        if (false === $isDisableParser) {
            $parsedHTML = $this->parserService->pageParser($typoScriptFrontendController->content);

            if (true === is_string($parsedHTML)) {
                $typoScriptFrontendController->content = $parsedHTML;
            }
        }
    }
}
