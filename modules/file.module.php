<?php

Class file {

	private $comm;
	private $commandes = [];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}
}

?>