<?php

use GuzzleHttp\Exception\GuzzleException;
use Ufee\Amo\Oauthapi;

try {
    require_once 'vendor/autoload.php';
    require_once 'Saleads.php.php';

    file_put_contents('log-leadsale.log', date('Y-m-d H:i:s').' => '.json_encode($_POST)."\n", FILE_APPEND);

    if ($_GET['env'] == 'test') {
        $leadId = $_GET['lead_id'];
    } else {
        $leadId = $_POST['status'][0]['id'] ?? $_POST['add'][0]['id'];
    }

    $client_id = '154bbcc6-c232-44ba-a705-1bd375dba1d1';
    $client_secret = 'yovyYOLEhC5CuUbKoqU1ZLAoj7dpEG3zJzt1pt6sZ28C8ibk5OGKrs6jbeOMCFj2';
    $access_code = 'def502004ddc15e8a1a79b3f00b4ca13b8f5a4045ac03e35984b3a9bedbad6b1e6f59a4b2bd5a0345a031363737d84f8d8c53482aaef3468a505731f67083d1cb260f91062c2da22272836c55638761cf7433388a512a062d8a532d8c837778012f4fbcac096d7cd4cb57634d8201f145876715b2d649ac957860da72227c0b872a743fbcf7cffb9a5e83f52cf3b9ebf798838680a0c3635aca91fa84ad714ae85d0826c734f9f9e8599b5fa8e8cb6c1f0e3427d9e96a0c0bb1b3819d5a031d13bc8d7315afcf224208d22ed49f282683a7a26b5f25c90a4dcd96355f77c4089870fd90e7e87fc91271d73cd4bd97f62200b279e0d9bc610621e1e685475ac529bd713abdb010d77be37e1568c03680e8ab42f82ad6bcb6a967fd50b99597ac5a829fcb1268c5d6daa3a6f931213c3e745fdff40bb778938ad078cd06312f4e9ac70cecc0b223a5863a8117f75b05c6c471edb657cb3ff1c263ee234089d2f31b5d8e6d749f9c764a0460adff8d987a7c892989e335f938a85591965f91dd7b95107fca0aa9d36fd5335765c8c5f9f881a58241fe8dc503d457e71b104f63d62c77cae';
    $amoCRM = \Ufee\Amo\Oauthapi::setInstance([
        'domain' => 'siriusfuture',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => 'https://siriusfuture.ru/salid',
    ]);

    $amoApi = Oauthapi::getInstance($client_id);

    $lead = $amoApi->leads()->find($leadId);

    if ($lead->cf('UTM_SOURCE')->getValue() !== 'saleads') exit;

    $saleleads = new Saleads();
    $saleleads->leadId = $leadId;
    $saleleads->condition = 123;


} catch (Exception $exception) {
} catch (GuzzleException $exception) {

    file_put_contents('log-error.log', date('Y-m-d H:i:s').' => '.$exception->getFile().' '.$exception->getLine().' : '.$exception->getMessage(), FILE_APPEND);
}
