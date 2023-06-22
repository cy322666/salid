<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Saleads
{
    /**
     * @var Client
     */
    private Client $client;

    private static string $defaultUrlAds = 'https://my.saleads.pro/pb';

    public ?string $click = null;
    public ?string $condition = null;
    public ?string $utm_source = 'saleads';

    public ?int $leadId;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function create(): ResponseInterface
    {
        return $this->client->get(static::$defaultUrlAds.'/'.$this->click.'/839/'.$this->leadId, [
            'form_params' => [
                'status' => 1
//                'utm_source'   => $this->utm_source,
//                'foreignOrder' => $this->leadId,
            ]
        ]);
    }
}