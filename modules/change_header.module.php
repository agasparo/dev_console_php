<?php

Class change_header {

	private $args = [];
	private $comm;
	private $commandes = ["change"];

	public function __Construct($commande) {

		$delete_usless = explode(" ", $commande);
		$this->args = $delete_usless;
		unset($this->args[0]);
		$this->args = implode(" ", array_values($this->args));
		$delete_usless = explode(".", $delete_usless[0]);
		$this->comm = $delete_usless[1];
	}

	private function change() {

		if (isset($this->args[0])) {

			$path_env = new link('.env');
			$env = file($path_env->get_link(1));
			$env[3] = $this->args[0];
			file_put_contents($path_env->get_link(1), implode("\n", $env));
			return ("Header change : success");
		}
		return ("Usage : change_header.change ['new header']");
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>