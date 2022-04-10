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
use Featdd\DpnGlossary\Pagination\CharacterPagination;
use Featdd\DpnGlossary\Pagination\CharacterPaginator;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
    public function __construct(TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
    }

    /**
     * @param string|null $currentCharacter
     */
    public function listAction(string $currentCharacter = null): void
    {
        /** @var array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $terms */
        $terms = 'character' === $this->settings['listmode']
            ? $this->termRepository->findAllGroupedByFirstCharacter()
            : $this->termRepository->findAll();

        if ('pagination' === $this->settings['listmode']) {
            $paginationCharacters = GeneralUtility::trimExplode(',', $this->settings['pagination']['characters'] ?? '', true);

            if (0 < $paginationCharacters) {
                $paginator = new CharacterPaginator($terms, 'name', $currentCharacter, ...$paginationCharacters);
                $pagination = new CharacterPagination($paginator, ...$paginationCharacters);

                $this->view->assignMultiple([
                    'paginator' => $paginator,
                    'pagination' => $pagination,
                ]);
            }
        }

        $this->view->assignMultiple([
            'listmode' => $this->settings['listmode'],
            'terms' => $terms,
        ]);
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
        $this->view->assign('term', $term);

        /** @var \Featdd\DpnGlossary\PageTitle\TermPageTitleProvider $pageTitleProvider */
        $pageTitleProvider = GeneralUtility::makeInstance(TermPageTitleProvider::class);
        $pageTitleProvider->setTitle($term->getSeoTitle());

        if (false === empty($term->getMetaDescription())) {
            /** @var \TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry $metaTagManagerRegistry */
            $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
            $metaTagManager = $metaTagManagerRegistry->getManagerForProperty('description');

            if (true === empty($metaTagManager->getProperty('description'))) {
                $metaTagManager->addProperty('description', $term->getMetaDescription());
            }
        }
    }
}
