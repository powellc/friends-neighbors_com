<?php $Skin = 'plain';

$WikiTitle="Castine Friends and Neighbors";

## Use Clean URLs
$EnablePathInfo=1;
$ScriptUrl="http://friends-neighbors.com/castine";
$EditTemplatesFmt='{$Group}.Template';
$DefaultPasswords['admin'] = crypt('mainroot');
$EnableUpload=1;
$DefaultPasswords['upload']=crypt('upload207');
$UploadPrefixFmt='/$Group/$Name';
#include_once("$FarmD/cookbook/pmwiki2pdf/pmwik2pdf.php");
include_once("$FarmD/cookbook/pmcal.php");
