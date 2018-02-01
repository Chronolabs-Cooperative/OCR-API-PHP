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
 */
    $formats = getImageFormats();
	$ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="<?php echo $place['iso2']; ?>thor" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'AntonyXaies'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>

<body>
<div class="main">
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This is an API Service for conducting a query on a Optical Character Recognition to find out text inside an image.</p>
    <h2>RAW Document Output</h2>
    <p>This is done with the <em>raw.api</em> extension at the end of the url, you replace the example address with the Optical Character Recognition you are testing the following example is of calls to the api</p>
    <blockquote>
        <font color="#009900">You need too submit a form to the following URL for the field name of <em>'<?php echo $ua; ?>'</em> containing the image to be OCR'd</font><br/><br/>
        <em>Form action path: <strong><?php echo API_URL; ?>/v2/<?php echo $ua; ?>/raw.api</strong></em>
        <form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/raw.api">
    			Select image to upload:
    		     <input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>">
   				 <input type="submit" value="Upload Image" name="submit">
		</form>
		<h3>Code Example:</h3>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/raw.api"&gt;
	Select image to upload:
	&lt;input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>"&gt;
	&lt;input type="submit" value="Upload Image" name="submit"&gt;
&lt;/form&gt;
		</pre>
    </blockquote>
    <h2>HTML Document Output</h2>
    <p>This is done with the <em>html.api</em> extension at the end of the url, you replace the address with the Optical Character Recognition you are testing the following example is of calls to the api</p>
    <blockquote>
        <font color="#009900">You need too submit a form to the following URL for the field name of <em>'<?php echo $ua; ?>'</em> containing the image to be OCR'd</font><br/><br/>
         <em>Form action path: <strong><?php echo API_URL; ?>/v2/<?php echo $ua; ?>/html.api</strong></em>
        <form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/html.api">
    			Select image to upload:
    		     <input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>">
   				 <input type="submit" value="Upload Image" name="submit">
		</form>
		<h3>Code Example:</h3>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/html.api"&gt;
	Select image to upload:
	&lt;input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>"&gt;
	&lt;input type="submit" value="Upload Image" name="submit"&gt;
&lt;/form&gt;
		</pre>
    </blockquote>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, you replace the address with the Optical Character Recognition you are testing the following example is of calls to the api</p>
    <blockquote>
        <font color="#009900">You need too submit a form to the following URL for the field name of <em>'<?php echo $ua; ?>'</em> containing the image to be OCR'd</font><br/><br/>
         <em>Form action path: <strong><?php echo API_URL; ?>/v2/<?php echo $ua; ?>/serial.api</strong></em>
        <form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/serial.api">
    			Select image to upload:
    		     <input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>">
   				 <input type="submit" value="Upload Image" name="submit">
		</form>
		<h3>Code Example:</h3>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/serial.api"&gt;
	Select image to upload:
	&lt;input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>"&gt;
	&lt;input type="submit" value="Upload Image" name="submit"&gt;
&lt;/form&gt;
		</pre>
    </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, you replace the address with the Optical Character Recognition you are testing the following example is of calls to the api</p>
    <blockquote>
        <font color="#009900">You need too submit a form to the following URL for the field name of <em>'<?php echo $ua; ?>'</em> containing the image to be OCR'd</font><br/><br/>
         <em>Form action path: <strong><?php echo API_URL; ?>/v2/<?php echo $ua; ?>/json.api</strong></em>
        <form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/json.api">
    			Select image to upload:
    		     <input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>">
   				 <input type="submit" value="Upload Image" name="submit">
		</form>
		<h3>Code Example:</h3>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/json.api"&gt;
	Select image to upload:
	&lt;input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>"&gt;
	&lt;input type="submit" value="Upload Image" name="submit"&gt;
&lt;/form&gt;
		</pre>
    </blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, you replace the address with the Optical Character Recognition you are testing the following example is of calls to the api</p>
    <blockquote>
        <font color="#009900">You need too submit a form to the following URL for the field name of <em>'<?php echo $ua; ?>'</em> containing the image to be OCR'd</font><br/><br/>
        <em>Form action path: <strong><?php echo API_URL; ?>/v2/<?php echo $ua; ?>/xml.api</strong></em>
        <form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/xml.api">
    			Select image to upload:
    		     <input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>">
   				 <input type="submit" value="Upload Image" name="submit">
		</form>
		<h3>Code Example:</h3>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;form name="<?php echo $ua; ?>" method="POST" enctype="multipart/form-data" action="<?php echo API_URL; ?>/v2/<?php echo $ua; ?>/xml.api"&gt;
	Select image to upload:
	&lt;input type="file" name="<?php echo $ua; ?>" id="<?php echo $ua; ?>"&gt;
	&lt;input type="submit" value="Upload Image" name="submit"&gt;
&lt;/form&gt;
		</pre>
    </blockquote>
    <h2>Following Image Formats Supported</h2>
    <p>The following image format extension and titles are supported by this api, which the <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'><?php echo ini_get('upload_max_filesize') ?>!!!</em></strong><br/></p>
    <blockquote>
    	<ol>
    		<?php foreach($formats as $ext => $title) { ?>
    		<ul><font style="font-size: 135%; font-weight: 800; margin-right: 10px;">*.<?php echo $ext; ?></font>&nbsp;~&nbsp;<?php echo $title; ?></ul>
    		<?php } ?>
    	</ol>
    </blockquote>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2018 and is part of the Chronolabs System and Xortify. if you need to contact simon you can do so at the following address <a href="mailto:simon@snails.email">simon@snails.email</a></p></body>
</div>
</html>
<?php 
