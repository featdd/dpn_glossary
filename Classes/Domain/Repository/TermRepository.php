<?php
namespace Featdd\DpnGlossary\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Dorndorf <dorndorf@featdd.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Featdd\DpnGlossary\Domain\Model\Term;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TermRepository extends Repository
{
    const DEFAULT_LIMIT = 5;

    /**
     * Default orderings ascending by name
     *
     * @var array $defaultOrderings
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
        $terms       = $this->findAll();
        $numbers     = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $normalChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $sortedTerms = array();

        /** @var Term $term */
        foreach ($terms as $term) {
            $firstCharacter = mb_strtolower(mb_substr($term->getName(), 0, 1, 'UTF-8'), 'UTF-8');

            if (in_array($firstCharacter, $numbers)) {
                $firstCharacter = '0-9';
            } else if (FALSE === in_array($firstCharacter, $normalChars)) {
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

            $firstCharacter = mb_strtoupper($firstCharacter, 'UTF-8');

            if (FALSE === isset($sortedTerms[$firstCharacter])) {
                $sortedTerms[$firstCharacter] = array();
            }

            $sortedTerms[$firstCharacter][] = $term;
        }

        return $sortedTerms;
    }
}
