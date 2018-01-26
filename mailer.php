<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/vendor/autoload.php';
require_once 'common.php';



function qq_send_qq($info_arr){

	$FromName = $info_arr['FromName'];
	$Username = $info_arr['Username'];
	$Password = unserialize(COMPWD)[$Username];
	$From = $info_arr['From'];
	$AddAddress_arr = $info_arr['AddAddress_arr'];
	$Subject = $info_arr['Subject'];
	$Body = is_array($info_arr['Body'])? implode("<br>",$info_arr['Body']) : $info_arr['Body'];


	// 实例化PHPMailer核心类
	$mail = new PHPMailer();
	// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
	$mail->SMTPDebug = 1;
	// 使用smtp鉴权方式发送邮件
	$mail->isSMTP();
	// smtp需要鉴权 这个必须是true
	$mail->SMTPAuth = true;
	// 链接qq域名邮箱的服务器地址
	$mail->Host = 'smtp.qq.com';
	// 设置使用ssl加密方式登录鉴权
	$mail->SMTPSecure = 'ssl';
	// 设置ssl连接smtp服务器的远程服务器端口号
	$mail->Port = 465;
	// 设置发送的邮件的编码
	$mail->CharSet = 'UTF-8';
	// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
	$mail->FromName = $FromName;
	// smtp登录的账号 QQ邮箱即可
	$mail->Username = $Username;
	// smtp登录的密码 使用生成的授权码
	$mail->Password = $Password;
	// 设置发件人邮箱地址 同登录账号
	$mail->From = $From;
	// 邮件正文是否为html编码 注意此处是一个方法
	$mail->isHTML(true);
	// 设置收件人邮箱地址
	// $mail->addAddress('whoto@example.com', 'John Doe');
	
	foreach ($AddAddress_arr as $key => $value) {
	 	# code...
		$mail->addAddress($value);
	 	
	 } 
	
	// 添加多个收件人 则多次调用方法即可
	// $mail->addAddress('zhangze1029@gmail.com');
	// 添加该邮件的主题
	$mail->Subject = $Subject;
	// 添加邮件正文
	$mail->Body = $Body;
	// 为该邮件添加附件
	// $mail->addAttachment('./example.pdf');
	// 发送邮件 返回状态
	$status = $mail->send();




}











// $mail = new PHPMailer;
// //Tell PHPMailer to use SMTP
// $mail->isSMTP();
// //Enable SMTP debugging
// // 0 = off (for production use)
// // 1 = client messages
// // 2 = client and server messages
// $mail->SMTPDebug = 2;
// //Set the hostname of the mail server
// $mail->Host = 'smtp.gmail.com';
// // use
// // $mail->Host = gethostbyname('smtp.gmail.com');
// // if your network does not support SMTP over IPv6
// //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
// $mail->Port = 587;
// //Set the encryption system to use - ssl (deprecated) or tls
// $mail->SMTPSecure = 'tls';
// //Whether to use SMTP authentication
// $mail->SMTPAuth = true;
// //Username to use for SMTP authentication - use full email address for gmail
// $mail->Username = "zhangze1029@gmail.com";
// //Password to use for SMTP authentication
// $mail->Password = "Aa108163207811";
// //Set who the message is to be sent from
// $mail->setFrom('from@example.com', 'First Last');
// //Set an alternative reply-to address
// $mail->addReplyTo('replyto@example.com', 'First Last');
// //Set who the message is to be sent to
// $mail->addAddress('605166577@qq.com', 'John Doe');
// //Set the subject line
// $mail->Subject = 'PHPMailer GMail SMTP test';
// //Read an HTML message body from an external file, convert referenced images to embedded,
// //convert HTML into a basic plain-text alternative body
// $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
// //Replace the plain text body with one created manually
// $mail->AltBody = 'This is a plain-text message body';
// //Attach an image file
// // $mail->addAttachment('images/phpmailer_mini.png');
// //send the message, check for errors
// if (!$mail->send()) {
//     echo "Mailer Error: " . $mail->ErrorInfo;
// } else {
//     echo "Message sent!";
//     //Section 2: IMAP
//     //Uncomment these to save your message in the 'Sent Mail' folder.
//     #if (save_mail($mail)) {
//     #    echo "Message saved!";
//     #}
// }
// //Section 2: IMAP
// //IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
// //Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
// //You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
// //be useful if you are trying to get this working on a non-Gmail IMAP server.
// function save_mail($mail)
// {
//     //You can change 'Sent Mail' to any other folder or tag
//     $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
//     //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
//     $imapStream = imap_open($path, $mail->Username, $mail->Password);
//     $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
//     imap_close($imapStream);
//     return $result;
// }
