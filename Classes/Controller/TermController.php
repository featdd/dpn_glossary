<?php
namespace Dpn\DpnGlossary\Controller;

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

use Dpn\DpnGlossary\Domain\Model\Term;
use Dpn\DpnGlossary\Domain\Repository\TermRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 *
 *
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TermController extends ActionController {

	/**
	 * @var TermRepository
	 */
	protected $termRepository;

	/**
	 * @param TermRepository $termRepository
	 * @return void
	 */
	public function injectTermRepository(TermRepository $termRepository) {
		$this->termRepository = $termRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$terms = $this->termRepository->findAll();
		$this->view->assign('detailPage', $this->settings['detailPage']);
		$this->view->assign('terms', $terms);
	}

	/**
	 * action for a character navigation view
	 *
	 * @return void
	 */
	public function characterAction() {
		$terms = $this->termRepository->findAllGroupedByFirstCharacter();
		$this->view->assign('detailPage', $this->settings['characterDetailPage']);
		$this->view->assign('terms', $terms);
	}

	/**
	 * action show
	 *
	 * @param Term $term
	 * @return void
	 */
	public function showAction(Term $term) {
		if(TRUE === $this->request->hasArgument('pageUid')) {
			$pageUid = $this->request->getArgument('pageUid');
			$this->view->assign('pageUid', $pageUid);
		}
		$this->view->assign('listPage', $this->settings['listPage']);
		$this->view->assign('term', $term);
	}
}