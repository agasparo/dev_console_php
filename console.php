<?php

require 'class/request.class.php';
require 'class/console.class.php';
require 'class/bdd_tab.class.php';

session_start();

$tab['_SESSION["id"]'] = 8;

extract($tab);
echo $_SESSION["id"];
print_r($_SESSION);
exit(0);

extract($_POST);

$aff = "";

if (isset($anc))
	$aff = $anc;
if (isset($commande)) {
	$console = new console($commande, $aff);
	$tab = $console->return_var();
	if (is_array($tab))
		extract($tab);
} else
	$console = new console("init", $aff);

print_r($_SESSION);
?>