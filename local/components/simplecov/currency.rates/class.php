<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use \Bitrix\Main\Entity;

//\CBitrixComponent::includeComponentClass('highloadblock');
CModule::IncludeModule('highloadblock');

class CurrencyRates extends CBitrixComponent
{

    public $errors = array();

    public $arRecievedData = array();

    public $query = '';

    public $baseCurrencyCode = '';

    private $hlBlockID;

    private $hlBlockEntity;


    /**
     * Инициализация сайта
     */
    public function init()
    {
        $this->SetHLBlockId();
        $this->SetSiteCurrency();
        $this->GetEntityDataClass($this->hlBlockID);
    }

    public function ViewErrors()
    {
        if(!empty($this->errors))
            wwq($this->errors);
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
    public function GetSiteCurrency()
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
            return false;

        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        $this->hlBlockEntity = $entity->getDataClass();

        return true;
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

        if(empty($arrData))
            $this->errors['empty'] = 'Данные не получены, попробуйте позднее';

        return $arrData;
    }


    /**
     * @param $date
     * @return mixed
     *
     * Проверяет наличие элементов по дате
     * Ограничивает дублирование информации
     */
    public function CheckElementsDataExists($date)
    {
        $entity_data_class = $this->hlBlockEntity;

        $arFilter = Array(
            Array(
                "UF_DATE" => $this->FormatDateToSite($date),
            )
        );
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'runtime' => array(
                new Entity\ExpressionField('*', 'COUNT(*)')
            ),
            'filter' => $arFilter
        ));

        if((int)$rsData->fetch()['*'] > 0)
        {
            $this->errors[] = 'Курсы валют на выбранную дату уже находятся в базе, повторная запись не произведена';
            return true;
        }

        return false;
    }


    /**
     * @param $dateFrom
     * @param string $dateTo
     *
     * Возвращает элементы по заданным датам
     */
    public function GetElements($dateFrom, $dateTo = '')
    {
        $entity_data_class = $this->hlBlockEntity;

        $arFilter = array();
        if($dateTo == '')
        {
            $arFilter['UF_DATE'] = $this->FormatDateToSite($dateFrom);
        }
        else
        {
            $arFilter['LOGIC'] = "AND";
            $arFilter[] = array(
                ">=UF_DATE" => $this->FormatDateToSite($dateFrom)
            );
            $arFilter[] = array(
                "<=UF_DATE" => $this->FormatDateToSite($dateTo)
            );
        }

        wwq($arFilter);

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => $arFilter
        ));
        while($el = $rsData->fetch()){
            $this->arResult['ELEMENTS'][] = $el;
        }
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


    /**
     * @param $arrData
     * @throws ReflectionException
     *
     * Записывает информацию в hl-блок
     */
    public function SaveData($arrData)
    {
        if(!empty($arrData))
        {

            $queriedDate = $this->FormatDateToSite($this->PrepareDate($_GET['date']));

            $entity_data_class = $this->hlBlockEntity;
            foreach ($arrData['rates'] as $key => $rate)
            {
                $result = $entity_data_class::add(array(
                    'UF_VALUE'        => $rate,
                    'UF_DATE'         => $queriedDate,
                    'UF_CODE'         => $key,
                ));

                $this->GetResultError($result);
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
     * Приводит дату к формату сайта
     */
    protected function FormatDateToSite($date)
    {
        return date('d.m.Y', strtotime($date));
    }

    /**
     * @param $date
     * @return false|string
     *
     * Приводит дату к формату компонента
     */
    protected function FormatDateToComponent($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * Says ASDASDASDASDDASD
     */
    public function sayASD(){

        echo 'ASDASDASDASDDASD';
    }

    /**
     * @param $obj
     * @param $prop
     * @return mixed
     * @throws ReflectionException
     *
     * Получает свойства объекта через reflection
     */
    private function accessProtected($obj, $prop) {

        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }

    /**
     * @param $result
     * @throws ReflectionException
     *
     * Ищет ошибку...жесть, короче
     */
    private function GetResultError($result)
    {
        if(!empty($result))
        {
            $resultErrors = $this->accessProtected($result, errors);
        }

        if(!empty($resultErrors))
        {
            $values = $this->accessProtected($resultErrors, values)[0];
        }

        if(!empty($values))
        {
            $errorText = $this->accessProtected($values, message);
            $this->errors[] = $errorText;
        }

    }
}