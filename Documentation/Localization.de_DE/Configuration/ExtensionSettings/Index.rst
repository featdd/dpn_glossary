.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

Referenz
^^^^^^^^

Alle Konstanten zugehörig zu plugin.tx_dpnglossary.settings:

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Constant
        settings.detailpage

   Data Type
         integer

   Description
         Seiten ID der Detailseite (Parser verlinkt Begriffe auf diese)

   Default

.. container:: table-row

   Constant
        settings.listPage

   Data Type
         integer

   Description
         Seiten ID der Listenseite

   Default

.. container:: table-row

   Constant
        settings.addCanonicalUrl

   Data Type
        boolean

   Description
        Fügt eine kanonische URL zur Detailseite hinzu

   Default
        1

.. container:: table-row

   Constant
        settings.parsingPids

   Data Type
        string

   Description
        Komma separierte Liste von Seiten die geparsed werden sollen (0 für alle)

   Default
        0

.. container:: table-row

   Constant
        settings.parsingExcludePidList

   Data Type
        string

   Description
        Komma separierte Liste von seiten die nicht geparsed werden sollen

   Default

.. container:: table-row

   Constant
        settings.maxReplacementPerPage

   Data Type
        integer

   Description
        Maximale Ersetzungen für Begriffe (-1 = alle)

   Default
        -1

.. container:: table-row

   Constant
        settings.maxReplacementPerPageRespectSynonyms

   Data Type
        boolean

   Description
        Respektiere Ersetzungszähler beim parsen von Synonymen

   Default
        0

.. container:: table-row

   Constant
        settings.parsingTags

   Data Type
        string

   Description
        Komma separierte Liste von Tags deren Inhalt geparsed werden soll

   Default
        p

.. container:: table-row

   Constant
        settings.forbiddenParentTags

   Data Type
        string

   Description
        Komma separierte Liste von Tags die nicht als eltern von parsing Tags erlaubt sind

   Default
        a,script

.. container:: table-row

   Constant
        settings.forbiddenParsingTagClasses

   Data Type
        string

   Description
        Komma separierte Liste von Klassen die nicht für parsing Tags erlaubt sind

   Default

.. container:: table-row

   Constant
        settings.listmode

   Data Type
        options

   Description
        Listenmodus für die Listenseite (normal, character, pagination)

   Default
        normal

.. container:: table-row

   Constant
        settings.previewmode

   Data Type
        options

   Description
        Vorschaumodus für das Vorschauplugin (neueste oder zufall)

   Default
        newest

.. container:: table-row

   Constant
        settings.previewlimit

   Data Type
        integer

   Description
        Begrenzung für die Vorschauliste

   Default
        5

.. container:: table-row

   Constant
        settings.disableParser

   Data Type
        boolean

   Description
        Deaktivieren des Parsers

   Default
        0

.. container:: table-row

   Constant
        settings.parseSynonyms

   Data Type
        boolean

   Description
        Aktiviert das parsen der Begriffe von Synonymen

   Default
        1

.. ###### END~OF~TABLE ######
