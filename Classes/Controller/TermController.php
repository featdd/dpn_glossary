<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Controller;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\PageTitle\TermPageTitleProvider;
use Featdd\DpnGlossary\Utility\ObjectUtility;
use Featdd\DpnGlossary\ViewHelpers\Widget\Controller\PaginateController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * @package Featdd\DpnGlossary\Controller
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
        $limit = (int) $this->settings['previewlimit'];

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
        $previewSelectedUids = GeneralUtility::intExplode(',', $this->settings['previewSelected']);

        $this->view->assign(
            'terms',
            $this->termRepository->findByUids($previewSelectedUids)
        );
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Term $term
     */
    public function showAction(Term $term): void
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

        $this->view->assign('term', $term);

        /** @var \Featdd\DpnGlossary\PageTitle\TermPageTitleProvider $pageTitleProvider */
        $pageTitleProvider = ObjectUtility::makeInstance(TermPageTitleProvider::class);
        $pageTitleProvider->setTitle($term->getName());
    }
}
