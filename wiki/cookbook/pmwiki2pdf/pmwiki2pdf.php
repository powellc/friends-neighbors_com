<?php
/**
  PmWiki module to generate a PDF file from the ?action=pdf page view.
  with the HTML2FPDF PHP Class ( http://html2fpdf.sourceforge.net/ )
  Hacked by Stephane HUC for PmWiki !
  Copyright (C) 2005 Stephane HUC <devs@stephane-huc.net>
  This is GPL, only it seems odd to include a license in a program
  that has twice the size of the license.

  Modifications to work with PmWiki 2.0, 
  Copyright (c) 2005 by Patrick R. Michaud <pmichaud@pobox.com>
*/
if (!defined('PmWiki')) exit();

define("HOST", $_SERVER["HTTP_HOST"]);
define("URI", $_SERVER["REQUEST_URI"]);
define("MEM", ini_get('memory_limit') );
define("MAX_TIME", ini_get('max_execution_time') );
define("VERSION", "html2fpdf-3.0.2b"); 

include(VERSION.'/html2fpdf4pmwiki.php');
#
#  add the $HandleActions PDF variable array ...
#
$HandleActions['pdf'] = 'HandlePDF';


function HandlePDF($pagename) {
	global $WikiTitle;
	
	// modify WikiTitle
	$WikiTitle = str_replace(' ', '_', $WikiTitle);
	$WikiTitle = html_entity_decode($WikiTitle);
	
	// read wiki page !
	//$page = ReadPage($pagename);
	$page = RetrieveAuthPage($pagename, 'read', true, READPAGE_CURRENT);
    //$date['modif'] = filemtime($_SERVER['DOCUMENT_ROOT'].'/wiki.d/'.$pagename);
	
	// define variable 
	$xyz['author'] = 'by '.$page['author']; // pdf author
	$xyz['name']['page'] = str_replace('.', '_', $pagename); // page name
	$xyz['name']['pdf'] = $WikiTitle.'_'.$xyz['name']['page'].'.pdf'; // pdf name
	$xyz['text'] = mv_breakpage($page['text']); // to transform breakpage markup 
	$xyz['title'] = $WikiTitle.' : page '.$xyz['name']['page']; // pdf title
    $xyz['path'] = $_SERVER["DOCUMENT_ROOT"]; // return root path of your site web
	$xyz['url'] = 'http://'.HOST.URI; // pdf URL 
	
	// transform text to html !
	$html = change_code(MarkupToHTML($pagename, $xyz['text']));
	
	/*** for test ! ***
	echo $xyz['text'];
	echo "\n HTML : ".$html;
	/** */
	
	//out pass memory server
	ini_set('memory_limit', '24M');
	ini_set('max_execution_time', 0);
	
	//  declare a new object pdf
	$pdf = new HTML2FPDF();
	// Disactive elements HTML ... cause bad support !
	$pdf->DisableTags('<span>');
	$pdf->DisableTags('<dl>');
	$pdf->DisableTags('<dt>');
	$pdf->DisableTags('<dd>');
	
	// generals informations
	$pdf->SetCompression(1);
	$pdf->SetAuthor($xyz['author']);
	$pdf->SetTitle($xyz['title']);
    
    // method implemented by me to return in footer pdf generated.
	$pdf->PutHREF($xyz['url']);
    
    // method implemented by html2pdf author !
    $pdf->setBasePath($xyz['path']); // to implement path of your site ; need it for include correctly the image on pdf !
    $pdf->UseCSS(false); // to recognize CSS ... run correctly ?
    $pdf->UsePRE(false); // to recognize element PRE in your code HTML ... but, really bad support !
	
	// build the page PDF	
	$pdf->AddPage();
	$pdf->WriteHTML($html);
	$pdf->Output($xyz['name']['pdf'], I);
	/**/
	// retabli valeur serveur
	ini_set('memory_limit', MEM);
	ini_set('max_execution_time', MAX_TIME);
}

function mv_breakpage($buffer) {
	$search = array (
		"!(:breakpage:)!", /* original version */
		"!____!", /* version 2.0.7 */
		"!====!", /* version for PmWiki 2.0.beta54 and > */
	);
	
	return(preg_replace($search, 'page_break', $buffer));
}

function change_code($buffer) {
	    $search = array (
	    "!<p>page_break\s</p>!",
		"!<p class='vspace'></p>!",
        "!\n!",
		"!&eacute;!",
		"!&egrave;!",
        "!&ensp;!",
		"!&hellip;!",
		"!&ldquo;!",
		"!&rdquo;!",
		"!&lsquo;!",
		"!&rsquo;!",
		"!&mdash;!",
        "!&minus;!",
		"!&nbsp;!",
        "!&trade;!",
        "!&copy;!",
        "!&euro;!",
        "!&reg;!",
	    );

	    $replace = array (
	    '<page_break>',
		"",
        " ",
		"é",
		"è",
        "",
		"...",
		"\"",
		"\"",
		"'",
		"'",
		"--",
        "-",
		" ",
        "™",
        "©",
        "€",
        "®",
	    );

    return(preg_replace($search, $replace, $buffer));
}
?>
