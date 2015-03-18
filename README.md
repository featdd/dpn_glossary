# dpn_glossary

A TYPO3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is developed and tested in TYPO3 6.2.X

## Contact
<dorndorf@dreipunktnull.com>

# Configuration

Integrate the plugin in your root template.
Over the constant-editor you will be able to make some configurations.
- Set the pageIds which should be parsed for terms (1,n | 0 for all)
- PageId where plugin is avialable, for the the parsed terms
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

##RealURL Configuration

The configuration for realUrl is now integrated into the localconf.
If you want to use it set the detailpage in the extension settings.

But if you prefer to it manually or use more than one glossary plugins,
you can use the configuration in your realurl_conf.php.

- Add the id of your detailpage as the key and "dpn_glossary_RealUrlConfig" as it's value.
```PHP
'fixedPostVars' => array(
	'PAGEUID' => 'dpn_glossary_RealUrlConfig',
),
```
