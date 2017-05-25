<?php
function get_rss_xsl(){
	$out = "<" . "?xml version=\"1.0\" encoding=\"ISO-8859-1\"?" . ">\n";
	$out .= "<xsl:stylesheet version=\"1.0\" xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\">\n";
	$out .= "<xsl:output method=\"xml\" encoding=\"UTF-8\" indent=\"yes\"/>\n";
	$out .= "<xsl:template match=\"/journal\">\n";
	$out .= "<rss version=\"2.0\">\n";
	$out .= "	<channel>\n";
	$out .= "		 <title>" . $_SESSION['journal_name'] . "</title>\n";
	$out .= "		 <link>" . $_SESSION['journal_url'] . "</link>\n"; 
	$out .= "		 <description>" . $_SESSION['meta_description'] . "</description>\n";
	$out .= "		 <language>en-us</language>\n";
	$out .= "		 <pubDate><xsl:value-of select=\"@lastupdated\" /></pubDate>\n";
	$out .= "		 <lastBuildDate><xsl:value-of select=\"@lastupdated\" /></lastBuildDate>\n";
	$out .= "		 <docs>" . $_SESSION['journal_url'] . "</docs>\n";
	$out .= "		 <generator>http://offcentric.com</generator>\n";
	if($_SESSION['cm__admin']['enable_publishing']){
		$out .= "		<xsl:for-each select=\"/journal/entry[@published=1]\">\n";
	}else{
		$out .= "		<xsl:for-each select=\"/journal/entry\">\n";
	}
	$out .= "			<xsl:sort data-type=\"number\" select=\"timestamp\" order=\"descending\" />\n";
	$out .= "			<xsl:if test=\"position() &lt; 20\">\n";
	$out .= "				<item>\n";
	$out .= "					<title><xsl:value-of select=\"title\" /></title>\n"; 
	$out .= "					<link>" . $_SESSION['journal_url'] . "entry/<xsl:value-of select=\"@id\" /></link>\n"; 
	$out .= "					<description><xsl:text disable-output-escaping=\"yes\">&lt;</xsl:text>![CDATA[\n";
	$out .= "					<xsl:if test=\"normalize-space(mood)\"><strong>Mood:</strong><xsl:text> </xsl:text><xsl:value-of select=\"mood\" /><br /></xsl:if>\n";
	$out .= "					<xsl:if test=\"normalize-space(listening)\"><strong>Listening to:</strong><xsl:text> </xsl:text><xsl:value-of select=\"listening\" /><br /></xsl:if>\n";
	$out .= "					<xsl:if test=\"normalize-space(reading)\"><strong>Reading:</strong><xsl:text> </xsl:text><xsl:value-of select=\"reading\" /><br /></xsl:if>\n";
	$out .= "					<xsl:if test=\"normalize-space(watching)\"><strong>Watching:</strong><xsl:text> </xsl:text><xsl:value-of select=\"watching\" /><br /></xsl:if>\n";
	$out .= "					<br />\n";
	$out .= "					<xsl:value-of disable-output-escaping=\"yes\" select=\"content\" />\n";
	$out .= "					]]<xsl:text disable-output-escaping=\"yes\" >></xsl:text></description>\n";
	$out .= "					<comments>" . $_SESSION['journal_url'] . "entry/<xsl:value-of select=\"@id\" />#comments</comments>\n";
	$out .= "					<pubDate><xsl:value-of select=\"date\" /><xsl:text> </xsl:text><xsl:value-of select=\"time\" /> +0200</pubDate>\n";
	$out .= "					<author>" . $_SESSION['meta_author'] . "</author>\n";
	$out .= "					<guid>" . $_SESSION['journal_url'] . "entry/<xsl:value-of select=\"@id\" /></guid>\n";
	$out .= "				</item>\n";
	$out .= "			</xsl:if>\n";
	$out .= "		</xsl:for-each>\n";
	$out .= "	</channel>\n";
	$out .= "</rss>\n";
	$out .= "</xsl:template>\n";

	$out .= "</xsl:stylesheet>\n";

	return $out;
}