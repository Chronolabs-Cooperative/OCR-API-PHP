<?php
//
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: API Translation Team


define('API_EXTRAS',"API Constants & Extra Definitions");


define('API_WIDTH_LABEL','Mimimum original source for icon pixels in width x height!');
define('API_IDENTIFY_LABEL','ImageMagick Identifing executable');
define('API_IDENTIFY_HELP','You need to install imagemagick ie. $ sudo apt-get install imagemagick* -y');
define('API_CONVERT_LABEL','ImageMagick image conversion executable');
define('API_CONVERT_HELP','You need to install imagemagick ie. $ sudo apt-get install imagemagick* -y');

// Email Constants
define('API_IMAP_LABEL','IMAP Service for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_HELP','This is the service host netbios path name for the IMAP Services');
define('API_SMTP_LABEL','SMTP Service for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_SMTP_HELP','This is the service host netbios path name for the SMTP Services');
define('API_IMAPPORT_LABEL','IMAP Service Port for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAPPORT_HELP','This is the service host netbios path name for the IMAP Services Port ');
define('API_SMTPPORT_LABEL','SMTP Service Port for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_SMTPPORT_HELP','This is the service host netbios path name for the SMTP Services Port ');
define('API_CATCHALL_LABEL','This is the email address for the catch all for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_CATCHALL_HELP','This is the service host netbios path name for the Email Catch All');
define('API_USERNAME_LABEL','IMAP, SMTP Service for Catch All Username for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_USERNAME_HELP','This is the service host netbios path name for the IMAP, SMTP Services Username');
define('API_PASSWORD_LABEL','IMAP, SMTP Service for Catch All Password for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_PASSWORD_HELP','This is the service host netbios path name for the IMAP, SMTP Services Password');
