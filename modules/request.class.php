<?php
class http_request {

	private $methode;
	private $args = [];
	private $url;

	public function __Construct($methode, array $param, $url) {
		$this->methode = $methode;
		$this->args = $param;
		$this->url = $url;
	}

	public function send_request() {
		
		$postdata = http_build_query(
			$this->args
		);

		$opts = array('http' =>
			array(
				'method'  => $this->methode,
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);

		return (file_get_contents($this->url, false, $context));
	}
}

?>