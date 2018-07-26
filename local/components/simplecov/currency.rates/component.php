<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $baseCurrency;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate())
	return;



// Код базовой валюты сайта
$baseCurrency = CCurrency::GetBaseCurrency();
wwq($baseCurrency);

// Дата по запросу пользователя
$date = strlen($_GET['date']) ? $_GET['date'] : 'latest';

// Сбор строки запроса
$query = $arParams['SOURCE']; // базовый адрес сервиса
$query .= $date; // указываем дату
$query .= '?base=' . $baseCurrency; // указываем базовую валюту сайта
wwq($query);

//$data = file_get_contents('https://exchangeratesapi.io/api/latest?symbols=USD,RUB,EUR');
//$data = file_get_contents('https://exchangeratesapi.io/api/latest');
//$arrData = json_decode($data, true);
//
//var_dump($arrData);
//wwq($arrData);

//wwq('asdasdasd');

$this->IncludeComponentTemplate();



