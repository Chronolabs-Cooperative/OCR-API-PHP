<?php
/**
 * Chronolabs REST Whois API
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
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Whois API Service REST
 * @filesource
 */


if (!function_exists("whitelistGetIP")) {

	/** function whitelistGetIPAddy()
	 * 
	 * 	provides an associative array of whitelisted IP Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @return 		array
	 */
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/** function whitelistGetNetBIOSIP()
	 *
	 * 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		array
	 */
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		} 
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/** function whitelistGetIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param boolean $asString	Whether to return an address or network long integer
	 * 
	 * @return 		mixed
	 */
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 * 
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {
	
		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 * 
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {
	
			$domElement = is_null($domElement) ? $this : $domElement;
	
			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {
	
					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}
					 
					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}
					 
					$this->fromMixed($mixedElement, $node);
					 
				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}
			 
		}
		 
	}
}

function obj2array($obj, $ret = array()) {
	foreach($obj as $id => $value) {
		if (is_object($value)||is_array($value))
			$ret[$id] = obj2array((array)$value, $ret[$id]);
		else 
			$ret[$id] = $value;
	}
	return $ret;
}

function ordergroup($ret = array())
{
	foreach($ret['group'] as $id => $group) {
		foreach($group as $type => $values) {
			if ($type != "@attributes")
				foreach($values as $key => $value) {
					$ret[$group['@attributes']['id']][$type][$value['@attributes']['name']] = $value['@attributes']['value'];
				}
		}
	}
	$ret['key'] = md5(implode('|', $ret['@attributes']));
	unset ($ret['group']);
	unset ($ret['@attributes']);
	return $ret;
}


function ocrFieldImages($field, $output = 'json') 
{
	$ret = array($field=>array());
	try {
		$image = WideImage::loadFromUpload($field);
		if (is_object($image))
		{
			$image->saveToFile(OCR_PATH . DIRECTORY_SEPARATOR . ($file=sha1(microtime(true).md5($image))) . '.jpeg');
			if (is_file(OCR_PATH . DIRECTORY_SEPARATOR . $file . '.jpeg'))
				$exec = __DIR__ . '/jpegtopnm "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.jpeg" > "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm"';
				@shell_exec($exec);
			if (is_file(OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm'))
			{
				switch($output)
				{
					case "raw":
						return shell_exec(__DIR__ . '/gocr -i "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm" -f UTF8');
						break;
					case "html":
						return shell_exec(__DIR__ . '/gocr -i "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm" -f HTML');
						break;
					default:
						$utf8 = shell_exec(__DIR__ . '/gocr -i "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm" -f UTF8');
						$ascii = shell_exec(__DIR__ . '/gocr -i "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm" -f ASCII');
						$ocrdata = shell_exec(__DIR__ . '/gocr -i "'.OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm" -f XML');
						break;
				}
			}
			unlink(OCR_PATH . DIRECTORY_SEPARATOR . $file . '.jpeg');
			unlink(OCR_PATH . DIRECTORY_SEPARATOR . $file . '.pnm');
			$ret[$field] = obj2array(simplexml_load_string($ocrdata));
			if (isset($utf8) && !empty($utf8))
				$ret[$field]['utf8'] = $utf8;
			if (isset($ascii) && !empty($ascii))
				$ret[$field]['ascii'] = $ascii;
		}
	}
	catch (Exception $e)
	{
		trigger_error($e, E_CORE_ERROR);
	}
	return $ret;
}
?>
