<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate())
	return;

$this->IncludeComponentTemplate();

//$data = file_get_contents('https://exchangeratesapi.io/api/latest?symbols=USD,RUB,EUR');
$data = file_get_contents('https://exchangeratesapi.io/api/latest');
$arrData = json_decode($data, true);

var_dump($arrData);
wwq($arrData);

//wwq('asdasdasd');



