.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _realurl:

RealURL Example
===============

.. code-block:: php
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT'] = array(
    	'fixedPostVars' => array(
    			'dpn_glossary' => array(
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
    					'GETvar' => 'tx_dpnglossary_glossary[controller]',
    					'noMatch' => 'bypass'
    				),
    				array(
    					'GETvar' => 'tx_dpnglossary_glossary[action]',
    					'noMatch' => 'bypass'
    				),
    				array(
    					'GETvar' => 'tx_dpnglossary_glossary[pageUid]'
    				)
    			),
    			'DETAILPAGE_ID' => 'dpn_glossary',
    		),
    	),
    );