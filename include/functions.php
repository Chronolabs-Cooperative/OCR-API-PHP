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
		
		if (!empty($_FILES[$field]))
        {
            $savedDestination = API_VAR_PATH . DS . md5(microtime(true)) . DS . $_FILES[$field]['name'];
            if (!is_dir(dirname($savedDestination)))
                mkdir(dirname($savedDestination), 0777, true);
            if (!move_uploaded_file($_FILES[$field]['tmp_name'], $savedDestination)) {
                trigger_error("Unable to upload file and save at: $savedDestination", E_ERROR);
                return false;
            }
			if (is_file($savedDestination))
			    $exec = API_MAGICK_CONVERT . ' "' . $savedDestination . '" "' . $savedDestination . '.pnm"';
				@shell_exec($exec);
		    if (is_file($savedDestination . '.pnm'))
			{
				switch($output)
				{
					case "raw":
						return shell_exec(API_GOCR_GOCR . ' -i "' . $savedDestination . '.pnm" -f UTF8');
						break;
					case "html":
						return shell_exec(API_GOCR_GOCR . ' -i "' . $savedDestination . '.pnm" -f HTML');
						break;
					default:
						$utf8 = shell_exec(API_GOCR_GOCR . ' -i "' . $savedDestination . '.pnm" -f UTF8');
						$ascii = shell_exec(API_GOCR_GOCR . ' -i "' . $savedDestination . '.pnm" -f ASCII');
						$ocrdata = shell_exec(API_GOCR_GOCR . ' -i "' . $savedDestination . '.pnm" -f XML');
						break;
				}
			}
			shell_exec('rm -Rfv "' . dirname($savedDestination));
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

function getImageFormats()
{
    exec(API_MAGICK_IDENTIFY . ' -list format', $results);
    unset($results[0]);
    unset($results[1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    $formats = array();
    foreach($results as $id => $value)
    {
        while(strpos(" $value", "\t") || strpos(" $value", "  ") || strpos(" $value", "*|") || strpos(" $value", "* "))
            $value = str_replace(array("\t", "  ","*|","* "),"|",$value);
            while(strpos(" $value", "||"))
                $value = str_replace("||","|",$value);
                while(strpos(" $value", "|"))
                    $value = str_replace("|"," ",$value);
                    $parts = explode(" ", $value);
                    $parts = array_unique($parts);
                    $extension = $title = '';
                    $skip = 0;
                    foreach($parts as $id => $value) {
                        if (!empty($value) && empty($extension)) {
                            $extension = strtolower($value);
                        } elseif (!empty($value) && !empty($extension) && $skip < 2)
                        {
                            $skip++;
                            if (substr($value, 0, 1) == 'r' || substr($value, 0, 1) == 'w')
                                $skip = 3;
                        } elseif(!empty($value) && $skip >= 2)
                        {
                            $title .= " $value";
                        }
                    }
                    if (!empty($extension) && !empty($title))
                        $formats[strtolower($extension)] = trim($title);
    }
    unset($formats['json']);
    unset($formats['thumbnail']);
    unset($formats['htm']);
    unset($formats['html']);
    unset($formats['http']);
    unset($formats['https']);
    unset($formats['ftp']);
    unset($formats['ftps']);
    unset($formats['specified']);
    unset($formats['canvas']);
    unset($formats['caption']);
    unset($formats['(dicom)",']);
    unset($formats['see']);
    unset($formats['and']);
    unset($formats['they']);
    unset($formats['resized']);
    unset($formats['gradient']);
    unset($formats['histogram']);
    unset($formats['info']);
    unset($formats['label']);
    unset($formats['null']);
    unset($formats['preview']);
    unset($formats['w']);
    unset($formats['+']);
    return $formats;
}

