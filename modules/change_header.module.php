<?php

Class change_header {


	/**
	 * @args Array
	 */
	private $args = [];

	/**
	 * @comm String
	 */
	private $comm;

	/**
	 * @commandes Array
	 */
	private $commandes = ["change"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function change() {

		if (isset($this->args[0])) {

			$path_env = new link('.env');
			$env = file($path_env->get_link(1));
			$env[3] = $this->args[0];
			file_put_contents($path_env->get_link(1), implode("", $env));
			return ("Header change : success (haeder change for the next commandes)");
		}
		return ("Usage : change_header.change ['new header']");
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>