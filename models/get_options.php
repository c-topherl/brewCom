<?php
	/*
	Put this function wherever you want, but it has to be in a file I can
	easily call from JS with an HTTP request object to get options.
	Check views/penrose/options.txt for a sample. I'm imagining
	filling this file with options specific to the client's site, so 
	each design folder will contain one with unique settings. JS doesn't
	have a convenient way to do file i/o.
	*/
	
	function getUserOpt($path, $opt){
		$options_file = fopen($path, "r") or die("Couldn't open file: " . $path);
		$line = fgets($options_file);
		while ($line !== null) {
			$line_split = explode($line, ":");
			if ($line_split[0] === $opt){
				return $line_split[1];
			}
		}
		return null;
	}
?>