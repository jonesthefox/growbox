<!DOCTYPE html>

<html lang="<?=_LANGUAGE_CODE_SHORT;?>">

<title><?=_APP_TITLE;?></title>
<meta charset="UTF-8">
<!--suppress HtmlUnknownTarget-->
<link rel="manifest" href="/manifest/">

<?php include(Bootstrap::TEMPLATEDIR."/app/webapp-header-tmpl.inc.php");?>

<meta name="keywords" content="<?=_APP_KEYWORDS;?>">
<meta name="description" content="<?=_APP_DESCRIPTION;?>">
<meta property="og:sitename" content="<?=_APP_TITLE;?>">
<meta property="og:title" content="<?=_OGTITLE;?>">
<meta property="og:type" content="website">
<meta property="og:description" content="<?=_APP_DESCRIPTION;?>">
<meta property="og:url" content="<?=Bootstrap::HTTPHOST;?>">
<meta property="article:publisher" content="<?=Bootstrap::HTTPHOST;?>">
<meta property="article:author" content="<?=Bootstrap::HTTPHOST;?>">

<!--suppress HtmlUnknownTarget-->
<link rel="stylesheet" href="<?=Bootstrap::TEMPLATEDIRHTML;?>/css/w3.min.css">
<!--suppress HtmlUnknownTarget-->
<link rel="stylesheet" href="<?=Bootstrap::TEMPLATEDIRHTML;?>/css/fa/css/all.css">
<!--suppress HtmlUnknownTarget-->
<link rel="stylesheet" href="<?=Bootstrap::TEMPLATEDIRHTML;?>/css/roboto.css">

<style>html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif}</style>

<body class="w3-light-grey">

<?php (!isset($_SESSION["login"])) ? include(Bootstrap::TEMPLATEDIR."/nav-nli-box.php") : include(Bootstrap::TEMPLATEDIR."/nav-box.php"); ?>
