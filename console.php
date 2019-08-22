<?php

require 'class/request.class.php';
require 'class/console.class.php';
require 'class/bdd_tab.class.php';

session_start();

extract($_POST);
$url = "http://192.168.99.100.xip.io:41062/www/";

$aff = "";

if (isset($anc))
	$aff = $anc;
if (isset($commande)) {
	$console = new console($commande, $aff, get_defined_vars());
	$tab = $console->return_var();
	if (is_array($tab) && count($tab) >= 1) {
		$e = explode("[", key($tab));
		if (isset($e[1])) {
			$e[1] = str_replace("]", "", $e[1]);
			$e[1] = str_replace("'", "", $e[1]);
			$e[1] = str_replace('"', "", $e[1]);
			${$e[0]}[$e[1]] = $tab[key($tab)];
		}
		else
			${$e[0]} = $tab[key($tab)];
		header("Location:".$url);
	}
} else
	$console = new console("init", $aff, get_defined_vars());
?>
