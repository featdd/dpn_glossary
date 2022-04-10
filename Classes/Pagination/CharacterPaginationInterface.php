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
interface CharacterPaginationInterface
{
    /**
     * @param \Featdd\DpnGlossary\Pagination\CharacterPaginatorInterface $characterPaginator
     * @param string[] $characters
     */
    public function __construct(CharacterPaginatorInterface $characterPaginator, string...$characters);

    /**
     * @return string[]
     */
    public function getCharacters(): array;
}
