<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Pagination;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
interface CharacterPaginatorInterface
{
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @param string $field
     * @param string $character
     */
    public function __construct(QueryResultInterface $queryResult, string $field, string $character);

    /**
     * @return iterable
     */
    public function getPaginatedItems(): iterable;

    /**
     * @return string
     */
    public function getCurrentCharacter(): ?string;

    /**
     * @param string $character
     * @return bool
     */
    public function characterHasItems(string $character): bool;
}
