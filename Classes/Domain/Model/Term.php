<?php
namespace Featdd\DpnGlossary\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * @package DpnGlossary
 * @subpackage Domain\Model
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

    public function __construct()
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
     */
    public function setSynonyms(ObjectStorage $synonyms)
    {
        $this->synonyms = $synonyms;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media
     */
    public function setMedia(ObjectStorage $media)
    {
        $this->media = $media;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     */
    public function addMedia(FileReference $file)
    {
        $this->media->attach($file);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
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
