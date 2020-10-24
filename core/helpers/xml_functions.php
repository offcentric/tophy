<?php
function getXMLDOMObject($xmlfile){
    $dom = new DOMDocument();
    if(!$dom->load(realpath($xmlfile))){
        echo "error!!! xml doc invalid or not found at ";
        echo realpath($xmlfile);
        exit;
    }
    return $dom;
}

function saveXMLFile($xmlfile, $dom){
    //write modified xml to file
    if(is_writable($xmlfile)){
        if(!$dom->save($xmlfile)){
            print "Cannot write to file " . $xmlfile;
            return false;
            exit;
        }
    } else {
        print "The file " . $xmlfile . " is not writable";
        return false;
        exit;
    }
    return true;
}

//function to return element value by tagname
function getElementValue($parentelement, $tagname, $index, $defaultval){
    if($parentelement != null){
        if($parentelement->hasChildNodes()){
            $resultList = $parentelement->getElementsByTagName($tagname);
            if($resultList->item($index)){
                if($resultList->item($index)->textContent == ""){
                    return $defaultval;
                }else{
                    return $resultList->item($index)->textContent;
                }
            }else{
                return $defaultval;
            }
        }else{
            return $defaultval;
        }
    }else{
        echo "getElementValue() ERROR: tagname \"$tagname\" does not have a valid parentelement.<br />";
    }
}

//function to return element attribute value by tagname
function getAttributeValue($parentelement, $tagname, $index, $attname, $defaultval){
    if($parentelement != null){
        $resultList = $parentelement->getElementsByTagName($tagname);
//print_r($resultList->item($index));
        if($resultList->item($index) && $resultList->item($index)->nodeValue){
            if($resultList->item($index)->getAttribute($attname) == NULL || $resultList->item($index)->getAttribute($attname) == ""){
                return $defaultval;
            }else{
                return $resultList->item($index)->getAttribute($attname);
            }
        }
    }else{
        echo "getAttributeValue() ERROR:tagname \"$tagname\" does not have a valid parentelement.<br/>";
    }
}

//function to set element value by tagname
function setElementValue($parentelement, $tagname, $index, $newval,$create_if_missing = false){
    if($parentelement != null){
        $resultList = $parentelement->getElementsByTagName($tagname);
        if($resultList->item($index) && $resultList->item($index)->nodeValue){
            replace_content($resultList->item($index), $newval);
        }else{
            if($create_if_missing){
                $dom = $parentelement->ownerDocument;
                $node = $dom->createElement($tagname);
                $node->nodeValue = $newval;
                $node = $parentelement->appendChild($node);
            }else{
                echo "setElementValue() ERROR: tagname \"$tagname\" not found with index $index.<br />";
            }
        }
    }else{
        echo "setElementValue() ERROR:tagname \"$tagname\" does not have a valid parentelement.<br />";
    }
}

//function to set element attribute value by tagname
function setAttributeValue($parentelement, $tagname, $index, $attname, $newval){
    if($parentelement != null){
        $resultList = $parentelement->getElementsByTagName($tagname);
        if($resultList->item($index) && $resultList->item($index)->nodeValue){
            echo $resultList->item($index)->getAttribute($attname);
            $resultList->item($index)->setAttribute($attname,$newval);
        }else{
            echo "setAttributeValue() ERROR: tagname \"$tagname\" not found with index $index.<br />";
        }
    }else{
        echo "setAttributeValue() ERROR:tagname \"$tagname\" does not have a valid parentelement.<br />";
    }
}

function replace_content( $node, $new_content )
{
    $newnode = $node->cloneNode(true);
    if($newnode->childNodes->item(0)->nodeType=="4"){
        //check for CDATA node, preserve it if present
        $newnode->childNodes->item(0)->nodeValue = $new_content;
    }else{
        $newnode->nodeValue = $new_content;
    }
    $node->parentNode->replaceChild($newnode, $node);
}

/**
 * Pretty an XML string typically returned from DOMDocument->saveXML()
 *
 * Ignores ?xml !DOCTYPE !-- tags (adjust regular expressions and pad/indent logic to change this)
 *
 * @param string $xml the xml text to format
 * @param boolean $debug set to get debug-prints of RegExp matches
 * @returns string formatted XML
 * @copyright TJ
 * @link kml.tjworld.net
 */
function prettyXML($xml, $debug=false) {
    // add marker linefeeds to aid the pretty-tokeniser
    // adds a linefeed between all tag-end boundaries
    $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);

    // now pretty it up (indent the tags)
    $tok = strtok($xml, "\n");
    $formatted = ''; // holds pretty version as it is built
    $pad = 0; // initial indent
    $matches = array(); // returns from preg_matches()

    /* pre- and post- adjustments to the padding indent are made, so changes can be applied to
    * the current line or subsequent lines, or both
  */
    while($tok !== false) { // scan each line and adjust indent based on opening/closing tags
        // test for the various tag states
        if (preg_match('/.+<\/\w[^>]*>$/', $tok, $matches)) { // open and closing tags on same line
            if($debug) echo " =$tok= ";
            $indent=0; // no change
        }else if (preg_match('/^<\/\w/', $tok, $matches)) { // closing tag
            if($debug) echo " -$tok- ";
            $pad--; // outdent now
        }else if (preg_match('/^<\w[^>]*[^\/]>.*$/', $tok, $matches)) { // opening tag
            if($debug) echo " +$tok+ ";
            $indent=1; // don't pad this one, only subsequent tags
        }else{
            if($debug) echo " !$tok! ";
            $indent = 0; // no indentation needed
        }

        // pad the line with the required number of leading spaces
        $prettyLine = str_pad($tok, strlen($tok)+$pad, ' ', STR_PAD_LEFT);
        $formatted .= $prettyLine . "\n"; // add to the cumulative result, with linefeed
        $tok = strtok("\n"); // get the next token
        $pad += $indent; // update the pad size for subsequent lines
    }
    return $formatted; // pretty format
}

?>