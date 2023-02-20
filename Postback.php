<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Postback
{
    /**
     * @var Client
     */
    private Client $client;

//    private static string $defaultUrlAds = 'https://webhook.site/1c780d37-9f6f-4371-8552-cd43e583ccb9';
    private static string $defaultUrlAds = 'https://salid.ru/postback/ads.php';

    public ?string $utm_medium = 'offer1234';
    public ?string $utm_term = '';
    public ?string $utm_campaign;

    public ?int $id_polzovatelya;
    public ?int $id_zakaza;
    public ?int $summa_zakaza;
    public ?string $klient = 'siriusfuture';

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function registration(): ResponseInterface
    {
        return $this->client->get(static::$defaultUrlAds, [
            'query' => [
                'offer'     => $this->utm_medium ?? '',
                'webmaster' => $this->utm_campaign ?? '',
                'clickid'   => $this->utm_term ?? '',
                'id_polzovatelya' => $this->id_polzovatelya ?? '',
                'klient'    => $this->klient,
                'cel'       => 'registration',
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function order(): ResponseInterface
    {
        return $this->client->get(static::$defaultUrlAds, [
            'query' => [
                'offer'     => $this->utm_medium,
                'webmaster' => $this->utm_campaign,
                'clickid'   => $this->utm_term,
                'id_polzovatelya' => $this->id_polzovatelya,
                'klient'    => $this->klient,
                'id_zakaza' => $this->id_zakaza,
                'summa_zakaza' => $this->summa_zakaza,
                'cel'       => 'order',
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function payed(): ResponseInterface
    {
        return $this->client->get(static::$defaultUrlAds, [
            'query' => [
                'offer'     => $this->utm_medium,
                'webmaster' => $this->utm_campaign,
                'clickid'   => $this->utm_term,
                'id_polzovatelya' => $this->id_polzovatelya,
                'klient'    => $this->klient,
                'id_zakaza' => $this->id_zakaza,
                'summa_zakaza' => $this->summa_zakaza,
                'cel'       => 'sale',
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function refused(): ResponseInterface
    {
        return $this->client->get(static::$defaultUrlAds, [
                'query' => [
                    'offer'     => $this->utm_medium,
                    'webmaster' => $this->utm_campaign,
                    'clickid'   => $this->utm_term,
                    'id_polzovatelya' => $this->id_polzovatelya,
                    'klient'    => $this->klient,
                    'id_zakaza' => $this->id_zakaza,
                    'cel'       => 'cancel',
            ]
        ]);
    }

    public function toArray() : array
    {
        return [
            'offer'     => $this->utm_medium,
            'webmaster' => $this->utm_campaign,
            'clickid'   => $this->utm_term,
            'id_polzovatelya' => $this->id_polzovatelya,
            'klient'    => $this->klient,
            'id_zakaza' => $this->id_zakaza,
            'summa_zakaza' => $this->summa_zakaza,
        ];
    }
}