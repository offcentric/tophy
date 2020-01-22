<?php

$base_path = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], 'index.php'));
session_start();

function find_folder($current_dir, $search_dir){
    $basepath = '';

    foreach(glob($current_dir."/*", GLOB_ONLYDIR) as $dirpath) {
        $dir = substr($dirpath, strrpos($dirpath, $current_dir)+strlen($current_dir)+1);
        if($dir == $search_dir){
            $basepath = $dirpath;
            return $basepath;
            break;
        }
    }
    if($basepath == ""){
        if($current_dir != ""){
            $parent_dir = substr($current_dir, 0, strrpos($current_dir, "/"));
            return find_folder($parent_dir,$search_dir);
        }else{
            echo "CRITICAL ERROR! <strong>" . $search_dir . "</strong> folder cannot be found on the server!!";
            die();
        }
    }
}

if(@!is_array($_SESSION['BASEPATH'])){

    $tophy_base_path = find_folder($base_path, $tophy_base_folder) . '/';

    $_SESSION['BASEPATH'] = array("CORE" => "", "MODULES" => "", "THEMES" => "");
    $_SESSION['BASEPATH']['CORE'] =  $tophy_base_path . 'core';
    $_SESSION['BASEPATH']['MODULES'] =  $tophy_base_path . 'modules';
    $_SESSION['BASEPATH']['THEMES'] = $tophy_base_path . 'themes';
}

define('COREBASEPATH', $_SESSION['BASEPATH']['CORE'] . '/');
define('MODULESBASEPATH', $_SESSION['BASEPATH']['MODULES'] . '/');
define('THEMESBASEPATH', $_SESSION['BASEPATH']['THEMES'] . '/');
define('ROOTFILEPATH', $base_path . '/');

require(COREBASEPATH . 'models/global_config.php');
include COREBASEPATH . 'helpers/global_functions.php';
include MODULESBASEPATH . $_SESSION['cm__pages']['module_name'] . '/helpers/page_functions.php';

require(COREBASEPATH . 'models/content_loader.php');
require(COREBASEPATH . 'helpers/path_translator.php');
require(COREBASEPATH . 'models/global.php');
if(isset($_REQUEST['action'])){
    require(COREBASEPATH . 'models/action.php');
}

if(!$GLOBALS['filefound']){
    $GLOBALS['filepath'] = get_page('404');
    //TODO: Include tracking of 404's and log the referer
}else{
    if($GLOBALS['filetype'] == 'scripts' || $GLOBALS['filetype'] == 'styles') write_content_type_header($GLOBALS['filetype']);
}

