<?php
namespace Dpn\DpnGlossary\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Daniel Dorndorf <dorndorf@dreipunktnull.com>, Dreipunktnull
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

/**
 *
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Term extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * name of the term
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * text shown in the css tooltip
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $tooltiptext;

	/**
	 * description of the term
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $description;

	/**
	 * Returns the name
	 *
	 * @return \string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param \string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the tooltiptext
	 *
	 * @return \string $tooltiptext
	 */
	public function getTooltiptext() {
		return $this->tooltiptext;
	}

	/**
	 * Sets the tooltiptext
	 *
	 * @param \string $tooltiptext
	 * @return void
	 */
	public function setTooltiptext($tooltiptext) {
		$this->tooltiptext = $tooltiptext;
	}

	/**
	 * Returns the description
	 *
	 * @return \string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param \string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

}