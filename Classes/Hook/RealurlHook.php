<?php
namespace Featdd\DpnGlossary\Hook;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Julian Hofmann <julian.hofmann@webenergy.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RealurlHook
{
    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param array $params Default configuration
     * @param \DmitryDulepov\Realurl\Configuration\AutomaticConfigurator $pObj Parent object
     * @return array configuration
     */
    public function addConfig($params, &$pObj)
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
