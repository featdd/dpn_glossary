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
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
class TermRepository extends AbstractTermRepository
{
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

        return array_slice($terms, 0, $limit);
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

            if (is_numeric($firstCharacter)) {
                $firstCharacter = (int) $firstCharacter;
            }

            if (in_array($firstCharacter, $numbers, true)) {
                $firstCharacter = '0-9';
            } else {
                if (!in_array($firstCharacter, $normalChars, true)) {
                    $firstCharacter = match ($firstCharacter) {
                        'ä' => 'a',
                        'ö' => 'o',
                        'ü' => 'u',
                        default => '_',
                    };

                }
            }

            $firstCharacter = mb_strtoupper($firstCharacter, 'UTF-8');

            if (!isset($sortedTerms[$firstCharacter])) {
                $sortedTerms[$firstCharacter] = [];
            }

            $sortedTerms[$firstCharacter][] = $term;
        }

        return $sortedTerms;
    }
}
