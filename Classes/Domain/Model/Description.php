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
 *  (c) 2022 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package Featdd\DpnGlossary\Domain\Model
 */
class Description extends AbstractEntity
{
    public const TABLE = 'tx_dpnglossary_domain_model_description';

    /**
     * @var string
     * @Validate(validator="NotEmpty")
     */
    protected $meaning = '';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @return string
     */
    public function getMeaning(): string
    {
        return $this->meaning;
    }

    /**
     * @param string $meaning
     */
    public function setMeaning(string $meaning): void
    {
        $this->meaning = $meaning;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
