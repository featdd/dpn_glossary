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
class CharacterPaginationItem implements CharacterPaginationItemInterface
{
    /**
     * @var string
     */
    protected $character;

    /**
     * @var bool
     */
    protected $isCurrent;

    /**
     * @var bool
     */
    protected $isEmpty;

    /**
     * @param string $character
     * @param bool $isCurrent
     * @param bool $isEmpty
     */
    public function __construct(string $character, bool $isCurrent, bool $isEmpty)
    {
        $this->character = $character;
        $this->isCurrent = $isCurrent;
        $this->isEmpty = $isEmpty;
    }

    public function __toString(): string
    {
        return $this->character;
    }

    /**
     * @return string
     */
    public function getCharacter(): string
    {
        return $this->character;
    }

    /**
     * @return bool
     */
    public function getIsCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @return bool
     */
    public function getIsEmpty(): bool
    {
        return $this->isEmpty;
    }
}
