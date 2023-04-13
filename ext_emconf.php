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
    'version' => '4.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.2-11.5.99',
            'php' => '7.2.0-8.0.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
