# dpn_glossary

A TYPO3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is developed and tested in TYPO3 6.1.X

## Contact
<dorndorf@dreipunktnull.com>

# Configuration

Integrate the plugin in your root template.
Over the constant-editor you will be able to make some configurations.
- Set the pageIds which should be parsed for terms (1,n | 0 for all)
- PageId where a detailpage is aviable, for the the parsed terms
 - Otherwise if tooltips are turned on the link will be an anchor
- The StoragePids where the terms are stored
- Link configuration:
 - The generated Link is whole configurable over TypoScript (see example).

##Example TypoScript Configuration
```TypoScript
plugin.tx_dpnglossary {
    settings {
        termWraps {
            default {
                dataWrap = <span class="dpnglossary" lang="{field:term_lang}" title="{field:name}">|</span>
                typolink.ATagParams.dataWrap = title="{field:tooltiptext}" class="dpnglossary-link"
            }
            acronym {
                datawrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
            }
            abbreviation {
                dataWrap = <abbr title="{field:tooltiptext}" lang="{field:term_lang}">|</abbr>
            }
            acronym {
                dataWrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
            }
            definition {
                dataWrap = <dfn title="{field:tooltiptext}" lang="{field:term_lang}">|</dfn>
            }
        }
    }
}
```

##Example RealURL Configuration
```PHP
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'] = array(
	'fixedPostVars' => array(
		'dpn_glossary' => array(
			array(
				'GETvar' => 'tx_dpnglossary_glossary[controller]',
				'noMatch' => 'bypass'
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[action]',
				'valueMap' => array(
					'detail' => 'show',
				),
				'valueDefault' => 'list',
				'noMatch' => 'bypass'
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[term]',
				'lookUpTable' => array(
					'table' => 'tx_dpnglossary_domain_model_term',
					'id_field' => 'uid',
					'alias_field' => 'name',
					'addWhereClause' => ' AND NOT deleted',
					'useUniqueCache' => 1,
					'useUniqueCache_conf' => array(
						'strtolower' => 1,
						'spaceCharacter' => '-'
					),
					'languageGetVar' => 'L',
					'languageExceptionUids' => '',
					'languageField' => 'sys_language_uid',
					'transOrigPointerField' => 'l10n_parent',
					'autoUpdate' => 1,
					'expireDays' => 180
				),
			),
			array(
				'GETvar' => 'tx_dpnglossary_glossary[pageUid]'
			),
		),
		'DETAILPAGE_ID' => 'dpn_glossary',
	),
);
```