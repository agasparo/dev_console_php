<?php

Class system {

	private $comm;
	private $args = [];
	private $commandes = ["reload_modules"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function reload_modules() {

		$reload = new init();
		$reload->force();
		return ("Modules are reloading with success");
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>