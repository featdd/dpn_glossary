<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\ViewHelpers;

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

use Psr\Http\Message\ServerRequestInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * @package Featdd\DpnGlossary\ViewHelpers
 */
class BacklinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @return string
     */
    public function render(): string
    {
        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
        $httpReferer = $request?->getServerParams()['HTTP_REFERER'] ?? '';

        $url = false === empty($httpReferer)
            ? $httpReferer
            : 'javascript:history.back(1)';

        $this->tag->addAttribute('href', $url);

        $this->tag->setContent(
            $this->renderChildren()
        );

        $this->tag->forceClosingTag(true);

        return $this->tag->render();
    }
}
