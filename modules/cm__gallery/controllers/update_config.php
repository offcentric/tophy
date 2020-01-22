<?php //script will time out in 5 minutes (instead of default 30 seconds)
set_time_limit(300);

// gather all $_REQUEST vars
$action = $_REQUEST['action'];

function upload_nav_image($suffix){
    $nav_image = $_FILES['image' . $suffix];
    $dom = new DOMDocument();
    $xpath = new DOMXPath($dom);
    $gallery_node = $xpath->query("gallery")->item(0);
    $navigation_image_format = getElementValue($gallery_node, "navigation_image_format", 0, "");

    if ($nav_image['name'] != "") {
        $nav_image_type = get_image_type($nav_image);
        if ($nav_image_type == $navigation_image_format) {
            $image_name = strtolower($_REQUEST['book'] . $suffix);
            $image_path = $GLOBALS['path_to_root'] . "images/nav/" . $image_name;

            $image_name .= "." . $navigation_image_format;
            $image_name = preg_replace('/+', '/', $image_name);
            $image_name = stripslashes($image_name);

            // first remove all existing jpg/gif/png for this nav image
            if (file_exists($image_path . ".png")) unlink($image_path . ".png");
            if (file_exists($image_path . ".gif")) unlink($image_path . ".gif");
            if (file_exists($image_path . ".jpg")) unlink($image_path . ".jpg");

            if (is_uploaded_file($nav_image['tmp_name'])) {
                $file_path = $GLOBALS['path_to_root'] . "images/nav/" . $image_name;
                move_uploaded_file($nav_image['tmp_name'], $file_path);
                chmod($file_path, 0644);
                return true;
            } else {
                return false;
            }
        } else {
            $GLOBALS['errs']['nav_image'] = "The naviation image(s) you have supplied are not in the correct format. They must match the format you chose in the dropdown at the top.";
            return false;
        }
    } else {
        return false;
    }
}

if (sizeof($_POST) > 0) {
    if ($action == "upload_nav_images") {
        // determine if the nav images are gif, png, or jpg

        $success = upload_nav_image("");
        $success_on = upload_nav_image("_on");
        $GLOBALS['success'] = $success;
        if ($success) {
            setElementValue($gallery_node, "navigation_image_format", 0, $navigation_image_format, true);
            if ($_REQUEST['json_response'] != "true")
                header("Location:.?action=edit_nav_images&book=" . $_REQUEST['book'] . "&success=true");
        }

    } elseif ($action == "edit_splash_images") {
        require(MODULESBASEPATH . $_SESSION['cm__gallery']['module_name'] . "/helpers/gd_functions.php");
        $splash_image = $_FILES['new_splash_image'];
        if ($splash_image['name'] != "") {
            $splash_path = $GLOBALS['path_to_root'] . "images/splash/splashimage_";
            $splash_image_type = get_image_type($splash_image);
            if ($splash_image_type == "jpg" || $splash_image_type == "png") {
                if (is_uploaded_file($splash_image['tmp_name'])) {
                    $index = (sizeof(glob($splash_path . "*")) + 1) . "";
                    if (strlen($index) == 1) $index = "0" . $index;
                    $file_path = $splash_path . $index . "." . $splash_image_type;
                    move_uploaded_file($splash_image['tmp_name'], $file_path);
                    chmod($file_path, 0644);
                    if (checkbox_to_boolean($_POST['resize_splash_images'])) {
                        resizeimg($file_path, $file_path, 0, $_POST['splash_image_resize_h'], "constrain_h");
                    }
                    $thumb_path = $_SESSION['path_db'] . "__splash/";
                    if (!is_dir($thumb_path))
                        mkdir($thumb_path);
                    resizeimg($file_path, $thumb_path . "splashimage_" . $index . "." . $splash_image_type, 0, 70, "constrain_h");
                }
                setElementValue($gallery_node, "resize_splash_images", 0, checkbox_to_boolean($_POST['resize_splash_images']), true);
                setElementValue($gallery_node, "splash_image_resize_w", 0, $_POST['splash_image_resize_w'], true);
                setElementValue($gallery_node, "splash_image_resize_h", 0, $_POST['splash_image_resize_h'], true);
            } else {
                $GLOBALS['errs']['new_splash_image'] = "The splash image must be a JPG or a PNG file.";
            }
        }
    } else {
        $gallery_name = stripslashes($_POST["gallery_name"]);
        $gallery_name = utf8_encode($gallery_name);
        $admin_email = stripslashes($_POST["admin_email"]);
        $email_subject = stripslashes($_POST["email_subject"]);
        $email_subject = utf8_encode($email_subject);

        $logo_align = $_POST["logo_align"];
        $page_layout = $_POST["page_layout"];
        $theme = $_POST["theme"];

        /* CUSTOM CSS STYLE DEFINITIONS */
        $custom_body_bgcolor = $_POST["custom_body_bgcolor"];
        $custom_container_bgcolor = $_POST["custom_container_bgcolor"];
        $custom_body_textcolor = $_POST["custom_body_textcolor"];
        $custom_link_textcolor = $_POST["custom_link_textcolor"];
        $custom_link_hover_textcolor = $_POST["custom_link_hover_textcolor"];
        $custom_nav_textcolor = $_POST["custom_nav_textcolor"];
        $custom_nav_hover_textcolor = $_POST["custom_nav_hover_textcolor"];
        $custom_nav_active_textcolor = $_POST["custom_nav_active_textcolor"];
        $custom_nav_active_bgcolor = $_POST["custom_nav_active_bgcolor"];
        $custom_bar_bgcolor = $_POST["custom_bar_bgcolor"];
        $custom_bar2_bgcolor = $_POST["custom_bar2_bgcolor"];
        $custom_bar_textcolor = $_POST["custom_bar_textcolor"];
        $custom_main_font_family = $_POST["custom_main_font_family"];
        $custom_main_font_size = $_POST["custom_main_font_size"];
        $custom_nav_font_family = $_POST["custom_nav_font_family"];
        $custom_nav_font_size = $_POST["custom_nav_font_size"];

        $startpage = $_POST["startpage"];
        $startbook = $_POST["startbook"];

        $gallery_display = $_POST["gallery_display"];
        $slideshow_interval = $_POST["slideshow_interval"];

        $resize_splash_images = checkbox_to_boolean($_POST["resize_splash_images"]);
        $splash_image_resize_w = $_POST["splash_image_resize_w"];
        $splash_image_resize_h = $_POST["splash_image_resize_h"];

        $gallery_thumb_ratio = $_POST["gallery_thumb_ratio"];
        $gallery_thumb_w = $_POST["gallery_thumb_w"];
        $gallery_thumb_h = $_POST["gallery_thumb_h"];
        $gallery_strip_h = $_POST["gallery_strip_h"];
        $enable_thickbox = checkbox_to_boolean($_POST["enable_thickbox"]);
        $enable_slideshow = checkbox_to_boolean($_POST["enable_slideshow"]);
        $enable_control_panel = checkbox_to_boolean($_POST["enable_control_panel"]);
        $enable_exif_display = checkbox_to_boolean($_POST["enable_exif_display"]);
        $enable_all_books_list = checkbox_to_boolean($_POST["enable_all_books_list"]);
        $gallery_navigation_style = $_POST["gallery_navigation_style"];
        $navigation_image_format = $_POST["navigation_image_format"];

        $nav_items = $_POST["nav_item"];
        $aliases = $_POST["alias"];

        $meta_author = $_POST["meta_author"];
        $meta_company_name = $_POST["meta_company_name"];
        $meta_keywords = $_POST["meta_keywords"];
        $meta_description = $_POST["meta_description"];
        $meta_classification = $_POST["meta_classification"];

        $footercontent = $_POST["footercontent"];

        // TODO: Server-side validation!!
        if ($gallery_name == "") $GLOBALS['errs']['gallery_name'] = "You have not supplied a gallery name.";

        /* OPEN A POINTER TO TEMPLATE XML FOR NICELY FORMATTED XML NODES */
        $templatexml = COREBASEPATH . "xml/_tophy_conf_template.xml";

        //open xml docment, throw exception on fail
        $domtemplate = new DOMDocument("1.0");
        $domtemplate->formatOutput = true;

        if (!$domtemplate->load(realpath($templatexml))) {
            echo "error!!! template xml doc invalid or not found at ";
            echo realpath($templatexml);
            exit;
        } else {
            $domtemplate->formatOutput = true;
            // get new xpath context
            $xpathtemplate = new DOMXPath($domtemplate);
        }

        /* UPDATE XML */
        /* General Site Info */
        setElementValue($gallery_node, "gallery_name", 0, $gallery_name, true);
        setElementValue($gallery_node, "admin_email", 0, $admin_email, true);
        setElementValue($gallery_node, "email_subject", 0, $email_subject, true);

        /* Gallery Customization */
        /* UPLOAD LOGO IMAGE */
        $main_logo = $_FILES['gallery_logo'];
        if ($main_logo['name'] != "") {
            $logo_path = $GLOBALS['path_to_root'] . "images/logo.";
            $logo_image_type = get_image_type($main_logo);
            if ($logo_image_type == "jpg" || $logo_image_type == "png" || $logo_image_type == "gif") {
                $file_path = $logo_path . $logo_image_type;
                // first remove all existing logos in jpg/gif/png
                if (file_exists($logo_path . "png")) unlink($logo_path . "png");
                if (file_exists($logo_path . "gif")) unlink($logo_path . "gif");
                if (file_exists($logo_path . "jpg")) unlink($logo_path . "jpg");

                if (is_uploaded_file($main_logo['tmp_name'])) {
                    move_uploaded_file($main_logo['tmp_name'], $file_path);
                    chmod($file_path, 0644);
                }

                setElementValue($gallery_node, "logo_name", 0, "logo." . $logo_image_type, true);
            } else {
                $GLOBALS['errs']['gallery_logo'] = "The logo you have uploaded is not a valid or recognized image type. The allowed image types are JPG, PNG and GIF.";
            }
        }

        /* Saves will be atomic, i.e. the XML will only updated if the error count is 0 */
        if (count($GLOBALS['errs']) == 0) {
            setElementValue($gallery_node, "logo_align", 0, $logo_align, true);
            setElementValue($gallery_node, "page_layout", 0, $page_layout, true);
            setElementValue($gallery_node, "theme", 0, $theme, true);

            $custom_styles_node = $xpath->query("gallery/custom_styles")->item(0);
            setElementValue($custom_styles_node, "custom_body_bgcolor", 0, $custom_body_bgcolor, true);
            setElementValue($custom_styles_node, "custom_container_bgcolor", 0, $custom_container_bgcolor, true);
            setElementValue($custom_styles_node, "custom_body_textcolor", 0, $custom_body_textcolor, true);
            setElementValue($custom_styles_node, "custom_link_textcolor", 0, $custom_link_textcolor, true);
            setElementValue($custom_styles_node, "custom_link_hover_textcolor", 0, $custom_link_hover_textcolor, true);
            setElementValue($custom_styles_node, "custom_nav_textcolor", 0, $custom_nav_textcolor, true);
            setElementValue($custom_styles_node, "custom_nav_hover_textcolor", 0, $custom_nav_hover_textcolor, true);
            setElementValue($custom_styles_node, "custom_nav_active_textcolor", 0, $custom_nav_active_textcolor, true);
            setElementValue($custom_styles_node, "custom_nav_active_bgcolor", 0, $custom_nav_active_bgcolor, true);
            setElementValue($custom_styles_node, "custom_bar_bgcolor", 0, $custom_bar_bgcolor, true);
            setElementValue($custom_styles_node, "custom_bar2_bgcolor", 0, $custom_bar2_bgcolor, true);
            setElementValue($custom_styles_node, "custom_bar_textcolor", 0, $custom_bar_textcolor, true);
            setElementValue($custom_styles_node, "custom_main_font_family", 0, stripslashes($custom_main_font_family), true);
            setElementValue($custom_styles_node, "custom_main_font_size", 0, $custom_main_font_size, true);
            setElementValue($custom_styles_node, "custom_nav_font_family", 0, stripslashes($custom_nav_font_family), true);
            setElementValue($custom_styles_node, "custom_nav_font_size", 0, $custom_nav_font_size, true);


            if ($startpage == "book:") {
                $startpage .= $startbook;
            }
            setElementValue($gallery_node, "startpage", 0, $startpage, true);
            setElementValue($gallery_node, "slideshow_interval", 0, $slideshow_interval, true);
            setElementValue($gallery_node, "resize_splash_images", 0, $resize_splash_images, true);
            setElementValue($gallery_node, "splash_image_resize_w", 0, $splash_image_resize_w, true);
            setElementValue($gallery_node, "splash_image_resize_h", 0, $splash_image_resize_h, true);

            setElementValue($gallery_node, "gallery_display", 0, $gallery_display, true);
            setElementValue($gallery_node, "gallery_thumb_ratio", 0, $gallery_thumb_ratio, true);
            setElementValue($gallery_node, "gallery_thumb_w", 0, $gallery_thumb_w, true);
            setElementValue($gallery_node, "gallery_thumb_h", 0, $gallery_thumb_h, true);
            setElementValue($gallery_node, "gallery_strip_h", 0, $gallery_strip_h, true);
            setElementValue($gallery_node, "enable_thickbox", 0, $enable_thickbox, true);
            setElementValue($gallery_node, "enable_slideshow", 0, $enable_slideshow, true);
            setElementValue($gallery_node, "enable_control_panel", 0, $enable_control_panel, true);
            setElementValue($gallery_node, "enable_exif_display", 0, $enable_exif_display, true);
            setElementValue($gallery_node, "enable_all_books_list", 0, $enable_all_books_list, true);

            setElementValue($gallery_node, "gallery_navigation_style", 0, $gallery_navigation_style, true);
            setElementValue($gallery_node, "navigation_image_format", 0, $navigation_image_format, true);

            /* Aliases */
            $aliases_element = $xpath->query("gallery/aliases")->item(0);
            $alias_template_node = $xpathtemplate->query("gallery/aliases/alias")->item(0);

            while ($aliases_element->hasChildNodes()) {
                $remove = $aliases_element->removeChild($aliases_element->firstChild);
            }

            for ($x = 0; $x < count($aliases); $x++) {
                $alias_node = $alias_template_node->cloneNode(true);
                //now place node in proper place
                $adopt = $dom->importNode($alias_node, true);
                $alias_node = $aliases_element->appendChild($adopt);

                setElementValue($alias_node, "domain", 0, $aliases[$x]['domain'], true);
                setElementValue($alias_node, "gallery_webpath", 0, $aliases[$x]['gallery_webpath'], true);
                setElementValue($alias_node, "media_path", 0, $aliases[$x]['media_path'], true);
                setElementValue($alias_node, "media_url", 0, $aliases[$x]['media_url'], true);
            }

            /* Books in Navigation Menu */
            $navigation_element = $xpath->query("gallery/navigation")->item(0);
            $navigation_template_node = $xpathtemplate->query("gallery/navigation/item")->item(0);

            while ($navigation_element->hasChildNodes()) {
                $remove = $navigation_element->removeChild($navigation_element->firstChild);
            }

            for ($x = 0; $x < count($nav_items); $x++) {
                $navigation_node = $navigation_template_node->cloneNode(true);
                //now place node in proper place
                $adopt = $dom->importNode($navigation_node, true);
                $navigation_node = $navigation_element->appendChild($adopt);

                setElementValue($navigation_node, "title", 0, $nav_items[$x]['title'], true);
                setElementValue($navigation_node, "folder", 0, $nav_items[$x]['folder'], true);
            }

            /* Metadata */
            $metadata_node = $xpath->query("gallery/metadata")->item(0);
            setElementValue($metadata_node, "meta_author", 0, $meta_author, true);
            setElementValue($metadata_node, "meta_company_name", 0, $meta_company_name, true);
            setElementValue($metadata_node, "meta_keywords", 0, $meta_keywords, true);
            setElementValue($metadata_node, "meta_description", 0, $meta_description, true);
            setElementValue($metadata_node, "meta_classification", 0, $meta_classification, true);

            /* Content */
            setElementValue($gallery_node, "footercontent", 0, stripslashes($footercontent), true);


            /* END UPDATE XML */

            //write modified xml to file
            if (is_writable($GLOBALS['config_xmlfile'])) {
                $saved = $dom->save($GLOBALS['config_xmlfile']);
                if (!$saved) {
                    print "Cannot write to file (" . $GLOBALS['config_xmlfile'] . ")";
                    exit;
                }
                //	print "Success, wrote to file ($xmlfile)";
            } else {
                print "The file " . $GLOBALS['config_xmlfile'] . " is not writable";
            }

        }
//		header("Location:.");
    }
} else {
    if ($action == "delete_nav_image") {
        $image_name = strtolower($_REQUEST['book'] . $_GET['state']);
        $image_path = $GLOBALS['path_to_root'] . "images/nav/" . $image_name;
        unlink($image_path . "." . $_SESSION['cm__gallery']['navigation_image_format']);
        header("Location:?action=edit_nav_images&success=true&book=" . $_REQUEST['book']);
    } elseif ($_GET['action'] == "remove_logo") {
        if (file_exists($GLOBALS['path_to_root'] . "images/" . $_SESSION['main_logo'])) {
            unlink($GLOBALS['path_to_root'] . "images/" . $_SESSION['main_logo']);
        }
        $gallery_node = $xpath->query("gallery")->item(0);
        setElementValue($gallery_node, "logo_name", 0, "", true);

        //write modified xml to file
        if (is_writable($GLOBALS['config_xmlfile'])) {
            $saved = $dom->save($GLOBALS['config_xmlfile']);
            if (!$saved) {
                print "Cannot write to file (" . $GLOBALS['config_xmlfile'] . ")";
                exit;
            }
            //	print "Success, wrote to file ($xmlfile)";
        } else {
            print "The file " . $GLOBALS['config_xmlfile'] . " is not writable";
        }
    } elseif ($action == "remove_splash_image") {
        $file = $_REQUEST['file'];
        $index = get_index($file);

        unlink($_SESSION['filepath'] . "images/splash/" . $file);
        unlink($_SESSION['path_db'] . "__splash/splashimage_" . $index . substr($file, strrpos($file, ".")));
        foreach (glob($GLOBALS['path_to_root'] . "images/splash/splashimage_*") as $image) {
            $current_index = get_index($image);
            if ($current_index > $index) {
                $new_index = ($current_index - 1) . "";
                if (strlen($new_index) == 1) $new_index = "0" . $new_index;
                $thumb = $_SESSION['path_db'] . "__splash/" . substr($image, strrpos($image, "/"));
                rename($image, preg_replace('splashimage_[0-9]{2}', 'splashimage_' . $new_index, $image));
                rename($thumb, preg_replace('splashimage_[0-9]{2}', 'splashimage_' . $new_index, $thumb));
            }
        }
        header("Location:?action=edit_splash_images&file=" . $_REQUEST['file']);
    } elseif ($action == "move_splash_image") {
        if ($_GET['new_file'] != "") {
            $file = $_SESSION['filepath'] . "images/splash/" . $_GET['file'];
            $new_file = $_SESSION['filepath'] . "images/splash/" . $_GET['new_file'];
            rename($file, $new_file . ".tmp");
            rename($new_file, $file);
            rename($new_file . ".tmp", $new_file);

            $thumb = $_SESSION['path_db'] . "__splash/" . substr($file, strrpos($file, "/"));
            $new_thumb = $_SESSION['path_db'] . "__splash/" . substr($new_file, strrpos($new_file, "/"));

            rename($thumb, $new_thumb . ".tmp");
            rename($new_thumb, $thumb);
            rename($new_thumb . ".tmp", $new_thumb);
        }

    } elseif ($action == "delete_alias") {

    } elseif ($action == "delete_nav_item") {

    } elseif ($action == "move_nav_item") {

    } elseif ($action == "delete_book") {

    } elseif ($action == "move_book") {

    }
}
