<?php


declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$update = json_decode(file_get_contents('php://input'));


$token = "6985551569:AAF1KLAE2EuPhi3MgI1d9_ef2uB48uXMNnQ";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$currencyClient = new Client(['base_uri' => 'https://cbu.uz/oz/arkhiv-kursov-valyut/json/']);

$amountF = 0;

$response = $currencyClient->request('GET', '');
$data = json_decode($response->getBody()->getContents(), true);
$currencies = [];

foreach ($data as $item) {
    $currencies[strtolower($item['Ccy'])] = $item['Rate'];
}


if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $mid = $message->message_id;
    $name = $message->from->first_name;
    $fromId = $message->from->id;
    $text = $message->text;
    $photo = $message->photo ?? '';
    $video = $message->video ?? '';
    $audio = $message->audio ?? '';
    $voice = $message->voice ?? '';
    $reply = $message->reply_markup ?? '';

    $exp = explode('-', $text);

    if (count($exp) == 2) {
        $amount = floatval($exp[0]);
        $currency = strtolower($exp[1]);

        if (isset($currencies[$currency])) {
            $rate = $currencies[$currency];
            $result = $amount / $rate;

            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => "Exchange rate for $amount UZS is $result $currency"
                ]
            ]);
        } else {
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => "Currency not found."
                ]
            ]);
        }
    } else {
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => "Invalid format. Use: amount-currency (e.g., 12600-usd)"
            ]
        ]);
    }

}


$dsn = 'mysql:host=localhost;dbname=tgBot;charset=utf8';
$username = 'root';
$password = 'iskan2066';

$pdo = new PDO($dsn, $username, $password);


    $chatId = $update->message->chat->id;
    $conType = $update->message->text;
    $date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO data(chatId, conType, amount, date) VALUES (:chatId, :conType, :amount, :date)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':chatId', $chatId);
    $stmt->bindParam(':conType', $conType);
    $stmt->bindParam(':amount', $result);
    $stmt->bindParam(':date', $date);
    $stmt->execute();






