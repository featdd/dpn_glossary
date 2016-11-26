.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _configuration:

Konfigurationsreferenz
======================

Die gesamte Konfiguration der Verlinkung von Begriffen kann per TypoScript gesteuert werden.
Das Parsing selbst lässt sich ebenfalls so präzise wie gewünscht anpassen.

Es sind außerdem beispiel Styles und JavaScript für Listen- & Detailansicht, sowie CSS3 Tooltips

+ CSS: EXT:dpn_glossary/Resources/Public/css/styles.min.css
+ JS:  EXT:dpn_glossary/Resources/Public/js/scripts.min.js

Spezial: RealUrl
^^^^^^^^^^^^^^^^

Die Konfiguration für RealUrl ist in die Extension integriert.
Wenn Sie sie benutzen wollen tragen sie die ID der Listen- & Detailseite in Ihre RealUrl Konfiguration ein

+ Ersetzen Sie einfach die Platzhalter mit den IDs (see example below)

::

    'fixedPostVars' => array(
	    'LISTPAGEUID' => 'dpn_glossary_list_RealUrlConfig',
	    'DETAILPAGEUID' => 'dpn_glossary_detail_RealUrlConfig',
    ),

Spezial: Umlaute
^^^^^^^^^^^^^^^^

Wenn Sie Umlaute in der Paginierung verwenden wollen überprüfen Sie das Format der Begriffs-Tabelle in der Datenbank

+ Normales utf8 kann nicht zwischen Ä und A unterscheiden, Sie müssen dafür "utf8_german2_ci" verwenden.
+ Sie können einfach das Format der Spalte "name" anpassen und müssen nur Ä,Ö,Ü zur Komma separierten Liste im TypoScript hinzufügen
+ Schauen Sie sich `MySQL reference <http://dev.mysql.com/doc/refman/5.7/de/charset-collation-effect.html>`_ an für mehr Informationen

Extension Settings
------------------

.. toctree::
    :maxdepth: 1
    :titlesonly:
    :glob:

    ExtensionSettings/Index
    ExampleTypoScriptSetup/Index
