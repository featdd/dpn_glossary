<?php
namespace DPN\DpnGlossary\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Daniel Dorndorf <dorndorf@dreipunktnull.com>, dreipunktnull
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

/**
 *
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Description extends AbstractEntity {

	/**
	 * meaning of the terms description
	 *
	 * @var string $meaning
	 */
	protected $meaning;

	/**
	 * text of the term
	 *
	 * @var string $text
	 */
	protected $text;

	/**
	 * Returns the meaning
	 *
	 * @return string
	 */
	public function getMeaning() {
		return $this->meaning;
	}

	/**
	 * Sets the meaning
	 *
	 * @param string $meaning
	 * @return void
	 */
	public function setMeaning($meaning) {
		$this->meaning = $meaning;
	}

	/**
	 * Returns the text
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Sets the text
	 *
	 * @param string $text
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}
}
