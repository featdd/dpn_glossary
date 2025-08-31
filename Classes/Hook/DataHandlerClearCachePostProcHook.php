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
 *  (c) 2025 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Featdd\DpnGlossary\Hook
 */
class DataHandlerClearCachePostProcHook
{
    /**
     * @param array $parameters
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     */
    public function clearCache(array $parameters, DataHandler $dataHandler): void
    {
        if (($parameters['table'] ?? '') === Term::TABLE) {
            /** @var \TYPO3\CMS\Core\Cache\CacheManager $cacheManager */
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

            if ($cacheManager->hasCache('dpnglossary_termscache')) {
                $cacheManager
                    ->getCache('dpnglossary_termscache')
                    ->flushByTag(
                        sprintf('storage-%d', $parameters['uid_page'])
                    );
            }
        }
    }
}
