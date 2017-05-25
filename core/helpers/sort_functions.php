<?php
// create class for sorting nested child nodes using multidimensional arrays
class mdasort {
	var $aData;
	var $aSortkeys;
	var $y = 0;
	
	function _sortcmp($a, $b) {
		$sortby = $this->aSortkeys["sortby"];
		$sortorder =  $this->aSortkeys["sortorder"];
		if(strtoupper($sortorder) == "DESC"){
			$sortorder = -1;
		}else{
			$sortorder = 1;
		}
		$children = $a->childNodes;
			for($i=0; $i < $children->length; $i++){
				if($children->item($i)->nodeName == $sortby){
					$da = $children->item($i)->textContent;
					$children_b = $b->childNodes;
				if($children_b->item($i)->nodeName != $sortby){
					for($y=0;$y<$children_b->length;$y++){
						if($children_b->item($i)->nodeName == $sortby){
							break;
						}
					}
				}else{
					$y=$i;
				}
				$db = $children_b->item($i)->textContent;
				if($sortby == "date"){
					return strcmp(intval($da), intval($db)) * $sortorder;
				}else{
					return strcmp(strtolower($da), strtolower($db)) * $sortorder;
				}
			}
	   }
	}
	
	// this function calls php's built-in usort function using sortcmp function to compare
   	function sort() {
       if(count($this->aSortkeys)) {
		   usort($this->aData,array($this,"_sortcmp"));
	   }
   }
}

function multisort($array){
   for($i = 1; $i < func_num_args(); $i += 3){
       $key = func_get_arg($i);
       if (is_string($key)) $key = '"'.$key.'"';

       if($i + 1 < func_num_args()) $order = func_get_arg($i + 1);

       $type = 0;
       if($i + 2 < func_num_args()) $type = func_get_arg($i + 2);

       switch($type){
           case 1: // Case insensitive natural.
               $t = 'strcasecmp($a[' . $key . '], $b[' . $key . '])';
               break;
           case 2: // Numeric.
               $t = '($a[' . $key . '] == $b[' . $key . ']) ? 0:(($a[' . $key . '] < $b[' . $key . ']) ? -1 : 1)';
               break;
           case 3: // Case sensitive string.
               $t = 'strcmp($a[' . $key . '], $b[' . $key . '])';
               break;
           case 4: // Case insensitive string.
               $t = 'strcasecmp($a[' . $key . '], $b[' . $key . '])';
               break;
           case 5: // Case sensitive natural.
               $t = 'strnatcmp($a[' . $key . '], $b[' . $key . '])';
               break;
           default: // Case insensitive natural.
               $t = 'strnatcasecmp($a[' . $key . '], $b[' . $key . '])';
               break;
       }
       usort($array, create_function('$a, $b', '; return ' . ($order=="desc" ? '-' : '') . '(' . $t . ');'));
   }
   return $array;
}

?>