<?php

Class system {

	private $comm;
	private $args = [];
	private $commandes = ["reload_modules", "clear", "model_module"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function reload_modules() {

		$reload = new init();
		$reload->force();
		return ("Modules are reloading with success");
	}

	private function clear() {

		$str = "<br>";
		$i = 0;
		while ($i <= 3) {
			$str .= $str;
			$i++;
		}

		return ($str);
	}

	private function model_module() {

		$path_env = new link('template/module_type.txt');
		return (file_get_contents($path_env->get_link(1)));
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>