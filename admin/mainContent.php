<?php

$basedir = '../';

$directories = scandir($basedir);

$action = $_GET["action"];

if($action == 'deploy') {
	foreach($directories as $Value) {
		if(ctype_upper(substr($Value,0,1)) == true) {
			copy("index.php", "$basedir/$Value/index.php");
			
			$filename = "$basedir/$Value/mainContent.php";
			
			if(file_exists($filename)) {
			} 
			else {
				$myfile = fopen($filename, "w") or die("unable to open file");
				$txt = " ";
			fwrite($myfile, $txt);
			}
		}	
	}
}

?>

<a href = "index.php?action=deploy">Deploy template</a>