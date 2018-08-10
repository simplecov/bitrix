<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


$testData = [
  'base' => 'RUB',
  'date' => 2018 - 07 - 26,
  'rates' => [
    'AUD' => 0.021382149,
    'BGN' => 0.0264812608,
    'BRL' => 0.0589295386,
    'CAD' => 0.020698386,
  ],
];

/**
 * @global CMain         $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate()) {
    return;
}

/**
 * Обработка внешнего запроса
 */
if ($_GET['request'] == 'external') {
    $this->CreateDateArray();
    //$this->CreateQueryString($arParams['SOURCE'], $_GET['date']);
//    $arrData = $this->GetQueryData($arParams['SOURCE'], $_GET['date'], $_GET['getstack']);
//    wwq($arrData);
    //    if(!$this->CheckElementsDataExists($_GET['date']))
    //        $this->SaveData($arrData);
}

if ($_GET['request'] == 'internal') {
    $this->GetElements($_GET['dateFrom'], $_GET['dateTo']);
}

$this->ViewErrors();
$this->IncludeComponentTemplate();



