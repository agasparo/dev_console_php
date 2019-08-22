<?php

require 'class/request.class.php';
require 'class/console.class.php';
require 'class/bdd_tab.class.php';

session_start();

extract($_POST);

$aff = "";

if (isset($anc))
	$aff = $anc;
if (isset($commande)) {
	$console = new console($commande, $aff);
	$tab = $console->return_var();
	if (is_array($tab))
		${$tab[0]} = $tab[1];
} else
	$console = new console("init", $aff);

print_r($_SESSION);
?>
