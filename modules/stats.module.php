<?php

Class stats {

	private $comm;
	private $commandes = ["", "", "", "", ""];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>