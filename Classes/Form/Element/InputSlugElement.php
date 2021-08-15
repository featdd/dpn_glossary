<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Form\Element;

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

use TYPO3\CMS\Backend\Form\Element\InputSlugElement as CoreInputSlugElement;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package Featdd\DpnGlossary\Form\Element
 */
class InputSlugElement extends CoreInputSlugElement
{

    /**
     * @return array
     */
    public function render(): array
    {
        if (
            true === version_compare(TYPO3_version, '10.4', '>=') &&
            true === empty($this->data['customData']['url_segment']['slugPrefix'])
        ) {
            $languageId = 0;
            $tableName = $this->data['tableName'];
            $record = $this->data['databaseRow'];

            if (
                true === isset($GLOBALS['TCA'][$tableName]['ctrl']['languageField']) &&
                false === empty($GLOBALS['TCA'][$tableName]['ctrl']['languageField'])
            ) {
                $languageField = $GLOBALS['TCA'][$tableName]['ctrl']['languageField'];
                $languageId = (int) (true === is_array($record[$languageField])
                    ? $record[$languageField][0]
                    : $record[$languageField] ?? 0
                );
            }

            try {
                $this->data['customData']['url_segment']['slugPrefix'] = $this->getSlugPrefix(
                    $this->data['site'], $languageId
                );
            } catch (SiteNotFoundException | InvalidRouteArgumentsException $exception) {
                // nothing
            }
        }

        return parent::render();
    }

    /**
     * @param \TYPO3\CMS\Core\Site\Entity\SiteInterface $site
     * @param int $languageId
     * @return string
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     * @throws \TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException
     */
    protected function getSlugPrefix(SiteInterface $site, int $languageId): string
    {
        $pageUid = (int) $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['pageUid'];
        $action = $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['action'];
        $controller = $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['controller'];
        $plugin = $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['plugin'];
        $extensionName = $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['extensionName'];
        $slugAlias = $this->data['parameterArray']['fieldTSConfig']['config.']['previewUrl.']['slugAlias'];

        if (
            0 === $pageUid ||
            true === empty($action) ||
            true === empty($controller) ||
            true === empty($plugin) ||
            true === empty($extensionName) ||
            true === empty($slugAlias)
        ) {
            return '';
        }

        if ($this->data['site'] instanceof NullSite) {
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

            try {
                $site = $siteFinder->getSiteByPageId($pageUid);
            } catch (SiteNotFoundException $exception) {
                return LocalizationUtility::translate('tx_dpnglossary.error.slug_preview_site_missing', 'dpn_glossary');
            }
        }

        $pluginUrlParameter = 'tx_' . strtolower($extensionName) . '_' . strtolower($plugin);

        $prefixUrl = (string) $site->getRouter()->generateUri(
            $pageUid,
            [
                '_language' => 0 < $languageId
                    ? $site->getLanguageById($languageId)
                    : $site->getDefaultLanguage(),
                $pluginUrlParameter => [
                    'action' => $action,
                    'controller' => $controller,
                    $slugAlias => 'SLUG',
                ],
            ]
        );

        return preg_replace('/SLUG\/?$/', '', $prefixUrl);
    }

    /**
     * @param \TYPO3\CMS\Core\Site\Entity\SiteInterface $site
     * @param int $requestLanguageId
     * @return string
     */
    protected function getPrefix(SiteInterface $site, int $requestLanguageId = 0): string
    {
        try {
            return $this->getSlugPrefix($site, $requestLanguageId);
        } catch (SiteNotFoundException | InvalidRouteArgumentsException $exception) {
            return '';
        }
    }
}
