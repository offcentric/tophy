<?php
$links_xml = new XmlFile(realpath($_SESSION['filepath'] . "xml/links.xml"));

echo "<h3>" . getElementValue($links_xml->xpath->query("/links")->item(0), "title", 0, "") . "</h3>";
echo "<ul class=\"linkage\">\n";
foreach($links_xml->xpath->query("/links/link") as $link){
	echo "<li><a href=\"" . getElementValue($link, "a", 0, "") . "\">" . getElementValue($link, "name", 0, "") . "</a></li>\n";
}
echo "</ul>\n"
?>