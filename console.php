<?php

require 'class/request.class.php';
require 'class/console.class.php';
require 'class/bdd_tab.class.php';

extract($_POST);

$aff = "";

if (isset($anc))
	$aff = $anc;
if (isset($commande))
	$console = new console($commande, $aff);
else
	$console = new console("init", $aff);
?>