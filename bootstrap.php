<?php

final class Bootstrap {

    public static function init(){

        session_start();

        if(@!is_array($_SESSION['BASEPATH'])){

            $_SESSION['BASEPATH'] = ["CORE" => "", "MODULES" => "", "THEMES" => ""];
            $_SESSION['BASEPATH']['CORE'] =  __DIR__ . '/core';
            $_SESSION['BASEPATH']['MODULES'] =  __DIR__ . '/modules';
            $_SESSION['BASEPATH']['THEMES'] = __DIR__ . '/themes';
        }

        define('COREBASEPATH', $_SESSION['BASEPATH']['CORE'] . '/');
        define('MODULESBASEPATH', $_SESSION['BASEPATH']['MODULES'] . '/');
        define('THEMESBASEPATH', $_SESSION['BASEPATH']['THEMES'] . '/');
        define('ROOTFILEPATH', __DIR__ . '/');

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
    }
}

//Bootstrap::init();