<?php

Class system {

	private $comm;
	private $args = [];
	private $commandes = ["reload_modules"];

	public function __Construct($commande) {

		$delete_usless = explode(" ", $commande);
		$this->args = $delete_usless;
		unset($this->args[0]);
		$this->args = array_values($this->args);
		$delete_usless = explode(".", $delete_usless[0]);
		$this->comm = $delete_usless[1];
	}

	private function go_to() {

		if ($this->comm == "reload_modules")
			return ($this->reload_mod());
	}

	private function reload_mod() {

		$reload = new init();
		$reload->force();
		return ("Modules are reloading with success");
	}

	public function execute() {

		return ($this->go_to());
	}
}

?>