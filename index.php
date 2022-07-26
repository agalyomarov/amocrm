<?php 
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
require "vendor/autoload.php";
use GuzzleHttp\Client;
$CLIENT_ID='28748a51-5685-49cb-8930-3e2f333d862b';
$CLIENT_SECRET='pX4rvoPFK1XtJXRl6JAEwxlmKqQ07fXe2uyySO0gzWfFBntuqQTqFcA1JDMHwK6m';
$CODE='def50200382ea040de5e278a04dd6efd95e0a605226a2a6469cd0ff82906542753a361be67c75ce64e1ce6b6abd92e5e4bac8bf09ca0775c256d4290f73857336948eb214d39c012e6ece8da2469b1640ec64b2a873d333966d289244076d9ab6d1a75a70796f6f0d8265c1b31edf717a858b6753225c4557d8f1609052ee99bac2b1b09518cfa66ebcb3b0e7b4372984dae1ffbb8af0d857bef39d6cbec244a3cc6d8e0dab9e0e26a98327f68914171c681793070184e4c611f1105e039d9e001e1f4f18800fecd3714e8a359bd0a20b6c071b70e44728ee04a14e645e8384785a6a79c3ac6115608d3899bca0d9958fd8a6f02e394e1d1a636a8104c91f7de2129c903c547dd628638e4ab3c63baa08f31b838cbc82181141bc9b2be6e59307f546c74793816d0dc38b3b240d1d32ccac9e05b3d46ee8f431111a4f4729a28e07a50806c09680a29bef3dd085a74b42c3322b170abe04ccf42841ad8ed9474f272d8c4c8f142477fc6bdb8b3ed935a4dc70fb2026aee4884c011f629032b8c47c10a6f491288cfff8b59df75a20a6a0ab178c703c064a62e9a09849c067407c35afc8ca5';
$URL='https://mytestdomenfortmsat.site';
$client = new Client();
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
if(isset($_GET['access_token'])){
    $response = $client->post('https://pillargroup.amocrm.ru/oauth2/access_token',[
    'form_params' => [
        'client_id' => $CLIENT_ID,
        'client_secret' => $CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'code' => $CODE,
        'redirect_uri'=> $URL
    ],
    'timeout' => 5
]);
$body = $response->getBody();
$content=$body->getContents();
file_put_contents('token.txt',$content);
die;
}
if(isset($_GET['refresh_token'])){
    $data=file_get_contents('token.txt');
    $array=json_decode($data,true);
    $refresh_token=$array['refresh_token'];
    $response = $client->post('https://pillargroup.amocrm.ru/oauth2/access_token',[
    'form_params' => [
        'client_id' => $CLIENT_ID,
        'client_secret' => $CLIENT_SECRET,
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'redirect_uri'=> $URL
    ],
    'timeout' => 5
]);
$body = $response->getBody();
$content=$body->getContents();
file_put_contents('token.txt',$content);
die;
}
$input = file_get_contents("php://input");
parse_str(urldecode($input),$array);
$id=$array['leads']['status'][0]['id'];
$token=file_get_contents('token.txt');
$token=json_decode($token,true);
$access_token=$token['access_token'];
try{
$response = $client->get('https://pillargroup.amocrm.ru/api/v4/leads/'.$id,[
    'headers' => [
         'Authorization' => 'Bearer '.$access_token
    ]
]);
$body = $response->getBody();
$content=$body->getContents();
$content=json_decode($content,true);
$custom_fields_values=$content['custom_fields_values'];
$id693407=0;
foreach($custom_fields_values as $object){
    if($object['field_id']=='693407'){
       $id693407 = $object['values'][0]['value'];
    }
}
$id693407=$id693407*3;
$response = $client->patch('https://pillargroup.amocrm.ru/api/v4/leads/'.$id,[
    'headers' => [
        'Authorization' => 'Bearer '.$access_token,
        'Content-Type'=>'application/json',
     ],
    "json"=>[
     "price"=>$id693407
    ],
]);
}catch(\Exception $e){
    echo $e->getMessage();
}


