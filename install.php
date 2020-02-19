<?php require __DIR__ . '/app/classes/crest.php';

$result = CRest::installApp();
$setPlacement = CRest::call('placement.bind', [
    'PLACEMENT' => 'CRM_DEAL_DETAIL_TAB',
    'HANDLER' => 'https://wall.bot24.xyz/index.php',
    'TITLE' => 'Расчёт стоимости'
]);

/*
 * Сюда можно добавить любой код, который должен выполняться единоразово в процессе установки
 */
if($result['rest_only'] === false):?>
    <head>
        <script src="//api.bitrix24.com/api/v1/"></script>
        <?if($result['install'] == true):?>
        <script>
            BX24.init(function(){
                BX24.installFinish();
            });
        </script>
        <?endif;?>
    </head>
    <body>
        <?if($result['install'] == true):?>
            installation has been finished
        <?else:?>
            installation error
        <?endif;?>
    </body>
<?endif;