<?php
namespace Featdd\DpnGlossary\Hook;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use DmitryDulepov\Realurl\Configuration\AutomaticConfigurator;

/**
 * @package DpnGlossary
 * @subpackage Hook
 */
class RealurlHook
{
    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param array $params Default configuration
     * @param \DmitryDulepov\Realurl\Configuration\AutomaticConfigurator $automaticConfigurator
     * @return array configuration
     */
    public function addConfig($params, AutomaticConfigurator $automaticConfigurator)
    {
        return array_merge_recursive(
            $params['config'],
            [
                'fixedPostVars' => [
                    'dpn_glossary_list_RealUrlConfig' => [
                        [
                            'GETvar' => 'tx_dpnglossary_glossarylist[controller]',
                            'noMatch' => 'bypass',
                        ],
                        [
                            'GETvar' => 'tx_dpnglossary_glossarylist[action]',
                            'noMatch' => 'bypass',
                        ],
                        [
                            'GETvar' => 'tx_dpnglossary_glossarylist[@widget_0][character]',
                        ],
                    ],
                    'dpn_glossary_detail_RealUrlConfig' => [
                        [
                            'GETvar' => 'tx_dpnglossary_glossarydetail[controller]',
                            'noMatch' => 'bypass',
                        ],
                        [
                            'GETvar' => 'tx_dpnglossary_glossarydetail[action]',
                            'noMatch' => 'bypass',
                        ],
                        [
                            'GETvar' => 'tx_dpnglossary_glossarydetail[term]',
                            'lookUpTable' => [
                                'table' => 'tx_dpnglossary_domain_model_term',
                                'id_field' => 'uid',
                                'alias_field' => 'name',
                                'addWhereClause' => ' AND NOT deleted',
                                'useUniqueCache' => 1,
                                'useUniqueCache_conf' => [
                                    'strtolower' => 1,
                                    'spaceCharacter' => '-',
                                ],
                                'languageGetVar' => 'L',
                                'languageExceptionUids' => '',
                                'languageField' => 'sys_language_uid',
                                'transOrigPointerField' => 'l10n_parent',
                                'autoUpdate' => 1,
                                'expireDays' => 180,
                            ],
                        ],
                        [
                            'GETvar' => 'tx_dpnglossary_glossarydetail[pageUid]',
                            'optional' => true,
                        ],
                    ],
                ],
            ]
        );
    }
}
