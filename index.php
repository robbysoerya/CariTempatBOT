<?php
require __DIR__ . '/vendor/autoload.php';
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// set false for production
// set LINE channel_access_token and channel_secret
$channel_access_token = "4naib3sEwmqONsibs3zg3/wXWyxha+MJK+vfbsed7GtFGPs82rT/muX+f/mAcPMNcNpHaLz7eoJCTjQXiiRB8YJrVqsAr3IeeDqGtENP6aE9S40HhWDzwGWTpRCfbMAD1obzedMo0dNkUY5ZBn7RZQdB04t89/1O/w1cDnyilFU=";
$channel_secret = "d7b1e75f288adbbca55bf606175bd2bc";


$channelAccessToken = '4naib3sEwmqONsibs3zg3/wXWyxha+MJK+vfbsed7GtFGPs82rT/muX+f/mAcPMNcNpHaLz7eoJCTjQXiiRB8YJrVqsAr3IeeDqGtENP6aE9S40HhWDzwGWTpRCfbMAD1obzedMo0dNkUY5ZBn7RZQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'd7b1e75f288adbbca55bf606175bd2bc';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$message 	= $client->parseEvents()[0]['message'];
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
	$lokasi = json_decode($request,true);
	file_put_contents('./balasan2.json',$request);
	
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
	


	
if(is_array($data['events'])){
		
    foreach ($data['events'] as $event2)
    {		
        if ($event2['type'] == 'message')
        {
			$pesan_masuk = 	strtolower($event2['message']['text']);
			
            if($event2['message']['type'] == 'text')
            {
				if($pesan_masuk == "rumah sakit"){
					
					$outputText = new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder("Eiffel Tower", "Champ de Mars, 5 Avenue Anatole France, 75007 Paris, France", 48.858328, 2.294750);
					$result = $bot->replyMessage($event2['replyToken'], $outputText);
					return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
				
				

}
}
}
}
}
}
);

$app->run();
