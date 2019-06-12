<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Utility;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package MindshapeApi
 * @subpackage Utility
 */
class ObjectUtility
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected static $objectManager;

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public static function makeInstance(string $className, ...$arguments): object
    {
        if (!static::$objectManager instanceof ObjectManager) {
            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            static::$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }

        return static::$objectManager->get($className, ...$arguments);
    }
}
