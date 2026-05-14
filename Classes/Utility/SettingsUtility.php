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
 *  (c) 2026 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\IncludeTree\SysTemplateRepository;
use TYPO3\CMS\Core\TypoScript\IncludeTree\SysTemplateTreeBuilder;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Traverser\IncludeTreeTraverser;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Visitor\IncludeTreeAstBuilderVisitor;
use TYPO3\CMS\Core\TypoScript\Tokenizer\LossyTokenizer;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class SettingsUtility
{
    /**
     * Maps the Extbase-style plugin settings key to its Site Settings counterpart.
     * Used by getSetting() to translate caller-side keys (e.g. "detailPage") to
     * the key under `dpn-glossary.*` in site settings (e.g. "glossaryPage").
     */
    protected const EXTBASE_TO_SITE_SETTINGS_MAP = [
        'detailPage' => 'glossaryPage',
        'storagePid' => 'storagePidList',
    ];

    protected ?Site $site;
    protected ?int $pageId;
    protected ?ServerRequestInterface $request;
    protected ?array $typoScriptCache = null;

    public function __construct(
        ?Site $site = null,
        ?int $pageId = null,
        ?ServerRequestInterface $request = null
    ) {
        // Fallback to global request if no explicit request is given
        $this->request = $request ?? ($GLOBALS['TYPO3_REQUEST'] ?? null);
        // Fetch site from the resolved request if not explicitly given
        $this->site = $site ?? $this->request?->getAttribute('site');
        // Prefer routed page id, otherwise fall back to the site root page
        $routing = $this->request?->getAttribute('routing');
        $this->pageId = $pageId
            ?? ($routing instanceof PageArguments ? $routing->getPageId() : null)
            ?? $this->site?->getRootPageId();
    }

    /**
     * Returns a single plugin setting.
     *
     * Lookup order: site settings (via the reverse rename map) → plugin
     * TypoScript. Site setting wins when non-empty; empty values (0/''/null/false)
     * fall through to TypoScript — for `detailPage = 0` this is desirable
     * ("no detail page configured"). If a future boolean setting needs to
     * distinguish "explicit false" from "unset", revisit this method.
     *
     * Only resolves flat keys under the `settings` / `persistence` buckets.
     * Nested values (e.g. `pagination.characters`) need getExtensionTypoScript().
     */
    public function getSetting(string $settingsKey): mixed
    {
        $siteSettingValue = $this->site->getSettings()->get(
            sprintf(
                'dpn-glossary.%s',
                self::EXTBASE_TO_SITE_SETTINGS_MAP[$settingsKey] ?? $settingsKey
            )
        );

        if ($siteSettingValue !== null) {
            return $siteSettingValue;
        }

        return $this->getExtensionTypoScript()[$settingsKey === 'storagePid' ? 'persistence' : 'settings'][$settingsKey] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtensionTypoScript(bool $convertToPlainArray = true): array
    {
        $typoScript = $this->loadTypoScriptSetup();
        $pluginTypoScript = $typoScript['plugin.']['tx_dpnglossary.'] ?? [];

        if ($pluginTypoScript === []) {
            return [];
        }

        if (!$convertToPlainArray) {
            return $pluginTypoScript;
        }

        return GeneralUtility::makeInstance(TypoScriptService::class)
            ->convertTypoScriptArrayToPlainArray($pluginTypoScript);
    }

    /**
     * @return array<string, mixed>
     */
    protected function loadTypoScriptSetup(): array
    {
        if ($this->typoScriptCache !== null) {
            return $this->typoScriptCache;
        }

        // Check if current request already has the frontend TypoScript available
        if (
            $this->request instanceof ServerRequestInterface &&
            $this->request->getAttribute('frontend.typoscript') instanceof FrontendTypoScript &&
            $this->request->getAttribute('frontend.typoscript')->hasSetup()
        ) {
            return $this->typoScriptCache = $this->request->getAttribute('frontend.typoscript')->getSetupArray();
        }

        if (empty($this->pageId)) {
            return $this->typoScriptCache = [];
        }

        try {
            $rootLine = GeneralUtility::makeInstance(RootlineUtility::class, $this->pageId)->get();
        } catch (Throwable) {
            return $this->typoScriptCache = [];
        }

        $sysTemplateRepository = GeneralUtility::makeInstance(SysTemplateRepository::class);
        $sysTemplateRows = $sysTemplateRepository->getSysTemplateRowsByRootline($rootLine);

        $treeBuilder = GeneralUtility::makeInstance(SysTemplateTreeBuilder::class);
        $tokenizer = new LossyTokenizer();

        $constantsTree = $treeBuilder->getTreeBySysTemplateRowsAndSite(
            'constants',
            $sysTemplateRows,
            $tokenizer,
            $this->site
        );

        $constantsAstBuilder = GeneralUtility::makeInstance(IncludeTreeAstBuilderVisitor::class);
        (new IncludeTreeTraverser())->traverse($constantsTree, [$constantsAstBuilder]);
        $flatConstants = $constantsAstBuilder->getAst()->flatten();

        $setupTree = $treeBuilder->getTreeBySysTemplateRowsAndSite(
            'setup',
            $sysTemplateRows,
            $tokenizer,
            $this->site
        );

        $setupAstBuilder = GeneralUtility::makeInstance(IncludeTreeAstBuilderVisitor::class);
        $setupAstBuilder->setFlatConstants($flatConstants);
        (new IncludeTreeTraverser())->traverse($setupTree, [$setupAstBuilder]);

        return $this->typoScriptCache = $setupAstBuilder->getAst()->toArray();
    }
}
