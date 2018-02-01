## Chronolabs Cooperative presents

# Optical Character Recognition REST API v2.0.2

#### Demo: http://ocr.snails.email

### Author: Simon Antony Roberts <simon@snails.email>

The following REST API allows for images over nearly every format to be processed for OCR that is optical character recognition, there is a later version of gocr the tool used for this you can manually install from external repositories that has font training formats as well!

# Setting Up the environment in Ubuntu/Debian

There is a couple of extensions you will require for this API to run you need to execute the following at your terminal bash shell to have the modules installed before installation.

    $ sudo apt-get install imagemagick* gocr* -y
    

# Apache Module - URL Rewrite

The following script goes in your API_ROOT_PATH/.htaccess file

    php_value memory_limit 145M
    php_value upload_max_filesize 69M
    php_value post_max_size 69M
    php_value error_reporting 0
    php_value display_errors 0
    php_value log_errors 0
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^v([0-9]+)/(.*?)/(raw|html|json|serial|xml).api ./index.php?version=$1&field=$2&output=$3 [L,NC,QSA]


To Turn on the module rewrite with apache run the following:

    $ sudo a2enmod rewrite
    $ sudo service apache2 restart
