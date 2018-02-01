<?php
/**
 * Chronolabs REST Blowfish Salts Repository API
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
 * @package         salty
 * @since           2.0.1
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         $Id: apiconfig.php 1000 2015-06-16 23:11:55Z wishcraft $
 * @subpackage		api
 * @description		Blowfish Salts Repository API
 * @link			http://cipher.labs.coop
 * @link			http://sourceoforge.net/projects/chronolabsapis
 */


if (!is_file(__DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php') || !is_file(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'license.php'))
{
    if (strpos($_SERVER["REQUEST_URI"], 'install/')>0)
        return false;
        header('Location: ' . "./install");
        exit(0);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'constants.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
/**
 * Opens Access Origin Via networking Route NPN
 */
header('Access-Control-Allow-Origin: *');
header('Origin: *');

/**
 * Turns of GZ Lib Compression for Document Incompatibility
 */
ini_set("zlib.output_compression", 'Off');
ini_set("zlib.output_compression_level", -1);

$sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('formats') . "`";
list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
if ($count==0)
{
    $GLOBALS['APIDB']->queryF($sql = "START TRANSACTION");
    
    $sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` NOT IN ('".implode("', '", array_keys($formats)) . "')";
    @$GLOBALS['APIDB']->queryF($sql);
    foreach(getImageFormats() as $extension => $title)
    {
        $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` LIKE '$extension'";
        list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($count == 0)
        {
            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('formats') . "` (`extension`, `title`, `created`) VALUES('$extension','".$GLOBALS['APIDB']->escape($title)."',UNIX_TIMESTAMP())";
            @$GLOBALS['APIDB']->queryF($sql);
        }
    }
    $GLOBALS['APIDB']->queryF($sql = "COMMIT");
}

?>