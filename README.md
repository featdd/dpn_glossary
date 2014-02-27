# dpn_glossary

A TYPO3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is developed and tested in TYPO3 6.1.X

## Contact
<dorndorf@dreipunktnull.com>

# Configuration

Integrate the plugin in your root template.
Over the constant-editor you will be able to make some configurations.
- Set the pageIds which should be parsed for terms (1,n | 0 for all)
- PageId where the extensionplugin is aviable, for the detailpage
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
            abbrevation {
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