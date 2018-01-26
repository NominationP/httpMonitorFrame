<?php


$config_http_monitor = serialize([

	[
		"cluster_name"=>"crontab_log",
		"url"=>"",
		"get_data"=>"",
		"post_data"=>"",
		"cookie_data"=>"",
		"result"=>"OK", 
	],

]); 

define("CONFIG_HTTP_MONITOR",$config_http_monitor);
