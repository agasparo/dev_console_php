<?php

Class style {

	private $comm;
	private $args = [];
	private $commandes = ["size", "txt_color", "back_color"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function size() {

		if (!isset($this->args[0]) && !isset($this->args[1]))
			return ("Usage : style.size [width] [height]");

	}

	private function txt_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.txt_color [txt_color]");
	}

	private function back_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.back_color [back_color]");		
	}

	private function change_css() {

		$path_env = new link('css/style.css');
		$data = file($path_env->get_link(1));
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>