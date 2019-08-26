<?php

Class git {

	private $comm;
	private $commandes = ["push", "set_commit_name"];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function set_commit_name() {

		
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>