<?php

Class init {

	private $modules = [];

	public function __Construct() {

		$this->get_modules();
	}

	private function get_modules() {

		$path = new link('modules/');
		$this->readdirectory($path->get_link(1));
	}

	private function readdirectory($directory) {

		$fileList = glob($directory."*.module.php");

		foreach($fileList as $filename) {

			$data = $this->infos_module($filename);
   			$this->modules[][$data['name_module']] = $data['infos_module'];
		}

		return ($this->modules);
	}

	private function infos_module($link_module) {

		$data = [];
		$cut = explode("/", $link_module);
		$data['name_module'] = substr($cut[count($cut) - 1], 0, strpos($cut[count($cut) - 1], '.'));
		$data['infos_module']['full_link'] = $link_module;
		$data['infos_module']['commandes'] = $this->get_module_commande($link_module);
		return ($data);
	}

	private function get_module_commande($link_module) {

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