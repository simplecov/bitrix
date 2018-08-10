<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use \Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');

class CurrencyRates extends CBitrixComponent
{

    private $errors = [];

    private $hlBlockID;

    private $hlBlockEntity;

    protected $request = [];

    private $queryString;

    public function __construct($component)
    {
        parent::__construct($component);

        $this->hlBlockID = HL_CURRENCY;
        $this->arResult['SITE_CURRENCY_CODE'] = CCurrency::GetBaseCurrency();

        $this->GetEntityDataClass($this->hlBlockID);
    }

    /**
     * Вывод ошибок методом "слепил из того, что было"
     */
    public function ViewErrors()
    {
        if (!empty($this->errors)) {
            wwq($this->errors);
        }
    }

    /**
     * @param $HlBlockId
     *
     * @return bool
     *
     * Получаем экземпляр класса для манипуляций с hl-блоком
     */
    public function GetEntityDataClass($HlBlockId)
    {

        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }

        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        $this->hlBlockEntity = $entity->getDataClass();

        return true;
    }


    public function MultiplyRequest($source)
    {
        foreach ($this->arResult['QUERY_DATES'] as $key => $date) {
            $queryString = $this->CreateQueryString($source, $date);
            $arData = $this->GetRates($queryString);
        }
    }

    private function CreateQueryString($source, $date)
    {
        $query = $source;
        $query .= $date;
        $query .= '?base=' . $this->arResult['SITE_CURRENCY_CODE'];
        wwq($query);
        return $query;
    }

    public function GetRates($queryString)
    {
        $queryData = file_get_contents($queryString);
        $arrData = json_decode($queryData, true);

        if (empty($arrData)) {
            $this->errors['empty'] = 'Данные не получены, попробуйте позднее';
        }

        return $arrData;
    }

    public function PrepareDate($date = null)
    {
        return strlen($date) ?
            date('Y-m-d', $date) :
            date('Y-m-d', strtotime('today - 30 days'));
    }



    public function CreateDateArray($days = 30)
    {
        for($i = 0; $i < $days; $i++) {
            $timeString = 'today - '. $i .' days';
            $this->arResult['QUERY_DATES'][] = date('Y-m-d', strtotime($timeString));
        }
        $this->arResult['DAYS_COUNT'] = $days;
        //wwq($this->arResult['DAYS_COUNT']);
    }


    /**
     * @param $date
     *
     * @return mixed
     *
     * Проверяет наличие элементов по дате
     * Ограничивает дублирование информации
     */
    /**
     * @TODO Переделать проверку на запись, сделать проверку одной конкретной записи
     */
    public function CheckElementsDataExists($date)
    {
        $entity_data_class = $this->hlBlockEntity;

        $arFilter = [
          [
            "UF_DATE" => $this->FormatDateToSite($this->PrepareDate($date)),
          ],
        ];
        $rsData = $entity_data_class::getList([
          'select' => ['*'],
          'runtime' => [
            new Entity\ExpressionField('*', 'COUNT(*)'),
          ],
          'filter' => $arFilter,
        ]);

        if ((int)$rsData->fetch()['*'] > 0) {
            $this->errors[]
              = 'Курсы валют на выбранную дату уже находятся в базе, повторная запись не произведена';
            return true;
        }

        return false;
    }


    /**
     * @param        $dateFrom
     * @param string $dateTo
     *
     * Возвращает элементы по заданным датам
     */
    public function GetElements($dateFrom, $dateTo = '')
    {
        $entity_data_class = $this->hlBlockEntity;

        $arFilter = [];
        if ($dateTo == '') {
            $arFilter['UF_DATE'] = $this->FormatDateToSite($dateFrom);
        } else {
            $arFilter['LOGIC'] = "AND";
            $arFilter[] = [
              ">=UF_DATE" => $this->FormatDateToSite($dateFrom),
            ];
            $arFilter[] = [
              "<=UF_DATE" => $this->FormatDateToSite($dateTo),
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

    /**
     * @param $arrData
     *
     * @throws ReflectionException
     *
     * Записывает информацию в hl-блок
     */
    /**
     * @TODO Переделать сохранение даты, сейчас она сохраняет запрашиваемую, а
     *       нужно, чтобы для каждой валюты сохранялась дата, соответствующая
     *       запрошенному дню
     */
    public function SaveData($arrData)
    {
        if (!empty($arrData)) {

            $queriedDate
              = $this->FormatDateToSite($this->PrepareDate($_GET['date'],
              false));

            $entity_data_class = $this->hlBlockEntity;
            foreach ($arrData['rates'] as $key => $rate) {
                $result = $entity_data_class::add([
                  'UF_VALUE' => $rate,
                  'UF_DATE' => $queriedDate,
                  'UF_CODE' => $key,
                ]);

                $this->GetResultError($result);
            }
        }
    }

    /**
     * @param $date
     *
     * @return false|string
     *
     * Приводит дату к формату сайта
     */
    protected function FormatDateToSite($date)
    {
        return date('d.m.Y', strtotime($date));
    }

    /**
     * @param $obj
     * @param $prop
     *
     * @return mixed
     * @throws ReflectionException
     *
     * Получает свойства объекта через reflection
     */
    private function accessProtected($obj, $prop)
    {

        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }

    /**
     * @param $result
     *
     * @throws ReflectionException
     *
     * Ищет ошибку...жесть, короче
     */
    private function GetResultError($result)
    {
        if (!empty($result)) {
            $resultErrors = $this->accessProtected($result, errors);
        }

        if (!empty($resultErrors)) {
            $values = $this->accessProtected($resultErrors, values)[0];
        }

        if (!empty($values)) {
            $errorText = $this->accessProtected($values, message);
            $this->errors[] = $errorText;
        }

    }

    /**
     * @param $arrData
     *
     * Записывает в arResult список кодов полученных валют
     */
    private function CollectCurrencyCodes($arrData)
    {
        if (!empty($arrData)) {
            $this->arResult['CUR_CODE'] = array_keys($arrData['rates']);
        }
    }

}