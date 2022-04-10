CREATE TABLE tx_dpnglossary_domain_model_term (

  uid                  int(11)                          NOT NULL auto_increment,
  pid                  int(11)             DEFAULT '0'  NOT NULL,

  name                 varchar(255)        DEFAULT ''   NOT NULL,
  url_segment          varchar(255)        DEFAULT ''   NOT NULL,
  tooltiptext          varchar(255)        DEFAULT ''   NOT NULL,
  term_type            varchar(255)        DEFAULT ''   NOT NULL,
  term_lang            char(2)             DEFAULT ''   NOT NULL,
  term_mode            varchar(255)        DEFAULT ''   NOT NULL,
  term_link            varchar(255)        DEFAULT ''   NOT NULL,
  seo_title            varchar(255)        DEFAULT ''   NOT NULL,
  meta_description     text                             NOT NULL,
  exclude_from_parsing tinyint(4) unsigned DEFAULT '0'  NOT NULL,
  case_sensitive       tinyint(4) unsigned DEFAULT '0'  NOT NULL,
  max_replacements     int(11)             DEFAULT '-1' NOT NULL,
  descriptions         int(11) unsigned    DEFAULT '0',
  synonyms             int(11) unsigned    DEFAULT '0',
  media                int(11) unsigned    DEFAULT '0',

  tstamp               int(11) unsigned    DEFAULT '0'  NOT NULL,
  crdate               int(11) unsigned    DEFAULT '0'  NOT NULL,
  cruser_id            int(11) unsigned    DEFAULT '0'  NOT NULL,
  deleted              tinyint(4) unsigned DEFAULT '0'  NOT NULL,
  hidden               tinyint(4) unsigned DEFAULT '0'  NOT NULL,
  starttime            int(11) unsigned    DEFAULT '0'  NOT NULL,
  endtime              int(11) unsigned    DEFAULT '0'  NOT NULL,

  t3ver_oid            int(11)             DEFAULT '0'  NOT NULL,
  t3ver_id             int(11)             DEFAULT '0'  NOT NULL,
  t3ver_wsid           int(11)             DEFAULT '0'  NOT NULL,
  t3ver_label          varchar(255)        DEFAULT ''   NOT NULL,
  t3ver_state          tinyint(4)          DEFAULT '0'  NOT NULL,
  t3ver_stage          int(11)             DEFAULT '0'  NOT NULL,
  t3ver_count          int(11)             DEFAULT '0'  NOT NULL,
  t3ver_tstamp         int(11)             DEFAULT '0'  NOT NULL,
  t3ver_move_id        int(11)             DEFAULT '0'  NOT NULL,

  t3_origuid           int(11)             DEFAULT '0'  NOT NULL,
  sys_language_uid     int(11)             DEFAULT '0'  NOT NULL,
  l10n_source          int(11)             DEFAULT '0'  NOT NULL,
  l10n_parent          int(11)             DEFAULT '0'  NOT NULL,
  l10n_diffsource      mediumblob,

  PRIMARY KEY (uid),
  KEY parent(pid),
  KEY t3ver_oid(t3ver_oid, t3ver_wsid),
  KEY language(l10n_parent, sys_language_uid)

);

CREATE TABLE tx_dpnglossary_domain_model_synonym (

  uid              int(11)                         NOT NULL auto_increment,
  pid              int(11)             DEFAULT '0' NOT NULL,

  sorting          int(11) unsigned    DEFAULT '0' NOT NULL,

  term             int(11)             DEFAULT '0' NOT NULL,
  name             varchar(255)        DEFAULT ''  NOT NULL,

  tstamp           int(11) unsigned    DEFAULT '0' NOT NULL,
  crdate           int(11) unsigned    DEFAULT '0' NOT NULL,
  cruser_id        int(11) unsigned    DEFAULT '0' NOT NULL,
  deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
  starttime        int(11) unsigned    DEFAULT '0' NOT NULL,
  endtime          int(11) unsigned    DEFAULT '0' NOT NULL,

  t3ver_oid        int(11)             DEFAULT '0' NOT NULL,
  t3ver_id         int(11)             DEFAULT '0' NOT NULL,
  t3ver_wsid       int(11)             DEFAULT '0' NOT NULL,
  t3ver_label      varchar(255)        DEFAULT ''  NOT NULL,
  t3ver_state      tinyint(4)          DEFAULT '0' NOT NULL,
  t3ver_stage      int(11)             DEFAULT '0' NOT NULL,
  t3ver_count      int(11)             DEFAULT '0' NOT NULL,
  t3ver_tstamp     int(11)             DEFAULT '0' NOT NULL,
  t3ver_move_id    int(11)             DEFAULT '0' NOT NULL,

  t3_origuid       int(11)             DEFAULT '0' NOT NULL,
  sys_language_uid int(11)             DEFAULT '0' NOT NULL,
  l10n_source      int(11)             DEFAULT '0' NOT NULL,
  l10n_parent      int(11)             DEFAULT '0' NOT NULL,
  l10n_diffsource  mediumblob,

  PRIMARY KEY (uid),
  KEY parent(pid),
  KEY t3ver_oid(t3ver_oid, t3ver_wsid),
  KEY language(l10n_parent, sys_language_uid)

);

CREATE TABLE tx_dpnglossary_domain_model_description (

  uid              int(11)                         NOT NULL auto_increment,
  pid              int(11)             DEFAULT '0' NOT NULL,

  sorting          int(11) unsigned    DEFAULT '0' NOT NULL,

  term             int(11) unsigned    DEFAULT '0' NOT NULL,
  meaning          varchar(255)        DEFAULT ''  NOT NULL,
  text             text                            NOT NULL,

  tstamp           int(11) unsigned    DEFAULT '0' NOT NULL,
  crdate           int(11) unsigned    DEFAULT '0' NOT NULL,
  cruser_id        int(11) unsigned    DEFAULT '0' NOT NULL,
  deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
  starttime        int(11) unsigned    DEFAULT '0' NOT NULL,
  endtime          int(11) unsigned    DEFAULT '0' NOT NULL,

  t3ver_oid        int(11)             DEFAULT '0' NOT NULL,
  t3ver_id         int(11)             DEFAULT '0' NOT NULL,
  t3ver_wsid       int(11)             DEFAULT '0' NOT NULL,
  t3ver_label      varchar(255)        DEFAULT ''  NOT NULL,
  t3ver_state      tinyint(4)          DEFAULT '0' NOT NULL,
  t3ver_stage      int(11)             DEFAULT '0' NOT NULL,
  t3ver_count      int(11)             DEFAULT '0' NOT NULL,
  t3ver_tstamp     int(11)             DEFAULT '0' NOT NULL,
  t3ver_move_id    int(11)             DEFAULT '0' NOT NULL,

  t3_origuid       int(11)             DEFAULT '0' NOT NULL,
  sys_language_uid int(11)             DEFAULT '0' NOT NULL,
  l10n_source      int(11)             DEFAULT '0' NOT NULL,
  l10n_parent      int(11)             DEFAULT '0' NOT NULL,
  l10n_diffsource  mediumblob,

  PRIMARY KEY (uid),
  KEY parent(pid),
  KEY t3ver_oid(t3ver_oid, t3ver_wsid),
  KEY language(l10n_parent, sys_language_uid)

);
