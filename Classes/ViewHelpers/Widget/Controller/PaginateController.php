<?php
namespace Featdd\DpnGlossary\ViewHelpers\Widget\Controller;

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

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PaginateController extends AbstractWidgetController {

	/**
	 * @var array
	 */
	protected $configuration = array(
		'characters'  => 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z',
		'insertAbove' => TRUE,
		'insertBelow' => FALSE,
	);

	/**
	 * Objects to sort
	 *
	 * @var QueryResultInterface
	 */
	protected $objects;

	/**
	 * Query object to sort and count terms
	 *
	 * @var QueryInterface
	 */
	protected $query;

	/**
	 * Sorting fieldname of the object model
	 * what was passed by in objects
	 *
	 * @var string
	 */
	protected $field = '';

	/**
	 * Current page character
	 *
	 * @var string
	 */
	protected $currentCharacter = '';

	/**
	 * Characters used in the pagination
	 *
	 * @var array
	 */
	protected $characters = array();

	/**
	 * Init action of the controller
	 *
	 * @return void
	 */
	public function initializeAction() {
		/** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj */
		$cObj = $this->objectManager->get('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');

		ArrayUtility::mergeRecursiveWithOverrule(
			$this->configuration,
			(array) $this->settings['pagination'],
			TRUE
		);

		$this->field = FALSE === empty($this->widgetConfiguration['field']) ? $this->widgetConfiguration['field'] : 'name';
		$this->objects = $this->widgetConfiguration['objects'];
		$this->query = $this->objects->getQuery();

		// Apply stdWrap
		if (is_array($this->configuration['characters'])) {
			/** @var \TYPO3\CMS\Extbase\Service\TypoScriptService */
			$typoScriptService = $this->objectManager->get('TYPO3\CMS\Extbase\Service\TypoScriptService');

			// It's required to convert the "new" array to dot notation one before we can use `cObjGetSingle`
			$this->configuration['characters'] = $typoScriptService->convertPlainArrayToTypoScriptArray($this->configuration['characters']);
			$this->configuration['characters'] = $cObj->cObjGetSingle($this->configuration['characters']['_typoScriptNodeValue'], $this->configuration['characters']);
		}

		$this->characters = explode(',', $this->configuration['characters']);
	}

	/**
	 * Main action terms will be sorted
	 * by the currentCharacter
	 *
	 * @param string $character
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function indexAction($character = '') {

		if (TRUE === empty($character)) {
			$this->query->setLimit(1)->setOrderings(array($this->field => QueryInterface::ORDER_ASCENDING));
			$firstObject = $this->query->execute()->toArray();
			$this->query = $this->objects->getQuery();

			if (TRUE === empty($firstObject)) {
				$this->view->assign('noObjects', TRUE);
			} else {
				$getter = 'get' . GeneralUtility::underscoredToUpperCamelCase($this->field);

				if (TRUE === method_exists($firstObject[0], $getter)) {
					$this->currentCharacter = strtoupper(substr($firstObject[0]->{$getter}(), 0, 1));
				} else {
					throw new Exception('Getter for "' . $this->field . '" in "' . get_class($firstObject[0]) . '" does not exist', 1433257601);
				}
			}
		} else {
			$this->currentCharacter = $character;
		}

		$this->currentCharacter = str_replace(
			array('AE', 'OE', 'UE'),
			array('Ä', 'Ö', 'Ü'),
			$this->currentCharacter
		);

		$objects = $this->getMatchings()->execute()->toArray();

		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
		$this->view->assign('contentArguments', array($this->widgetConfiguration['as'] => $objects));
	}

	/**
	 * Pagination array gets build up
	 *
	 * @return array
	 */
	protected function buildPagination() {
		$pages = array();
		$numberOfCharacters = count($this->characters);

		/*
		 * Generates the pages and also checks if
		 * the page has no objects
		 */
		foreach ($this->characters as $character) {
			$pages[] = array(
				'linkCharacter' => str_replace(
					array('Ä', 'Ö', 'Ü'),
					array('AE', 'OE', 'UE'),
					$character
				),
				'character'     => $character,
				'isCurrent'     => $character === $this->currentCharacter,
				'isEmpty'       => 0 === $this->getMatchings($character)->execute()->count(),
			);
		}

		$pagination = array(
			'pages'          => $pages,
			'current'        => $this->currentCharacter,
			'numberOfPages'  => $numberOfCharacters,
			'startCharacter' => $this->characters[0],
			'endCharacter'   => $this->characters[count($this->characters) + 1],
		);

		return $pagination;
	}

	/**
	 * This function builds the matchings.
	 * It enables matchings like:
	 * - single character: 'B'
	 * - multiple characters: 'BDEFG'
	 * - range of characters: 'B-G'
	 *
	 * @param string $characters
	 * @return QueryInterface
	 */
	protected function getMatchings($characters = NULL) {
		$matching = array();

		if ($characters === NULL) {
			$characters = $this->currentCharacter;
		}

		$characterLength = strlen($characters);

		if ($characterLength === 1) {
			// single character B
			$matching = $this->query->like($this->field, $characters . '%');
		} else {
			if ($characterLength === 3 && $characters[1] === '-') {
				// range B-G
				// Build the characters like multiple characters B-G => BCDEFG

				// Fix orderings
				$firstCharacter = ord($characters[0]);
				$lastCharacter = ord($characters[2]);

				if ($firstCharacter - $lastCharacter > 0) {
					$tmp = $firstCharacter;
					$firstCharacter = $lastCharacter;
					$lastCharacter = $tmp;
				}

				// Build the new String
				$characters = '';

				for ($char = $firstCharacter; $char <= $lastCharacter; ++$char) {
					$characters .= chr($char);
				}
			}

			// multiple characters BDEFG
			$characters = str_split($characters);

			foreach ($characters as $char) {
				$matching[] = $this->query->like($this->field, $char . '%');
			}

			$matching = $this->query->logicalOr($matching);
		}

		return $this->query->matching($matching);
	}

	/**
	 * If the pagination is used this function
	 * will prepare the link arguments to get
	 * back to the last pagination page
	 *
	 * @param string $field
	 * @param string $paginationCharacters
	 * @return array
	 */
	static public function paginationArguments($field, $paginationCharacters) {
		$firstCharacter = mb_strtoupper(mb_substr($field, 0, 1, 'UTF-8'), 'UTF-8');
		$characters = array_change_key_case(explode(',', $paginationCharacters), CASE_UPPER);

		/*
		 * Replace umlauts if they are in characters
		 * else use A,O,U
		 */
		$hasUmlauts = array_intersect(array('Ä', 'Ö', 'Ü'), $characters);
		$umlautReplacement = 0 < count($hasUmlauts) ?
			array('AE', 'OE', 'UE') :
			array('A', 'O', 'U');

		$firstCharacter = str_replace(
			array('Ä', 'Ö', 'Ü'),
			$umlautReplacement,
			$firstCharacter
		);

		$characters = str_replace(
			array('Ä', 'Ö', 'Ü'),
			$umlautReplacement,
			$characters
		);

		$character = TRUE === in_array($firstCharacter, $characters, TRUE) ?
			$firstCharacter :
			FALSE;

		return array(
			'@widget_0' => array(
				'character' => $character,
			),
		);
	}
}
