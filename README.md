# dpn_glossary

A TYPO3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is tested in TYPO3 6.2.X & 7.6.X

## Contact
<dorndorf@dreipunktnull.com>

# Configuration

- Integrate the plugin in your root template.
- Over the constant-editor you will be able to make some configurations.
- You must set the listPage and detailPage to have the list- and detailplugin working.
 - If you just want some wrapped terms and no list- & detailpage you can keep it empty.
- There are also example styles and scripts for the views and a tiny CSS3 Tooltip
 - CSS: EXT:dpn_glossary/Resources/Public/css/styles.min.css
 - JS:  EXT:dpn_glossary/Resources/Public/js/scripts.min.js

## Settings

- (storagePid) Pids of storages containing terms
- (listPage) PageId of the listpage plugin
- (detailPage) PageId of the detailpage plugin (parser will link to this)
 - Otherwise if tooltips are turned on the link will be an anchor
- (parsingPids) Set the pageIds which should be parsed for terms (0 for all)
- (parsingPidsExcludePidList) Set the pageIds which should "not" be parsed for terms (0 for none)
- (maxReplacementPerPage) Configure the max replacement for each terms
- (parsingTags) The tags whish should be parsed for terms
- (forbiddenParentTags) The tags which are not allowed as a parent for a parsingTag
- (disableParser) Disables the term parser
- (listmode) Sets the listmode for the plugin
 - Normal: lists all terms in alphabetical order
 - Character: lists all terms grouped by their beginning characters
 - Pagination: lists terms by characters with a pagination
  - You can override the characters in TypoScript used in the pagination (see example).
    - Hint: if you want to add umlauts to the pagination you have to check the terms table collation.
      - Normal utf8 will not differ between Ä and A, you have to use "utf8_german2_ci" which would make a difference
      - You could change the 'name' column collation and add Ä,Ö,Ü to the comma list over typoscript (see example)
      - See [MySQL reference](http://dev.mysql.com/doc/refman/5.7/en/charset-collation-effect.html) for more info

- Link configuration:
 - The generated Link is whole configurable over TypoScript (see example).

##Example TypoScript Configuration
```TypoScript
plugin.tx_dpnglossary {
    settings {
		pagination {
			characters = A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z
			insertAbove = 1
			insertBelow = 0
		}

		termWraps = CASE
		termWraps {
			key.field = term_type
			default = TEXT
			default {
				field = name
				dataWrap = |
				typolink {
					ATagParams.dataWrap = title="{field:tooltiptext}" class="dpnglossary link"
					ATagParams.dataWrap {
						override = title="{field:name}" class="dpnglossary link"
						override.if.isFalse.data = field:tooltiptext
					}
					useCacheHash = 1
				}
			}
			abbreviation {
				dataWrap = <abbr title="{field:tooltiptext}" lang="{field:term_lang}">|</abbr>
				dataWrap {
					override = <abbr title="{field:name}" lang="{field:term_lang}">|</abbr>
					override.if.isFalse.data = field:tooltiptext
				}
			}
			acronym {
				dataWrap = <acronym title="{field:tooltiptext}" lang="{field:term_lang}">|</acronym>
				dataWrap {
					override = <acronym title="{field:name}" lang="{field:term_lang}">|</acronym>
					override.if.isFalse.data = field:tooltiptext
				}
			}
			definition {
				dataWrap = <dfn title="{field:tooltiptext}" lang="{field:term_lang}">|</dfn>
				dataWrap {
					override = <dfn title="{field:name}" lang="{field:term_lang}">|</dfn>
					override.if.isFalse.data = field:tooltiptext
				}
			}
		}
	}
}
```

##RealURL Configuration

The configuration for realUrl is now integrated into the localconf.
If you want to use it set the list & detailpage in your RealUrl configuration.
- Add the id of your list & detailpage as the key (see example below).
```PHP
'fixedPostVars' => array(
	'LISTPAGEUID' => 'dpn_glossary_list_RealUrlConfig',
	'DETAILPAGEUID' => 'dpn_glossary_detail_RealUrlConfig',
),
```
