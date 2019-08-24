<?php

Class style {

	private $comm;
	private $args = [];
	private $commandes = ["size", "txt_color", "back_color"];
	const max_height = 98;
	const min_height = 30;

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function size() {

		if (!isset($this->args[0]) || !isset($this->args[1]))
			return ("Usage : style.size [width] [height]");

		if ($this->args[1] > style::max_height)
			return ("Error : height must be less than ".style::max_height."%");

		if ($this->args[1] < style::min_height)
			return ("Error : height must be more than ".style::min_height."%");

		return ($this->change_css([$this->args[0], $this->args[1]], "conbsole_b"));

	}

	private function txt_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.txt_color [txt_color]");
	}

	private function back_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.back_color [back_color]");		
	}

	private function change_css($tab, $elem) {

		$path_env = new link('css/style.css');
		$data = file_get_contents($path_env->get_link(1));
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>