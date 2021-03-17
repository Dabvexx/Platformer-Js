<?PHP

function printRows() {
	
	$getReferred = basename($_SERVER['REQUEST_URI']);
	
	$dir = '../';
	$directories = scandir($dir);
	$printString = "";
	
	foreach($directories as $Value) {
		if ($Value == "README.md") {continue;}
		if(ctype_upper(substr($Value,0,1)) == true) {
			
			$wordArray = preg_split('/(?=[A-Z])/', $Value);
			
			$printString = ltrim(rtrim(implode(" ", $wordArray)));
			
			if($Value == $getReferred) {
				$dataClass = "leftNavRowSelected";
			}
			else {
				$dataClass = "leftNavRow";
			}	
			
			echo "<tr><td class = \"$dataClass\">
			<a href = \"../$Value\">$printString</a>
			</td></tr>\n";
		}
	}
}

?>

<table class = "leftNav">
<?PHP printRows(); ?>
</table>