<?php

Class system {

	private $comm;
	private $args = [];
	private $commandes = ["reload_modules"];

	public function __Construct($commande) {

		$delete_usless = explode(" ", $commande);
		$this->args = $delete_usless;
		unset($this->args[0]);
		$this->args = $this->return_arg(array_values($this->args));
		$delete_usless = explode(".", $delete_usless[0]);
		$this->comm = $delete_usless[1];
	}

	public function return_arg($tab) {

		$i = 0;
		$argum = [];
		$in = 0;
		$c = "";
		while (isset($tab[$i])) {

			if ($in == 0) {

				if ($tab[$i][0] == "'" || $tab[$i][0] == '"') {

					if ($tab[$i][strlen($tab[$i]) - 1] == $c)
						$argum[] = str_replace($c, '', $tab[$i]);
					else {

						$str = $tab[$i];
						$c = $tab[$i][0];
						$in = 1;
					}
				}  else
					$argum[] = $tab[$i];
			} else {

				$str .= " ".$tab[$i];
				if ($tab[$i][strlen($tab[$i]) - 1] == $c) {
					$in = 0;
					$argum[] = str_replace($c, '', $str);
				}
			}
			$i++;
		}
		return ($argum);
	}

	private function reload_modules() {

		$reload = new init();
		$reload->force();
		return ("Modules are reloading with success");
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>