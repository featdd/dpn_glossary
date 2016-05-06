<?php
namespace Featdd\DpnGlossary\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Term extends AbstractEntity {

	/**
	 * name of the term
	 *
	 * @var string $name
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * text shown in the css tooltip
	 *
	 * @var string $tooltiptext
	 */
	protected $tooltiptext;

	/**
	 * description of the term
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description> $descriptions
	 * @cascade remove
	 */
	protected $descriptions;

	/**
	 * synonyms for the term
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym> $synonyms
	 */
	protected $synonyms;

	/**
	 * the type of the term, must by empty or one of [abbrevation/description]
	 *
	 * @var string $termType
	 */
	protected $termType;

	/**
	 * the 2 char iso code of the term, can also be empty
	 *
	 * @var string $termLang
	 */
	protected $termLang;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 * @lazy
	 */
	protected $media;

	/**
	 * @return Term
	 */
	public function __construct()
	{
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties
	 * Do not modify this method!
	 * It will be rewritten on each save in the extension builder
	 * You may modify the constructor of this class instead
	 *
	 * @return void
	 */
	protected function initStorageObjects()
	{
		$this->descriptions = new ObjectStorage();
		$this->synonyms     = new ObjectStorage();
	}

	/**
	 * Returns the name
	 *
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the tooltiptext
	 *
	 * @return string $tooltiptext
	 */
	public function getTooltiptext() {
		return $this->tooltiptext;
	}

	/**
	 * Sets the tooltiptext
	 *
	 * @param string $tooltiptext
	 * @return void
	 */
	public function setTooltiptext($tooltiptext) {
		$this->tooltiptext = $tooltiptext;
	}

	/**
	 * Returns the descriptions
	 *
	 * @return ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description> $descriptions
	 */
	public function getDescriptions() {
		return $this->descriptions;
	}

	/**
	 * Adds the description
	 *
	 * @param Description $description
	 */
	public function addDescription(Description $description) {
		$this->descriptions->attach($description);
	}

	/**
	 * removes the description
	 *
	 * @param Description $description
	 */
	public function removeDescription(Description $description) {
		$this->descriptions->detach($description);
	}

	/**
	 * Sets the descriptions
	 *
	 * @param ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Description> $descriptions
	 * @return void
	 */
	public function setDescriptions($descriptions) {
		$this->descriptions = $descriptions;
	}

	/**
	 * Returns the synonyms
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym> $synonyms
	 */
	public function getSynonyms() {
		return $this->synonyms;
	}

	/**
	 * Adds the synonym
	 *
	 * @param Synonym $synonym
	 */
	public function addSynonym(Synonym $synonym) {
		$this->synonyms->attach($synonym);
	}

	/**
	 * removes the synonym
	 *
	 * @param Synonym $synonym
	 */
	public function removeSynonym(Synonym $synonym) {
		$this->synonyms->detach($synonym);
	}

	/**
	 * Sets the synonyms
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Featdd\DpnGlossary\Domain\Model\Synonym> $synonyms
	 * @return void
	 */
	public function setSynonyms($synonyms) {
		$this->synonyms = $synonyms;
	}

	/**
	 * Sets the language of the term
	 *
	 * @param string $termLang
	 */
	public function setTermLang($termLang) {
		$this->termLang = $termLang;
	}

	/**
	 * Returns the language of the term
	 *
	 * @return string
	 */
	public function getTermLang() {
		return $this->termLang;
	}

	/**
	 * Sets the type of the term
	 *
	 * @param string $termType
	 */
	public function setTermType($termType) {
		$this->termType = $termType;
	}

	/**
	 * Returns the type of the term
	 *
	 * @return string
	 */
	public function getTermType() {
		return $this->termType;
	}

	/**
	 * sets the Media
	 * @param ObjectStorage $media
	 * @return void
	 */
	public function setMedias($media) {
		$this->media = $media;
	}

	/**
	 * Adds a FileReference
	 *
	 * @param FileReference $file
	 * @return void
	 */
	public function addMedia(FileReference $file) {
		$this->media->attach($file);
	}

	/**
	 * Removes a FileReference
	 *
	 * @param FileReference $file
	 * @return void
	 */
	public function removeMedia(FileReference $file) {
		$this->media->detach($file);
	}

	/**
	 * get the Media
	 * @return ObjectStorage
	 */
	public function getMedia() {
		return $this->media;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			'uid'				=> $this->getUid(),
			'pid'				=> $this->getPid(),
			'name'				=> $this->getName(),
			'tooltiptext'		=> $this->getTooltiptext(),
			'descriptions'		=> $this->getDescriptions(),
			'synonyms'			=> $this->getSynonyms(),
			'term_type'			=> $this->getTermType(),
			'term_lang'			=> $this->getTermLang(),
		);
	}
}
