<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Utility;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use DOMDocument;
use DOMText;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Featdd\DpnGlossary\Utility
 */
class LinkUtility
{
    /**
     * @param string $parameter
     * @param array $additionalParams
     * @param bool $absolute
     * @return string
     */
    public static function renderTypoLink(string $parameter, array $additionalParams = [], bool $absolute = false): string
    {
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $additionalParamsString = http_build_query($additionalParams, '', '&', PHP_QUERY_RFC3986);

        return $contentObjectRenderer->typoLink_URL([
            'parameter' => $parameter,
            'additionalParams' => $additionalParamsString ? '&' . $additionalParamsString : '',
            'forceAbsoluteUrl' => $absolute,
        ]);
    }
}
