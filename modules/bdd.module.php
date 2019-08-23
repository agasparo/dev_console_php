<?php

Class bdd {

	private $args = [];
	private $comm;
	private $bdd;
	private $commandes = ["insert", "update", "delete", "show_table", "get"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;

		$path = new link('dev_console/.env');
		$data = file($path->get_link(1));

		if (!isset($data[2]))
			$data[2] = "";

		$this->bdd = new PDO(str_replace("\n", "", $data[0]), str_replace("\n", "", $data[1]), $data[2]);
	}

	public function execute() {
		
		return ($this->{$this->comm}());
	}
}

?>