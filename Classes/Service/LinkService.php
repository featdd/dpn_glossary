<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Service;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Featdd\DpnGlossary\Service
 */
class LinkService implements SingletonInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected $uriBuilder;

    public function __construct()
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $configurationManager->setContentObject($contentObjectRenderer);
        $this->uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $this->uriBuilder->injectConfigurationManager($configurationManager);
    }

    /**
     * @param int $pageUid
     * @param array $arguments
     * @param bool $absolute
     * @param int $sysLanguageUid
     * @return string
     */
    public function buildLink(int $pageUid, array $arguments = [], bool $absolute = false, int $sysLanguageUid = 0): string
    {
        if (0 < $sysLanguageUid) {
            $arguments = array_merge(
                ['L' => $sysLanguageUid],
                $arguments
            );
        }

        return $this->uriBuilder
            ->reset()
            ->setTargetPageUid($pageUid)
            ->setArguments($arguments)
            ->setCreateAbsoluteUri($absolute)
            ->buildFrontendUri();
    }
}
