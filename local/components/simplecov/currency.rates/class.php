<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

CModule::IncludeModule('highloadblock');

class CurrencyRates extends CBitrixComponent
{
    /**
     * Используется для записи логов
     * @var array
     */
    private $log = [];

    /**
     * Используется для записи ошибок
     * @var array
     */
    private $errors = [];

    /**
     * Храние ID hl-блока
     * @var int
     */
    private $hlBlockID;

    /**
     * Хранит строку названия класса для заданного hl-блока
     * @var string
     */
    private $hlBlockEntity;

    public function __construct($component)
    {
        parent::__construct($component);

        $this->hlBlockID = HL_CURRENCY;//$this->arParams['HL_BLOCK_ID'];
        $this->arResult['SITE_CURRENCY_CODE'] = CCurrency::GetBaseCurrency();
        $this->hlBlockEntity = Helper::getInstance()->GetEntityDataClass($this->hlBlockID);
    }

    /**
     * Выводит лог
     */
    public function ViewLog()
    {
        if (!empty($this->log)) {
            wwq($this->log);
        }
    }

    /**
     * Выводит ошибки
     */
    public function ViewErrors()
    {
        if (!empty($this->errors)) {
            wwq($this->errors);
        }
    }

    /**
     * Сохраняет данные в hl-блок
     * @throws ReflectionException
     *
     */
    public function SaveData()
    {
        if (empty($this->errors)) {

            foreach ($this->arResult['REQUESTED_RATES'] as $date => $rates) {

                $entity_data_class = $this->hlBlockEntity;

                foreach ($rates as $key => $rate) {
                    $requestedDate = Helper::getInstance()->FormatDateToSite($date);

                    if (!$this->CheckElementExists($key, $requestedDate)) {
                        $result = $entity_data_class::add([
                            'UF_VALUE' => $rate,
                            'UF_DATE' => $requestedDate,
                            'UF_CODE' => $key,
                        ]);

                        $this->GetResultError($result);
                    }
                }
            }

            if (count($this->errors['duplication']) > 0) {
                $this->errors[] = 'Найдены дубликаты';
            }

            if (empty($this->errors)) {
                $this->log[] = 'Данные записаны';
            }
        }
    }

    /**
     * Делает множество запросов
     * @param $source
     */
    public function MultiplyRequest($source)
    {
        foreach ($this->arResult['QUERY_DATES'] as $key => $date) {
            $queryString = $this->CreateRequestString($source, $date);
            $currencyRates = $this->GetCurrencyRates($queryString)['rates'];

            if (!empty($currencyRates)) {
                $this->arResult['REQUESTED_RATES'][$date] = $currencyRates;
            } else {
                $this->errors[] = 'Не получены данные для даты ' . $date;
            }
        }

        if (empty($this->errors)) {
            $this->log[] = 'Данные получены';
        }
    }

    /**
     * Собирает строку для запроса
     * @param $source
     * @param $date
     * @return string
     */
    private function CreateRequestString($source, $date)
    {
        $query = $source;
        $query .= $date;
        $query .= '?base=' . $this->arResult['SITE_CURRENCY_CODE'];
        return $query;
    }

    /**
     * Делает запрос на сторонний ресурс
     * @param $queryString
     * @return mixed
     */
    public function GetCurrencyRates($queryString)
    {
        $queryData = file_get_contents($queryString);
        $arrData = json_decode($queryData, true);

        return $arrData;
    }

    /**
     * Создает массив дат от текущей и на столько-то дней назад
     * @param int $days
     */
    public function CreateDateArray($days = 30)
    {
        for ($i = 0; $i < $days; $i++) {
            $timeString = 'today - ' . $i . ' days';
            $this->arResult['QUERY_DATES'][] = date('Y-m-d', strtotime($timeString));
        }
        $this->arResult['DAYS_COUNT'] = $days;
    }

    /**
     * Проверяет наличие элемента в базе. Не дает записать дубликат
     * @param $currencyCode
     * @param $date
     * @return bool
     */
    public function CheckElementExists($currencyCode, $date)
    {
        $entity_data_class = $this->hlBlockEntity;

        $rsData = $entity_data_class::getList([
            'select' => array('UF_CODE', 'UF_DATE'),
            'limit' => '1',
            'filter' => array(
                'UF_CODE' => $currencyCode,
                'UF_DATE' => Helper::getInstance()->FormatDateToSite($date)
            )
        ]);

        if ((int)$rsData->fetch() > 0) {
            $this->errors['duplication']
                = '1';
            return true;
        }

        return false;
    }


    /**
     * Получает свойства объекта через reflection
     * @param $obj
     * @param $prop
     * @return mixed
     * @throws ReflectionException
     */
    private function accessProtected($obj, $prop)
    {

        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }

    /**
     * Ищет ошибку...жесть, короче
     * @param $result
     * @throws ReflectionException
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

}