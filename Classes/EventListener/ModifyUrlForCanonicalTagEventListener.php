<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\EventListener;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\Pagination\CharacterPaginator;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;

/**
 * @package Featdd\DpnGlossary\EventListener
 */
class ModifyUrlForCanonicalTagEventListener
{
    /**
     * @var \Featdd\DpnGlossary\Domain\Repository\TermRepository
     */
    protected TermRepository $termRepository;

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @param \Featdd\DpnGlossary\Domain\Repository\TermRepository $termRepository
     * @param array $settings
     */
    public function __construct(TermRepository $termRepository, array $settings)
    {
        $this->termRepository = $termRepository;
        $this->settings = $settings;
    }

    /**
     * @param \TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent $modifyUrlForCanonicalTagEvent
     */
    public function __invoke(ModifyUrlForCanonicalTagEvent $modifyUrlForCanonicalTagEvent): void
    {
        /** @var \TYPO3\CMS\Extbase\Mvc\Request $request */
        $request = $GLOBALS['TYPO3_REQUEST'];

        $detailPage = (int)($this->settings["detailPage"] ?? 0);
        $listMode = $this->settings["listmode"] ?? '';

        $queryParameters = $request->getQueryParams();
        /** @var \TYPO3\CMS\Core\Routing\PageArguments $pageArguments */
        $pageArguments = $request->getAttribute('routing');
        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');

        if (
            $site instanceof Site &&
            !isset($queryParameters['tx_dpnglossary_glossary']['currentCharacter']) &&
            !isset($queryParameters['tx_dpnglossary_glossary']['term']) &&
            $listMode === 'pagination' &&
            $pageArguments->getPageId() === $detailPage
        ) {
            $currentCharacter = null;
            $paginationCharacters = GeneralUtility::trimExplode(',', $this->settings['pagination']['characters'] ?? '', true);

            if (0 < $paginationCharacters) {
                $paginator = new CharacterPaginator($this->termRepository->findAll(), 'name', null, ...$paginationCharacters);
                $currentCharacter = $paginator->getCurrentCharacter();
            }

            if (!empty($currentCharacter)) {
                $modifyUrlForCanonicalTagEvent->setUrl(
                    (string)$site->getRouter()->generateUri(
                        $detailPage,
                        [
                            'tx_dpnglossary_glossary' => [
                                'action' => 'list',
                                'controller' => 'Term',
                                'currentCharacter' => $currentCharacter,
                            ],
                        ],
                    )
                );
            }
        }
    }
}
