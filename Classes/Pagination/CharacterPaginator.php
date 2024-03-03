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
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @package Featdd\DpnGlossary\Pagination
 */
class CharacterPaginator implements CharacterPaginatorInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected QueryResultInterface $queryResult;

    /**
     * @var string|null
     */
    protected ?string $currentCharacter;

    /**
     * @var string[]
     */
    protected array $characters;

    /**
     * @var string
     */
    protected string $field;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @param string $field
     * @param string|null $character
     * @param string ...$paginationCharacters
     */
    public function __construct(QueryResultInterface $queryResult, string $field, ?string $character, string...$paginationCharacters)
    {
        $this->queryResult = $queryResult;
        $this->field = $field;
        $this->characters = $paginationCharacters;
        $this->currentCharacter = $character;

        if ($character === null) {
            foreach ($paginationCharacters as $paginationCharacter) {
                if ($this->characterHasItems($paginationCharacter)) {
                    $this->currentCharacter = $paginationCharacter;
                    break;
                }
            }
        }
    }

    /**
     * @return string|null
     */
    public function getCurrentCharacter(): ?string
    {
        return $this->currentCharacter;
    }

    /**
     * @return iterable
     */
    public function getPaginatedItems(): iterable
    {
        if ($this->currentCharacter === null) {
            return [];
        }

        return $this->itemsForCharacter($this->currentCharacter);
    }

    /**
     * @param string $character
     * @return bool
     */
    public function characterHasItems(string $character): bool
    {
        return 0 < $this->itemsForCharacter($character)->count();
    }

    /**
     * @param string $character
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected function itemsForCharacter(string $character): QueryResultInterface
    {
        $query = $this->queryResult->getQuery();

        try {
            $query->matching(
                $query->like($this->field, $character . '%')
            );
        } catch (InvalidQueryException) {
            // ignore
        }

        return $query->execute();
    }
}
