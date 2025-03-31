<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\EventListener;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\ParserService;
use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

/**
 * @package Featdd\DpnGlossary\EventListener
 */
class AfterCacheableContentIsGeneratedEventListener
{
    /**
     * @var \Featdd\DpnGlossary\Service\ParserService
     */
    protected ParserService $parserService;

    public function __construct()
    {
        $this->parserService = GeneralUtility::makeInstance(ParserService::class);
    }

    /**
     * @param \TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function __invoke(AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent): void
    {
        $typoScriptFrontendController = $afterCacheableContentIsGeneratedEvent->getController();
        $request = $afterCacheableContentIsGeneratedEvent->getRequest();

        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            $settings = $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                'dpnglossary'
            );

            $isDisableParser = (bool)($settings['disableParser'] ?? false);
        } catch (InvalidConfigurationTypeException|RuntimeException) {
            $isDisableParser = true;
        }

        if ($typoScriptFrontendController->page['tx_dpnglossary_disable_parser']) {
            $isDisableParser = true;
        }

        if (!$isDisableParser) {
            $parsedHTML = $this->parserService->pageParser($request, $typoScriptFrontendController->content);

            if (is_string($parsedHTML)) {
                $typoScriptFrontendController->content = $parsedHTML;
            }
        }
    }
}
