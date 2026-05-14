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
 *  (c) 2026 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use Featdd\DpnGlossary\Pagination\CharacterPaginator;
use Featdd\DpnGlossary\Utility\SettingsUtility;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;

class ModifyUrlForCanonicalTagEventListener
{
    public function __construct(
        protected readonly TermRepository $termRepository
    ) {}

    /**
     * @param \TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent $modifyUrlForCanonicalTagEvent
     */
    public function __invoke(ModifyUrlForCanonicalTagEvent $modifyUrlForCanonicalTagEvent): void
    {
        $request = $modifyUrlForCanonicalTagEvent->getRequest();
        $site = $request->getAttribute('site');

        if (!$site instanceof Site) {
            return;
        }

        /** @var \TYPO3\CMS\Core\Routing\PageArguments $pageArguments */
        $pageArguments = $request->getAttribute('routing');
        $pageId = $pageArguments->getPageId();
        $settingsUtility = new SettingsUtility($site, $pageId, $request);
        $detailPage = (int) ($settingsUtility->getSetting('detailPage') ?? 0);
        $listMode = (string) ($settingsUtility->getSetting('listmode') ?? '');

        $queryParameters = $request->getQueryParams();

        if (
            isset($queryParameters['tx_dpnglossary_glossary']['currentCharacter']) ||
            isset($queryParameters['tx_dpnglossary_glossary']['term']) ||
            $listMode !== 'pagination' ||
            $pageId !== $detailPage
        ) {
            return;
        }

        $paginationCharacters = GeneralUtility::trimExplode(',', ($settingsUtility->getSetting('pagination') ?? [])['characters'] ?? '', true);

        if ($paginationCharacters === []) {
            return;
        }

        $paginator = new CharacterPaginator($this->termRepository->findAll(), 'name', null, ...$paginationCharacters);
        $currentCharacter = $paginator->getCurrentCharacter();

        if (empty($currentCharacter)) {
            return;
        }

        $modifyUrlForCanonicalTagEvent->setUrl(
            (string) $site->getRouter()->generateUri(
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
