#Installation

Drop the dpn_glossary folder in your ext folder and enable the extension over the Typo3 extensionmanager

#Configuration

Integrate the plugin in your root template, over the constant-editor you are able then to make some configurations.
- You can turn on and off the CSS tooltips
- Set the pageIds which should be parsed for terms (1-n | 0 for all)
- PageId where the extensionplugin is aviable, for the detailpage
 - Otherwise if tooltips are turned on the link will be an anchor
- The StoragePids where the terms are stored

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
