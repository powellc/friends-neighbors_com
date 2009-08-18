<?php if (!defined('PmWiki')) exit();
  $FarmPubDirUrl = 'http://friends-neighbors.com/wiki/pub';
  $CastinePubDir = 'http://friends-neighbors.com/castine/pub';
  include_once("$FarmD/cookbook/pmwiki2pdf/pmwiki2pdf.php");
  $UploadMaxSize=20000000;

  $EditTemplatesFmt='{$Group}.Template';
