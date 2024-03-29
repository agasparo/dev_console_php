<?php

Class init {


	/**
	 * @modules Array
	 */
	private $modules = [];

	/**
	 * @nb_modules Int
	 */
	private $nb_modules = 0;

	public function __Construct() {

		$this->get_modules();
		$this->compare_modules();
	}

	public function force() {
		
		$path = new link('.env_modules');
		$file = $path->get_link(1);
		$this->load_modules($file);
	}

	private function compare_modules() {

		$path = new link('.env_modules');
		$file = $path->get_link(1);
		if (count(file($file)) != $this->nb_modules)
			$this->load_modules($file);
	}

	private function load_modules(String $file) {

		file_put_contents($file, "");
		$i = 0;
		while (isset($this->modules[$i])) {
			file_put_contents($file, serialize($this->modules[$i])."\n", FILE_APPEND);
			$i++;
		}
	}

	private function get_modules() {

		$path = new link('modules/');
		$this->readdirectory($path->get_link(1));
	}

	private function readdirectory(String $directory) {

		$fileList = glob($directory."*.module.php");

		$path = new link('require/to_include.php');
		$file = $path->get_link(1);

		file_put_contents($file, "<?php\n");

		foreach($fileList as $filename) {

			$data = $this->infos_module($filename, $file);
   			$this->modules[][$data['name_module']] = $data['infos_module'];
   			$this->nb_modules++;
		}

		file_put_contents($file, "\n?>", FILE_APPEND);
	}

	private function infos_module(String $link_module, String $file) : Array {

		$data = [];
		$cut = explode("/", $link_module);
		$data['name_module'] = substr($cut[count($cut) - 1], 0, strpos($cut[count($cut) - 1], '.'));
		file_put_contents($file, "require '".$link_module."';\n", FILE_APPEND);
		$data['infos_module']['commandes'] = $this->get_module_commande($link_module);
		return ($data);
	}

	private function get_module_commande(String $link_module) : Array {

		$cat_file = file($link_module);
		$i = 0;
		while (isset($cat_file[$i])) {

			if (preg_match('#commandes = #', $cat_file[$i])) {

				preg_match_all('#(\[[^\]]*\])#', $cat_file[$i], $matches);
				$matches[0][0] = str_replace("[", "", $matches[0][0]);
				$matches[0][0] = str_replace("]", "", $matches[0][0]);
				$matches[0][0] = str_replace("'", "", $matches[0][0]);
				$matches[0][0] = str_replace('"', '', $matches[0][0]);
				$matches[0][0] = str_replace(' ', '', $matches[0][0]);
				return (explode(",", $matches[0][0]));
			}
			$i++;
		}
	}
}

?>