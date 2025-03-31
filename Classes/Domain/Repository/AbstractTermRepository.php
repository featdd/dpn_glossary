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
use Featdd\DpnGlossary\Domain\Model\TermInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
abstract class AbstractTermRepository extends Repository implements TermRepositoryInterface
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
     * @return \Featdd\DpnGlossary\Domain\Model\TermInterface[]
     */
    public function findByNameLength(): array
    {
        $terms = $this->findAll()->toArray();

        // Sort terms
        usort(
            $terms,
            fn(
                TermInterface $termA,
                TermInterface $termB
            ) => mb_strlen($termB->getName()) - mb_strlen($termA->getName())
        );

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

        if (count($uids) === 0) {
            return [];
        }

        try {
            $query->matching(
                $query->in('uid', $uids)
            );
        } catch (InvalidQueryException) {
            // nothing
        }

        return $query->execute()->toArray();
    }
}
