<?php

include_once 'include/const.php';

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use \Bitrix\Main\Entity;

function wwq($data)
{
    echo '<pre>';

    if(!empty($data))
        print_r($data);
    else
        echo 'Нет инфы';

    echo '</pre>';
}

class Helper
{
    private static $instance = null;

    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function GetEntityDataClass($HlBlockId)
    {

        if (empty($HlBlockId) || $HlBlockId < 1) {
            return null;
        }

        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);

        return $entity->getDataClass();
    }

    public function FormatDateToSite($date)
    {
        return date('d.m.Y', strtotime($date));
    }
}
?>