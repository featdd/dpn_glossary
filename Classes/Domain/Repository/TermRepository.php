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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
class TermRepository extends AbstractTermRepository
{
    public function findByTerm(
        string $term
    ): QueryResultInterface {
        $query = $this->createQuery();

        $queryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);
        $queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);

        $searchTerm = '%' . $queryBuilder->getConnection()->escapeLikeWildcards($term) . '%';

        $query->matching($query->logicalOr(
            $query->like('name', $searchTerm),
            $query->like('synonyms.name', $searchTerm),
            $query->like('descriptions.meaning', $searchTerm),
        ));
        $queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);

        $result = $query->execute();
        return $result;
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

        return array_slice($terms, 0, $limit);
    }
}
