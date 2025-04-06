<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\DataProcessing;

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

use Featdd\DpnGlossary\Domain\Model\Term;
use Featdd\DpnGlossary\Domain\Repository\TermRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * @package Featdd\DpnGlossary\DataProcessing
 */
class AddTermToMenuProcessor implements DataProcessorInterface
{
    /**
     * @var \Featdd\DpnGlossary\Domain\Repository\TermRepository
     */
    protected TermRepository $termRepository;

    public function __construct()
    {
        $this->termRepository = GeneralUtility::makeInstance(TermRepository::class);
    }

    /**
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array
     * @throws \TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (empty($processorConfiguration['menus'])) {
            return $processedData;
        }

        $parameters = $cObj->getRequest()->getQueryParams()['tx_dpnglossary_glossary'] ?? null;

        if (is_array($parameters) && (int) ($parameters['term'] ?? 0) > 0) {
            $term = $this->termRepository->findByUid((int) $parameters['term']);

            if ($term instanceof Term) {
                $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);

                foreach ($menus as $menu) {
                    if (isset($processedData[$menu])) {
                        $this->addTermToMenu($term, $processedData[$menu]);
                    }
                }
            }
        }

        return $processedData;
    }

    /**
     * @param \Featdd\DpnGlossary\Domain\Model\Term $term
     * @param array $menu
     */
    protected function addTermToMenu(Term $term, array &$menu): void
    {
        foreach ($menu as &$menuItem) {
            $menuItem['current'] = 0;
        }

        $menu[] = [
            'data' => $term,
            'title' => $term->getName(),
            'active' => 1,
            'current' => 1,
            'link' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'isTerm' => true,
        ];
    }
}
