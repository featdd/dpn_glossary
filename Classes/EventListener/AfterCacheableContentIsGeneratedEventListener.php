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
 *  (c) 2026 Daniel Dorndorf <dorndorf@featdd.de>
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
    protected ParserService $parserService;

    public function __construct(ParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    /**
     * @param \TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function __invoke(AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent): void
    {
        // Fallback for v13 event where content still lays in the TypoScriptFrontendController
        if (method_exists($afterCacheableContentIsGeneratedEvent, 'getController')) {
            $typoScriptFrontendController = $afterCacheableContentIsGeneratedEvent->getController();
            $setContentCallback = fn($content) => $typoScriptFrontendController->content = $content;
            $content = $typoScriptFrontendController->content;
        } else {
            $setContentCallback = fn($content) => $afterCacheableContentIsGeneratedEvent->setContent($content);
            $content = $afterCacheableContentIsGeneratedEvent->getContent();
        }

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

        $pageRecord = $request->getAttribute('frontend.page.information')->getPageRecord();

        if ($pageRecord['tx_dpnglossary_disable_parser'] ?? false) {
            $isDisableParser = true;
        }

        if (!$isDisableParser) {
            $parsedHTML = $this->parserService->pageParser($request, $content);

            if (is_string($parsedHTML)) {
                $setContentCallback($parsedHTML);
            }
        }
    }
}
