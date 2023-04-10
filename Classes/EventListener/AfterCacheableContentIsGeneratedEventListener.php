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
 *  (c) 2023 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

/**
 * @package Featdd\DpnGlossary\EventListener
 */
class AfterCacheableContentIsGeneratedEventListener
{
    use ParseHtmlEventTrait;

    /**
     * @param \TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function __invoke(AfterCacheableContentIsGeneratedEvent $afterCacheableContentIsGeneratedEvent): void
    {
        $this->parseHtml($afterCacheableContentIsGeneratedEvent->getController());
    }
}
