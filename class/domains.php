<?php
/**
 * Chronolabs IP Lookup's REST API File
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		netbios
 * @description		Internet Protocol Address Information API Service REST
 */

if (!class_exists("Domains")) {

	/**
	 * TLD/gTLD Domain Validation
	 *
	 * @author     Simon Roberts <meshy@labs.coop>
	 * @package    lookups
	 * @subpackage netbios
	 */
	class Domains
	{      
	        // path to valid TLD files
	        // This file can be downloaded from http://data.iana.org/TLD/tlds-alpha-by-domain.txt
	        private $tld_list = 'tlds-alpha-by-domain.txt';
	       
	        // url to the tld list
	        private $tld_url = 'http://data.iana.org/TLD/tlds-alpha-by-domain.txt';
	
	        // the timezone for this server
	        private $timezone = 'America/Denver';
	       
	        // messaging
	        private $invalid = 'The domain name is not RFC compliant.';
	       
	        // ip url to get this servers public IP
	        private $ip_url = 'http://www.whatismyip.com/automation/n09230945.asp';
	       
	        // set an empty array for populating dns server information on validation
	        public $servers = array();
	       
	        // set an empty place holder for the valid domain
	        public $valid_domain;
	       
	        // set an empty place holder for this server fqdn (or IP address)
	        public $this_server;
	       
	        // place holder for status of domains
	        public $status = array();
	       
	        /**
	         * function _tld_file_status_check()
	         * 
	         * @summary		Check the Current Freshness of the TLD list to see if we need to download another copy
	         * @summary		Here is the email received from iana about pulling the file from their server.
	         * 
	         * @author		Simon Roberts (Chronolabs) simon@labs.coop
	         * 
	         */
	        function _tld_file_status_check()
	        {                                          
	                // this is required for some versions of PHP
	                date_default_timezone_set($this->timezone);
	               
	                // set the length of time (default is 30)
	                $days = 60 * 60 * 24 * 30;
	               
	                // does the file does not exist or does not fall within freshness threshold
	                if(!file_exists($this->tld_list) or (time() - filemtime($this->tld_list)) > $days)
	                {
	                        // if we can pull the  file
	                        if($tld_file = file_get_contents($this->tld_url))
	                        {
	                                // save the file over the top of the existing tld file
	                                file_put_contents($this->tld_list, $tld_file);
	                        }
	                }
	        }
	
	       
	        /**
	         * function _checkDomain()
	         * 
	         * @summary		ValidateDomain
	         *
	         * @summary		Basic rules of the domain validation
	         * @summary		√ - must be at least one character long
	         * @summary		√ - must start with a letter or number
	         * @summary		√ - contains letters, numbers, and hyphens
	         * @summary		√ - must end in a letter or number
	         * @summary		√ - may contain multiple nodes (i.e. node1.node2.node3)
	         * @summary		√ - each node can only be 63 characters long max
	         * @summary		√ - total domain name can only be 255 characters long max
	         * @summary		√ - must end in a valid TLD
	         * @summary		√ - can be an IP4 address
	         * 
	         * @author		Simon Roberts (Chronolabs) simon@labs.coop
	         * 
	         * return		boolean
	         *
	         */    
	        function _checkDomain($domain)
	        {
	                // domain name must exist
	                if(strlen($domain) > 256 or strlen($domain) < 4)
	                {
	                        $this->status = array(false, 'INVALID', $this->invalid, 'The domain cannot be bigger than 256 or less than 4');
	                        return false;                                                                                  
	                }
	               
	                // check to see if this might be an IP address
	                if(ip2long($domain))
	                {
	                        return $this->validateIP($domain);              // validate the IP
	                       
	                } else {
	               
	                        // split on each . to get the nodes
	                        $nodes = explode('.', $domain);
	                       
	                        // process each node
	                        foreach($nodes as $node)
	                        {
	                                // each node is limited to 63 characters
	                                if(strlen($node) > 63)
	                                {
	                                        $this->status = array(false, 'INVALID', $this->invalid, 'Each node in the domain can only be 63 characters long');
	                                        return false;                                                                                  
	                                }
	                               
	                                // each node is limited to specific characters and structure
	                                if(!preg_match('/^([a-z0-9]([-a-z0-9]*[a-z0-9])?)$/i', $node))
	                                {
	                                        $this->status = array(false, 'INVALID', $this->invalid, 'The domain name contains illegal charaters or is formatted incorrectly.');
	                                        return false;                                                                                  
	                                }
	                        }
	                       
	                        // build regex list of valid TLDs
	                        $tld = $this->_validTLD();      
	                       
	                        // make sure the domain name has a valid TLD
	                        if(!preg_match('/^('.$tld.')$/i', $node, $match))
	                        {
	                                $this->status = array(false, 'INVALID', $this->invalid, 'The domain does not have a valid TLD.');
	                                return false;                                                                                  
	                        }
	               
	                        // made it this far, it must be valid
	                        $this->valid_domain = $domain;
	                        return true;
	                }
	        }
	       
	       
	        /**
	         * function _validTLD()
	         * 
	         * @summary		Valid TLD
	         *
	         * @summary		Builds a list of valid TLDs to check domain against. The file is pulled from http://data.iana.org/TLD/tlds-alpha-by-domain.txt
	         * @summary		In order for the TLD list to stay up to date, you must do one of the following:
	         * @summary		- download the file and place it in location this class reads from
	         * @summary		- change the $tld_list file location the http address (this can return 404 when iana is building the list and other reasons)
	         * @summary		- create a cron function that runs independant of this class that will pull and update the file as needed
	         * 
	         * @author		Simon Roberts (Chronolabs) simon@labs.coop
	         *
	         * return 		array
	         */
	        function _validTLD()
	        {
	                # first do a quick check on the TLD file
	                $this->_tld_file_status_check();
	                $tld_regex = null;
	     
	                # foreach item in the tild list (as read into an array)
	                foreach(file($this->tld_list) as $tld){
	                        # if the line does NOT contain NON-ALPHA characters
	                        if (preg_match('/^[\w]+$/', $tld)){
	                                # add it to the tld regex for inclusion
	                                $tld_regex .= preg_replace('/\W/', '', $tld)."|";
	                        }
	                }
	                # strip off the last | so the regex will not break
	                $tld_regex = substr_replace($tld_regex, '', -1);
	               
	                #return the valid tld regex string
	                return $tld_regex;
	        }
	         
	       
	        /**
	         * function validateDomain()
	         * 
	         * @summary		Check registry / DNS
	         * 
	         * @summary		This will validate the domain to RFC sepcifcations. It also has the option to verify the
	         * @summary		domain via DNS.
	         * 
	         * @author		Simon Roberts (Chronolabs) simon@labs.coop
	         * 
	         * return 		boolean
	         *
	         */
	        function validateDomain($domain, $verify = false)
	        {
	                if(!$this->_checkDomain($domain))                                                       return false;   // domain must be RFC compliant (status set)
	               
	                if($verify)
	                {
	                        // make sure the needed function exists
	                        if(!function_exists('checkdnsrr')) {
	                                $this->status = array(false, 'NOT TESTED', 'Function needed (checkdnsrr) does not exist.');
	                                return false;
	                        }
	                       
	                        // if domain is an IP we need to try to get the domain name
	                        if(ip2long($domain))
	                        {
	                                $this->status = array(false, 'NOT TESTED', "Cannot determine domain from IP ($domain).", null);
	                                return false;
	                        }
	                       
	                        // make sure we only have the last two nodes of the domain name
	                        $nodes = split("\.", $this->valid_domain);
	                        $top_domain = $nodes[count($nodes)-2].'.'.$nodes[count($nodes)-1];
	                       
	                        // get the DNS records for the server
	                        if(!dns_get_mx($top_domain, $this->servers))
	                        {
	                                $this->status = array(false, 'NOT TESTED', "No MX servers were returned for domain ($domain).", null);  
	                                return false;
	                        }
	                       
	                        // must return servers or verify failed
	                        if(empty($this->servers))
	                        {
	                                $this->status = array(false, 'NOT TESTED', "No mailservers were found in the DNS for domain ($domain).", null);
	                                return false;
	                        }
	                }
	               
	                return true;
	               
	         }
	         
	         /**
	          * function validateIP()
	          * @summary	Validate an IP Address
	          *
	          * 	Allows filters (private and reserved)
	          * 
	          * @author	Simon Roberts (Chronolabs) simon@labs.coop
	          *  
	          * returns 	boolean		flase if detected
	          *
	          */
	        function validateIP($ip, $filter = false)
	        {
	                // validate the IP address is valid
	                if(!filter_var($ip, FILTER_VALIDATE_IP))
	                {
	                        $this->status = array(false, 'INVALID', $this->invalid, "IP address ($ip) is invalid.");
	                        return false;
	                }
	                               
	                // filter private IP range
	                if($filter == 'private' and !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE))
	                {
	                        $this->status = array(false, 'INVALID', $this->invalid, "IP address ($ip) is in a private range and does not pass the filter setting.");
	                        return false;
	                }
	               
	                // filter for reserved IP range
	                if($filter == 'reserved' and !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE))
	                {
	                        $this->status = array(false, 'INVALID', $this->invalid, "IP address ($ip) is in a reserved range and does not pass the filter setting.");
	                        return false;
	                }
	               
	                return true;
	        }
	       
	}
}
?>