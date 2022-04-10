<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Domain\Repository;

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

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
class TermRepository extends Repository
{
    public const DEFAULT_LIMIT = 5;

    /**
     * @var array
     */
    protected $defaultOrderings = [
        'name' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * find all terms sorted by name length
     *
     * @return array
     */
    public function findByNameLength(): array
    {
        $terms = $this->findAll()->toArray();

        /**
         * Sorting Callback
         *
         * @param Term $termA
         * @param Term $termB
         * @return int
         */
        $sortingCallback = function (Term $termA, Term $termB) {
            return mb_strlen($termB->getName()) - mb_strlen($termA->getName());
        };

        // Sort terms
        usort($terms, $sortingCallback);

        return $terms;
    }

    /**
     * finds terms by multiple uids
     *
     * @param int[] $uids
     * @return array
     */
    public function findByUids(array $uids): array
    {
        $query = $this->createQuery();

        if (0 === count($uids)) {
            return [];
        }

        try {
            $query->matching(
                $query->in('uid', $uids)
            );
        } catch (InvalidQueryException $e) {
            // nothing
        }

        return $query->execute()->toArray();
    }

    /**
     * finds the newest terms
     *
     * @param int $limit
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findNewest(int $limit = self::DEFAULT_LIMIT): QueryResultInterface
    {
        return $this->createQuery()
            ->setOrderings(['crdate' => QueryInterface::ORDER_DESCENDING])
            ->setLimit($limit)
            ->execute();
    }

    /**
     * finds random terms
     *
     * @param integer $limit
     * @return array
     */
    public function findRandom(int $limit = self::DEFAULT_LIMIT): array
    {
        $terms = $this->createQuery()->execute()->toArray();

        shuffle($terms);

        return \array_slice($terms, 0, $limit);
    }

    /**
     * find all terms ordered by name and grouped by first character
     *
     * @return array
     */
    public function findAllGroupedByFirstCharacter(): array
    {
        $terms = $this->findAll();
        $numbers = range(0, 9);
        $normalChars = range('a', 'z');
        $sortedTerms = [];

        /** @var Term $term */
        foreach ($terms as $term) {
            $firstCharacter = mb_strtolower(mb_substr($term->getName(), 0, 1, 'UTF-8'), 'UTF-8');

            if (true === is_numeric($firstCharacter)) {
                $firstCharacter = (int) $firstCharacter;
            }

            if (true === \in_array($firstCharacter, $numbers, true)) {
                $firstCharacter = '0-9';
            } else {
                if (false === \in_array($firstCharacter, $normalChars, true)) {
                    switch ($firstCharacter) {
                        case 'ä':
                            $firstCharacter = 'a';
                            break;
                        case 'ö':
                            $firstCharacter = 'o';
                            break;
                        case 'ü':
                            $firstCharacter = 'u';
                            break;
                        default:
                            $firstCharacter = '_';
                            break;
                    }

                }
            }

            $firstCharacter = mb_strtoupper($firstCharacter, 'UTF-8');

            if (false === isset($sortedTerms[$firstCharacter])) {
                $sortedTerms[$firstCharacter] = [];
            }

            $sortedTerms[$firstCharacter][] = $term;
        }

        return $sortedTerms;
    }
}
