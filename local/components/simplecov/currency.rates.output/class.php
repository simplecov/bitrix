<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CurrencyRatesOutput extends CBitrixComponent
{
    private $hlBlockID;

    private $hlBlockEntity;

    public function __construct($component)
    {
        parent::__construct($component);

        $this->hlBlockID = HL_CURRENCY;
        $this->hlBlockEntity = Helper::getInstance()->GetEntityDataClass($this->hlBlockID);
    }

    public function GetElements($dateFrom, $dateTo = '')
    {
        $entity_data_class = $this->hlBlockEntity;

        $arFilter = [];
        if ($dateTo == '') {
            $arFilter['UF_DATE'] = Helper::getInstance()->FormatDateToSite($dateFrom);
        } else {
            $arFilter['LOGIC'] = "AND";
            $arFilter[] = [
                ">=UF_DATE" => Helper::getInstance()->FormatDateToSite($dateFrom),
            ];
            $arFilter[] = [
                "<=UF_DATE" => Helper::getInstance()->FormatDateToSite($dateTo),
            ];
        }

        $rsData = $entity_data_class::getList([
            'select' => ['*'],
            'filter' => $arFilter,
        ]);
        while ($el = $rsData->fetch()) {
            $this->arResult['ELEMENTS'][] = $el;
        }
    }

}