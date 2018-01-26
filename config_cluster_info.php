<?php



$config_cluster_info = serialize([

	"crontab_log"=>
					[
						"ip_list"=>[],
						"port"=>"80",
						"owner_list"=>["yibo"],
					],


]); 

define("CONFIG_CLUSTER_INFO",$config_cluster_info);
