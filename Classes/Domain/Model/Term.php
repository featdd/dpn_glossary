<?php
namespace Featdd\DpnGlossary\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Daniel Dorndorf <dorndorf@featdd.de>
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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Term extends AbstractEntity
{
    const TABLE = 'tx_dpnglossary_domain_model_term';

    /**
     * @var string
     * @validate NotEmpty
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $tooltiptext = '';

    /**
     * @var string
     */
    protected $termType = '';

    /**
     * @var string
     */
    protected $termLang = '';

    /**
     * @var string
     */
    protected $termMode = '';

    /**
     * @var string
     */
    protected $termLink = '';

    /**
     * @var bool
     */
    protected $excludeFromParsing = false;

    /**
     * @var bool
     */
    protected $caseSensitive = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description>
     * @cascade remove
     */
    protected $descriptions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     * @cascade remove
     */
    protected $synonyms;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @cascade remove
     */
    protected $media;

    /**
     * @return Term
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->descriptions = new ObjectStorage();
        $this->synonyms = new ObjectStorage();
        $this->media = new ObjectStorage();
    }

    /**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string $tooltiptext
     */
    public function getTooltiptext()
    {
        return $this->tooltiptext;
    }

    /**
     * @param string $tooltiptext
     * @return void
     */
    public function setTooltiptext($tooltiptext)
    {
        $this->tooltiptext = $tooltiptext;
    }

    /**
     * @param string $termType
     */
    public function setTermType($termType)
    {
        $this->termType = $termType;
    }

    /**
     * @return string
     */
    public function getTermType()
    {
        return $this->termType;
    }

    /**
     * @param string $termLang
     */
    public function setTermLang($termLang)
    {
        $this->termLang = $termLang;
    }

    /**
     * @return string
     */
    public function getTermLang()
    {
        return $this->termLang;
    }

    /**
     * @return string
     */
    public function getTermMode()
    {
        return $this->termMode;
    }

    /**
     * @param string $termMode
     * @return void
     */
    public function setTermMode($termMode)
    {
        $this->termMode = $termMode;
    }

    /**
     * @return string
     */
    public function getTermLink()
    {
        return $this->termLink;
    }

    /**
     * @param string $termLink
     * @return void
     */
    public function setTermLink($termLink)
    {
        $this->termLink = $termLink;
    }

    /**
     * @return bool
     */
    public function getExcludeFromParsing()
    {
        return $this->excludeFromParsing;
    }

    /**
     * @param bool $excludeFromParsing
     * @return void
     */
    public function setExcludeFromParsing($excludeFromParsing)
    {
        $this->excludeFromParsing = $excludeFromParsing;
    }

    /**
     * @return bool
     */
    public function getCaseSensitive()
    {
        return $this->caseSensitive;
    }

    /**
     * @param bool $caseSensitive
     * @return void
     */
    public function setCaseSensitive($caseSensitive)
    {
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description>
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Description $description
     */
    public function addDescription(Description $description)
    {
        $this->descriptions->attach($description);
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Description $description
     */
    public function removeDescription(Description $description)
    {
        $this->descriptions->detach($description);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description> $descriptions
     * @return void
     */
    public function setDescriptions(ObjectStorage $descriptions)
    {
        $this->descriptions = $descriptions;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     */
    public function getSynonyms()
    {
        return $this->synonyms;
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Synonym $synonym
     */
    public function addSynonym(Synonym $synonym)
    {
        $this->synonyms->attach($synonym);
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Synonym $synonym
     */
    public function removeSynonym(Synonym $synonym)
    {
        $this->synonyms->detach($synonym);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym>
     * @return void
     */
    public function setSynonyms(ObjectStorage $synonyms)
    {
        $this->synonyms = $synonyms;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media
     * @return void
     */
    public function setMedia(ObjectStorage $media)
    {
        $this->media = $media;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     * @return void
     */
    public function addMedia(FileReference $file)
    {
        $this->media->attach($file);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     * @return void
     */
    public function removeMedia(FileReference $file)
    {
        $this->media->detach($file);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'uid' => $this->getUid(),
            'pid' => $this->getPid(),
            'name' => $this->getName(),
            'tooltiptext' => $this->getTooltiptext(),
            'term_type' => $this->getTermType(),
            'term_lang' => $this->getTermLang(),
            'term_mode' => $this->getTermMode(),
            'term_link' => $this->getTermLink(),
            'exclude_from_parsing' => $this->getExcludeFromParsing(),
            'descriptions' => $this->getDescriptions(),
            'synonyms' => $this->getSynonyms(),
            'media' => $this->getMedia(),
        );
    }
}
