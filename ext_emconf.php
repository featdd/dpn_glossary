<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'dreipunktnull Glossar',
    'description' => 'Glossary extension with page parser',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@featdd.de',
    'state' => 'stable',
    'version' => '5.2.2',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
            'php' => '8.0.0-8.3.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
