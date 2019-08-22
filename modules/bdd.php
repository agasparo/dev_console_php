<?php

$path = new link('dev_console/.env');
$data = file($path->get_link(1));

if (!isset($data[2]))
	$data[2] = "";

$bdd = new PDO(str_replace("\n", "", $data[0]), str_replace("\n", "", $data[1]), $data[2]);

?>