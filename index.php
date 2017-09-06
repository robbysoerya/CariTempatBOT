<?php
require __DIR__ . '/vendor/autoload.php';
require_once('./line_class.php');
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// set false for production
$pass_signature = true;

// set LINE channel_access_token and channel_secret
$channel_access_token = "4naib3sEwmqONsibs3zg3/wXWyxha+MJK+vfbsed7GtFGPs82rT/muX+f/mAcPMNcNpHaLz7eoJCTjQXiiRB8YJrVqsAr3IeeDqGtENP6aE9S40HhWDzwGWTpRCfbMAD1obzedMo0dNkUY5ZBn7RZQdB04t89/1O/w1cDnyilFU=";
$channel_secret = "d7b1e75f288adbbca55bf606175bd2bc";


$channelAccessToken = '4naib3sEwmqONsibs3zg3/wXWyxha+MJK+vfbsed7GtFGPs82rT/muX+f/mAcPMNcNpHaLz7eoJCTjQXiiRB8YJrVqsAr3IeeDqGtENP6aE9S40HhWDzwGWTpRCfbMAD1obzedMo0dNkUY5ZBn7RZQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'd7b1e75f288adbbca55bf606175bd2bc';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$replyToken = $client->parseEvents()[0]['replyToken'];
$pesan_datang = strtolower($message['text']);

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

// buat route untuk url homepage
$app->get('/', function($req, $res)
{
  echo "Welcome at Slim Framework";
});

// buat route untuk webhook
$app->post('/webhook', function ($request, $response) use ($bot, $pass_signature)
{
    // get request body and line signature header
    $body        = file_get_contents('php://input');
    $request = file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-6.357712,106.842937&radius=5000&type=hospital&keyword=rumah+sakit&key=AIzaSyC7jWhmMD7bR6JmfG9B8qwbSVapdDoze3o'); 
    
    $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if(empty($signature)){
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if(! SignatureValidator::validateSignature($body, $channel_secret, $signature)){
            return $response->withStatus(400, 'Invalid signature');
        }
    }

    // kode aplikasi nanti disini
	$data = json_decode($body, true);
	file_put_contents('./balasan.json',$body);
	
if(is_array($data['events'])){
    foreach ($data['events'] as $event)
    {		
        if ($event['type'] == 'message')
        {
            if($event['message']['type'] == 'location')
            {
				$latitude = $event['message']['latitude'];
				$longitude = $event['message']['longitude'];
				$geolocation = $latitude.','.$longitude;
                // send same message as reply to user
                $result = $bot->replyText($event['replyToken'], "Lokasi ditemukan : $latitude , $longitude");
                // or we can use replyMessage() instead to send reLaply message
                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
            }
        }
    }
}
	
if($message['type']=='text')
{
	if($pesan_datang == "rumah sakit"){
		
	$lokasi = json_decode($request,true);
	file_put_contents('./balasan2.json',$request);
	
	if(is_array($lokasi['results'])){
    foreach ($lokasi['results'] as $results)
    {	
		if ($lokasi['types'][0] == 'hospital')
        {
				
		$title = $results[0]['name'];
		$address = $results[0]['vicinity'];
		$lat = $results[0]['geometry']['location']['lat'];
		$long = $results[0]['geometry']['location']['lng'];
		
		$balas = array(
										'replyToken' 	=> $replyToken,
										'messages' => array(
												'type' => 'location',
												'title' => $title,
												'address' => $address,
												'latitude' => $lat,
												'longitude' => $long
							
						)
						);
						
    }
}

}
}
    

	
}
$result2 =  json_encode($balas);

file_put_contents('./lokasi.json',$result2);

$client->replyMessage($balas);

}
);

$app->run();
