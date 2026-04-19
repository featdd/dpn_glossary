<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'dreipunktnull Glossar',
    'description' => 'Glossary extension with page parser',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@featdd.de',
    'state' => 'stable',
    'version' => '7.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
            'php' => '8.2.0-8.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
