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

    
    /**
     * URI Path Finding of API URL Source Locality
     * @var unknown_type
     */
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
        
    /**
     * URI Path Finding of API URL Source Locality
     * @var unknown_type
     */
    $odds = $inner = array();
    foreach($_GET as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }
    
    foreach($_POST as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }
    
    foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }

	$help=false;
	if (!isset($inner['field']) || empty($inner['field'])) {
		$help=true;
	} elseif (isset($inner['output']) || !empty($inner['output'])) {
	    $field = trim($inner['field']);
	    $output = trim($inner['output']);
	} else {
		$help=true;
	}
	
	if ($help==true) {
		if (function_exists('http_response_code'))
			http_response_code(400);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	/**
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
	*/
			
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
		    header('Content-type: application/x-httpd-php');
		    die("<"."?"."php\n\n\treturn " . var_export($data, true) . ";\n\n?".">");
		    break;
		case 'json':
		    header('Content-type: application/json');
		    die(json_encode($data));
		    break;
		case 'serial':
		    header('Content-type: text/text');
		    die(serialize($data));
		    break;
		case 'xml':
		    header('Content-type: application/xml');
		    $dom = new XmlDomConstruct('1.0', 'utf-8');
		    $dom->fromMixed(array('root'=>$data));
		    die($dom->saveXML());
		    break;
	}
	exit(0);
?>
