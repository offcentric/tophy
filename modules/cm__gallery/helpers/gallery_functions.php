<?php
function get_gallery_intro($page)
{
    $intro = '';
    if (isset($page) || $page = "") {
        if (file_exists($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/intro.txt")) {
            $intro = file_get_contents($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/intro.txt");
        }
    } else {
        if (file_exists($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/" . $page . "/intro.txt")) {
            $intro = file_get_contents($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/" . $page . "/intro.txt");
        }
    }

    return $intro;
}

function get_previous_link($page, $html_template)
{
    $previouspage = "";
    if (preg_match("/^[0-9]+$/", $page, $matches) > 0) {
        $previouspage = intval($page) - 1;
        if ($previouspage < 10) {
            $previouspage = "0" . $previouspage;
        }
        if (is_dir($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/" . $previouspage . "/")) {
            preg_match("~\{\{image_link_start\}\}(.+)\{\{image_link_end\}\}(.*)\{\{text_link_start\}\}(.+)\{\{text_link_end\}\}~s", $html_template, $link_components);

            $item_content['link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] . "/" . $previouspage . "/";
            if ($_SESSION['cm__gallery']['navigation_style'] == "images") {
                if ($_SESSION['theme'] != "") {
                    $item_content['image'] = $_SESSION['webpath'] . "images/themes/" . $_SESSION['theme'] . "/paging/previous_" . $_SESSION['cm__gallery']['display'] . ".png";
                } else {
                    $item_content['image'] = $_SESSION['webpath'] . "images/paging/previous_" . $_SESSION['cm__gallery']['display'] . ".png";
                }
                return replace_template_placeholders($link_components[1], $item_content);
            } else {
                return replace_template_placeholders($link_components[3], $item_content);
            }
        }
    }
}

function get_next_link($page, $html_template)
{
    $nextpage = "";
    if (preg_match("/^[0-9]+$/", $page, $matches) > 0) {
        $nextpage = intval($page) + 1;
        if ($nextpage < 10) {
            $nextpage = "0" . $nextpage;
        }
        if (is_dir($_SESSION['media_path'] . $_SESSION['cm__gallery']['book'] . "/" . $nextpage . "/")) {
            preg_match("~\{\{image_link_start\}\}(.+)\{\{image_link_end\}\}(.*)\{\{text_link_start\}\}(.+)\{\{text_link_end\}\}~s", $html_template, $link_components);

            $item_content['link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $_SESSION['cm__gallery']['book'] . "/" . $nextpage . "/";
            if ($_SESSION['cm__gallery']['navigation_style'] == "images") {
                if ($_SESSION['theme'] != "") {
                    $item_content['image'] = $_SESSION['webpath'] . "images/themes/" . $_SESSION['theme'] . "/paging/next_" . $_SESSION['cm__gallery']['display'] . ".png";
                } else {
                    $item_content['image'] = $_SESSION['webpath'] . "images/paging/next_" . $_SESSION['cm__gallery']['display'] . ".png";
                }
                return replace_template_placeholders($link_components[1], $item_content);
            } else {
                return replace_template_placeholders($link_components[3], $item_content);
            }
        }
    }
}


function get_gallery_html($page, $html_template)
{
    $pagehtml = '';
    $suffix = '';

    $book = $_SESSION['cm__gallery']['book'];
    if ($page != null) {
        $images_path = $_SESSION['media_path'] . $book . "/" . $page . "/";
        $images_webpath = $_SESSION['media_webpath'] . $book . "/" . $page . "/";
        $resize_path_part = "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/" . $page . "/";
    } else {
        $images_path = $_SESSION['media_path'] . $book . "/";
        $images_webpath = $_SESSION['media_webpath'] . $book . "/";
        $resize_path_part = "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/";
    }

    $resize_path = $_SESSION['path_db'] . $resize_path_part;
    $resize_webpath = $_SESSION['webpath_db'] . $resize_path_part;

    if (!is_dir($_SESSION['path_db'])) {
        mkdir($_SESSION['path_db']);
    }

    if (is_dir($images_path)) {
        if (!is_dir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/")) mkdir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/");
        if (!is_dir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/")) mkdir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/");
        if ($page != null) {
            if (!is_dir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/" . $page . "/")) mkdir($_SESSION['path_db'] . "__" . $_SESSION['cm__gallery']['display'] . "/" . $book . "/" . $page . "/");
        }
    } else {
        echo "<div style=\"text-align:center;padding:25px;\">directory $images_webpath does not exist!</div>";
        die();
    }
    $pattern = "*\.[Jj][Pp][Gg]";
    $item_content['count'] = 0;
    $item_content['book'] = str_replace("'", "", $book);

    foreach (glob($images_path . $pattern, GLOB_BRACE) as $file) {
        $item_content['exif_details'] = "";
        $filename = substr($file, strlen($images_path));
        if (!is_dir($file) && substr($filename, 0, 12) != "__coverimage") {
            if (!is_dir($file)) {
                $item_content['photo_page_link'] = $_SESSION['webpath'] . $_SESSION['cm__gallery']['webpath'] . $book . ($page == null ? "" : "/" . $page) . "/photo:" . $filename;
                $item_content['image_link'] = $images_webpath . $filename;
                if (preg_match("/https?:\/\//", $item_content['image_link'], $matches) === false) $item_content['image_link'] = "http://" . $item_content['image_link'];

                if ($_SESSION['cm__gallery']['enable_exif_display']) {
                    $exif = get_EXIF_JPEG($file);
                    //print_r($exif);
                    $exif_date = get_EXIF_tag_value($exif, "Date and Time of Original");
                    $exif_shutterspeed = get_EXIF_tag_value($exif, "Exposure Time");
                    $exif_aperture = get_EXIF_tag_value($exif, "Aperture F Number");
                    $exif_make = get_EXIF_tag_value($exif, "Make (Manufacturer)");
                    $exif_model = get_EXIF_tag_value($exif, "Model");
                    $exif_flash = get_EXIF_tag_text_value($exif, "Flash");
                    $exif_focal_lenth = get_EXIF_tag_value($exif, "FocalLength");
                    $exif_iso = get_EXIF_tag_text_value($exif, "ISO Speed Ratings");

                    if ($exif_date != "") {
                        $exif_date = substr($exif_date, 8, 2) . "/" . substr($exif_date, 5, 2) . "/" . substr($exif_date, 0, 4);
                        $item_content['exif_details'] = add_info_to_panel("Date", $exif_date);
                        $item_content['exif_details'] .= add_info_to_panel("Camera", $exif_model);
                        if ($exif_shutterspeed){
                            if(strpos($exif_shutterspeed, "/") !== false){
                                if(substr($exif_shutterspeed, strpos($exif_shutterspeed, "/") + 1, 10) != 0) {
                                    $calculated_shutter_speed = (int)substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/")) / (int)substr($exif_shutterspeed, strpos($exif_shutterspeed, "/") + 1, 10);
                                    if ($calculated_shutter_speed > 1) {
                                        $exif_shutterspeed = $calculated_shutter_speed;
                                    } else if (substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/")) == "10") {
                                        $exif_shutterspeed = ((int)substr($exif_shutterspeed, 0, strpos($exif_shutterspeed, "/")) / 10) . "/" . ((int)substr($exif_shutterspeed, strpos($exif_shutterspeed, "/") + 1, 10) / 10);
                                    }
                                }
                            }
                        }
                        if ($exif_shutterspeed != "" && $exif_aperture != "") $item_content['exif_details'] .= add_info_to_panel("Exposure", $exif_shutterspeed . "s at f/" . $exif_aperture);
                        else if ($exif_aperture != "") $item_content['exif_details'] .= add_info_to_panel("Aperture", "f/" . $exif_aperture);
                        else if ($exif_shutterspeed != "") $item_content['exif_details'] .= add_info_to_panel("Exposure", $exif_shutterspeed . "s");
                        else $item_content['exif_details'] .= add_info_to_panel("Exposure", "");
                        //if($exif_flash != "") $item_content[exif_details] .= add_info_to_panel("Flash", $exif_flash);	
                        if ($exif_focal_lenth != "") $item_content['exif_details'] .= add_info_to_panel("Focal Length", $exif_focal_lenth . "mm");
                        else $item_content['exif_details'] .= add_info_to_panel("Focal Length", $exif_focal_lenth);
                        if ($exif_iso != "") $item_content['exif_details'] .= add_info_to_panel("ISO Rating", $exif_iso);
                    }
                }
                $item_content['title'] = str_replace(".jpg", "", $filename);
                $item_content['title'] = str_replace("_", " ", $item_content['title']);
                $item_content['title'] = preg_replace("/([0-9]+\s)([a-zA-Z0-9\'\"\s]+)/", "$2", $item_content['title']);
                if ($_SESSION['cm__gallery']['display'] == "thumbnails") {
                    preg_match("~(.*)\{\{thickbox_on_start\}\}(.+)\{\{thickbox_on_end\}\}(.*)\{\{thickbox_off_start\}\}(.+)\{\{thickbox_off_end\}\}(.*)\{\{exif_display_start\}\}(.+)\{\{exif_display_end\}\}(.*)~s", $html_template, $item_html_components);
                    $suffix = "_" . $_SESSION['cm__gallery']['thumb_ratio'];
                    $resizename = str_replace(' ', '_',substr($filename, 0, stripos($filename, ".jpg")) . $suffix . ".jpg");
                    $pagehtml .= $item_html_components[1];
                    if (file_exists($resize_path . $resizename)) {
                        $timediff = time() - (filectime($resize_path . $resizename));
                        $daysdiff = $timediff / 3600 / 24;
                    }
                    if (!file_exists($resize_path . $resizename) || $daysdiff > $_SESSION['cm__gallery']['resize_maxage']) {
                        resizeimg($file, $resize_path . $resizename, $_SESSION['cm__gallery']['thumb_w'], $_SESSION['cm__gallery']['thumb_h'], $_SESSION['cm__gallery']['thumb_ratio']);
                    }
                    //chmod($resize_path . $resizename, 0644);
                    $item_content['thumbnail'] = $resize_webpath . $resizename;
                    $item_content['thumbnail'] = str_replace("#", "%23", $item_content['thumbnail']);
                    if ($_SESSION['cm__gallery']['enable_thickbox']) {
                        $pagehtml .= replace_template_placeholders($item_html_components[2], $item_content);
                    } else {
                        $pagehtml . replace_template_placeholders($item_html_components[4], $item_content);
                    }
                    if ($_SESSION['cm__gallery']['enable_exif_display']) {
                        $pagehtml .= replace_template_placeholders($item_html_components[6], $item_content);
                    }
                    $pagehtml .= $item_html_components[7];
                } else {
                    preg_match("~(.*)\{\{item_start\}\}(.+)\{\{item_end\}\}(.*)~s", $html_template, $item_html_components);
                    $resizename = str_replace(' ', '_', substr($filename, 0, strpos($filename, ".jpg")) . $suffix . ".jpg");
                    if (file_exists($resize_path . $resizename)) {
                        $timediff = time() - (filectime($resize_path . $resizename));
                        $daysdiff = $timediff / 3600 / 24;
                    }
                    if (!file_exists($resize_path . $resizename) || $daysdiff > $_SESSION['cm__gallery']['resize_maxage']) {
                        if ($_SESSION['cm__gallery']['display'] == "strip") {
                            resizeimg($file, $resize_path . $resizename, 0, $_SESSION['cm__gallery']['strip_h'], "constrain_h");
                        } else {
                            resizeimg($file, $resize_path . $resizename, $_SESSION['cm__gallery']['full_w'], $_SESSION['cm__gallery']['full_h'], "constrain_both");

                        }
                    }
                    $item_content['image'] = $resize_webpath . $resizename;
                    $item_content['image'] = str_replace("#", "%23", $item_content['image']);
                    $pagehtml .= replace_template_placeholders($html_template, $item_content);

                }
                $item_content['count']++;
            }
        }
    }

    //cleanup old thumbs
    clean_thumbs($resize_path);

    return $pagehtml;
}

function clean_thumbs($resize_path)
{
    foreach (glob($resize_path . "*.jpg") as $resize_file) {
        $timediff = time() - (filectime($resize_file));
        if ($timediff / 3600 / 24 > $_SESSION['cm__gallery']['resize_maxage']) {
            unlink($resize_file);
        }
    }
}


function ee_extract_exif_from_pscs_xmp($filename, $printout = 0)
{

    // very straightforward one-purpose utility function which
    // reads image data and gets some EXIF data (what I needed) out from its XMP tags (by Adobe Photoshop CS)
    // returns an array with values
    // code by Pekka Saarinen http://photography-on-the.net

    ob_start();
    readfile($filename);
    $source = ob_get_contents();
    ob_end_clean();

    $xmpdata_start = strpos($source, "<x:xmpmeta");
    $xmpdata_end = strpos($source, "</x:xmpmeta>");
    $xmplenght = $xmpdata_end - $xmpdata_start;
    $xmpdata = substr($source, $xmpdata_start, $xmplenght + 12);
    $xmp_parsed = array();

    $regexps = array(
        array("name" => "DC creator", "regexp" => "/<dc:creator>\s*<rdf:Seq>\s*<rdf:li>(.+)<\/rdf:li>\s*<\/rdf:Seq>\s*<\/dc:creator>/"),
        array("name" => "TIFF camera model", "regexp" => "/<tiff:Model>(.+)<\/tiff:Model>/"),
        array("name" => "TIFF maker", "regexp" => "/<tiff:Make>(.+)<\/tiff:Make>/"),
        array("name" => "EXIF exposure time", "regexp" => "/<exif:ExposureTime>(.+)<\/exif:ExposureTime>/"),
        array("name" => "EXIF f number", "regexp" => "/<exif:FNumber>(.+)<\/exif:FNumber>/"),
        array("name" => "EXIF aperture value", "regexp" => "/<exif:ApertureValue>(.+)<\/exif:ApertureValue>/"),
        array("name" => "EXIF exposure program", "regexp" => "/<exif:ExposureProgram>(.+)<\/exif:ExposureProgram>/"),
        array("name" => "EXIF iso speed ratings", "regexp" => "/<exif:ISOSpeedRatings>\s*<rdf:Seq>\s*<rdf:li>(.+)<\/rdf:li>\s*<\/rdf:Seq>\s*<\/exif:ISOSpeedRatings>/"),
        array("name" => "EXIF datetime original", "regexp" => "/<exif:DateTimeOriginal>(.+)<\/exif:DateTimeOriginal>/"),
        array("name" => "EXIF exposure bias value", "regexp" => "/<exif:ExposureBiasValue>(.+)<\/exif:ExposureBiasValue>/"),
        array("name" => "EXIF metering mode", "regexp" => "/<exif:MeteringMode>(.+)<\/exif:MeteringMode>/"),
        array("name" => "EXIF focal length", "regexp" => "/<exif:FocalLength>(.+)<\/exif:FocalLength>/"),
        array("name" => "AUX lens", "regexp" => "/<aux:Lens>(.+)<\/aux:Lens>/")
    );

    foreach ($regexps as $key => $k) {
        $name = $k["name"];
        $regexp = $k["regexp"];
        unset($r);
        preg_match($regexp, $xmpdata, $r);
        $xmp_item = "";
        $xmp_item = @$r[1];
        $xmp_parsed[$name] = $xmp_item;
    }

    if ($printout == 1) {
        foreach ($xmp_parsed as $key => $k) {
            $item = $k["item"];
            $value = $k["value"];
            print "<br><b>" . $item . ":</b> " . $value;
        }
    }

    return ($xmp_parsed);
}

function get_EXIF_tag_text_value($EXIF_array, $tag_name)
{
    $tag_value = "";
    $exif_index = -1;
    if (is_array($EXIF_array)) {
        if (is_array($EXIF_array[0])) {
            foreach ($EXIF_array[0] as $key => $entry) {
                if(!is_array($entry) || !array_key_exists("Tag Name", $entry))
                    continue;
                if ($entry["Tag Name"] == $tag_name) {
                    $tag_value = $entry["Text Value"];
                } else if ($entry["Tag Name"] == "EXIF Image File Directory (IFD)") {
                    $exif_index = $key;
                }
            }
            if ($tag_value == "") {
                if (is_array($EXIF_array[0][$exif_index])) {
                    if (is_array($EXIF_array[0][$exif_index]["Data"])) {
                        if (is_array($EXIF_array[0][$exif_index]["Data"][0])) {
                            foreach ($EXIF_array[0][$exif_index]["Data"][0] as $value_key => $data_value) {
                                if(!is_array($data_value) || !array_key_exists("Tag Name", $data_value))
                                    continue;
                                if ($data_value["Tag Name"] == $tag_name) {
                                    $tag_value = $data_value["Text Value"];
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $tag_value;
}


function get_EXIF_tag_value($EXIF_array, $tag_name)
{
//	print_r($EXIF_array);
    $tag_value = "";
    $exif_index = -1;
    if (is_array($EXIF_array)) {
        $exif_data = $EXIF_array[0];
        if (is_array($exif_data)) {
            foreach ($exif_data as $key => $entry) {
				if(!is_array($entry) || !array_key_exists("Tag Name", $entry))
					continue;
                if ($entry["Tag Name"] == $tag_name) {
                    $tag_value = get_tag_value($entry);
                } else if ($entry["Tag Name"] && $entry["Tag Name"] == "EXIF Image File Directory (IFD)") {
                    $exif_index = $key;
                }
            }
            if ($tag_value == "") {
                if (is_array($exif_data[$exif_index])) {
                    if (is_array($exif_data[$exif_index]["Data"])) {
                        if (is_array($exif_data[$exif_index]["Data"][0])) {
                            foreach ($exif_data[$exif_index]["Data"][0] as $key => $ifd_tag) {
                                if(!is_array($ifd_tag) || !array_key_exists("Tag Name", $ifd_tag))
                                    continue;
                                if ($ifd_tag["Tag Name"] == $tag_name) {
                                    $tag_value = get_tag_value($ifd_tag);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $tag_value;
}

function get_tag_value($entry)
{
    $value_data = $entry["Data"];
    if (is_array($value_data)) {
        if (sizeof($value_data) < 2) {
            if (is_array($value_data[0])) {
                $numerator = "";
                $denominator = "";
                foreach ($value_data[0] as $value_key => $data_value) {
                    if ($value_key == "Numerator") {
                        $numerator = $data_value;
                    }
                    if ($value_key == "Denominator") {
                        $denominator = $data_value;
                    }
                }
                $tag_value = parse_fraction($numerator, $denominator);
            } else {
                $tag_value = $value_data[0];
            }
        } else {
            $tag_value = array();
            foreach ($value_data as $data_entry) {
                if (is_array($data_entry)) {
                    $numerator = "";
                    $denominator = "";
                    foreach ($data_entry as $value_key => $data_value) {
                        if ($value_key == "Numerator") {
                            $numerator = $data_value;
                        }
                        if ($value_key == "Denominator") {
                            $denominator = $data_value;
                        }
                    }
                    $tag_value[sizeof($tag_value)] = $tag_value = parse_fraction($numerator, $denominator);
                } else {
                    $tag_value[sizeof($tag_value)] = $data_entry;
                }
            }
        }
    }
    return $tag_value;
}

function add_info_to_panel($left_column, $right_column)
{
    if(!$right_column)
        return '';
    $out = "<div class=\"exif_info_column clearfix\">";
    $out .= "<div class=\"left_column\"><span>" . $left_column . "</span></div>";
    $out .= "<div class=\"right_column\"><span>" . $right_column . "</span></div>";
    $out .= "</div>";
    return $out;
}
