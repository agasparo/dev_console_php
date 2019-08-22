<?php

require 'class/link.class.php';
require 'class/request.class.php';
require 'class/console.class.php';
require 'class/bdd_tab.class.php';
require 'class/init.class.php';

session_start();

extract($_POST);

$aff = "";

if (isset($anc))
	$aff = $anc;
if (isset($commande)) {
	$init = new init();
	require 'require/to_include.php';
	$console = new console($commande, $aff);
	/*$tab = $console->return_var();
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
	}*/
} else {
	$init = new init();
	require 'require/to_include.php';
	$console = new console("init", $aff);
}
?>
