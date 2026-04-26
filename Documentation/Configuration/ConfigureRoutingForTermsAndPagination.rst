.. _configuration-routing:

==========================================
Configure Routing for terms and pagination
==========================================

Site set routing
----------------

The site set :yaml:`featdd/dpn-glossary` ships a default route enhancer
configuration. If the set is included in your site, you usually do not need to
copy the full route enhancer manually.

Restrict the route enhancer to the page that contains the glossary plugin in
your site configuration:

.. code-block:: yaml
   :caption: config/sites/<your-site>/config.yaml

   routeEnhancers:
     dpnGlossary:
       limitToPages: [YOUR_PLUGINPAGE_UID]

Manual routing configuration
----------------------------

If you do not use the site set, or if you need a fully customized route
enhancer, use this configuration as a starting point.

The :yaml:`special: [ Ä,Ö,Ü ]` part for the pagination is only needed if you
want to use umlauts or other special characters.

.. code-block:: yaml

   routeEnhancers:
     dpnGlossary:
       type: Extbase
       limitToPages: [YOUR_PLUGINPAGE_UID]
       extension: DpnGlossary
       plugin: glossary
       routes:
         - routePath: '/{character}'
           _controller: 'Term::list'
           _arguments:
             character: currentCharacter
         - routePath: '/{localized_term}/{term_name}'
           _controller: 'Term::show'
           _arguments:
             term_name: term
       defaultController: 'Term::list'
       defaults:
         character: ''
       aspects:
         term_name:
           type: PersistedAliasMapper
           tableName: 'tx_dpnglossary_domain_model_term'
           routeFieldName: 'url_segment'
         character:
           type: StaticMultiRangeMapper
           ranges:
             - start: 'A'
               end: 'Z'
               special: [ Ä,Ö,Ü ]
         localized_term:
           type: LocaleModifier
           default: 'term'
           localeMap:
             - locale: 'de_DE.*'
               value: 'begriff'
