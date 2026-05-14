# AGENTS.md

This file provides guidance to coding agents when working with code in this repository.

## What this is

`dpn_glossary` (composer name `featdd/dpn-glossary`) is a TYPO3 CMS extension that provides a glossary plugin **plus** an automatic content parser that scans rendered frontend HTML and turns occurrences of glossary terms into links to a detail page.

- TYPO3 support range: `^13.4 || ^14.3` (see `composer.json`, `ext_emconf.php`)
- PHP: `>=8.2 <8.6`
- Node: `^24` (see `.nvmrc`, `package.json`)
- Distribution: TER (`dpn_glossary`) and Packagist (`featdd/dpn-glossary`)

## Commands

There is no PHP test suite, lint config, or CI in this repository — the only build pipeline is for frontend CSS and documentation.

```bash
# Frontend CSS (Scss → Resources/Public/Css/styles.css)
npm install
npm run build:development    # webpack --watch with sourcemaps
npm run build:production     # minified bundle

# Documentation (renders Documentation/ via the official TYPO3 render-guides container)
make docs
```

The webpack entry is `Resources/Private/Assets/Scss/Styles.scss`; output goes to `Resources/Public/Css/styles.css`. There is no JS bundle — empty JS chunks are stripped by `webpack-remove-empty-scripts`.

## Architecture

The extension has two largely independent halves: an Extbase plugin (list/detail/preview views of terms) and a frontend HTML post-processor that auto-links terms across the whole page.

### Plugin registration (`ext_localconf.php`)

Four Extbase plugins are registered, all backed by `TermController` but with different allowed actions:

| Plugin signature             | Actions                |
| ---------------------------- | ---------------------- |
| `Glossary`                   | `list`, `show`         |
| `Glossarypreviewnewest`      | `previewNewest`        |
| `Glossarypreviewrandom`      | `previewRandom`        |
| `Glossarypreviewselected`    | `previewSelected`      |

All are `PLUGIN_TYPE_CONTENT_ELEMENT` (CTypes), not legacy list_type plugins — the v7 migration moved away from list_type, and `PluginCTypeMigrationUpdateWizard` exists to upgrade older installs.

Also registered here: the `StaticMultiRangeMapper` routing aspect (used to map character-pagination ranges like `a-z` in URLs), and the `dpnglossary_termscache` cache configuration.

### The page parser (the non-obvious half)

`EventListener\AfterCacheableContentIsGeneratedEventListener` listens for TYPO3's `AfterCacheableContentIsGeneratedEvent` and, unless `disableParser` is set in TypoScript settings or `tx_dpnglossary_disable_parser` is set on the page record, hands the full rendered page HTML to `Service\ParserService::pageParser()`.

`ParserService` (a `SingletonInterface`) loads the HTML into a `DOMDocument`, walks it with `DOMXPath`, and replaces text-node occurrences of terms/synonyms with anchor tags pointing at the detail action. Constraints worth knowing:

- Parent tags `a` and `script` are always skipped (`$alwaysIgnoreParentTags`); additional ignore tags come from TypoScript.
- Terms come from `ParserTermRepository` (a dedicated read-side repository for the parser path — different from `TermRepository` used by the controller). Results are cached in the `dpnglossary_termscache` Caching Framework cache, wired in `Services.yaml` as `cache.dpnglossary_parserterms`.
- `Hook\DataHandlerClearCachePostProcHook` flushes that cache when term/synonym/description records change in the backend.

V13 vs v14 compat: the event carries content in different ways between versions. The listener uses `method_exists($event, 'getController')` to branch — keep this dual path when modifying.

### Domain model

Three tables, all extending `AbstractTerm` on the PHP side:

- `tx_dpnglossary_domain_model_term` — primary `Term` (has slug, used for detail URLs)
- `tx_dpnglossary_domain_model_synonym` — alternative spellings that resolve to a term
- `tx_dpnglossary_domain_model_description` — additional descriptions attached to a term

`TermInterface` / `AbstractTermRepository` / `TermRepositoryInterface` exist so the parser can treat terms and synonyms uniformly. `ParserTerm` / `ParserTermRepository` is the read-only flat representation used by the parser hot path.

### Configuration entry points

- `Configuration/Sets/Default/` — TYPO3 v13+ **Site Set**. This is the modern replacement for static TypoScript includes; `setup.typoscript`, `constants.typoscript`, `settings.definitions.yaml`, `route-enhancers.yaml`, and labels all live here.
- `Configuration/TypoScript/` — legacy TypoScript (kept for backward compatibility).
- `Configuration/TCA/` and `Configuration/TCA/Overrides/` — table definitions and `tt_content` / `pages` field additions.
- `Configuration/FlexForms/` — flexform definitions for the four plugins.
- `Configuration/Services.yaml` — DI; note the explicit exclusion of `Domain/Model` and `Utility` from autowiring. Settings (TypoScript `plugin.tx_dpnglossary.settings` + Site Set settings) are resolved via `Utility\SettingsUtility::getSettings($site, $pid, ?$request)`, which uses the request's `frontend.typoscript` attribute when available and falls back to the v13 `IncludeTree` API for BE / legacy sys_template contexts.

### Update wizards

`Classes/Updates/` contains wizards run via `vendor/bin/typo3 upgrade:run`:

- `PluginCTypeMigrationUpdateWizard` — migrates pre-v7 list_type plugins to CTypes
- `PluginSwitchableControllerMigrationUpdateWizard` — collapses the old switchableControllerActions config into per-CType plugins
- `SlugUpdateWizard` — populates the `slug` field added in v6

When touching plugin signatures or term table schema, check whether a new update wizard is needed.

### Other extension points

- `DataProcessing/AddTermToMenuProcessor` — adds term data to TypoScript menu processors.
- `PageTitle/TermPageTitleProvider`, `PageTitle/CharacterPaginationPageTitleProvider` — page title providers, registered automatically via TYPO3's provider chain.
- `Pagination/CharacterPaginator` — custom paginator that groups terms by first character (with the `StaticMultiRangeMapper` routing aspect for URL segments).
- `EventListener\ModifyUrlForCanonicalTagEventListener` — fixes canonical URLs on glossary detail pages; reads settings via `SettingsUtility::getSettings()` passing the event's request for the FE fast path.
- `ViewHelpers/BacklinkViewHelper` — Fluid VH used in detail templates.

## Conventions

- All PHP files use `declare(strict_types=1);` and the standard "dreipunktnull Glossar" license header. Match the existing header when adding new classes.
- Indent: 4 spaces for PHP, 2 spaces for JS/CSS/SCSS/JSON/YAML/TypoScript/XML/XLF (see `.editorconfig`).
- Fluid templates live in `Resources/Private/Templates/Term/` and `Resources/Private/Templates/TermWraps/` (the latter holds the snippets injected by the parser).
- Language labels: `Resources/Private/Language/` (TYPO3 XLF) for the extension itself; **Site Set** labels live in `Configuration/Sets/Default/labels.xlf` / `de.labels.xlf`.

## Release process

Bumping a release means updating the version in **both** `ext_emconf.php` and `package.json` (the TYPO3 extension version and the npm package version are kept in sync), then tagging. Recent commits (`[TASK] Release 7.0.1`, `[TASK] Release 7.0.0`) show the commit-message convention: bracketed prefixes like `[TASK]`, `[BUGFIX]`, `[FEATURE]`.
