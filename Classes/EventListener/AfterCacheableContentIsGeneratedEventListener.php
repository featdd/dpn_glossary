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
use Featdd\DpnGlossary\Utility\SettingsUtility;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

class AfterCacheableContentIsGeneratedEventListener
{
    public function __construct(
        protected ParserService $parserService,
        protected TimeTracker $timeTracker
    )
    {}

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
        $isDisableParser = (bool) ((new SettingsUtility(request: $request))->getSetting('disableParser') ?? false);
        $pageRecord = $request->getAttribute('frontend.page.information')->getPageRecord();

        if ($pageRecord['tx_dpnglossary_disable_parser'] ?? false) {
            $isDisableParser = true;
        }

        if (!$isDisableParser) {
            $this->timeTracker->incStackPointer();
            $this->timeTracker->push('Parsing via EXT:dpn_glossary');
            $parsedHTML = $this->parserService->pageParser($request, $content);
            $this->timeTracker->pull();
            $this->timeTracker->decStackPointer();

            if (is_string($parsedHTML)) {
                $setContentCallback($parsedHTML);
            }
        }
    }
}
