<?php
/************************************************************************
Form CLASS
Version: 1.0
Author: Mark Mulder (http://offcentric.com)
Copyright 2006 under GPL (http://www.gpl.org)
Description: 	Loads form definiton data from XML file, 
				generates form, valdation, handler classes 
				for storing results in database and/or 
				sending results by email
Requirements:	PHP5 (makes use of DOM for parsing XML), MySQL4-5 (in case
				of storing form submissions in a database)
************************************************************************/

class Form{
	
	var $items;
	var $settings;
	var $errText;
	var $errs;
	var $xpath;
	var $answers;
	var $dbCn;
	var $dbName;
	var $tblName;
	var $formData;

/******** readFormData() PARSES FORM XML DOCUMENT AND LOADS DATA INTO items ARRAY **********/
	function readFormData($xmlfile){
		$dom = new DOMDocument();
		if(!$dom->load(realpath($xmlfile))){
			echo "error!!! xml doc invalid or not found at ";
			echo realpath($xmlfile);
			exit;
		}else{
			// get new xpath context
			$this->xpath = new DOMXPath($dom);
			$xpresult = $this->xpath->query("/form/questions/item");
			for($i = 0; $i < $xpresult->length; $i++){
				$node = $xpresult->item($i);
				$this->items[] = $this->addQuestion($node);
			}
			$this->loadSettings();
			$this->loadErrors();
		}
	}

/******* loadSettings() RETRIEVES GENERAL SETTINGS WHICH APPLY TO THE APPLICATION AND LOADS INTO settings ARRAY *****/
	function loadSettings(){
		$xpresult = $this->xpath->query("/form/settings");
		$node = $xpresult->item(0);
		foreach($node->childNodes as $setting){
			$this->settings[$setting->nodeName] = $setting->nodeValue;
		}
	}

/******* loadErrors() RETRIEVES ERROR MESSAGES FROM XML AND PUTS THEM IN errors ARRAY *****/
	function loadErrors(){
		if($this->settings[mustValidate]){
			$xpresult = $this->xpath->query("/form/errors");
			$node = $xpresult->item(0);
			foreach($node->childNodes as $error){
				if($error->nodeType != 3){
					$this->errText[$error->getAttribute("type")] = $error->nodeValue;
				}
			}
		}
	}

/****** dbConntect() INSTANTIATES mysql_connect OBJECT FOR STORING FORM SUBMISSIONS TO DATABASE ******/
	function dbConnect($db, $user, $pass){
		if($this->settings[storeInDb]){
			$this->dbName = $db;
			$hostname = "localhost";
			$this->tblName = "form_data";
			$create_tbl_if_missing = false;
			if(func_num_args()>3){
				$hostname = func_get_arg(3);
				if(func_num_args()>4){
					$this->tblName = func_get_arg(4);
					if(func_num_args()>5){
						$create_tbl_if_missing = func_get_arg(5);
					}
				}
			}
			$cnMS = mysql_connect($hostname,$user,$pass);
			mysql_select_db($this->dbName);
			$this->dbCn = $cnMS;
			if($create_tbl_if_missing) $this->createFormTable();
		}
	}

/****** createFormTable() CREATES THE TABLE USED TO STORE FORM DATA IF IT IS MISSING *******/
	function createFormTable(){
		$sql = 	"CREATE TABLE IF NOT EXISTS `" . $this->dbName . "`.`" . $this->tblName . "`(";	
		$sql .=	" `id` int(10) unsigned NOT NULL auto_increment, `timestamp` varchar(45) NOT NULL default '', `ip_address` varchar(45) NOT NULL default '', `data` text";
		foreach($this->items as $item){
			if($item[type]!="text"){
				if($item[type]=="textarea"){
					$dataType = "TEXT";
				}else{
					$dataType = "varchar(64)";
				}
				$sql .= ", `question_" . $item[id] . "` " . $dataType . " NULL default ''";
			}
		}
		$sql .=	", PRIMARY KEY  (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		mysql_query($sql);
	}

/******* addQuestion() HANDLES INDIVIDUAL QUESTION NODES FROM XML DATA AND RETURNS DATA TO readFormData() *******/
	function addQuestion($node){
		$question[type] = $node->getAttribute("type");
		$question[number] = $node->getAttribute("number");
		$question[id] = $node->getAttribute("id");
		if($question[type]=="text"){
			$question[text] = getElementValue($node, "text", 0 , "");
		}else{
			foreach($node->childNodes as $childnode){
				if($childnode->nodeName == "answers"){
					$answernode = $childnode;
					$question[required] = (boolean) $answernode->getAttribute("required");
					break;
				}
			}
			$question[text] = getElementValue($node, "question", 0 , "");
			$question[answernode] = $answernode;
			$out = "		<div class=\"answers clearfix\" id=\"answer_" . $question[id] . "\">\n";
			switch($question[type]){
				case "radio":
					$out .= $this->getRadioAnswers($answernode, $question[id]);
					break;
				case "yesno":
					$out .= $this->getYesNoAnswers($answernode, $question[id]);
					break;
				case "checkbox":
					$out .= $this->getCheckboxAnswers($answernode, $question[id]);
					$question[numchoices] = $answernode->getAttribute("numchoices");
					break;
				case "rating":
					$out .= $this->getRatingAnswers($answernode, $question[id]);
					break;
				case "multirating":
					$out .= $this->getMultiRatingAnswers($answernode, $question[id]);
					break;
				case "textinput":
				case "email":
					$out .= $this->getTextInput($answernode, $question[id]);
					break;
				case "date":
					$out .= $this->getDateInput($answernode, $question[id]);
					break;
				case "textarea":
					$out .= $this->getTextarea($answernode, $question[id]);
					break;
				case "select-single":
					$out .= $this->getSelectSingle($answernode, $question[id]);
					break;
				case "select-multiple":
					$out .= $this->getSelectMultiple($answernode, $question[id]);
					break;
				default:
					$out .= "";
			}
			$out .= "		</div>\n";
			$question[answer] = $out;
		}
		return $question;
	}

/****** getRadioAnswers() RETURNS XHTML FOR A QUESTION WITH RADIO BUTTON ANSWERS (MULTIPLE CHOICE, 1 ANSWER ALLOWED) TO addQuestion() ******/
	function getRadioAnswers($node, $index){
		$out = "";
		$i = 0;
		$perrow = $node->getAttribute("perrow");
		if($perrow == NULL OR $perrow == ""){$perrow = "1";}
		foreach($node->childNodes as $childnode){
			if($childnode->nodeType != 3){
				$rowClosed = false;
				$opentext = "";
				$disabled = "true";
				if($i==0 OR fmod($i, $perrow) == 0){$out .= "			<div class=\"answer_row clearfix\">\n";}
				if($childnode->getAttribute("open")=="true"){
					$opentext = "<input disabled=\"disabled\" id=\"question_" . $index . "_" . $childnode->getAttribute("id") . "_text\" class=\"open_answer\" type=\"text\" name=\"question_" . $index . "[]['openanswer']\" />";
					$disabled = "false";
				}
				$out .= "				<div class=\"answer perrow_" . $perrow . "\">\n";
				$out .= "					<input type=\"radio\" class=\"radio\" id=\"question_" . $index . "_" . $childnode->getAttribute("id") . "\" name=\"question_" . $index . "[]\" value=\"" . $childnode->getAttribute("id") . "\" onclick=\"if(this.checked)disableOpenText(this)\" />";
				$out .= "<div class=\"answer_text\">" . getElementValue($node, "answer", $i , "[[ question missing ]]");
				$out .= $opentext;
				$out .= "</div>\n";
				$out .= "				</div>\n";
				$i++;
				if(fmod($i, $perrow) == 0){
					$rowClosed = true;
					$out .= "			</div>\n";
				}
			}
		}
		if(!$rowClosed){ $out .= "		</div>\n";}
		return $out;
	}

/****** getYesNoAnswers() RETURNS XHTML FOR A QUESTION WITH YES/NO ANSWERS TO addQuestion() ******/
	function getYesNoAnswers($node, $index){
		$out = "";
		$out .= "			<div class=\"answer yesno\"><input type=\"radio\" name=\"question_" . $index . "[]\" value=\"yes\" />Ja</div>\n";
		$out .= "			<div class=\"answer yesno\"><input type=\"radio\" name=\"question_" . $index . "[]\" value=\"no\" />Nee</div>\n";
		return $out;
	}

/****** getCheckboxAnswers() RETURNS XHTML FOR A QUESTION WITH CHECKBOX ANSWERS (MULTIPLE CHOICE, >1 ANSWERS ALLOWED) TO addQuestion() ******/
	function getCheckboxAnswers($node, $index){
		$out = "";
		$i = 0;
		$perrow = $node->getAttribute("perrow");
		$numchoices = $node->getAttribute("numchoices");
		if($perrow == NULL OR $perrow == ""){$perrow = "1";}
		if($numchoices == NULL OR $numchoices == ""){$numchoices = "3";}
		foreach($node->childNodes as $childnode){
			if($childnode->nodeType != 3){
				$rowClosed = false;
				$opentext = "";
				$disableText = "";
				if($i==0 OR fmod($i, $perrow) == 0){$out .= "			<div class=\"answer_row clearfix\">\n";}
				if($childnode->getAttribute("open")=="true"){
					$opentext = "<input disabled=\"disabled\" id=\"question_" . $index . "_text\" class=\"open_answer\" type=\"text\" name=\"question_" . $index . "[]['openanswer']\" />";
					$disableText = "disableOpenText(this)";
				}
				$out .= "				<div class=\"answer perrow_" . $perrow . "\">\n";
				$out .= "					<input type=\"checkbox\" class=\"checkbox\" name=\"question_" . $index . "[]\" value=\"" . $childnode->getAttribute("id") . "\" onclick=\"checkTopChoices(" . $numchoices . "," . $index . ",this);" . $disableText . "\" />";
				$out .= "<div class=\"answer_text\">" . getElementValue($node, "answer", $i , "[[ question missing ]]");
				$out .= $opentext;
				$out .= "</div>\n";
				$out .= "				</div>\n";
				$i++;
				if(fmod($i, $perrow) == 0){
					$rowClosed = true;
					$out .= "			</div>\n";
				}
			}
		}
		if(!$rowClosed){ $out .= "			</div>\n";}
		return $out;
	}

/****** getRatingAnswers() RETURNS XHTML FOR A QUESTION WITH A RATING (1-5) TO addQuestion() ******/
	function getRatingAnswers($node, $index){
		$out = "";
		foreach($node->childNodes as $childnode){
			if($childnode->nodeType != 3){
				if($childnode->getAttribute("rating")=="max"){
					$maxText = $childnode->textContent;
				}else if($childnode->getAttribute("rating")=="min"){
					$minText = $childnode->textContent;
				}
			}
		}
		$out .= "			<div class=\"answer\">";
		$out .= "				" . $minText . "\n";
		$out .= "				<input type=\"radio\" name=\"question_" . $index . "[]\" value=\"1\" />\n";
		$out .= "				<input type=\"radio\" name=\"question_" . $index . "[]\" value=\"2\" />\n";
		$out .= "				<input type=\"radio\" name=\"question_" . $index . "[]\" value=\"3\" />\n";
		$out .= "				<input type=\"radio\" name=\"question_" . $index . "[]\" value=\"4\" />\n";
		$out .= "				<input type=\"radio\" name=\"question_" . $index . "[]\" value=\"5\" />\n";
		$out .= "				" . $maxText . "\n";
		$out .= "			</div>";
		return $out;
	}

	/****** getRatingAnswers() RETURNS XHTML FOR A QUESTION WITH A MULTIPLE POINTS OF RATING (1-5) TO addQuestion() ******/
		function getMultiRatingAnswers($node, $index){
			$out = "";
			$i = 0;
			$minText = $node->getAttribute("min");
			$maxText = $node->getAttribute("max");
			$out .= "(1 = " . $minText . ", 5 = " . $maxText . ")<br />\n";
			$out .= "<table>\n";
			$out .= "	<tr><td></td>";
			for($x=1;$x<=5;$x++){
				$out .= "<td style=\"text-align:center\">" . $x . "</td>";
			}
			$out .= "</tr>\n";
			foreach($node->childNodes as $childnode){
				if($childnode->nodeType != 3){
					$out .= "			<tr>\n";
					$out .= "				<td style=\"text-align:right\">" . getElementValue($node, "answer", $i , "[[ question missing ]]") . "</td>\n";
					$out .= "				<td><input type=\"radio\" name=\"question_" . $index . "[$i][]\" value=\"1\" /></td>\n";
					$out .= "				<td><input type=\"radio\" name=\"question_" . $index . "[$i][]\" value=\"2\" /></td>\n";
					$out .= "				<td><input type=\"radio\" name=\"question_" . $index . "[$i][]\" value=\"3\" /></td>\n";
					$out .= "				<td><input type=\"radio\" name=\"question_" . $index . "[$i][]\" value=\"4\" /></td>\n";
					$out .= "				<td><input type=\"radio\" name=\"question_" . $index . "[$i][]\" value=\"5\" /></td>\n";
					$out .= "			</tr>\n";
					$i++;
				}
			}
			$out .= "</table>\n";
			return $out;
		}

/****** getTextInput() RETURNS XHTML FOR A QUESTION WITH OPEN TEXT INPUT (SINGLE LINE) TO addQuestion() ******/
	function getTextInput($node, $index){
		$out = "";
		foreach($node->childNodes as $childnode){
			if($childnode->nodeType != 3){
				$fieldWidth = $childnode->getAttribute("width");
				break;
			}
		}
		if($fieldWidth==0 OR $fieldWidth == "" OR $fieldWidth == null){$fieldWidth = 200;}
		$out .= "			<div class=\"answer\">";
		$out .= "			<input style=\"width:" . $fieldWidth . "px\" type=\"text\" class=\"text\" name=\"question_" . $index . "\" value=\"\" />\n";
		$out .= "			</div>";
		return $out;
	}

/****** getTextarea() RETURNS XHTML FOR A QUESTION WITH TEXTAREA TO addQuestion() ******/
	function getTextarea($node, $index){
/// STILL GOTTA DO THIS ONE
		$out = "";
		return $out;
	}

/****** getSelectSingle() RETURNS XHTML FOR A QUESTION WITH SINGLE SELECT LIST (DROP DOWN) TO addQuestion() ******/
	function getSelectSingle($node, $index){
/// STILL GOTTA DO THIS ONE
		$out = "";
		return $out;
	}

/****** getSelectMultiple() RETURNS XHTML FOR A QUESTION WITH MULIPLE SELECT LIST TO addQuestion() ******/
	function getSelectMultiple($node, $index){
/// STILL GOTTA DO THIS ONE
		$out = "";
		return $out;
	}

/****** getDateInput() RETURNS XHTML FOR A QUESTION WITH DATE INPUT TO addQuestion() ******/
	function getDateInput($node, $index){
		foreach($node->childNodes as $childnode){
			if($childnode->nodeName=="minyear"){
				$minYear = $childnode->nodeValue;
			}else if($childnode->nodeName=="maxyear"){
				$maxYear = $childnode->nodeValue;
			}
		}
		$out = "";
		$out .= "			<div class=\"answer\">";
		$out .= "				<select id=\"question_" .$index . "_day\" name=\"question_" .$index . "[]\">\n";
		for($x=1;$x<=31;$x++){
			$out .= "					<option value=\"$x\">$x</option>\n";
		}
		$out .= "				</select>\n";
		$out .= "				<select onchange=\"updateDays($index)\" id=\"question_" .$index . "_month\" name=\"question_" .$index . "[]\">\n";
		for($x=1;$x<=12;$x++){
			$out .= "					<option value=\"$x\">$x</option>\n";
		}
		$out .= "				</select>\n";
		$out .= "				<select onchange=\"updateDays($index)\" id=\"question_" .$index . "_year\" name=\"question_" .$index . "[]\">\n";
		$count = 0;
		for($x=$minYear;$x<=$maxYear;$x++){
			if($count == intval(($maxYear-$minYear)/2)){$selected = " selected=\"selected\"";}else{$selected = "";}
			$out .= "					<option" . $selected . " value=\"$x\">$x</option>\n";
			$count++;
		}
		$out .= "				</select>\n";
		$out .= "			</div>";
		return $out;
	}

/****** makeJS() GENERATES CLIENT SIDE JS FORM VALIDATION ******/
	function makeJS(){
		$out = "";
		$topChoicesErrors = "	var topChoicesErrors = new Array();\n";
		$out .= "function submitForm(){\n";
		$out .= "		var f = document.frm;\n";
		$out .= "		var errs = '';\n";
		if($this->settings[mustValidate]){
			$e = $this->errText;
			foreach($this->items as $item){
				if($item[type] == "checkbox") $topChoicesErrors .= "	topChoicesErrors[" . $item[id] . "] = '" . sprintf($e[topChoices], $item[numchoices]) . "'\n";
				if($item[required]){
					switch($item[type]){
						case "radio":
						case "yesno":
						case "rating":
							$out .= "		if(!checkRadios(f.elements['question_" . $item[id] . "[]']))errs += '" . sprintf($e[radio], $item[number]) . "\\n';\n";
							$out .= "		if(!checkExtraText(f.elements['question_" . $item[id] . "[]']))errs += '" . sprintf($e[extraText], $item[number]) . "\\n';\n";
							break;
						case "multirating":
							$out .= "		if(!checkRadios(";
							$i = 0;
							foreach($item[answernode]->childNodes as $childnode){
								if($childnode->nodeType != 3){
									if($i>0){ $out .= ",";}
									$out .= "f.elements['question_" . $item[id] . "[$i][]']";
									$i++;
								}
							}
							$out .= "))errs += '" . sprintf($e[multiRadio], $item[number]) . "\\n';\n";
							break;
						case "checkbox":
							$out .= "		if(!checkBoxes(f.elements['question_" . $item[id] . "[]'], {$item[numchoices]}))errs += '" . sprintf($e[checkbox], $item[number]) . "\\n';\n";
							$out .= "		if(!checkExtraText(f.elements['question_" . $item[id] . "[]']))errs += '" . sprintf($e[extraText], $item[number]) . "\\n';\n";
							break;
						case "textinput":
							$out .= "		if(f.question_" . $item[id] . ".value=='')errs += '" . sprintf($e[textMissing],$item[number]) . "\\n';\n";
							break;
						case "email":
							$out .= "		if(f.question_" . $item[id] . ".value=='')errs += '" . sprintf($e[emailMissing],$item[number]) . "\\n';\n";
							$out .= "		else if(!checkEmail(f.question_" . $item[id] . ".value))errs += '" . sprintf($e[emailInvalid], $item[number]) . "\\n';\n";
							break;
					}
				}
			}
			$out .= "		if(errs!=''){alert('" . $e[intro] . ":\\n' + errs);return false;}else{f.submit();}\n";
		}else{
			$out .= "		f.submit();\n";
		}
		$out .= "	}\n";
		$out .= $topChoicesErrors;
		return $out;
	}

/******* writeForm() WRITES GENERATED FORM XHTML TO PAGE ******/
	function writeForm(){
		$out = "";
		$out .= "<form name=\"frm\" id=\"frm\" action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"" . strtoupper($this->settings[method]) . "\">\n";
		for($i=0;$i<count($this->items);$i++){
			if($this->items[$i][number]!=""){$period=". ";}else{$period="";}
			$out .= "	<div class=\"item\" id=\"item_" . ($i+1) . "\" class=\"clearfix\">\n";
			$out .= "		<div class=\"question_text\" id=\"text_" . ($i+1) . "\">" . $this->items[$i][number] . $period . utf8_decode($this->items[$i][text]);
			if($this->items[$i][required]){ $out .= "<span class=\"star\">*</span>"; }
			$out .= "</div>\n";
			$out .= utf8_decode($this->items[$i][answer]);
			$out .= "	</div>\n";
		}
		$out .= "	<input type=\"hidden\" name=\"f\" value=\"" . $_REQUEST[f] . "\" />\n";
		$out .= "	<input type=\"hidden\" name=\"submitted\" value=\"true\" />\n";
		if($this->settings[submitButton]!=""){
			$out .= "	<a href=\"#\" onclick=\"submitForm()\"><img class=\"submit\" alt=\"Submit\" src=\"" . $this->settings[submitButton] . "\" /></a>\n";
		}else{
			$out .= "	<input type=\"submit\" class=\"submit\" value=\"Submit\" onclick=\"submitForm();return false\" />\n";
		}
		$out .= "	</form>\n";
		return $out;
	}

/***** processForm() HANDLES SUBMITTED FORM DATA *******/
	function processForm(){
		eval("\$p = \$_" . strtoupper($this->settings[method]) . ";");
		$settings = $this->settings;
		$count = 0;
		$submitSuccess = false;
		foreach($this->items as $item){
			//exclude text/comment nodes
			if($item[type]!="text"){
				if(substr(key($p),9)==$item[id]){
					$entry = each($p);
				}else{
					$entry = "";
				}
				$this->answers[] = $this->getAnswer($item, $entry);
			}
		}
		if(($settings[mustValidate] AND $this->validateData()) OR !$settings[mustValidate]){
			$submitSuccess = true;
		}
		if($submitSuccess){
			$this->formData = $this->writeFormData();
			if($settings[mailWebmaster]) $this->mailResults();
			if($settings[mailUser]) $this->mailUser();
			if($settings[storeInDb]) $this->writeToDb();
			return $this->showThanks();
		}else{
			return $this->showErrs();
		}
	}

/****** getAnswer() PARSES FORM DATA FROM $_POST ARRAY AND PUTS IT INTO answers ARRAY ******/
	function getAnswer($xmlitem, $formitem){
		$i = 0;
		list($key,$value) = $formitem;
		$answer[id] = $xmlitem[id];
		$answer[number] = $xmlitem[number];
		$answer[question] = $xmlitem[text];
		$answer[type] = $xmlitem[type];
		$answer[required] = $xmlitem[required];
		$answer[numchoices] = $xmlitem[numchoices];
		// in the case of date and radio/checkbox open answers, we need to run through the array
		if(is_array($value)){
			foreach($value as $v){
				if(is_array($v)){
					// open answer textfield or multirating
					list($key2,$v2) = each($v);
					$rawval .= $v2 . ",";
					if($answer[type]=="multirating"){
						$minLabel = $xmlitem[answernode]->getAttribute("min");
						$maxLabel = $xmlitem[answernode]->getAttribute("max");
						$out = "";
						for($x=0;$x<$v2;$x++){$out .= "&lt;";}
						$out .= $v2;
						for($x=$v2;$x<=5;$x++){$out .= "&gt;";}
						$values[] = "<tr><td>" . getElementValue($xmlitem[answernode], "answer", $i , "") . "</td><td>" . $minLabel . " " . $out . " " . $maxLabel . "</td></tr>";
						$i++;
					}else{
						$values[] = $v2;
						$values[][openanswer] = true;
					}
				}else{
					switch($answer[type]){
						case "rating":
							$minLabel = $answers->getAttribute("min");
							$maxLabel = $answers->getAttribute("max");
							$out = "";
							for($x=0;$x<$v;$x++){$out .= "&lt;";}
							$out .= $v;
							for($x=$v;$x<=5;$x++){$out .= "&gt;";}
							$values[] = $minLabel . " " . $out . " " . $maxLabel;
							$rawval = $v;
						break;
						case "yesno":
							$values[] = $v;
							$rawval = $v;
						break;
						case "date":
							$values .= $v . " ";
							$rawval = $values;
						break;
						default:
							$values[] = $this->getAnswerLabel($xmlitem[answernode], $v);
							$rawval = $v;
					}
				}
			}
			$answer[value] = $values;
			$answer[rawvalue] = $rawval;
		}else{
			$answer[value] = $value;
			$answer[rawvalue] = $value;
		}
		return $answer;
	}

/***** getAnswerLabel() RETRIEVES TEXTUAL ANSWER LABELS FROM XML *****/
	function getAnswerLabel($answers, $id){
		$out = "";
		foreach($answers->childNodes as $node){
			if($node->nodeName=="answer" AND $node->getAttribute("id")==$id){
				$out = $node->textContent;
				break;
			}
		}
		return $out;
	}
	
/***** getAnswerId() RETRIEVES ID ATTRIBUTE OF ANSWER NODE ******/
	function getAnswerId($answers, $label){
		$out = "";
		foreach($answers->childNodes as $node){
			if($node->nodeValueName=="answer" AND $node->textContent==$label){
				$out = $node->getAttribute("id");
				break;
			}
		}
		return $out;	
	}

/***** vaidateData() PERFORMS SERVER-SIDE FORM VALIDATION ******/
	function validateData(){
		$errs = '';
		$e = $this->errText;
		$answers = $this->answers;
		foreach($answers as $answer){
			if($answer[required]){
				switch($answer[type]){
					case "radio":
					case "yesno":
					case "rating":
						if($answer[value]=="") $errs .= sprintf($e[radio],$answer[number]). "<br />";
						if($answer[value][openanswer]){
							if($answer[value][text]=="") $errs .= sprintf($e[extraText],$answer[number]). "<br />";
						}
					break;
					case "checkbox":
						if(count($answer[value])<$answer[numchoices]) $errs .= sprintf($e[checkbox],$answer[number]). "<br />";
					break;
					case "textinput":
						if($answer[value]=="") $errs .= sprintf($e[textMissing],$answer[number]). "<br />";
					break;
					case "email":
						if($answer[value]=="") $errs .= sprintf($e[emailMissing],$answer[number]). "<br />";
						else if(!$this->validEmail($answer[value])) $errs .= sprintf($e[emailInvalid],$answer[number]). "<br />";
					break;
				}
			}
		}
		$this->errs = $errs;
		return ($this->errs=='');
	}


/***** validEmail() CHECKS FOR VALID EMAIL ADDRESS, RETURNS TRUE IF VALID *****/
	function validEmail($email){
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return true;
		}else{
			return false;
		}
	}

/***** writeToDb() STORES SUBMISSION DATA TO MYSQL DATABASE *****/
	function writeToDB(){
		$sql = "INSERT INTO `" . $this->dbName . "`.`" . $this->tblName . "` (timestamp, ip_address ";
		foreach($this->answers as $answer){
			if($answer[type]!="text"){
				$sql .= ", question_" . $answer[id];
			}
		}
		$sql .= ") VALUES ('" . time() ."','" . $_SERVER['REMOTE_ADDR'] . "'";
		foreach($this->answers as $answer){
			$sql .= ",'" . addslashes($answer[rawvalue]) . "'";
		}
		$sql .= ")";
		mysql_query($sql);
	}

/***** mailUser() EMAILS THE USER DEPENDING ON WHETHER THEY ENTERED AN EMAIL ADDRESS AND WHAT LEVEL OF RESPONSE IS DEFINED IN THE SETTINGS *****/
	function mailUser(){
		$useremail = trim($this->getUserEmail());
		$emailbody = $this->settings[thankYouText];
		if($this->settings[mailUser]==2){
			$emailbody .= $this->formData;
		}
		mail($useremail,$this->settings[emailSubject],$emailbody,"From: " . $this->settings[fromName] . " <" . $this->settings[fromEmail] . ">" . "\r\n" . "Content-Type: text/html; charset=iso-8859-1");
	}

/***** mailUser() EMAILS THE RESULTS OF THE SUBMISSION TO THE WEBMASTER/WHOEVER  *****/
	function mailResults(){
		$emailbody .= $this->formData;
		mail($this->settings[mailbox],"Form submission",$emailbody,"From: " . $this->settings[fromName] . " <" . $this->settings[fromEmail] . ">" . "\r\n" . "Content-Type: text/html; charset=iso-8859-1");
	}

/**** getUserEmail() RETRIEVES EMAIL ADDRESS FROM SUBMITTED FORM DATA *******/
	function getUserEmail(){
		$out = "";
		foreach($this->answers as $answer){
			if($answer[type]=="email"){
				$out = $answer[value]; 
				break;
			}
		}
		if($this->validEmail($out)) return $out;
		else return false;
		
	}

/**** writeFormData() GENERATES XHTML FROM SUBMITTED FORM DATA FOR OUTPUT ON SCREEN OR EMAIL ******/
	function writeFormData(){
		$out = "";
		
		foreach($this->answers as $answer){
			$out .= $answer[number] . ": ";
			$out .= $answer[question] . "<br />\n";
			if($answer[type] == "multirating") $out .= "<table style=\"font-weight:bold;\">";
			if(is_array($answer[value])){
				foreach($answer[value] as $key=>$item){
					if(!is_array($item)) $out .= "<strong>" . $item . "</strong>\n";
				}
			}else{
				$out .= "<strong>" . $answer[value] . "</strong>\n";
			}
			if($answer[type] == "multirating") $out .= "</table>";
			$out .= "<br /><br />";
		}
		return utf8_decode($out);
	}

/***** showThanks() DISPLAYS THANK YOU TEXT ON SCREEN AFTER SUCCESSFUL FORM SUBMISSION *****/
	function showThanks(){
		$out = "";
		$out = $this->settings[thankYouText];
		return $out;
	}

/***** showErrs() DISPLAYS ERRORS IF SUBMISSION FAILS VALIDATION AT validateData() *****/
	function showErrs(){
		$out = "";
		$out = $this->errs;
		return $out;
	}


// END CLASS
}

?>