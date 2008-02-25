<?php
/***********************************************************
 Copyright (C) 2008 Hewlett-Packard Development Company, L.P.

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
***********************************************************/

/*************************************************
 Restrict usage: Every PHP file should have this
 at the very beginning.
 This prevents hacking attempts.
 *************************************************/
global $GlobalReady;
if (!isset($GlobalReady)) { exit; }

class ui_default extends Plugin
  {
  var $Name="Default";
  var $Version="1.0";
  var $MenuList="";
  var $Title="FOSSology";

  var $Dependency=array("topnav","folders","basenav");

  /***********************************************************
   DrawHTMLPage(): This function draws the HTML page.
   Since Output() and OutputRefresh() both draw the same page,
   it is more convenient to put the common stuff here.
   Other plugins are not expected to call this.
   $GoMod = module that should be shown for the refresh.
   $GoOpt = options to $GoMod.
   ***********************************************************/
  function DrawHTMLPage($GoMod,$GoOpt)
    {
    $V = "";
    $TreeNav = "folders"; // default value
    $BaseNav = "basenav"; // default value
    global $Plugins;

    if (empty($GoMod)) { $Mod = NULL; }
    else { $Mod = &$Plugins[plugin_find_id($GoMod)]; } // may return null

    // This display has 2 loadable sections: treenav and basenav.
    // See if $ModId is defined and where it should go...
    if (isset($Mod))
      {
      if ($Mod->MenuTarget == 'treenav')
	{
	$TreeNav = "$GoMod";
	if (!empty($GoOpt)) { $TreeNav .= "&" . $GoOpt; }
	}
      else
	{
	$BaseNav = "$GoMod";
	if (!empty($GoOpt)) { $BaseNav .= "&" . $GoOpt; }
	}
      }

    $Uri = Traceback_uri();
    if (1)
	{
	$P = &$Plugins[plugin_find_id("topnav")];
	$P->OutputSet($this->OutputType,0);
	$V .= $P->Output();
	$P->OutputUnSet();
	$V .= "<div style='height:85%'>\n";
	$V .= "<iframe width='20%' style='height:100%' frameborder=1 id='treenav' name='treenav' src='$Uri?mod=$TreeNav'>Sorry, your browser must support iframes.</iframe>";
	$V .= "<iframe width='79%' style='height:100%' frameborder=1 id='basenav' name='basenav' src='$Uri?mod=$BaseNav'>Sorry, your browser must support iframes.</iframe>\n";
	$V .= "</div>\n";
	}
    else /* use frames */
	{
	$V .= "<frameset rows='106,*' border=0>\n";
	$V .= "  <frame name=topnav src='$Uri?mod=topnav'>\n";
	$V .= "  <frameset cols='25%,*' border=5 onResize='if (navigator.family == 'nn4') window.location.reload()'>\n";
	$V .= "    <frame name=treenav src='$Uri?mod=$TreeNav'>\n";
	$V .= "    <frame name=basenav src='$Uri?mod=$BaseNav'>\n";
	$V .= "  </frameset>\n";
	$V .= "</frameset>\n";
	$V .= "<noframes>\n";
	$V .= "<h1>Your browser does not appear to support frames</h1>\n";
	$V .= "</noframes>\n";
	}
    return($V);
    } // DrawHTMLPage()

  /***********************************************************
   Output(): This function is called when user output is
   requested.  This function is responsible for content.
   (OutputOpen and Output are separated so one plugin
   can call another plugin's Output.)
   This uses $OutputType.
   The $ToStdout flag is "1" if output should go to stdout, and
   0 if it should be returned as a string.  (Strings may be parsed
   and used by other plugins.)
   ***********************************************************/
  function Output()
    {
    if ($this->State != PLUGIN_STATE_READY) { return; }
    $V="";
    switch($this->OutputType)
      {
      case "XML":
	break;
      case "HTML":
	$V .= $this->DrawHTMLPage(NULL,NULL);
	break;
      case "Text":
	break;
      default:
	break;
      }
    if (!$this->OutputToStdout) { return($V); }
    print("$V");
    return;
    } // Output()

  /***********************************************************
   OutputRefresh(): This function is called when user output changes.
   This function is responsible for redrawing content and is expected
   to be called by other plugins.
   $GoMod and $GoOpt identify another module that should be displayed.
   (Both may be NULL or may reference disabled modules!)
   This uses $OutputType.
   ***********************************************************/
  function OutputRefresh($GoMod,$GoOpt)
    {
    if ($this->State != PLUGIN_STATE_READY) { return; }
    $V="";
    switch($this->OutputType)
      {
      case "XML":
	break;
      case "HTML":
	$V .= $this->DrawHTMLPage($GoMod,$GoOpt);
	break;
      case "Text":
	break;
      default:
	break;
      }
    if (!$this->OutputToStdout) { return($V); }
    print("$V");
    return;
    } // OutputRefresh()

  };
$NewPlugin = new ui_default;
$NewPlugin->Initialize();
?>
