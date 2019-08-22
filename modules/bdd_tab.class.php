<?php

Class bdd_tab {

	private $tb;

	public function __Construct() {
		$path = new link('dev_console/modules/bdd.php');
		require $path->get_link(1);
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