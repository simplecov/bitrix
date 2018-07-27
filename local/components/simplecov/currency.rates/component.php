<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $baseCurrency;

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

/**
 * Обработка внешнего запроса
 */
if($_GET['request'] == 'external')
{
    $arrData = $this->GetQueryData($arParams['SOURCE'], $_GET['date']);
    wwq($arrData);


//    wwq($result);
//    wwq($formattedDate);
//    wwq(CSite::GetDateFormat("SHORT"));

}


$this->IncludeComponentTemplate();



