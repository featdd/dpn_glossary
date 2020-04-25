<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Domain\Model;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package Featdd\DpnGlossary\Domain\Model
 */
class Synonym extends AbstractEntity
{
    public const TABLE = 'tx_dpnglossary_domain_model_synonym';

    /**
     * @var string $name
     * @validate NotEmpty
     */
    protected $name = '';

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
