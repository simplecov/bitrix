<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
// id highload-инфоблока
const MY_HL_BLOCK_ID = 1;
//подключаем модуль highloadblock
CModule::IncludeModule('highloadblock');

//Напишем функцию получения экземпляра класса:
function GetEntityDataClass($HlBlockId) {
    if (empty($HlBlockId) || $HlBlockId < 1)
    {
        return false;
    }
    $hlblock = HLBT::getById($HlBlockId)->fetch();
    $entity = HLBT::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
}

global $baseCurrency;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate())
	return;

// Код базовой валюты сайта
$baseCurrency = CCurrency::GetBaseCurrency();
//wwq($baseCurrency);

switch ($_GET['request'])
{
    case 'external':
        break;

    case 'internal':
        break;
}
/**
 * Обработка внешнего запроса
 */
if($_GET['request'] == 'external')
{
    /**
     * Дата по запросу пользователя
     */
    $date = strlen($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    wwq('GET');
    wwq($date);
    wwq(strtotime($date));
    wwq(date('d.m.Y', strtotime($date)));

    /**
     * Сбор строки запроса
     */
    $query = $arParams['SOURCE']; // базовый адрес сервиса
    $query .= $date; // указываем дату
    $query .= '?base=' . $baseCurrency; // указываем базовую валюту сайта
    wwq($query);

    //$data = file_get_contents('https://exchangeratesapi.io/api/latest?symbols=USD,RUB,EUR');
    //$data = file_get_contents('https://exchangeratesapi.io/api/latest?base=RUB');
    $data = file_get_contents($query);
    $arrData = json_decode($data, true);
    wwq($arrData);


    /**
     * Запись данных в hl-блок
     */
    $entity_data_class = GetEntityDataClass($arParams['HL_BLOCK_ID']);
    $formattedDate = date('d.m.Y', strtotime($date));
    $result = $entity_data_class::add(array(
      'UF_VALUE'        => '123',
      'UF_DATE'         => $formattedDate,
      'UF_CODE'         => 'RUB',
   ));
    wwq($result);
    wwq($formattedDate);
    wwq(CSite::GetDateFormat("SHORT"));

}




$this->IncludeComponentTemplate();



