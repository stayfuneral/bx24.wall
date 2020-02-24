<?php require __DIR__ . '/app/classes/crest.php';

$result = CRest::installApp();
$setPlacement = CRest::call('placement.bind', [
    'PLACEMENT' => 'CRM_DEAL_DETAIL_TAB',
    'HANDLER' => 'https://wall.bot24.xyz/index.php',
    'TITLE' => 'Расчёт стоимости'
]);