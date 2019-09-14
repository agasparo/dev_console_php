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
	private $commandes = ["reload_modules", "clear", "model_module", "server_infos", "server_get_info"];

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

	private function server_infos() : String {

		$str = "";

		foreach ($_SERVER as $key => $value) {
			
			$str .= $key.": ".$value."<br>";
		}

		return ($str);
	}

	public function server_get_info() : String {

		if (!isset($this->args[0]) || empty($this->args[0]))
			return ("Usage : system.server_get_info [info to search]");

		if (!isset($_SERVER[strtoupper($this->args[0])]))
			return ("The info '".$this->args[0]."' doesn't exist");

		return ($_SERVER[strtoupper($this->args[0])]);
	}

	public function execute() : String {

		return ($this->{$this->comm}());
	}
}

?>