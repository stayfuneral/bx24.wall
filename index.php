<?php require __DIR__.'/vendor/autoload.php';
if(!empty($_REQUEST['PLACEMENT_OPTIONS'])) {
    $placementOptions = json_decode($_REQUEST['PLACEMENT_OPTIONS']);
    $dealId = intval($placementOptions->ID);
    $dealInfo = CRest::call('crm.deal.get', ['ID' => $dealId])['result'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/app/assets/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="//api.bitrix24.com/api/v1/"></script>
</head>
<body>
    <div class="workarea">
        <div class="container-fluid">
            <div class="row mb-2">
                <div id="mainParams" class="col-4">
                    
                    <p class="font-weight-bold">Тип расчёта</p>            
                    <select name="paymentType" id="paymentType" class="formData form-control">
                        <option></option>
                        <option value="cash">Наличный</option>
                        <option value="wire">Безналичный</option>
                    </select>
                    <p class="font-weight-bold">Транспортные расходы</p>
                    <input type="number" name="transportCost" id="transportCost" class="formData form-control">
                    <input class="formData" type="hidden" name="dealId" id="dealId" value="<?=$dealId?>">
                </div>
                <div id="result" class="col-6">
                </div>
            </div>
            <div class="row">
                <div class="col-4 font-weight-bold">Материал:</div>
                <div class="col-4 font-weight-bold">Диаметр, мм:</div>
                <div class="col-4 font-weight-bold">Толщина, см:</div>
                
            </div>

            <div id="holeArea">    
                <div id="holeRow" class="row mt-1 mb-1">
                    <div class="col-4 font-weight-bold">
                        <select class="formData form-control mt-1" name="materialType[]" id="materialType">
                            <option></option>
                            <option value="1">Бетон</option>
                            <option value="2">Кирпич</option>
                        </select>        
                    </div>
                    <div class="col-4 font-weight-bold">
                        <input class="formData form-control mt-1" type="number" name="diameter[]" id="diameter" placeholder="диаметр в мм">
                    </div>
                    <div class="col-4 font-weight-bold">
                        <input class="formData form-control mt-1" type="number" name="width[]" id="width" placeholder="толщина в см">
                    </div>
                    
                </div>    
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-primary" id="addHole">Добавить отверстие</button>
                </div>        
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-success" id="calc" type="submit">Посчитать стоимость</button>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-danger" id="reset">Сбросить</button>
                </div>
            </div>
        </div>        
    </div>

<script>
    BX24.init(function() {
        BX24.resizeWindow(document.body.clientWidth, 650);
    });
</script>
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <script src="/app/assets/app.js"></script>
</body>
</html>