<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



$testData = array(
    'base' => 'RUB',
    'date' => 2018-07-26,
    'rates' => Array(
        'AUD' => 0.021382149,
        'BGN' => 0.0264812608,
        'BRL' => 0.0589295386,
        'CAD' => 0.020698386
    )
);

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate())
	return;

/**
 * Инициализируем класс
 */
$this->init();

global $baseCurrency;
$baseCurrency = $this->GetSiteCurrency();
/**
 * Обработка внешнего запроса
 */
if($_GET['request'] == 'external')
{
    $arrData = $this->GetQueryData($arParams['SOURCE'], $_GET['date']);
    if(!$this->CheckElementsDataExists($_GET['date']))
        $this->SaveData($arrData);
}

if($_GET['request'] == 'internal')
{
    $arrData = $this->GetQueryData($arParams['SOURCE'], $_GET['date']);
    //$this->SaveData($arrData);
}

$this->ViewErrors();

$this->IncludeComponentTemplate();



