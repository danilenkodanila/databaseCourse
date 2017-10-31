<?php
	$url  = 'http://site.com/section/index.php?var1=1&var2=2';

	$url_data = parse_url($url);

	print_r($url_data);
?>