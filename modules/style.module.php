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

		return ($this->change_css([$this->args[0]."%", $this->args[1]."%"], "#conbsole_b", ["width", "height"]));

	}

	private function txt_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.txt_color [txt_color]");
	}

	private function back_color() {

		if (!isset($this->args[0]))
			return ("Usage : style.back_color [back_color]");		
	}

	private function change_css($tab, $elem, $keys) {

		$path_env = new link('css/style.css');
		$data = str_replace("{", "", file_get_contents($path_env->get_link(1)));
		$data = explode("}", $data);

		$i = 0;
		while (isset($data[$i])) {
			if (trim(substr($data[$i], 0, strpos($data[$i], " "))) == $elem) {
				$data[$i] = str_replace(trim(substr($data[$i], 0, strpos($data[$i], " "))), "", $data[$i]);
				$e = explode(";", $data[$i]);
				foreach ($e as $key => $value) {
					$ex = explode(":", trim(str_replace(" ", "", $value)));
					if ($ex[0] == $keys[0])
						$ex[1] = $tab[0];
					if ($ex[0] == $keys[1])
						$ex[1] = $tab[1];
					$e[$key] = implode(": ", $ex);
				}
				$data[$i] = $elem." ".implode(";", $e);
			}
			$i++;
		}
		print_r($data);

		//print_r($data);
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>