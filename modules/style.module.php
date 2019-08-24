<?php

Class style {

	private $comm;
	private $args = [];
	private $commandes = ["size", "txt_color", "back_color"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function commande_1() {

		[...]
	}

	private function commande_2() {

		[...]
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>