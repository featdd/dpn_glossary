# dpn_glossary

A Typo3 extension for a glossary and a contentparser to link terms to a detailpage.

This extension is all namespaced and is developed and tested in Typo3 6.1.7

## Contact
<dorndorf@featdd.de>

#Configuration

Integrate the plugin in your root template, over the constant-editor you are able then to make some configurations.
- Set the pageIds which should be parsed for terms (1-n | 0 for all)
- PageId where the extensionplugin is aviable, for the detailpage
 - Otherwise if tooltips are turned on the link will be an anchor
- The StoragePids where the terms are stored
- Link configuration:
 - You have to set the aTagParams and Linktext in your Root Template
 - You can use TEXT and NAME as variables for the tooltiptext and the name of the term

##Example TypoScript Configuration
```TypoScript
plugin.tx_dpnglossary.settings.linkTextConf = <span>TEXT</span>NAME
plugin.tx_dpnglossary.settings.aTagParams = class="csstooltip"
```

###RealURL example
```PHP
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'] = array(
	'details' => array(
		array(
			'GETvar' => 'tx_dpnglossary_main[action]',
		),
		array(
			'GETvar' => 'tx_dpnglossary_main[controller]',
			'valueMap' => array(
				'term' => 'Term'
			)
		),
		array(
			'GETvar' => 'tx_dpnglossary_main[term]',
			'lookUpTable' => array(
				'table' => 'tx_dpnglossary_domain_model_term',
				'id_field' => 'uid',
				'alias_field' => 'name',
				'addWhereClause' => ' AND NOT deleted'
			)
		),
		array(
			'GETvar' => 'tx_dpnglossary_main[pageuid]'
		)
	)
);
```