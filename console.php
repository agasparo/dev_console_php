<?php

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'class/tab.class.php';
require 'class/link.class.php';
require 'class/console.class.php';
require 'class/init.class.php';

extract($_POST);

$js_path = new link('console.php');
$js_p = $js_path->get_link(0);
?>
<script type="text/javascript">
var link = "<?= $js_p; ?>";
</script>
<?php

$aff = "";

if (isset($anc))
	$aff = $anc;

$init = new init();
require 'require/to_include.php';

if (isset($commande))
	$console = new console($commande, $aff);
else
	$console = new console("init", $aff);
?>
