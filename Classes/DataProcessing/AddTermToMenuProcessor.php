<?php
namespace Featdd\DpnGlossary\DataProcessing;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Add the current glossary record to any menu, e.g. breadcrumb
 *
 * @example
 * page.20.dataProcessing {
 *      20 = Featdd\DpnGlossary\DataProcessing\AddTermToMenuProcessor
 *      20.menus = breadcrumbMenu,specialMenu
 * }
 */

class AddTermToMenuProcessor implements DataProcessorInterface {
    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (!$processorConfiguration['menus']) {
            return $processedData;
        }

        $glossaryRecord = $this->getGlossaryRecord();
        if ($glossaryRecord) {
            $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
            foreach ($menus as $menu) {
                if (isset($processedData[$menu])) {
                    $this->addGlossaryRecordToMenu($glossaryRecord, $processedData[$menu]);
                }
            }
        }
        return $processedData;
    }

    /**
     * Add the Glossary record to the menu items
     *
     * @param array $glossaryRecord
     * @param array $menu
     */
    protected function addGlossaryRecordToMenu(array $glossaryRecord, array &$menu)
    {
        foreach ($menu as &$menuItem) {
            $menuItem['current'] = 0;
        }

        $menu[] = [
            'data' => $glossaryRecord,
            'title' => $glossaryRecord['name'],
            'active' => 1,
            'current' => 1,
            'link' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'isGlossaryTerm' => true
        ];
    }

    /**
     * Get the glossary record including possible translations
     *
     * @return array
     */
    protected function getGlossaryRecord(): array
    {
        $termId = 0;
        $vars = GeneralUtility::_GET('tx_dpnglossary_glossary');
        if (isset($vars['term'])) {
            $termId = (int)$vars['term'];
        }

        if ($termId) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_dpnglossary_domain_model_term');
            $row = $queryBuilder
                ->select('*')
                ->from('tx_dpnglossary_domain_model_term')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($termId, \PDO::PARAM_INT))
                )
                ->execute()
                ->fetch();

            if ($row) {
                $row = $this->getTsfe()->sys_page->getRecordOverlay('tx_dpnglossary_domain_model_term', $row, $this->getCurrentLanguage());
            }

            if (is_array($row) && !empty($row)) {
                return $row;
            }
        }
        return [];
    }

    /**
     * Get current language
     *
     * @return int
     */
    protected function getCurrentLanguage(): int
    {
        $languageId = 0;
        $context = GeneralUtility::makeInstance(Context::class);
        try {
            $languageId = $context->getPropertyFromAspect('language', 'contentId');
        } catch (AspectNotFoundException $e) {
            // do nothing
        }

        return (int)$languageId;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
