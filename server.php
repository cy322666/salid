<?php

use Ufee\Amo\Oauthapi;

try {

    require_once 'vendor/autoload.php';

    $leadId = $_POST['update'][0]['id'] ?? $_POST['add'][0]['id'];

    $amoApi = Oauthapi::getInstance('154bbcc6-c232-44ba-a705-1bd375dba1d1');

    print_r($amoApi->account->toArray());

    $lead = $amoApi->leads()->find($leadId);

    $postback = new Postback();
    $postback->sale = $lead->sale;
    $postback->id_polzovatelya = $lead->contact->id;
    $postback->id_zakaza = $leadId;
    $postback->utm_medium = $lead->cf('utm_medium')->getValue();
    $postback->utm_campaign = $lead->cf('utm_campaign')->getValue();
    $postback->utm_term = $lead->cf('utm_term')->getValue();

    switch ($lead->status_id) {

        case 143 :
            $response = $postback->refused();
            break;

        case 142 ://TODO
            $response = $postback->payed();
            break;

        case '' ://TODO
            $response = $postback->registration();
            break;

        case '2' ://TODO
            $response = $postback->order();
            break;

        default:
            'msg save';
            exit;
    }

    $msg = $response->getResponse()->getMessage();

} catch (Exception $exception) {


}

