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
 *  (c) 2023 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\EventListener\ParseHtmlEventTrait;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package Featdd\DpnGlossary\Hook
 */
class ContentPostProcHook
{
    use ParseHtmlEventTrait;

    /**
     * @param array $params
     * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function all(array &$params, TypoScriptFrontendController $typoScriptFrontendController): void
    {
        if ($typoScriptFrontendController->no_cache) {
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
}
