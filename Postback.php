<?php

use Guzzle\Http\Client;

class Postback
{
    /**
     * @var Client
     */
    private $client;

    private static string $defaultUrlAds = 'https://salid.ru/postback/ads.php';

    private static string $utm_source = 'utm_medium';
    public string $utm_medium;
    public string $utm_content;
    public string $utm_term;
    public string $utm_campaign;

    public int $sale;
    public string $id_polzovatelya;
    public string $id_zakaza;
    public string $summa_zakaza;
    public string $klient = 'siriusfuture';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function registration()
    {
        return $this->client->get(static::$defaultUrlAds, null, [
            'offer'     => $this->utm_medium,
            'webmaster' => $this->utm_campaign,
            'clickid'   => $this->utm_term,
            'summa_zakaza' => $this->summa_zakaza,
            'id_polzovatelya' => $this->id_polzovatelya,
            'klient'    => $this->klient,
            'cel'       => 'registration',
        ]);
    }

    public function order()
    {
        return $this->client->get(static::$defaultUrlAds, null, [
            'offer'     => $this->utm_medium,
            'webmaster' => $this->utm_campaign,
            'clickid'   => $this->utm_term,
            'id_polzovatelya' => $this->id_polzovatelya,
            'klient'    => $this->klient,
            'cel'       => 'order',
        ]);
    }

    public function payed()
    {
        return $this->client->get(static::$defaultUrlAds, null, [
            'offer'     => $this->utm_medium,
            'webmaster' => $this->utm_campaign,
            'clickid'   => $this->utm_term,
            'id_polzovatelya' => $this->id_polzovatelya,
            'klient'    => $this->klient,
            'cel'       => 'sale',
        ]);
    }

    public function refused()
    {
        return $this->client->get(static::$defaultUrlAds, null, [
            'offer'     => $this->utm_medium,
            'webmaster' => $this->utm_campaign,
            'clickid'   => $this->utm_term,
            'id_polzovatelya' => $this->id_polzovatelya,
            'klient'    => $this->klient,
            'cel'       => 'cancel',
        ]);
    }
}