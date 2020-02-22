<?php

require __DIR__ . '/../classes/crest.php';
require_once __DIR__.'/../configs/db.php';

$DB = new DB();

$jsonRawData = file_get_contents('php://input');
$jsonDecodedData = json_decode($jsonRawData);

if(!empty($jsonDecodedData)) {
    $count = intval($jsonDecodedData->count);
    $diameter = intval($jsonDecodedData->diameter);
    $width = intval($jsonDecodedData->width);
    $materialType = intval($jsonDecodedData->materialType);
    $paymentType = htmlspecialchars($jsonDecodedData->paymentType);
    $transportCost = intval($jsonDecodedData->transportCost);
    (!empty($jsonDecodedData->dealId)) ? $dealId = intval($jsonDecodedData->dealId) : null;
    $raisingFactor = 1.10;

    $result = [];
    (!empty($jsonDecodedData->dealId)) ? $result['dealId'] = $dealId : null;
    $result['result'] = 'success';
    if($diameter > 350) {
        $agreedPrice = 'Цена договорная';
        $result['finalPrice'] = 'Цена договорная';
        echo json_encode($result, JSON_UNESCAPED_UNICODE);

    } else {
        $startDiameterSql = "SELECT * FROM DIAMETERS WHERE start_diameter <= $diameter ORDER BY start_diameter DESC LIMIT 1";
        $startDiameterQuery = $DB->customSelect($startDiameterSql);
        $startDiameterId = intval($startDiameterQuery[0]['id']);

        $diameterPriceSql = "SELECT price FROM PRICES WHERE diameter_id = $startDiameterId && material_type_id = $materialType";

        $diameterPriceQuery = $DB->customSelect($diameterPriceSql);
        $diameterPrice = intval($diameterPriceQuery[0]['price']);
        if($paymentType === 'wire') {
            $diameterPrice = (float)(number_format(($diameterPrice * $raisingFactor), 2, '.',''));
        }
        $holePrice = (($diameterPrice * $width) < 800) ? 800 : ($diameterPrice * $width);
        $finalPrice = (($holePrice * $count) + $transportCost) < 5000 ? 5000 : ($holePrice * $count) + $transportCost;

        $contactType = CRest::callBatch([
            'get_deal' => [
                'method' => 'crm.deal.get',
                'params' => [
                    'ID' => $dealId
                ]
            ],
            'get_contact' => [
                'method' => 'crm.contact.get',
                'params' => [
                    "ID" => '$result[get_deal][CONTACT_ID]'
                ]
            ]
        ])['result']['result']['get_contact']['TYPE_ID'];

        ($contactType === 'SUPPLIER' || $contactType === 'PARTNER') ? $finalPrice = $finalPrice - ($finalPrice * 0.10) : null;
        if(!isset($agreedPrice)) {
            $setPrice = CRest::call('crm.deal.update', [
                'id' => $dealId,
                'fields' => [
                    'OPPORTUNITY' => $finalPrice
                ]
            ]);
        }
        $result['oneHolePrice'] = $holePrice;
        $result['finalPrice'] = $finalPrice;
        $result['updateDeal'] = (!empty($setPrice['result'])) ? $setPrice['result'] : $setPrice;
        $result['contact'] = $contactType;

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    
}