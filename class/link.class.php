<?php

Class link {

	private $file;
	private $actual_url;

	public function __Construct($link) {
		$this->file = $link;
		$this->actual_url = "http://{$_SERVER['HTTP_HOST']}";
	}

	public function get_link($type) {
		$real_path = getcwd();
		$path = $real_path."/".$this->file;
		while (!file_exists($path)) {
			$e = explode("/", $real_path);
			unset($e[count($e) - 1]);
			$real_path = implode("/", $e)."/";
			$path = $real_path.$this->file;
		}
		if ($type == 1)
			return ($path);
		$e = explode("/", getcwd());
		$e1 = explode("/", $path);
		$i = 0;
		while (isset($e[$i]) && $e[$i] == $e1[$i]) {
			unset($e1[$i]);
			$i++;
		}
		$e1 = array_values($e1);
		if (is_dir($e1[0]))
			$str = $e[$i - 2]."/".$e[$i - 1];
		else
			$str = $e[$i - 1];
		return ($this->actual_url."/".$str."/".implode("/", $e1));
	}

}

?>