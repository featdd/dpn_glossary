<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\UserFunc;

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

use Featdd\DpnGlossary\Utility\SettingsUtility;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSlug;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class SlugPreviewUserFunc
{
    /**
     * @param array $parameters
     * @param \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSlug $tcaSlug
     * @return string|null
     */
    public function slugPrefixUserFunc(array $parameters, TcaSlug $tcaSlug): ?string
    {
        $termMode = is_array($parameters['row']['term_mode'])
            ? reset($parameters['row']['term_mode'])
            : $parameters['row']['term_mode'];

        if ($termMode === 'link') {
            return '#';
        }

        $pageId = (int) ($parameters['row']['pid'] ?? 0);
        $site = $parameters['site'] ?? null;

        if ($site instanceof NullSite) {
            try {
                $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($pageId);
            } catch (SiteNotFoundException) {
                return LocalizationUtility::translate('tx_dpnglossary.error.slug_preview_site_missing', 'dpn_glossary');
            }
        }

        if (!$site instanceof Site) {
            return LocalizationUtility::translate('tx_dpnglossary.error.slug_preview_site_missing', 'dpn_glossary');
        }


        $detailPageUid = (int) ((new SettingsUtility($site, $pageId))->getSetting('detailPage') ?? 0);

        if ($detailPageUid <= 0) {
            return '#';
        }

        $languageId = $parameters['languageId'];
        $termUid = $parameters['row']['uid'];
        $slugValue = $parameters['row']['url_segment'];

        $prefixUrl = (string) $site->getRouter()->generateUri(
            $detailPageUid,
            [
                '_language' => 0 < $languageId
                    ? $site->getLanguageById($languageId)
                    : $site->getDefaultLanguage(),
                'tx_dpnglossary_glossary' => [
                    'action' => 'show',
                    'controller' => 'Term',
                    'term' => $termUid,
                ],
            ]
        );

        return preg_replace('#' . preg_quote($slugValue) . '/?$#', '', $prefixUrl);
    }
}
