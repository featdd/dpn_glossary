<?php
namespace Dpn\DpnGlossary\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Daniel Dorndorf <dorndorf@dreipunktnull.com>, Dreipunktnull
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

use Dpn\DpnGlossary\Domain\Model\Description;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 *
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
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
	 * @validate NotEmpty
	 */
	protected $tooltiptext;

	/**
	 * description of the term
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Dpn\DpnGlossary\Domain\Model\Description> $descriptions
	 */
	protected $descriptions;

	/**
	 * alternative names for the termn
	 *
	 * @var string $nameAlternative
	 */
	protected $nameAlternative;

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
	protected $images;

	/**
	 * sets the Images
	 * @param ObjectStorage $images
	 * @return void
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
	 * Adds a FileReference
	 *
	 * @param FileReference $image
	 * @return void
	 */
	public function addImage(FileReference $image) {
		$this->images->attach($image);
	}

	/**
	 * Removes a FileReference
	 *
	 * @param FileReference $image
	 * @return void
	 */
	public function removeImage(FileReference $image) {
		$this->images->detach($image);
	}

	/**
	 * get the Images
	 * @return ObjectStorage
	 */
	public function getImages() {
		return $this->images;
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
	 * @return string $descriptions
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
	 * @param string $descriptions
	 * @return void
	 */
	public function setDescriptions($descriptions) {
		$this->descriptions = $descriptions;
	}

	/**
	 * Sets the alternative names
	 *
	 * @param string $nameAlternative
	 */
	public function setNameAlternative($nameAlternative) {
		$this->nameAlternative = $nameAlternative;
	}

	/**
	 * Returns the alternaive names
	 *
	 * @return string
	 */
	public function getNameAlternative() {
		return $this->nameAlternative;
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
	 * @return array
	 */
	public function toArray() {
		return array(
			'uid'				=> $this->getUid(),
			'pid'				=> $this->getPid(),
			'name'				=> $this->getName(),
			'tooltiptext'		=> $this->getTooltiptext(),
			'descriptions'		=> $this->getDescriptions(),
			'name_alternative'	=> $this->getNameAlternative(),
			'term_type'			=> $this->getTermType(),
			'term_lang'			=> $this->getTermLang(),
		);
	}
}