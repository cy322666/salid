<?php

use GuzzleHttp\Exception\GuzzleException;
use Ufee\Amo\Oauthapi;

try {

    require_once 'vendor/autoload.php';
    require_once 'Postback.php';

    file_put_contents('log-salid.log', date('Y-m-d H:i:s').' => '.json_encode($_POST)."\n", FILE_APPEND);

    if ($_GET['env'] == 'test') {

        $leadId = $_GET['lead_id'];
    } else
        $leadId = $_POST['status'][0]['id'] ?? $_POST['add'][0]['id'];

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

    if (!empty($lead->toArray()['custom_fields']['630237'])) {

        $source = $lead->toArray()['custom_fields']['630237']->values[0]->value;
//        echo '<pre>';print_r($source);echo '</pre>'; exit;
    } else {
        file_put_contents('log-salid.log', date('Y-m-d H:i:s').' => НЕТ SOURCE : '."\n", FILE_APPEND);

        exit;
    }

    $lead = $amoApi->leads()->find($leadId);

    file_put_contents('log-salid.log', date('Y-m-d H:i:s').' => SOURCE : '.$source.' PIPELINE : '.$lead->pipeline_id."\n", FILE_APPEND);

    if ($source !== 'salid' || $lead->pipeline_id !== 1679545) {

        file_put_contents('log-salid.log', date('Y-m-d H:i:s').' => НЕ НУЖНЫЙ lead : '.$leadId."\n", FILE_APPEND);

        exit;
    }

    $postback = new Postback();
    $postback->summa_zakaza = $lead->sale;
    $postback->id_polzovatelya = $lead->main_contact_id;
    $postback->id_zakaza = $leadId;
    $postback->utm_campaign = $lead->cf('UTM_CAMPAIGN')->getValue();

    $referrer = $lead->cf('REFERER')->getValue();

    if ($referrer) {

        $postback->utm_term = explode('fbclid=', $referrer)[1];

        if ($postback->utm_term == '') {

            $postback->utm_term = explode('utm_term=', $referrer)[1];
        }
    }

    switch ($lead->status_id) {

        case 143 :
            $response = $postback->refused();
            break;

        case 142 :
            $response = $postback->payed();
            break;

        case 25242892:
            $response = $postback->registration();
            break;

        case 28582030:
            $response = $postback->order();
            break;

        default:
            exit;
    }

    file_put_contents('log-salid.log', date('Y-m-d H:i:s').' => lead : '.$leadId.' > '.json_encode($postback->toArray())."\n", FILE_APPEND);

} catch (Exception $exception) {
} catch (GuzzleException $exception) {

//    echo '<pre>'; print_r($exception->getMessage()); echo '</pre>'; exit;

    file_put_contents('log-error.log', date('Y-m-d H:i:s').' => '.$exception->getFile().' '.$exception->getLine().' : '.$exception->getMessage(), FILE_APPEND);
}

