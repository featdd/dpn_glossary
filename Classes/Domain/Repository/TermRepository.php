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
}
