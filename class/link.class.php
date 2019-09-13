<?php

Class link {

	/**
	 * @file String
	 */
	private $file;

	/**
	 * @actual_url String
	 */
	private $actual_url;

	public function __Construct(String $link) {

		$this->file = $link;
		$this->actual_url = "http://{$_SERVER['HTTP_HOST']}";
	}

	private function get_console_dir() : String {

		$actual_path = getcwd();

		while (!file_exists($actual_path."/dev_console")) {
			
			$ex = explode("/", $actual_path);
			unset($ex[count($ex) - 1]);
			$actual_path = implode("/", $ex);
		}

		return ($actual_path."/dev_console");
	}

	public function get_link(Int $type) : String {

		$path = $this->get_console_dir()."/".$this->file;

		if ($type == 1)
			return ($path);

		$url_c = explode("/", $path);
		$doc_root = explode("/", $_SERVER['DOCUMENT_ROOT']);
		
		return ($this->actual_url."/".implode("/", array_diff($url_c, $doc_root)));
	}
}

?>