<?php


require_once('./line_class.php');

$channelAccessToken = 'ioeCVbUnTf8wVO7u4dsxAomYXuUBi3JgGmvdQx1x1KZWN9F1vQynjMK6pYYPH/0qudOGGAU+4E4SOei1Kk3z3yqzB4zpnYMBaNHnPFjz+csRRDVu3JMCDBfmqDj+PO5KzorW7hwBPWKzju+wsryCrQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6c872e40ef73d069b56bc459d5ff7f87';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];


$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = strtolower($message['text']);
$kode = substr("$pesan_datang",0,3);
$kode2 = substr("$pesan_datang",0,9);

if($message['type']=='text')
{
	if($kode=='npm')
	{

$npm = substr("$pesan_datang",4,8);
$content = file_get_contents('http://library.gunadarma.ac.id/deposit-system/redirect.php?npmnya='.$npm.''); 

libxml_use_internal_errors(true);
$doc = new DOMDocument();
$doc->loadHTML($content);
libxml_clear_errors();

$tags = $doc->getElementsByTagName('input');

foreach ($tags as $tag) {
    if($tag->getAttribute('name') === 'password') {
         $token2 = $tag->getAttribute('value');
    }
    if($tag->getAttribute('name') === 'username') {
         $token = $tag->getAttribute('value');
}

$isi = "Username : $token\nPassword : $token2";
		$balas = array(
							'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => $isi
									)
							)
						);
				
	}
	
 }else if($kode2=='informasi'){

$user_agent       = "Mozilla/5.0 (X11; Linux i686; rv:24.0) Gecko/20140319 Firefox/24.0 Iceweasel/24.4.0";
$curl_crack = curl_init();

CURL_SETOPT($curl_crack,CURLOPT_URL,"http://library.gunadarma.ac.id/epaper/login/");
CURL_SETOPT($curl_crack,CURLOPT_USERAGENT,$user_agent);
CURL_SETOPT($curl_crack,CURLOPT_POST,True);
CURL_SETOPT($curl_crack,CURLOPT_POSTFIELDS,"username=admin&password=wowkeren&login=yes");
CURL_SETOPT($curl_crack,CURLOPT_RETURNTRANSFER,True);
CURL_SETOPT($curl_crack,CURLOPT_FOLLOWLOCATION,True);
CURL_SETOPT($curl_crack,CURLOPT_COOKIEFILE,"cookie.txt"); //Put the full path of the cookie file if you want it to write on it
CURL_SETOPT($curl_crack,CURLOPT_COOKIEJAR,"cookie.txt"); //Put the full path of the cookie file if you want it to write on it
CURL_SETOPT($curl_crack,CURLOPT_CONNECTTIMEOUT,30);
CURL_SETOPT($curl_crack,CURLOPT_TIMEOUT,30);  

$exec = curl_exec($curl_crack);
if(preg_match("/^you are logged|logout|successfully logged$/i",$exec))
{
	$npm2 = substr("$pesan_datang",10,8);
    $post = array('search' => 'keyword', 'abc' => 'xyz');

    curl_setopt($curl_crack, CURLOPT_POST, 1); // change back to GET
    curl_setopt($curl_crack, CURLOPT_POSTFIELDS, http_build_query($post)); // set post data
    curl_setopt($curl_crack, CURLOPT_URL, 'http://library.gunadarma.ac.id/deposit-system/administrasi.aktivasi/edit/'.$npm2.''); // set url for next request

    $exec = curl_exec($curl_crack); // make request to buy on the same handle with the current login session
    libxml_use_internal_errors(true);
$doc = new DOMDocument();
$doc->loadHTML($exec);
libxml_clear_errors();

$tags = $doc->getElementsByTagName('input');
$tags2 = $doc->getElementById('id_alamat');  

foreach ($tags as $tag) {
    if($tag->getAttribute('name') === 'realname') {
         $a = $tag->getAttribute('value');
    }
    if($tag->getAttribute('name') === 'nomorhp') {
         $b = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'alamat') {
         $c = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'rt') {
         $d = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'rw') {
         $e = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'kota') {
         $f = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'kodepos') {
         $g = $tag->getAttribute('value');
}
	if($tag->getAttribute('name') === 'kelas') {
         $h = $tag->getAttribute('value');
}
}
}
$alamat = $tags2->textContent;
$isi2 = "Nama : $a\nAlamat : $alamat Rt $d Rw $e Kota $f Kodepos $g\nKelas : $h\nNomor HP : $b"; 
	 $balas = array(
							'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => $isi2
									)
							)
						);

 }				
$result =  json_encode($balas);

file_put_contents('./balasan.json',$result);


$client->replyMessage($balas);

}
