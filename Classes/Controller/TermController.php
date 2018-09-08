<?php
namespace Featdd\DpnGlossary\Controller;

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

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * @package DpnGlossary
 * @subpackage Controller
 */
class TermController extends ActionController
{
    /**
     * @var \Featdd\DpnGlossary\Domain\Repository\TermRepository
     */
    protected $termRepository;

    /**
     * @param \Featdd\DpnGlossary\Domain\Repository\TermRepository $termRepository
     */
    public function injectTermRepository(TermRepository $termRepository): void
    {
        $this->termRepository = $termRepository;
    }

    public function listAction(): void
    {
        /** @var array|QueryResult $terms */
        $terms = 'character' === $this->settings['listmode']
            ? $this->termRepository->findAllGroupedByFirstCharacter()
            : $this->termRepository->findAll();

        $this->view->assign('detailPage', $this->settings['detailPage']);
        $this->view->assign('listmode', $this->settings['listmode']);
        $this->view->assign('terms', $terms);
    }

    public function previewNewestAction(): void
    {
        $limit = (integer) $this->settings['previewlimit'];

        if (0 >= $limit) {
            $limit = TermRepository::DEFAULT_LIMIT;
        }

        $this->view->assign(
            'terms',
            $this->termRepository->findNewest($limit)
        );
    }

    public function previewRandomAction(): void
    {
        $limit = (integer) $this->settings['previewlimit'];

        if (0 >= $limit) {
            $limit = TermRepository::DEFAULT_LIMIT;
        }

        $this->view->assign(
            'terms',
            $this->termRepository->findRandom($limit)
        );
    }

    public function previewSelectedAction(): void
    {
        $previewSelectedUids = GeneralUtility::trimExplode(',', $this->settings['previewSelected']);

        $this->view->assign(
            'terms',
            $this->termRepository->findByUids($previewSelectedUids)
        );
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Term $term
     * @param integer $pageUid
     */
    public function showAction(Term $term, $pageUid = null): void
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

        if ((int) $this->settings['detailPage'] !== $pageUid) {
            $this->view->assign('pageUid', $pageUid);
        }

        $this->view->assign('term', $term);
    }
}
