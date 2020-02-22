<?php

require __DIR__ . '/app/classes/crest.php';
require_once __DIR__.'/app/configs/db.php';

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

    $raisingFactor = 1.10;

    $result = [];
    $result['result'] = 'success';
    $result['holeCount'] = $count;
    $result['materialType'] = $materialType;
    $result['paymentType'] = $paymentType;
    if($diameter > 350) {
        $result['finalPrice'] = 'договорная цена';
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
        $holePrice = $diameterPrice * $width;

        if($holePrice < 800) {
            $holePrice = 800;
        }
        
        $finalPrice = ($holePrice * $count) + $transportCost;

        if($finalPrice < 5000) {
            $finalPrice = 5000;
        }

        $result['startDiameterId'] = $startDiameterId;
        $result['startDiameterPrice'] = $diameterPrice;
        $result['oneHolePrice'] = $holePrice;
        $result['finalPrice'] = $finalPrice;

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    
}