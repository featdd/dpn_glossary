<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Pagination;

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

/**
 * @package Featdd\DpnGlossary\Pagination
 */
interface CharacterPaginationItemInterface
{
    /**
     * @param string $character
     * @param bool $isCurrent
     * @param bool $isEmpty
     */
    public function __construct(string $character, bool $isCurrent, bool $isEmpty);

    /**
     * @return string
     */
    public function getCharacter(): string;

    /**
     * @return bool
     */
    public function getIsCurrent(): bool;

    /**
     * @return bool
     */
    public function getIsEmpty(): bool;
}
