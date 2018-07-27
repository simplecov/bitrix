<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

//\CBitrixComponent::includeComponentClass('highloadblock');
CModule::IncludeModule('highloadblock');

class CurrencyRates extends CBitrixComponent
{

    public $errors = array();

    public $arRecievedData = array();

    public $query = '';

    public $baseCurrencyCode = '';

    private $hlBlockID;


    /**
     * Инициализация сайта
     */
    public function init()
    {
        $this->SetHLBlockId();
        $this->SetSiteCurrency();
    }

    /**
     * Устанавливает ID hl-блока
     */
    private function SetHLBlockId()
    {
        $this->hlBlockID = HL_CURRENCY;
    }

    /**
     * Инициализирует значение валюты сайта
     */
    private function SetSiteCurrency()
    {
        $this->baseCurrencyCode = CCurrency::GetBaseCurrency();
    }

    /**
     * @return string
     *
     * Возвращает код валюты сайта
     */
    private function GetSiteCurrency()
    {
        return $this->baseCurrencyCode;
    }


    /**
     * @param $HlBlockId
     * @return bool
     *
     * Получаем экземпляр класса для манипуляций с hl-блоком
     */
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


    /**
     * @param $source
     * @param $date
     * @return array
     *
     *  Выполняет запрос
     */
    public function GetQueryData($source, $date)
    {
        $query = $this->GetQueryString($source, $date);

        $queryData = file_get_contents($query);
        $arrData = json_decode($queryData, true);

        return $arrData;
    }


    /**
     * @param $source
     * @param $date
     * @return string
     *
     * Сбор строки запроса
     */
    private function GetQueryString($source, $date)
    {
        $query = $source;
        $query .= $this->PrepareDate($date);
        $query .= '?base=' . $this->GetSiteCurrency();

        return $query;
    }


    public function SaveData($arrData)
    {
        if(!empty($arrData))
        {
            $entity_data_class = $this->GetEntityDataClass($this->hlBlockID);
            foreach ($arrData['rates'] as $key => $rate)
            {
                $result = $entity_data_class::add(array(
                    'UF_VALUE'        => $rate,
                    'UF_DATE'         => $this->FormatDateToSite($this->PrepareDate($_GET['date'])),
                    'UF_CODE'         => $key,
                ));
            }
        }
    }

    /**
     * @param $date
     * @return false|string
     *
     * Проверяет дату и возвращает подготовленное значение
     */
    public function PrepareDate($date)
    {
        return strlen($date) ? $date : date('Y-m-d');
    }

    /**
     * @param $date
     * @return false|string
     *
     * Приводим дату к формату сайта
     */
    protected function FormatDateToSite($date)
    {
        return date('d.m.Y', strtotime($date));
    }

    /**
     * @param $date
     * @return false|string
     *
     * Приводим дату к формату компонента
     */
    protected function FormatDateToComponent($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public function sayASD(){

        echo 'ASDASDASDASDDASD';
    }
}