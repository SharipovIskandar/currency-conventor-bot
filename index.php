<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "6985551569:AAF1KLAE2EuPhi3MgI1d9_ef2uB48uXMNnQ";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$currencyClient = new Client(['base_uri' => 'https://cbu.uz/oz/arkhiv-kursov-valyut/json/']);

$response = $currencyClient->request('GET', '');
$data = json_decode($response->getBody()->getContents(), true);
$currencies = [];

foreach ($data as $item) {
    $currencies[strtolower($item['Ccy'])] = $item['Rate'];
}

$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
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
                        'text' => "Exchange rate for $amount $currency is $result UZS"
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
}
?>
