<?php
function get_admin_pass(){
	
}


function write_htaccess(){
	$output = <<<__HTACCESS
	ErrorDocument 401 $_SESSION['webpath']401/
	ErrorDocument 403 $_SESSION['webpath']403/
	ErrorDocument 404 $_SESSION['webpath']404/

	RewriteEngine On
	RewriteRule ^/?$ gate.php?start=true [L]

	RewriteRule ^(.*_gallery_conf\.xml.*)$ /401/ [L]

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(images/)(.*)$ image.php?img=$2 [L]

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(scripts|styles)/([^\?]+)$ gate.php?template_folder=$1&template=$2 [L,QSA]

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_URI} !.*gate.php.*
	RewriteCond %{REQUEST_URI} !.*image.php.*
	#RewriteCond %{REQUEST_URI} !/404/?
	RewriteCond %{REQUEST_URI} !/error-404/?
	RewriteRule ^([^/]+)/?$ gate.php?template_folder=views&template=$1 [L,QSA]

	RewriteRule ^book/([^/]+)/?(thumbnails|strip)/?$ gate.php?template_folder=views&template=book&book=$1&display=$2 [L]
	RewriteRule ^book/([^/]+)/?(thumbnails)/(constrain_w|constrain_h|constrain_both|fixed)/?$ gate.php?template_folder=views&template=book&book=$1&display=$2&thumbratio=$3 [L]
	RewriteRule ^book/([^/]+)/([^/]+)/(thumbnails|strip)/?$ gate.php?template_folder=views&template=page&book=$1&p=$2&display=$3 [L]
	RewriteRule ^book/([^/]+)/([^/]+)/(thumbnails)/(constrain_w|constrain_h|constrain_both|fixed)/?$ gate.php?template_folder=views&template=page&book=$1&p=$2&display=$3&thumbratio=$4 [L]
	RewriteRule ^book/([^/]+)/([^/]+)?/?photo\:([^/]+)/?$ gate.php?template_folder=views&template=photo&book=$1&p=$2&photo=$3 [L]
	RewriteRule ^help/([^/\?]+)/([^/\?]+)/?$ gate.php?template_folder=views&template=help&section=$1&page=$2 [L,QSA]
	RewriteRule ^help/admin/?([^/\?]+)?/?$ gate.php?template_folder=views&template=help&section=admin&page=$1 [L,QSA]
	RewriteRule ^help/([^/\?]+)/?$ gate.php?template_folder=views&template=help&section=general&page=$1 [L,QSA]

	RewriteRule ^book/([^/]+)/?$ gate.php?template_folder=views&template=book&book=$1 [L]
	RewriteRule ^book/([^/]+)/([^/]+)/?$ gate.php?template_folder=views&template=page&book=$1&p=$2 [L]	
	__HTACCESS;
	echo $output;
}


?>