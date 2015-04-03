<?php
namespace Dpn\DpnGlossary\ViewHelpers\Widget\Controller;

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
		'characters'   => 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z',
		'insertAbove'  => TRUE,
		'insertBelow'  => FALSE
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
		ArrayUtility::mergeRecursiveWithOverrule(
			$this->configuration,
			(array)$this->settings['pagination'],
			TRUE
		);

		$this->field = FALSE === empty($this->widgetConfiguration['field']) ? $this->widgetConfiguration['field'] : 'name';
		$this->objects = $this->widgetConfiguration['objects'];
		$this->query = $this->objects->getQuery();
		$this->characters = explode(',', $this->configuration['characters']);
    }

    /**
	 * Main action terms will be sorted
	 * by the currentCharacter
	 *
     * @param string $character
     *
     * @return void
     */
    public function indexAction($character = 'A') {
		$this->currentCharacter = FALSE === empty($character) ? $character : $this->characters[0];

        $this->query->matching($this->query->like($this->field, $this->currentCharacter . '%'));
        $objects = $this->query->execute()->toArray();

		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $objects
        ));
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
		for ($i = 0; $i < $numberOfCharacters; $i++) {
			$pages[] = array(
				'character' => $this->characters[$i],
				'isCurrent' => $this->characters[$i] === $this->currentCharacter,
				'isEmpty'   => 0 === $this->query->matching($this->query->like($this->field, $this->characters[$i] . '%'))->execute()->count()
			);
		}

		$pagination = array(
			'pages'             => $pages,
			'current'           => $this->currentCharacter,
			'numberOfPages'     => $numberOfCharacters,
			'startCharacter'    => $this->characters[0],
			'endCharacter'      => $this->characters[count($this->characters) + 1]
		);

		return $pagination;
	}

}
