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
     * @var QueryResultInterface
     */
    protected $terms;

	/**
	 * @var QueryInterface
	 */
	protected $query;

    /**
     * @var string
     */
    protected $currentCharacter = 'A';

	/**
	 * @var array
	 */
	protected $characters = array();

    /**
     * @return void
     */
    public function initializeAction() {
		ArrayUtility::mergeRecursiveWithOverrule(
			$this->configuration,
			(array)$this->settings['pagination'],
			TRUE
		);

		$this->terms = $this->widgetConfiguration['terms'];
		$this->query = $this->terms->getQuery();
		$this->characters = explode(',', $this->configuration['characters']);
    }

    /**
     * @param string $character
     *
     * @return void
     */
    public function indexAction($character = 'A') {
		$this->currentCharacter = $character;

        $this->query->matching($this->query->like('name', $character . '%'));
        $terms = $this->query->execute()->toArray();

		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $terms
        ));
    }

	/**
	 * @return array
	 */
	protected function buildPagination() {
		$pages = array();
		$numberOfCharacters = count($this->characters);

		for ($i = 0; $i < $numberOfCharacters; $i++) {
			$pages[] = array(
				'character' => $this->characters[$i],
				'isCurrent' => $this->characters[$i] === $this->currentCharacter,
				'isEmpty'   => 0 === $this->query->matching($this->query->like('name', $this->characters[$i] . '%'))->execute()->count()
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
