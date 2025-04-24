<?php
/********************************************************/
/* file: 		config.class.php 						*/
/* module:		COMMON CODEBASE							*/
/* author:		Mark Mulder (http://offcentric.com)		*/
/* date:		13/12/2024								*/
/* version:		0.1										*/
/* description:											*/
/********************************************************/

require(COREBASEPATH . "models/xmlfile.class.php");
include COREBASEPATH . "helpers/xml_functions.php";

set_time_limit(120);
date_default_timezone_set('Europe/Amsterdam');

class Config{
    $xml_folder = 'xml/';
    $config_xmlfile = ROOTFILEPATH . $xml_folder . 'tophy_config.xml';
    $current_webpath = $_SERVER['REQUEST_URI'];
    $alias_node = null;
    $webpath;

    if(substr($current_webpath, strlen($current_webpath)-1) != "/"){
        $current_webpath .= "/";
    }

    if(strpos($_SERVER['REQUEST_URI'], "?") > 0){
        $current_webpath = substr($_SERVER['REQUEST_URI'], 0 ,strpos($_SERVER['REQUEST_URI'], "?"));
    }

	/* DETERMINE SERVER PLATFORM */
	if(stristr($_SERVER['SERVER_SOFTWARE'], "win32") || stristr($_SERVER['SERVER_SOFTWARE'], "win64")){
		$server_OS = "win";
	}else{
		$server_OS = "nix";
	}

    $system_pathnames = [
        'scripts' => 'scripts',
        'images' => 'images',
        'themes' => 'themes',
        'modules' => 'modules',
        'default_theme' => '__default',
        'transparent_img' => $webpath . 'images/global/t.png';
    ];


    function load_global_config(){


        /* OPEN TOPHY CONFIG XML */
        $config_global = new XmlFile(realpath($config_xmlfile));
        $config_root_node = $GLOBALS['config_global']->xpath->query("/config")->item(0);

        /***********************************/
        /* RESOLVE ALIASES */
        $http_host = $_SERVER['HTTP_HOST'];
        if(strpos($http_host, "www.") ===0){
            $http_host = substr($http_host, 4);
        }
        $url_pattern = "/^(http:\/\/)?(www\.)?" . $http_host . "(\/)?/i";

        $xpresult_aliases = $config_global->xpath->query("/config/aliases/alias");
        if($xpresult_aliases->length > 1){
        // using a stupid for loop instead of the handy fn:matches XPath function cause PHP's XPath support is lame
            foreach($xpresult_aliases as $alias){
                if(preg_match($url_pattern, getElementValue($alias, "domain", 0, ""))){
                    $alias_node = $alias;
                }
            }
            if($alias_node == null){
                $alias_node = $xpresult_aliases->item(0);
            }
        }else if($xpresult_aliases->length == 1){
            $alias_node = $xpresult_aliases->item(0);
        }else{
            echo "CRITICAL ERROR! No &lt;alias&gt; nodes are defined in <strong>_tophy_conf.xml</strong>.";
            die();
        }

        if($_SESSION['alias_node'] != null){
            $webpath = getElementValue($_SESSION['alias_node'], "webpath", 0, "/");
            $_SESSION['media_path'] = getElementValue($_SESSION['alias_node'], "media_path", 0, "conf");
            $_SESSION['media_url'] = getElementValue($_SESSION['alias_node'], "media_url", 0, "conf");

            if($_SESSION['webpath'] != "" && $_SESSION['webpath'] != "/"){
                if(strpos($_SESSION['webpath'], "/") === 0){
                    $_SESSION['filepath'] = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['webpath'];
                }else{
                    $_SESSION['filepath'] = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['webpath'];
                }
            }else{
                $_SESSION['filepath'] = $_SERVER['DOCUMENT_ROOT'];
            }

            /* add a trailing slash to the webpath and filepath global variables, if it is missing */
            if(substr($_SESSION['filepath'], strlen($_SESSION['filepath'])-1) != "/") $_SESSION['filepath'] .= "/";
            if(substr($_SESSION['webpath'], strlen($_SESSION['webpath'])-1) != "/") $_SESSION['webpath'] .= "/";

            /* if media_path is a relative path, make it relative to the application's location */
            if(substr($_SESSION['media_path'], 0, 1) != "/"){
                $_SESSION['media_path'] = $_SESSION['filepath'] . $_SESSION['media_path'];
            }

            if(substr($_SESSION['media_url'], 0, 1) != "/" && preg_match("/https?:\/\//", $_SESSION['media_url'], $matches) === false){
                $_SESSION['media_webpath'] = $_SESSION['webpath'] . $_SESSION['media_url'];
            }else{
                $_SESSION['media_webpath'] = $_SESSION['media_url'];
            }
            /* get paths to the _db folder, where all thumbnails and strip sized images are generated */
            $_SESSION['path_db'] = $_SESSION['filepath'] . "_db/";
            $_SESSION['webpath_db'] = $_SESSION['webpath'] . "_db/";
        }else{
            echo "CRITICAL ERROR! No alias found for this URL " . $http_host . ". Check your configuration and make sure an alias exists.";
            die();
        }

        /***********************************/
        /* GENERAL SITE DATA & METADATA */
        $_SESSION['website_name'] = getElementValue($config_root_node, "website_name", 0, "");
        $_SESSION['website_subtitle'] = getElementValue($config_root_node, "website_subtitle", 0, "");
        $_SESSION['admin_email'] = getElementValue($config_root_node, "admin_email", 0, "");
        $_SESSION['email_subject'] = getElementValue($config_root_node, "email_subject", 0, $_SERVER['HTTP_HOST']);

        $_SESSION['logo_format'] = getElementValue($config_root_node, "logo_format", 0, "gif");
        $_SESSION['logo_align'] = getElementValue($config_root_node, "logo_align", 0, "center");


        $metadata_node = $GLOBALS['config_global']->xpath->query("/config/metadata")->item(0);
        $_SESSION['meta_author'] = getElementValue($metadata_node, "meta_author", 0, "");
        $_SESSION['meta_keywords'] = getElementValue($metadata_node, "meta_keywords", 0, "");
        $_SESSION['meta_description'] = getElementValue($metadata_node, "meta_description", 0, "");
        $_SESSION['meta_company_name'] = getElementValue($metadata_node, "meta_company_name", 0, "");
        $_SESSION['meta_classification'] = getElementValue($metadata_node, "meta_classification", 0, "");

        $_SESSION['footercontent'] = getElementValue($config_root_node, "footercontent", 0, "");

        /***********************************/
        /* THEMES */
        $themesnode = $GLOBALS['config_global']->xpath->query("/config/themes")->item(0);
        $xpresult_themes = $GLOBALS['config_global']->xpath->query("/config/themes/theme");

        if(isset($_GET["theme"])){
            $_SESSION['theme'] = $_GET["theme"];
        }else{
            for($x=0;$x<$xpresult_themes->length;$x++){
                $_SESSION['themes'][$x] = getElementValue($themesnode, "theme", $x, "");
                if((!array_key_exists('theme', $_SESSION) || !$_SESSION['theme']) && getAttributeValue($themesnode, "theme", $x, "default", "")){
                    $_SESSION['theme'] = $_SESSION['themes'][$x];
                }
            }
        }

        $_SESSION['scripts_path'] = "scripts/themes/" . $_SESSION['theme'] . "/";
        $_SESSION['styles_path'] = "styles/themes/" . $_SESSION['theme'] . "/";
        $_SESSION['images_path'] = "images/themes/" . $_SESSION['theme'] . "/";

        /***********************************/
        /* CUSTOM STYLES */
        if(count($GLOBALS['config_global']->xpath->query("/config/custom_styles")) > 0){
            $custom_styles_node = $GLOBALS['config_global']->xpath->query("/config/custom_styles")->item(0);
            $_SESSION['custom_body_bgcolor'] = getElementValue($custom_styles_node, "custom_body_bgcolor", 0, "666666");
            $_SESSION['custom_container_bgcolor'] = getElementValue($custom_styles_node, "custom_container_bgcolor", 0, "FAFAFA");
            $_SESSION['custom_body_textcolor'] = getElementValue($custom_styles_node, "custom_body_textcolor", 0, "222222");
            $_SESSION['custom_link_textcolor'] = getElementValue($custom_styles_node, "custom_link_textcolor", 0, "003388");
            $_SESSION['custom_link_hover_textcolor'] = getElementValue($custom_styles_node, "custom_link_hover_textcolor", 0, "0000FF");
            $_SESSION['custom_nav_textcolor'] = getElementValue($custom_styles_node, "custom_nav_textcolor", 0, "003388");
            $_SESSION['custom_nav_hover_textcolor'] = getElementValue($custom_styles_node, "custom_nav_hover_textcolor", 0, "0055DD");
            $_SESSION['custom_nav_active_textcolor'] = getElementValue($custom_styles_node, "custom_nav_active_textcolor", 0, "FFFFFF");
            $_SESSION['custom_nav_active_bgcolor'] = getElementValue($custom_styles_node, "custom_nav_active_bgcolor", 0, "003388");
            $_SESSION['custom_bar_bgcolor'] = getElementValue($custom_styles_node, "custom_bar_bgcolor", 0, "003388");
            $_SESSION['custom_bar2_bgcolor'] = getElementValue($custom_styles_node, "custom_bar2_bgcolor", 0, "002277");
            $_SESSION['custom_bar_textcolor'] = getElementValue($custom_styles_node, "custom_bar_textcolor", 0, "FFFFFF");
            $_SESSION['custom_main_font_family'] = getElementValue($custom_styles_node, "custom_main_font_family", 0, "Arial, Helvetica, sans-serif");
            $_SESSION['custom_main_font_size'] = getElementValue($custom_styles_node, "custom_main_font_size", 0, "1em");
            $_SESSION['custom_nav_font_family'] = getElementValue($custom_styles_node, "custom_nav_font_family", 0, "Arial, Helvetica, sans-serif");
            $_SESSION['custom_nav_font_size'] = getElementValue($custom_styles_node, "custom_nav_font_size", 0, "1.3em");

        }

        $_SESSION['page_layout'] = getElementValue($config_root_node, "page_layout", 0, "full");
        if($_SESSION['page_layout'] == "full")
            $_SESSION['container_width'] = "100%";
        else
            $_SESSION['container_width'] = $_SESSION['page_layout'] . "px";


        /************************************/
        /* 			GET HOMEPAGE			*/
        $homepage_items = $GLOBALS['config_global']->xpath->query("/config/homepage/item");
        $_SESSION['homepage_items'] = array();
        $x = 0;

        foreach($homepage_items as $item){
            $_SESSION['homepage_items'][$x]['module'] = getElementValue($item, "module", 0, "");
            $_SESSION['homepage_items'][$x]['view'] = getElementValue($item, "view", 0, "");
            $params = $GLOBALS['config_global']->xpath->query("param", $item);
            $y = 0;
            foreach($params as $param){
                $_SESSION['homepage_items'][$x]['params'][$y]['name'] = getElementValue($param, "name", 0, "");
                $_SESSION['homepage_items'][$x]['params'][$y]['value'] = getElementValue($param, "value", 0, "");
                $y++;
            }
            $x++;
        }

        /***********************************/
        /* TOPHY CORE MODULES */

        $modules = $GLOBALS['config_global']->xpath->query("/config/modules/module");
        $homepage_module = '';
        $modules_enabled = array();
        $module_paths = array();
        $module_config_include_files = [];
        $module_content_include_files = [];

        foreach($modules as $module){
            $name = getElementValue($module, "name", 0, "");
            $enabled = getElementValue($module, "enabled", 0, "0");

            if($enabled){
                $_SESSION['modules_enabled'][count($_SESSION['modules_enabled'])] = $name;
                if(!array_key_exists($name, $_SESSION))
                    $_SESSION[$name] = array();
                $_SESSION[$name]['config_xml_file'] = $xml_folder . getElementValue($module, "config_xml_file", 0, $name . "_config.xml");
                $_SESSION[$name]['content_xml_file'] = $xml_folder . getElementValue($module, "content_xml_file", 0, $name . ".xml");
                $_SESSION[$name]['webpath'] = getElementValue($module, "webpath", 0, "");
                //append a slash at the end of the path if it's missing
                if(substr($_SESSION[$name]['webpath'], strlen($_SESSION[$name]['webpath'])-1) != "/") $_SESSION[$name]['webpath']  .= "/";
                $_SESSION[$name]['module_name'] = $name;
                if($_SESSION[$name]['webpath'] == "/"){
                    if($homepage_module != ""){
                        echo "ERROR! more than one homepage defined! Reverting to first one chosen ($homepage_module).";
                    }else{
                        $homepage_module = $name;
                        $_SESSION['homepage_module'] = $homepage_module;
                    }
                }
                $_SESSION['module_paths'][count($_SESSION['module_paths'])] = $_SESSION[$name]['webpath'];
                $config_include_file = MODULESBASEPATH . $name . '/models/module_config.php';
                $content_include_file = MODULESBASEPATH . $name . '/models/module.php';
                if(file_exists($config_include_file)){
                    $module_config_include_files[count($module_config_include_files)] = $config_include_file;
                }
                if(file_exists($content_include_file)){
                    $_SESSION['module_content_include_files'][count($_SESSION['module_content_include_files'])] = $content_include_file;
                }
            }
        }

        /***********************************/
        /* QUICK LINKS MENU */
        $xpresult_quicklinks = $GLOBALS['config_global']->xpath->query("/config/quick_links/item");
        $quicklinks_node = $GLOBALS['config_global']->xpath->query("/config/quick_links")->item(0);
        $_SESSION['quicklinks'] = array();
        for($x=0;$x<$xpresult_quicklinks->length;$x++){
            $itemnode = $xpresult_quicklinks->item($x);
            $_SESSION['quicklinks']['items'][$x]['type'] = getAttributeValue($quicklinks_node, "item", $x, "type", "text");
            $_SESSION['quicklinks']['items'][$x]['title'] = getElementValue($itemnode, "title", 0, "");
            $_SESSION['quicklinks']['items'][$x]['link'] = getElementValue($itemnode, "link", 0, "");
            $_SESSION['quicklinks']['items'][$x]['img'] = getElementValue($itemnode, "img", 0, "");
        }
        /***********************************/


    }

}

// if XML has been updated, clear the session and repopulate it
if(filectime($config_xmlfile) != @$_SESSION['config_xmlfile_time'] || filesize($config_xmlfile) != @$_SESSION['config_xmlfile_size']){
	 load_global_config();
}
if(isset($_GET["theme"])) $_SESSION['theme'] = $_GET["theme"];

foreach(Config->$module_config_include_files as $config_include_file){
    include $config_include_file;
 }


