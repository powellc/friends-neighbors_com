<?php if (!defined('PmWiki')) exit();
$WikiTitle = "Friends and Neighbors";
$PageLogoUrl = "http://fn.rubyvroom.com/img/logo.png";
$Skin = 'maguila';

$DefaultPasswords['admin'] = crypt('mainroot');
$WikiSubTitle="";
$EnableUpload = 1;
$DefaultPasswords['upload'] = crypt('fn207upload');

putenv("TZ=EST5EDT");
$TimeFmt = '%B %d, %Y, at %I:%M %p EST';
