<?php
/**
 * @package     mod_evangelizo
 * @version     1.1
 * @author      Nicolas Fischmeister - Agence web Cibles
 * @link        http://www.cibles.fr
 * @copyright   Copyright (C) 2018 Agence web Cibles. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$date = JHtml::_('date', '', 'Y-m-d');
$langue = $params->get('langue');
$document = JFactory::getDocument();
$document->addStyleDeclaration('
.evangelizo .modal { max-width:800px; }
.evangelizo .modal-body { max-height:inherit; }
.evangelizo .modal-body > div { padding:0.5em 1em 1em; }
.evangelizo .modal-body > div img { float:left; margin:0 1em 0.5em 0; max-width:30%; }
');
$file = realpath(dirname(__FILE__)).'/mod_evangelizo_'.strtolower($params->get('langue', 'AM')).'.json';
if(file_exists($file)) chmod($file, 0666);
$json = file_get_contents($file);
if(!isset($json) OR empty($json))
	$get = 1;
else
{
	$evangelizo = json_decode($json);
	if($evangelizo->date != $date OR $evangelizo->langue != $langue)
		$get = 1;
}
if(isset($get) AND $get == 1)
{
	$json = file_get_contents('https://publication.evangelizo.ws/'.$params->get('langue', 'AM').'/days/'.$date);
	$evangelizo = json_decode($json);
	$evangelizo = $evangelizo->data;
	foreach($evangelizo->saints AS $i => $saint)
	{
		$bio = $saint->bio;
		$bio = preg_replace('#(font-size\s*?:.*?(;|(?=""|\'|;)))#', '', $bio);
		$bio = preg_replace('#(color\s*?:.*?(;|(?=""|\'|;)))#', '', $bio);
		$bio = preg_replace('#(line-height\s*?:.*?(;|(?=""|\'|;)))#', '', $bio);
		$bio = preg_replace('#<p[^>]*>[[:space:]|&nbsp;]*</p>#', '', $bio);
		$evangelizo->saints[$i]->bio = $bio;
	}
	$evangelizo->langue = $langue;
	$json = json_encode($evangelizo);
	$write = file_put_contents($file, $json);
	chmod($file, 0666);
}
$fete = $evangelizo->liturgic_title;
$saints = $evangelizo->saints;
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
require JModuleHelper::getLayoutPath('mod_evangelizo', $params->get('layout', 'default'));
