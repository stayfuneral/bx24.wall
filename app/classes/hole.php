<?php

class Hole {

    private $DB;

    private const RAISING_FACTOR = 1.10;
    const CLIENT_DISCOUNT = 0.10;

    public $holePrices = [];
    public $finalPrice = 0;

    public function __construct() {
        $this->DB = new DB;
    }

    public function getDiameterPrice($materialType, $diameter, $paymentType) {
        $startDiameterSql = "SELECT * FROM DIAMETERS WHERE start_diameter <= $diameter ORDER BY start_diameter DESC LIMIT 1";
        $startDiameterQuery = $this->DB->customSelect($startDiameterSql);

        $startDiameterId = intval($startDiameterQuery[0]['id']);
        $diameterPriceSql = "SELECT price FROM PRICES WHERE diameter_id = $startDiameterId && material_type_id = $materialType";
        $diameterPriceQuery = $this->DB->customSelect($diameterPriceSql);
        $diameterPrice = intval($diameterPriceQuery[0]['price']);
        if($paymentType === 'wire') {
            $diameterPrice = (float)(number_format(($diameterPrice * self::RAISING_FACTOR), 2, '.',''));
        }
        return $diameterPrice;
    }

    public function getHolePrice($diameterPrice, $width) {
        $holePrice = (($diameterPrice * $width) < 800) ? 800 : ($diameterPrice * $width);
        return $holePrice;
    }

    public function getClientType($dealId) {
        return CRest::callBatch([
            'getDeal' => [
                'method' => 'crm.deal.get',
                'params' => ['id' => $dealId]
            ],
            'getContact' => [
                'method' => 'crm.contact.get',
                'params' => ["ID" => '$result[getDeal][CONTACT_ID]']
            ]
        ])['result']['result']['getContact']['TYPE_ID'];
    }

    public function setDealSum($dealId, $fields) {
        return CRest::call('crm.deal.update', [
            'id' => $dealId,
            'fields' => $fields
        ]);
    }

}