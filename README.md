# dpn_glossary

A TYPO3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is developed and tested in TYPO3 6.2.X

## Contact
<dorndorf@dreipunktnull.com>

# Configuration

Integrate the plugin in your root template.
Over the constant-editor you will be able to make some configurations.
- (storagePid) Pids of storages containing terms
- (detailsPid) PageId where plugin is avialable, for the the parsed terms
 - Otherwise if tooltips are turned on the link will be an anchor
- (parsingPids) Set the pageIds which should be parsed for terms (0 for all)
- (parsingPidsExcludePidList) Set the pageIds which should "not" be parsed for terms (0 for none)
- (maxReplacementPerPage) Configure the max replacement for each terms
- (parsingTags) The tags whish should be parsed for terms
- (forbiddenParentTags) The tags which are not allowed as a parent for a parsingTag
- (listmode) Sets the listmode for the plugin, you may want to use a character ordered list

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
