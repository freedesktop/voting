<?xml version="1.0"?>
<!DOCTYPE xsl:stylesheet [
  <!ENTITY copy   "&#169;" >
  <!ENTITY middot "&#183;" >
]>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:date="http://exslt.org/dates-and-times"
		version="1.0" extension-element-prefixes="date">

  <!-- using encoding="US-ASCII" would make the output compatible with
       both ISO-8859-1 and UTF-8 -->
  <xsl:output method="xml" encoding="UTF-8" indent="yes"
	      omit-xml-declaration="yes"
	      doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
	      doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />

  <!-- the root directory for the website -->
  <xsl:param name="root" select="''" />

  <xsl:template match="html">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <xsl:copy-of select="@*" />
      <xsl:apply-templates />
    </html>
  </xsl:template>

  <xsl:template match="head">
    <head xmlns="http://www.w3.org/1999/xhtml">
      <link rel="stylesheet" type="text/css" href="http://www.gnome.org/default.css" />
      <link rel="stylesheet" type="text/css" href="{$root}/foundation.css" />
      <link rel="icon" type="image/png" href="http://www.gnome.org/img/logo/foot-16.png" />
      <xsl:copy-of select="@*" />
      <xsl:apply-templates select="node()" />
    </head>
  </xsl:template>

  <xsl:template match="body">
    <body xmlns="http://www.w3.org/1999/xhtml">
      <div id="body">
	<xsl:copy-of select="@*" />
	<xsl:apply-templates select="node()" />
      </div>
      <div id="sidebar">
	<p class="section">Foundation</p>
	<ul>
	  <li><a href="{$root}/about/">About the Foundation</a></li>
	  <li><a href="http://blogs.gnome.org/foundation/">Blog</a></li>
	  <li><a href="{$root}/reports/">Reports</a></li>
	  <li><a href="{$root}/membership/">Membership</a></li>
	  <li><a href="{$root}/elections/">Elections</a></li>
	  <li><a href="{$root}/referenda/">Referenda</a></li>
	  <li><a href="{$root}/legal/">Legal</a></li>
	  <li><a href="{$root}/finance/">Finance</a></li>
	  <li><a href="{$root}/contact/">Contact</a></li>
        </ul>
        <ul>
	  <li><a href="http://www.gnome.org/press/">Press</a></li>
	  <li><a href="http://www.gnome.org/friends/">Donate to GNOME</a></li>
	</ul>
      </div>

      <div id="hdr">
	<div id="logo"><a href="{$root}/"><img src="http://www.gnome.org/img/spacer" alt="Home" /></a></div>
        <div id="banner"><img src="http://www.gnome.org/img/spacer" alt="" /></div>
	<p class="none"></p>
        <div id="hdrNav">
	  <a href="http://www.gnome.org/about/">About GNOME</a> &middot;
	  <a href="http://www.gnome.org/start/stable/">Download</a> &middot;
	  <!--<a href="http://www.gnome.org/contribute/"><i>Get Involved!</i></a> &middot;-->
	  <a href="http://www.gnome.org/">Users</a> &middot;
	  <a href="http://developer.gnome.org/">Developers</a> &middot;
	  <a href="http://foundation.gnome.org/"><b>Foundation</b></a> &middot;
	  <a href="http://www.gnome.org/contact/">Contact</a>
	</div>
      </div>

      <div id="copyright">
	Copyright &copy; 2005-<xsl:value-of select="date:year()" />,
	<a href="http://www.gnome.org/">The GNOME Project</a>.<br />
        <!-- disabling output escaping in order to leave the email
	     addresses obfuscated -->
        <xsl:text disable-output-escaping="yes"><![CDATA[
	Maintained by the GNOME Foundation <a href="mailto:board-list&#64;gnome&#46;org">Board of Directors</a> and <a href="mailto:membership-committee&#64;gnome&#46;org">Membership Committee</a>.
        ]]></xsl:text><br />
	<a href="http://validator.w3.org/check/referer">Optimised</a> for
	<a href="http://www.w3.org/">standards</a>.
	Hosted by <a href="http://www.redhat.com/">Red Hat</a>.
      </div>
    </body>
  </xsl:template>

  <!-- copy elements, adding the XHTML namespace to elements with an
       empty namespace URI -->
  <xsl:template match="*">
    <xsl:choose>
      <xsl:when test="namespace-uri() = ''">
	<xsl:element namespace="http://www.w3.org/1999/xhtml"
		     name="{local-name()}">
	  <xsl:copy-of select="@*" />
	  <xsl:apply-templates select="node()" />
	</xsl:element>
      </xsl:when>
      <xsl:otherwise>
	<xsl:copy>
	  <xsl:copy-of select="@*" />
	  <xsl:apply-templates select="node()" />
	</xsl:copy>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <!-- get rid of processing instructions -->
  <xsl:template match="processing-instruction()">
  </xsl:template>

  <!-- copy everything else -->
  <xsl:template match="node()" priority="-1">
    <xsl:copy>
      <xsl:apply-templates select="node()" />
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
