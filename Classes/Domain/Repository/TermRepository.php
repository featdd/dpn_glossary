<?php
namespace Featdd\DpnGlossary\Domain\Repository;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package DpnGlossary
 * @subpackage Repository
 */
class TermRepository extends Repository
{
    const DEFAULT_LIMIT = 5;

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'name' => QueryInterface::ORDER_ASCENDING,
    );

    /**
     * find all terms sorted by name length
     *
     * @return array
     */
    public function findByNameLength()
    {
        $terms = $this->findAll()->toArray();

        /**
         * Sorting Callback
         *
         * @param Term $termA
         * @param Term $termB
         * @return int
         */
        $sortingCallback = function ($termA, $termB) {
            return strlen($termB->getName()) - strlen($termA->getName());
        };

        // Sort terms
        usort($terms, $sortingCallback);

        return $terms;
    }

    /**
     * finds terms by multiple uids
     *
     * @param array $uids
     * @return QueryResultInterface
     */
    public function findByUids(array $uids)
    {
        $query = $this->createQuery();

        $query->matching(
            $query->in('uid', $uids)
        );

        return $query->execute();
    }

    /**
     * finds the newest terms
     *
     * @param integer $limit
     * @return QueryResultInterface
     */
    public function findNewest($limit = self::DEFAULT_LIMIT)
    {
        return $this->createQuery()
            ->setOrderings(array(
                'crdate' => QueryInterface::ORDER_ASCENDING,
            ))
            ->setLimit($limit)
            ->execute();
    }

    /**
     * finds random terms
     *
     * @param integer $limit
     * @return array
     */
    public function findRandom($limit = self::DEFAULT_LIMIT)
    {
        $terms = $this->createQuery()->execute()->toArray();

        shuffle($terms);

        $terms = array_slice(
            $terms,
            0,
            $limit
        );

        return $terms;
    }

    /**
     * find all terms ordered by name and grouped by first character
     *
     * @return array
     */
    public function findAllGroupedByFirstCharacter()
    {
        $terms = $this->findAll();
        $numbers = range(0, 9);
        $normalChars = range('a', 'z');
        $sortedTerms = array();

        /** @var Term $term */
        foreach ($terms as $term) {
            $firstCharacter = mb_strtolower(mb_substr($term->getName(), 0, 1, 'UTF-8'), 'UTF-8');

            if (true === is_numeric($firstCharacter)) {
                $firstCharacter = (int) $firstCharacter;
            }

            if (true === in_array($firstCharacter, $numbers, true)) {
                $firstCharacter = '0-9';
            } else {
                if (false === in_array($firstCharacter, $normalChars)) {
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
                $sortedTerms[$firstCharacter] = array();
            }

            $sortedTerms[$firstCharacter][] = $term;
        }

        return $sortedTerms;
    }
}
