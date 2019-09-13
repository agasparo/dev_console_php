<?php

Class system {


	/**
	 * @comm String
	 */
	private $comm;

	/**
	 * @args Array
	 */
	private $args = [];

	/**
	 * @commandes Array
	 */
	private $commandes = ["reload_modules", "clear", "model_module"];

	public function __Construct(String $commande, Array $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function reload_modules() : String {

		$reload = new init();
		$reload->force();
		return ("Modules are reloading with success");
	}

	private function clear() : String {

		return ("clearing the console");
	}

	private function model_module() : String {

		$path_env = new link('template/module_type.txt');
		return (file_get_contents($path_env->get_link(1)));
	}

	public function execute() : String {

		return ($this->{$this->comm}());
	}
}

?>