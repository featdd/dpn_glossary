==========================================
Configure Routing for terms and pagination
==========================================

This is a working example routing configuration. |
The "special: [ Ä,Ö,Ü ]" part for the pagination is only needed if you want to use umlauts or other special characters.

::

  DpnGlossary:
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
