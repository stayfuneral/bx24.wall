<?php require __DIR__ . '/app/classes/crest.php';
if(!empty($_REQUEST['PLACEMENT_OPTIONS'])) {
    $placementOptions = json_decode($_REQUEST['PLACEMENT_OPTIONS']);
    $dealId = $placementOptions->ID;
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
        <div id="app" class="container-fluid">
            <h1>Расчёт стоимости по сделке <?=$dealInfo['TITLE']?></h1>

            <table class="table table-responsive-sm table-borderless align-middle text-left">
                <tbody>
                    <tr class="row">
                        <td class="col-3">Количество отверстий</td>
                        <td class="col-6"><input class="formData" type="number" name="count" id="count"></td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">Диаметр отверстия, мм</td>
                        <td class="col-6"><input class="formData" type="number" name="diameter" id="diameter"></td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">Толщина отверстия, см</td>
                        <td class="col-6"><input class="formData" type="number" name="width" id="width"></td>
                    </tr>

                    <tr class="row">
                        <td class="col-3">Материал стены</td>
                        <td class="col-6">
                            <select name="materialType" id="materialType" class="formData">
                                <option></option>
                                <option value="1">Бетон</option>
                                <option value="2">Кирпич</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">Тип расчёта</td>
                        <td class="col-6">
                            <select class="formData" name="paymentType" id="paymentType">
                                <option></option>
                                <option value="cash">Наличный</option>
                                <option value="wire">Безналичный</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">Транспортные расходы</td>
                        <td class="col-6"><input class="formData" type="number" name="transportCost" id="transportCost"></td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">
                            <input class="formData" type="hidden" name="dealId" value="<?=$dealId?>" id="dealId">
                            <button type="submit">Рассчитать стоимость</button>
                        </td>
                        <td class="col-6">
                            <div id="result"></h3>
                        </td>
                    </tr>
                </tbody>
            </table>        
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