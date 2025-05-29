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
 *  (c) 2025 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use DOMDocument;
use DOMText;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Featdd\DpnGlossary\Utility
 */
class ObjectUtility
{
    /**
     * @param iterable<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $objects
     * @param string $propertyName
     * @param array $characters
     * @param string $fallbackCharacter
     * @return array
     */
    public static function groupObjectsByFirstCharacter(iterable $objects, string $propertyName, array $characters, string $fallbackCharacter = '_'): array
    {
        $numbers = range(0, 9);
        $sortedTerms = [];

        foreach ($objects as $object) {
            $firstCharacter = mb_strtoupper(mb_substr($object->_getProperty($propertyName), 0, 1));

            if (is_numeric($firstCharacter)) {
                $firstCharacter = (int)$firstCharacter;
            }

            if (in_array($firstCharacter, $numbers, true)) {
                $firstCharacter = '0-9';
            } elseif (
                !in_array($firstCharacter, $characters, true) &&
                extension_loaded('intl')
            ) {
                /*
                 * This converts any special characters to their ASCII equivalent
                 * like Ä => A or È => E
                 */
                $firstCharacter = transliterator_transliterate(
                    'NFKC; [:Nonspacing Mark:] Remove; NFKC; Any-Latin; Latin-ASCII',
                    $firstCharacter
                );
            }

            if (!in_array($firstCharacter, $characters, true)) {
                $firstCharacter = $fallbackCharacter;
            }

            if (!isset($sortedTerms[$firstCharacter])) {
                $sortedTerms[$firstCharacter] = [];
            }

            $sortedTerms[$firstCharacter][] = $object;
        }

        ksort($sortedTerms);

        return $sortedTerms;
    }
}
