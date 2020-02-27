<?php require __DIR__.'/vendor/autoload.php';
if(!empty($_REQUEST['PLACEMENT_OPTIONS'])) {
    $placementOptions = json_decode($_REQUEST['PLACEMENT_OPTIONS']);
    $dealId = intval($placementOptions->ID);
    $dealInfo = CRest::call('crm.deal.get', ['ID' => $dealId])['result'];
    $hole = new Hole;
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
                <div id="mainParams" class="col-3">
                    
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
            <div class="row mt-2 mb-2">
                <div class="col-3 font-weight-bold">Количество</div>
                
                <div class="col-3 font-weight-bold">Диаметр, мм:</div>
                <div class="col-3 font-weight-bold">Толщина, см:</div>
                <div class="col-3 font-weight-bold">Материал:</div>
                
            </div>

            <div id="holeArea">    
                <div id="holeRow" class="row mt-1 mb-1">
                    <div class="col-3 font-weight-bold">
                        <input class="formData form-control" type="text" name="holeCount[]" id="holeCount">
                    </div>
                    
                    <div class="col-3 font-weight-bold">
                        <select  class="formData form-control" name="diameter[]" id="diameter">
                            <option></option>
                            <?php foreach($hole->diameters as $diameter):?>
                            <option value="<?=$diameter?>"><?=$diameter?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-3 font-weight-bold">
                        <input class="formData form-control mt-1" type="text" name="width[]" id="width" placeholder="толщина в см">
                    </div>
                    <div class="col-3 font-weight-bold">
                        <select class="formData form-control mt-1" name="materialType[]" id="materialType">
                            <option></option>
                            <option value="1">Бетон</option>
                            <option value="2">Кирпич</option>
                        </select>        
                    </div>
                    
                </div>    
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-primary" id="addHole">Новое</button>
                </div>        
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-success" id="calc" type="submit">Итого</button>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <button class="ui-btn ui-btn-danger" id="reset">Сброс</button>
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