<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'dreipunktnull Glossar',
    'description' => 'Glossary extension with page parser',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@featdd.de',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => true,
    'version' => '3.0.3',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.1-9.5.99',
            'php' => '7.2.0-7.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
