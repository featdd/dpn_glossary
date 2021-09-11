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
    'version' => '3.2.4',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.2-11.4.99',
            'php' => '7.2.0-7.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
