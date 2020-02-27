<?php

require __DIR__.'/../../vendor/autoload.php';

$jsonRawRequest = file_get_contents('php://input');
$Request = json_decode($jsonRawRequest);

$hole = new Hole;

if(!empty($Request)) {
    $dealId = $Request->dealId;
    $paymentType = htmlspecialchars($Request->paymentType);
    $transportCost = $Request->transportCost;
    $arHoleCount = $Request->holeCount;
    $arDiameter = $Request->diameter;
    $arWidth = $Request->width;
    $arTypes = $Request->materialType;
    $holeCounter = count($arDiameter);
    $response = [
        'result' => 'success',
        'dealId' => $dealId,
        'holeCounter' => $holeCounter,
        'holeCount' => $arHoleCount,
        'ts' => time()        
    ];

    $commentInfo = [];
    
    $contactType = $hole->getClientType($dealId);

    for($i = 0; $i < $holeCounter; $i++) {
        if($arDiameter[$i] < 350) {
            $startDiameterPrice = $hole->getDiameterPrice($arTypes[$i], $arDiameter[$i], $paymentType);
            $holePrice = $hole->getHolePrice($startDiameterPrice, $arWidth[$i]);

            $commentInfo['Отв. '.($i+1)] = 'Количество отверстий: '.$arHoleCount[$i].' шт.<br>'.
            'Диаметр отверстия:  '. $arDiameter[$i].' мм,<br>'.
            'Толщина отверстия: '. $arWidth[$i] .' см,<br>'.
            'Цена одного отверстия: '. $holePrice.' руб.<br>'.
            'Цена за все отверстия с выбранными параметрами: '. ($holePrice * $arHoleCount[$i]) .' руб.<br>';
            $hole->oneHolePrice[] = $holePrice;
            $hole->holePrices[] = $holePrice * $arHoleCount[$i];
            $hole->finalPrice +=($holePrice * $arHoleCount[$i]);
        } else {
            $hole->holePrices[] = 'Цена договорная';
            $commentInfo['Отв. '.($i+1)] = 'Количество отверстий: '.$arHoleCount[$i].' шт.<br>'.
            'Диаметр отверстия:  '. $arDiameter[$i].'мм,<br>'.
            'Толщина отверстия: '. $arWidth[$i] .'см,<br />'.
            'Цена договорная';
        }
        unset($holePrice, $startDiameterPrice);        
    }

    $hole->finalPrice = (($hole->finalPrice + $transportCost) < 5000) ? 5000 : $hole->finalPrice + $transportCost;

    $commentInfo['Транспортные расходы:'] = $transportCost.' руб.';

    if($contactType === 'SUPPLIER' || $contactType === 'PARTNER') {
        $hole->finalPrice = $hole->finalPrice - ($hole->finalPrice * $hole::CLIENT_DISCOUNT);
        $commentInfo['Итоговая сумма с учётом скидки: '] = $hole->finalPrice; 
    } else {
        $commentInfo['Итоговая сумма:'] = $hole->finalPrice.' руб.';
    }

    $fields = [
        'OPPORTUNITY' => $hole->finalPrice
    ];
    $comment = '';
    foreach($commentInfo as $key => $value) {
        $comment .= '<p><b>'.$key.'</b><br>'.$value.'</p>';
    }
    $fields['COMMENTS'] = $comment;

    $dealSum = $hole->setDealSum($dealId, $fields)['result'];

    $response['dealUpdate'] = $dealSum;
    $response['holePrices'] = $hole->holePrices;
    $response['oneHolePrice'] = $hole->oneHolePrice;
    $response['finalPrice'] = $hole->finalPrice;
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}