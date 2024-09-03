<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$dca = &$GLOBALS['TL_DCA']['tl_page'];

PaletteManipulator::create()
    ->addLegend('csp_legend', 'website_legend')
    ->addField('enableCsp', 'csp_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', 'tl_page')
    ->applyToPalette('rootfallback', 'tl_page');


$dca['palettes']['__selector__'][] = 'enableCsp';
$dca['subpalettes']['enableCsp'] = 'csp,cspReportOnly,cspReportLog';

$dca['fields']['enableCsp'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => ['type' => 'boolean', 'default' => false],
];
$dca['fields']['csp'] = [
    'inputType' => 'textarea',
    'default' => "default-src 'self'",
    'eval' => ['mandatory' => true, 'decodeEntities' => true],
    'sql' => ['type' => 'text', 'notnull' => false],
];
$dca['fields']['cspReportOnly'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => false],
];
$dca['fields']['cspReportLog'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => false],
];