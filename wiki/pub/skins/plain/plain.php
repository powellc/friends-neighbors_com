<?php if (!defined('PmWiki')) exit();
/**
* This is plain.php, the PHP script portion of the Plain Skin for PmWiki 2.
*/

global $HandleActions, $AuthRealm, $action, $SiteGroup, $HTMLStylesFmt,
  $HTMLHeaderFmt, $FmtPV, $EnableEditFormPrefs, $PlainMidAlign, $PlainBr;

/*
+------------------------------------------+
|  Settings
|
| You can override these default values in
| a local configuration file.
+------------------------------------------+
*/

## Allow use of an alternate Edit Form via the XLPage.
SDV($EnableEditFormPrefs, 1);

SDV($PlainMidAlign, 'center');

// End of Settings

## Add {$SkinName} and {$SkinVersion} page variables.
$FmtPV['$SkinName'] = "'PlainSkin'";
$FmtPV['$SkinVersion'] = "'0.05.0'";

## Enable (:if enabled PlainSkin:) conditional markup.
global $PlainSkin; $PlainSkin = TRUE;

## Trail path separator
global $TrailPathSep;  SDV($TrailPathSep, ' &gt; ');

## The second stylesheet can override PmWiki defaults.
$HTMLHeaderFmt['plaincss'] =
  "  <link rel='stylesheet' href='\$SkinDirUrl/plain2.css' type='text/css' />";

## Add an {$UploadScratchName} page variable.
if ($action == 'upload') {
  if (@$_REQUEST['upname']) $ScratchName = $_REQUEST['upname'];
  if (@$_REQUEST['uprname']) $ScratchName = $_REQUEST['uprname'];
  if (!empty($ScratchName)) {
    $FmtPV['$UploadScratchName'] = "'".htmlspecialchars($ScratchName, ENT_QUOTES)."'";
    global $ScratchExists; $ScratchExists = TRUE;
    $ScratchSize = round(($foo=strlen($pagename.$ScratchName)+3)*1.3);
    $FmtPV['$UploadScratchSize'] = "'$ScratchSize'"; } }

## Do some things based on the authorization level.
if (CondAuth($pagename, 'edit')) { global $TimeFmt; $TimeFmt = '%B %d, %Y'; }

## Add a custom page storage location for bundled pages.
global $WikiLibDirs;
$PageStorePath = dirname(__FILE__)."/wikilib.d/\$FullName";
$where = count($WikiLibDirs);
if ($where>1) $where--;
array_splice($WikiLibDirs, $where, 0, array(new PageStore($PageStorePath)));

## Enable the Preferences page.
global $XLLangs;
XLPage('plain', "$SiteGroup.PlainXLPage");
array_splice($XLLangs, -1, 0, array_shift($XLLangs));

## Enable the skin's custom EditForm, either
## configurable via a prefs page (XLPage) or not.
global $PageEditForm;
if ($EnableEditFormPrefs == TRUE) {
  SDV($PageEditForm, "$[$SiteGroup.PlainEditForm]");
} else {
  SDV($PageEditForm, "$SiteGroup.PlainEditForm"); }

## Use GUI buttons on edit pages, including add some extra buttons.
global $EnableGUIButtons, $GUIButtons;
$EnableGUIButtons = 1;
$GUIButtons['h3'] = array(402, '\\n!!! ', '\\n', '$[Subheading]',
                     '$GUIButtonDirUrlFmt/h3.gif"$[Subheading]"');
$GUIButtons['indent'] = array(500, '\\n->', '\\n', '$[Indented text]',
                     '$GUIButtonDirUrlFmt/indent.gif"$[Indented text]"');
$GUIButtons['outdent'] = array(510, '\\n-<', '\\n', '$[Hanging indent]',
                     '$GUIButtonDirUrlFmt/outdent.gif"$[Hanging indent]"');
$GUIButtons['stable'] = array(600,
                      '||border=1 width=80%\\n||!Hdr ||!Hdr ||!Hdr ||\\n||     ||     ||     ||\\n||     ||     ||     ||\\n', '', '',
                    '$GUIButtonDirUrlFmt/table.gif"$[Simple Table]"');
$GUIButtons['atable'] = array(610,
                     '(:table border=1 width=80%:)\\n(:cell style=\'padding:5px\;\':)\\n1a\\n(:cell style=\'padding:5px\;\':)\\n1b\\n(:cellnr style=\'padding:5px\;\':)\\n2a\\n(:cell style=\'padding:5px\;\':)\\n2b\\n(:tableend:)\\n', '', '',
                     '$GUIButtonDirUrlFmt/table.gif"$[Advanced Table]"');

## Hide sidebar and/or header sometimes.
if (in_array($action, array('edit', 'diff', 'attr'))
  || preg_match('/\\.(All)?Recent(Changes|Uploads)$/', $pagename)) {
  SetTmplDisplay('PageLeftFmt', 0);
  $HTMLStylesFmt['widebody']= "\n  #wikitext { width:100%; } "; }

## Assure bottom table isn't to the right of the middle one.
if ($PlainMidAlign == 'left') $PlainBr = "<br clear='all'>";

## Reset error reporting ("just in case")
if (@$orig_err_rept) { error_reporting($orig_err_rept); }

