<?php
/**
 * Chronolabs REST API File
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         whois
 * @since           1.1.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: help.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Whois API Service REST
 * @link			http://whois.labs.coop WhoIS API Service Operates from this URL
 * @filesource
 */

	error_reporting(E_ERROR);
	define('MAXIMUM_QUERIES', 15);
	
	define('OCR_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'ocr');
	if (!is_dir(OCR_PATH))
		mkdir(OCR_PATH, 0777, true);
	
	ini_set('memory_limit', '256M');
	include dirname(__FILE__).'/wideimage/WideImage.php';
	include dirname(__FILE__).'/functions.php';

	$help=false;
	if (!isset($_GET['field']) || empty($_GET['field'])) {
		$help=true;
	} elseif (isset($_GET['output']) || !empty($_GET['output'])) {
		$field = trim($_GET['field']);
		$output = trim($_GET['output']);
	} else {
		$help=true;
	}
	
	if ($help==true) {
		if (function_exists('http_response_code'))
			http_response_code(400);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	session_start();
	if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) {
		if (isset($_SESSION['reset']) && $_SESSION['reset']<microtime(true))
			$_SESSION['hits'] = 0;
		if ($_SESSION['hits']<=MAXIMUM_QUERIES) {
			if (!isset($_SESSION['hits']) || $_SESSION['hits'] = 0)
				$_SESSION['reset'] = microtime(true) + 3600;
			$_SESSION['hits']++;
		} else {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	
	if (function_exists('http_response_code'))
		http_response_code(200);
	
	$data = ocrFieldImages($field, $output);
	switch ($output) {
		default:
			echo '<h1>'.$field.' field data</h1>';
			echo '<pre style="font-family: \'Courier New\', Courier, Terminal; font-size: 0.77em;">';
			echo var_dump($data);
			echo '</pre>';
			break;
		case 'raw':
			echo '{ '. implode ("} | {", $data) . ' }';
			break;
		case 'json':
			header('Content-type: application/json');
			echo json_encode($data);
			break;
		case 'serial':
			header('Content-type: text/html');
			echo serialize($data);
			break;
		case 'xml':
			header('Content-type: application/xml');
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			echo $dom->saveXML();
			break;
	}
	exit(0);
?>
