<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
interface TermInterface extends DomainObjectInterface
{
    public const TABLE = 'tx_dpnglossary_domain_model_term';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getTermMode(): string;

    /**
     * @return string
     */
    public function getTermLink(): string;

    /**
     * @return bool
     */
    public function isExcludeFromParsing(): bool;

    /**
     * @return int
     */
    public function getMaxReplacements(): int;

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     */
    public function getSynonyms(): ObjectStorage;

    /**
     * @return array
     */
    public function __toArray(): array;
}
