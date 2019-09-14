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

	public function __Construct(String $commande, Array $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function change() : String {

		if (isset($this->args[0])) {

			$path_env = new link('.env');
			$env = file($path_env->get_link(1));
			$env[3] = $this->args[0]."\n";
			file_put_contents($path_env->get_link(1), implode("", $env));
			return ("Header change : success (haeder change for the next commandes)");
		}
		return ("Usage : change_header.change ['new header']");
	}

	public function execute() : String {

		return ($this->{$this->comm}());
	}
}

?>