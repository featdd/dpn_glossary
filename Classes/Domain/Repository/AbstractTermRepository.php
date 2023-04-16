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
 *  (c) 2023 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Model\TermInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
abstract class AbstractTermRepository extends Repository
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
        $sortingCallback = function (TermInterface $termA, TermInterface $termB) {
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
}
