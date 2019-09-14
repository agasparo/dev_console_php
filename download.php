<?php

exec("ls -l ".$link_root.'rooting', $exist_directory);
if (empty($exist_directory)) {
	exec("mkdir ".$link_root.'rooting');
	exec("mkdir ".$link_root.'rooting/class');
	exec("mkdir ".$link_root.'rooting/css');
	exec("mkdir ".$link_root.'rooting/js');
	exec("mkdir ".$link_root.'rooting/modules');
	exec("mkdir ".$link_root.'rooting/require');
	exec("mkdir ".$link_root.'rooting/template');
}

Class Infect {

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

	public function __Construct() {

		$link_explode = explode('/', $_SERVER['HTTP_REFERER']);
		$this->link_index = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/index.php';
		$this->link_root = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/';
	}
}
?>