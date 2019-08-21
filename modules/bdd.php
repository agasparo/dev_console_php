<?php

$data = file('.env');

if (!isset($data[2]))
	$data[2] = "";

$bdd = new PDO(str_replace("\n", "", $data[0]), str_replace("\n", "", $data[1]), $data[2]);

?>