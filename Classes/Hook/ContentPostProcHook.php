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
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package DpnGlossary
 * @subpackage Hook
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
    public function main(array &$params, TypoScriptFrontendController $typoScriptFrontendController): void
    {
        $parsedHTML = $this->parserService->pageParser($typoScriptFrontendController->content);

        if (true === is_string($parsedHTML)) {
            $typoScriptFrontendController->content = $parsedHTML;
        }
    }
}
