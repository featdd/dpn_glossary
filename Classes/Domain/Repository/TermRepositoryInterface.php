<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

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

/**
 * @package Featdd\DpnGlossary\Domain\Repository
 */
interface TermRepositoryInterface extends RepositoryInterface
{
    /**
     * @return \Featdd\DpnGlossary\Domain\Model\TermInterface[]
     */
    public function findByNameLength(): array;
}
