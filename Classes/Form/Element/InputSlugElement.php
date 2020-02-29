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
 *  (c) 2020 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Backend\Form\Element\InputSlugElement as CoreInputSlugElement;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\PseudoSite;
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
     * @param \TYPO3\CMS\Core\Site\Entity\SiteInterface $site
     * @param int $requestLanguageId
     * @return string
     * @throws \TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException
     */
    protected function getPrefix(SiteInterface $site, int $requestLanguageId = 0): string
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
            return parent::getPrefix($site, $requestLanguageId);
        }

        if ($site instanceof PseudoSite) {
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
                '_language' => 0 < $requestLanguageId
                    ? $site->getLanguageById($requestLanguageId)
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
}
