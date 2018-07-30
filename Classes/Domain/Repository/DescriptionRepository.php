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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package DpnGlossary
 * @subpackage Repository
 */
class DescriptionRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => QueryInterface::ORDER_ASCENDING,
    );
}
