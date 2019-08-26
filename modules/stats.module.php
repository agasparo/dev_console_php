<?php

Class stats {

	private $comm;
	private $commandes = ["most", "less", "moyenne", "", ""];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function check_require() {


	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>