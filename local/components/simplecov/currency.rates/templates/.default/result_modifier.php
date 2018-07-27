<?

$currentDate = FormatDate($arResult['ELEMENTS'][0]['UF_DATE']);
foreach ($arResult['ELEMENTS'] as $key => $element)
{
    if($currentDate == FormatDate($arResult['ELEMENTS'][$key]['UF_DATE']))
    {
        $arResult['BY_DATE'][$currentDate][] = $element;
    }
    else
    {
        $currentDate = FormatDate($arResult['ELEMENTS'][$key]['UF_DATE']);
        $arResult['BY_DATE'][$currentDate][] = $element;
    }
}

wwq($arResult);










//$arResult['CURRENCY_CODE'] = array();
//if(!in_array($element['UF_CODE'], $arResult['CURRENCY_CODE']))
//{
//    $arResult['CURRENCY_CODE'][] = $element['UF_CODE'];
//}