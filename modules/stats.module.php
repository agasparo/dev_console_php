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

	private function most() {


	}

	private function less() {


	}

	private function moyenne() {

		
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>