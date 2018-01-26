<?php
require_once "config_monitor_item.php";
require_once "config_cluster_info.php";
require_once "config_owner_info.php";
require_once "mailer.php";


$config_monitor_item = unserialize(CONFIG_HTTP_MONITOR);
$config_cluster_info = unserialize(CONFIG_CLUSTER_INFO);
$config_owner_info = unserialize(CONFIG_OWNER_INFO);

// 遍历所有监控项
foreach ($config_monitor_item as $key => $value) {


	// 取出监控项的集群名，URL，http数据，结果等信息
	
	$cluster_name = $key;
	$url = $value['url'];
	$get_data = $value['get_data'];
	$post_data = $value['post_data'];
	$cookie_data = $value['cookie_data'];
	$result = $value['result'];

	// 由集群名，获取集群信息
	 $cluster_info= $config_cluster_info[$cluster_name];

	 // 由集群信息，获取集群ip列表，集群负责人列表
	 $ips = $cluster_info['ip'];
	 $owners = $cluster_info['owner'];


	 // 集群内的每一个ip实例web-server，都需要监控
	 foreach ($ips as $key => $ip_each) {

	 	 // 根据ip，url，http数据构造请求
	 	 // 获取http请求执行结果
	 	$re = curl_both_getPost($url,"",$get_data,$post_data);

	 	// 如果返回为200，并且包含监控项里的业务特性结果
	 	if($re['state_code'] == 200 && strpos($re['res'], $result) !== false){

	 		//正常，继续监控
	 		continue;
	 	}

	 	// 否则，对所有集群负责人发送告警
	 	foreach ($owners as $key => $owner_each) {

	 		$phone = $owner_each['phone'];
	 		$email = $owner_each['email'];

	 		$info_arr = '{
				  "FromName": "Yibo",
				  "Username": "605166577@qq.com",
				  "Password": "",
				  "From": "605166577@qq.com",
				  "AddAddress_arr": ['.
				    $email
				  .'],
				  "Subject": "Work Error !",
				  "Body": '.$ip.",".$url.'
				}';
	 		qq_send_qq($info_arr);



	 	}
	 }


}


function curl_both_getPost($url,$headers="",$get_data,$post_data){

	$re = "";

	if($headers == null){
		$headers [] = 'content-type:application/json;charset=utf-8';
	}


	if($post_data != null){
		$re = get_url($url,$post_data,$headers);
	}else{
		$url .= $get_data;
		$re = get_url_get($url);
	}


	return $re;

}


function get_url_get($url){

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
        // CURLOPT_USERAGENT => 'Codular Sample cURL Request'
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    // 
    $state_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return ["res"=>$resp,"state_code"=>$state_code];
}


function get_url($url,$post_data,$headers){


    // $headers [] = 'content-type:application/json;charset=utf-8';

    $ch = curl_init ();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers );
    // curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] );
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );

    $result = curl_exec ( $ch );

    // $result = json_decode ($result,true);
    // print_r("+++++++++++++++++++++++++++++\n");
    // print_r($result);
    // print_r("+++++++++++++++++++++++++++++\n");
    
    $state_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset ( $ch );
    return ["res"=>$result,"state_code"=>$state_code];
}

