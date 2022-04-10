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
class CharacterPagination implements CharacterPaginationInterface
{
    /**
     * @var \Featdd\DpnGlossary\Pagination\CharacterPaginatorInterface
     */
    protected $paginator;

    /**
     * @var \Featdd\DpnGlossary\Pagination\CharacterPaginationItemInterface[]
     */
    protected $characters = [];

    /**
     * @param \Featdd\DpnGlossary\Pagination\CharacterPaginatorInterface $characterPaginator
     * @param string[] $characters
     */
    public function __construct(CharacterPaginatorInterface $characterPaginator, string...$characters)
    {
        $this->paginator = $characterPaginator;

        foreach ($characters as $character) {
            $this->characters[] = new CharacterPaginationItem(
                $character,
                $character === $characterPaginator->getCurrentCharacter(),
                false === $characterPaginator->characterHasItems($character)
            );
        }
    }

    /**
     * @return \Featdd\DpnGlossary\Pagination\CharacterPaginationItemInterface[]
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
}
