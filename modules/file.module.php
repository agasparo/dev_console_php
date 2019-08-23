<?php

Class file {

	private $comm;
	private $commandes = ["structure", "show", "update"];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function structure() {

		$fileList = glob("*");

		$file_system = "";

		foreach($fileList as $filename) {

			if (is_dir($filename)) {
				$file_system .= "└─ ".$filename."/<br>";
				$file_system .= "&nbsp;&nbsp;&nbsp;&nbsp;├─ ".implode("<br>&nbsp;&nbsp;&nbsp;&nbsp;├─ ", $this->search_file($filename))."<br>";
			} else
				$file_system .= "├─ ".$filename."<br>";
		}

		return ($file_system);

	}

	private function search_file($directory) {

		$path = new link($directory);
		$file = $path->get_link(1);
		$fileList = glob($file."/*");

		foreach ($fileList as $key => $value) {
			$fileList[$key] = str_replace($file."/", "", $value);
		}
		return ($fileList);		
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>