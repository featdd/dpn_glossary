<?php
namespace Featdd\DpnGlossary\Hook;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Service\ParserService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
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
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->parserService = $objectManager->get(ParserService::class);
    }

    /**
     * @param array $params
     * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController
     * @throws \Featdd\DpnGlossary\Service\Exception
     */
    public function main(array &$params, TypoScriptFrontendController $typoScriptFrontendController): void
    {
        $parsedHTML = $this->parserService->pageParser($typoScriptFrontendController->content);

        if (false !== $parsedHTML) {
            $typoScriptFrontendController->content = $parsedHTML;
        }
    }
}
