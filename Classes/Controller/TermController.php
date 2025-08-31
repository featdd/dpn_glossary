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
 *  (c) 2025 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\AbstractTermRepository;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\PageTitle\CharacterPaginationPageTitleProvider;
use Featdd\DpnGlossary\PageTitle\TermPageTitleProvider;
use Featdd\DpnGlossary\Pagination\CharacterPagination;
use Featdd\DpnGlossary\Pagination\CharacterPaginator;
use Featdd\DpnGlossary\Utility\ObjectUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * @package Featdd\DpnGlossary\Controller
 */
class TermController extends ActionController
{
    /**
     * @var \Featdd\DpnGlossary\Domain\Repository\TermRepository
     */
    protected TermRepository $termRepository;

    /**
     * @var int[]
     */
    protected $storagePids = [];

    /**
     * @param \Featdd\DpnGlossary\Domain\Repository\TermRepository $termRepository
     */
    public function __construct(TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
    }

    public function initializeAction(): void
    {
        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'DpnGlossary',
        );

        $this->storagePids = GeneralUtility::intExplode(
            ',',
            $frameworkConfiguration['persistence']['storagePid'] ?? '',
            true
        );
    }

    /**
     * @param string|null $currentCharacter
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(?string $currentCharacter = null): ResponseInterface
    {
        $listMode = $this->settings['listmode'] ?? 'normal';
        $terms = $this->termRepository->findAll();

        switch ($listMode) {
            case 'character':
                $terms = ObjectUtility::groupObjectsByFirstCharacter(
                    $terms,
                    'name',
                    GeneralUtility::trimExplode(
                        ',',
                        $this->settings['groupedListCharacters'] ?? range('A', 'Z'),
                        true
                    )
                );
                break;
            case 'pagination':
                $paginationCharacters = GeneralUtility::trimExplode(
                    ',',
                    $this->settings['pagination']['characters'] ?? range('A', 'Z'),
                    true
                );

                $paginator = new CharacterPaginator($terms, 'name', $currentCharacter, ...$paginationCharacters);
                $pagination = new CharacterPagination($paginator, ...$paginationCharacters);

                $this->view->assignMultiple([
                    'paginator' => $paginator,
                    'pagination' => $pagination,
                ]);

                if ($paginator->getCurrentCharacter() !== null) {
                    $pageTitle = !empty($GLOBALS['TSFE']->page['seo_title'])
                        ? $GLOBALS['TSFE']->page['seo_title']
                        : $GLOBALS['TSFE']->page['title'];

                    /** @var \Featdd\DpnGlossary\PageTitle\CharacterPaginationPageTitleProvider $characterPaginationPageTitleProvider */
                    $characterPaginationPageTitleProvider = GeneralUtility::makeInstance(CharacterPaginationPageTitleProvider::class);
                    $characterPaginationPageTitleProvider->setTitle($pageTitle . ' - ' . $paginator->getCurrentCharacter());
                }
                break;
        }

        $this->view->assignMultiple([
            'listmode' => $listMode,
            'terms' => $terms,
        ]);

        return $this->htmlResponse();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function previewNewestAction(): ResponseInterface
    {
        $limit = (int) ($this->settings['previewlimit'] ?? 5);

        if (0 >= $limit) {
            $limit = AbstractTermRepository::DEFAULT_LIMIT;
        }

        $this->view->assign(
            'terms',
            $this->termRepository->findNewest($limit)
        );

        return $this->htmlResponse();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function previewRandomAction(): ResponseInterface
    {
        $limit = (int) ($this->settings['previewlimit'] ?? 5);

        if (0 >= $limit) {
            $limit = AbstractTermRepository::DEFAULT_LIMIT;
        }

        $this->view->assign(
            'terms',
            $this->termRepository->findRandom($limit)
        );

        return $this->htmlResponse();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function previewSelectedAction(): ResponseInterface
    {
        $previewSelectedUids = GeneralUtility::intExplode(',', $this->settings['previewSelected'], true);

        $this->view->assign(
            'terms',
            $this->termRepository->findByUids($previewSelectedUids)
        );

        return $this->htmlResponse();
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Term $term
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(Term $term): ResponseInterface
    {
        $this->view->assign('term', $term);

        if (!in_array($term->getPid(), $this->storagePids)) {
            $errorResponse = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                $this->request,
                LocalizationUtility::translate('tx_dpnglossary.no_term_found', 'dpn_glossary')
            );

            throw new ImmediateResponseException($errorResponse);
        }

        /** @var \Featdd\DpnGlossary\PageTitle\TermPageTitleProvider $pageTitleProvider */
        $pageTitleProvider = GeneralUtility::makeInstance(TermPageTitleProvider::class);
        $pageTitleProvider->setTitle($term->getSeoTitle());

        if (!empty($term->getMetaDescription())) {
            /** @var \TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry $metaTagManagerRegistry */
            $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
            $metaTagManager = $metaTagManagerRegistry->getManagerForProperty('description');

            if (empty($metaTagManager->getProperty('description'))) {
                $metaTagManager->addProperty('description', $term->getMetaDescription());
            }
        }

        return $this->htmlResponse();
    }
}
