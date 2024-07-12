<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Currency
{
    const string CB_RATE_API_URL = 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/';

    private Client $http;
    private PDO $pdo;

    public function __construct()
    {
        $this->http = new Client(['base_uri' => self::CB_RATE_API_URL]);
        $this->pdo  = DB::connect();
    }

    /**
     * @throws GuzzleException
     */
    public function getRates()
    {
        $response = $this->http->get('');
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @throws GuzzleException
     */
    public function getUsd()
    {
        $rates = $this->getRates();
        return $rates[0]; // Assuming $rates[0] contains the USD rate, adjust as per API response structure
    }

    /**
     * @throws GuzzleException
     */
    public function convert(
        int    $chatId,
        string $originalCurrency,
        string $targetCurrency,
        float  $amount
    )
    {
        $now    = date('Y-m-d H:i:s');
        $status = "{$originalCurrency}2{$targetCurrency}";
        $rate   = $this->getUsd()->Rate; // Adjust to get the correct rate based on your API response

        $stmt = $this->pdo->prepare("INSERT INTO data (chatId, conType, amount, date) VALUES (:chatId, :conType, :amount, :date)");
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':conType', $status);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $now);
        $stmt->execute();

        if ($originalCurrency === 'usd') {
            $result = $amount * $rate;
        } else {
            $result = $amount / $rate;
        }

        $result = number_format($result, 0, '', '.');

        if ($originalCurrency === 'usd') {
            return $result . " $originalCurrency";
        } else {
            return $result . " $targetCurrency";
        }
    }
}
