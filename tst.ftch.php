<?php

require __DIR__.'/vendor/autoload.php';

$jsonRawRequest = file_get_contents('php://input');
$Request = json_decode($jsonRawRequest);

$hole = new Hole;

if(!empty($Request)) {
    $dealId = $Request->dealId;
    $paymentType = htmlspecialchars($Request->paymentType);
    $transportCost = $Request->transportCost;

    $arDiameter = $Request->diameter;
    $arWidth = $Request->width;
    $arTypes = $Request->materialType;

    

    $holeCounter = count($Request->diameter);

    $response = [
        'result' => 'success',
        'dealId' => $dealId,
        'holeCounter' => $holeCounter,
        'ts' => time()        
    ];

    $commentInfo = [];
    

    for($i = 0; $i < $holeCounter; $i++) {
        if($arDiameter[$i] < 350) {
            $startDiameterPrice = $hole->getDiameterPrice($arTypes[$i], $arDiameter[$i], $paymentType);
            $holePrice = $hole->getHolePrice($startDiameterPrice, $arWidth[$i]);
            $commentInfo['Отв. '.($i+1)] = 'Диаметр отверстия:  '. $arDiameter[$i].'мм,<br>'.
            'Толщина отверстия: '. $arWidth[$i] .'см,<br>'.
            'Цена отверстия: '. $holePrice.' руб.';
            $hole->holePrices[] = $holePrice;
            $hole->finalPrice +=$holePrice;
        } else {
            $commentInfo[] = 'Диаметр отверстия:  '. $arDiameter[$i].'мм,<br>'.
            'Толщина отверстия: '. $arWidth[$i] .'см,<br />'.
            'Цена договорная';
        }        
    }
    $hole->finalPrice = (($hole->finalPrice + $transportCost) < 5000) ? 5000 : $hole->finalPrice + $transportCost;

    $contactType = $hole->getClientType($dealId);

    if($contactType === 'SUPPLIER' || $contactType === 'PARTNER') {
        $hole->finalPrice = $hole->finalPrice - ($hole->finalPrice * $hole::CLIENT_DISCOUNT);
    }

    $fields = [
        'OPPORTUNITY' => $hole->finalPrice
    ];
    $comment = '';
    foreach($commentInfo as $key => $value) {
        $comment .= '<p><b>'.$key.'</b><br>'.$value.'</p>';
    }
    $fields['COMMENTS'] = $comment;

    $dealSum = $hole->setDealSum($dealId, $fields);

    $response['dealUpdate'] = $dealSum;
    $response['holePrices'] = $hole->holePrices;
    $response['finalPrice'] = $hole->finalPrice;
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}