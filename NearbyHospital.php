<?php 
$geolocation = $latitude.','.$longitude;
$request = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-6.357036,106.843065&radius=5000&type=hospital&keyword=rumah+sakit&key=AIzaSyC7jWhmMD7bR6JmfG9B8qwbSVapdDoze3o'; 
$file_contents = file_get_contents($request);
$replyToken = $bot->parseEvents()[0]['replyToken'];
$lokasi = json_decode($file_contents);

if(is_array($lokasi['results'])){
    foreach ($lokasi['results'] as $results)
    {	
		$title = $results[0]['name'];
		$address = $results[0]['vicinity'];
		$lat = $results[0]['geometry']['location']['lat'];
		$long = $results[0]['geometry']['location']['lng'];
		
		$get_sub = array();
		$aa = array(
										'type' => 'location',
										'title' => $title,
										'address' => $address,
										'latitude' => $lat,
										'longitude' => $long
							
						);
		array_push($get_sub,$aa);
		
		$get_sub[] = array(
		    
		                'type' => 'text',
						'text' => 'Terimakasih telah menggunakan layanan ini'
		);
		$balas = array(
					'replyToken' 	=> $replyToken,														
					'messages' 		=> $get_sub
				 );	
				
    }
    
}
$result =  json_encode($balas);

file_put_contents('./balasan.json',$result);


$client->replyMessage($balas);
?>
