<?php
namespace Featdd\DpnGlossary\Controller;

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

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * @package dpn_glossary
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TermController extends ActionController
{
    /**
     * @var \Featdd\DpnGlossary\Domain\Repository\TermRepository
     * @inject
     */
    protected $termRepository;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        /** @var array|QueryResult $terms */
        $terms = 'character' === $this->settings['listmode'] ?
            $this->termRepository->findAllGroupedByFirstCharacter() :
            $this->termRepository->findAll();

        $this->view->assign('detailPage', $this->settings['detailPage']);
        $this->view->assign('listmode', $this->settings['listmode']);
        $this->view->assign('terms', $terms);
    }

    /**
     * action show
     *
     * @param Term    $term
     * @param integer $pageUid
     * @return void
     */
    public function showAction(Term $term, $pageUid = NULL)
    {
        if ('pagination' === $this->settings['listmode']) {
            $this->view->assign(
                'paginateLink',
                PaginateController::paginationArguments(
                    $term->getName(),
                    $this->settings['pagination']['characters']
                )
            );
        }

        $this->view->assign('pageUid', $pageUid);
        $this->view->assign('listPage', $this->settings['listPage']);
        $this->view->assign('term', $term);
    }
}
