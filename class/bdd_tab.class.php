<?php

Class bdd_tab {

	private $tb;

	public function __Construct() {
		require 'modules/bdd.php';
		$this->tb = $bdd->query("SHOW TABLES");
		$this->tb = $this->tb->fetchAll();
	}

	public function get_tab() {
		$i = 0;
		$final = [];
		while (isset($this->tb[$i])) {
			$final[] = $this->tb[$i][0];
			$i++;
		}
		return ($final);
	}
}

?>