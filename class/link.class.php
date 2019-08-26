<?php

Class link {

	private $file;
	private $actual_url;

	public function __Construct($link) {

		$this->file = $link;
		$this->actual_url = "http://{$_SERVER['HTTP_HOST']}";
	}

	private function get_console_dir() {

		$actual_path = getcwd();

		while (!file_exists($actual_path."/dev_console")) {
			$ex = explode("/", $actual_path);
			unset($ex[count($ex) - 1]);
			$actual_path = implode("/", $ex);
		}

		return ($actual_path."/dev_console");
	}

	public function get_link($type) {

		$real_path = getcwd();
		$path = $this->get_console_dir()."/".$this->file;

		if ($type == 1)
			return ($path);

		$url_a = explode("/", $real_path);
		$url_c = explode("/", $path);

		$url_c1 = $url_c;
		unset($url_c1[count($url_c1) - 1]);

		$c = count($url_a);
		$i = count($url_c1);
		while ($i >= $c) {
			unset($url_c1[$i]);
			$i--;
		}

		if (empty(array_diff($url_c1, $url_a))) {
			$url = explode("/", $_SERVER['REQUEST_URI']);
			unset($url[count($url) - 1]);
			return ($this->actual_url.implode("/", $url)."/".implode("/", array_diff($url_c, $url_a)));
		}

		$i = 0;
		while (isset($url_a[$i]) && $url_c[$i] == $url_a[$i] && $url_a[$i] != "dev_console") {

			unset($url_c[$i]);
			$i++;
		}

		array_unshift($url_c, $url_a[$i - 1]);
		
		return ($this->actual_url."/".implode("/", $url_c));
	}
}

?>