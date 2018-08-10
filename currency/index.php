<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?$APPLICATION->IncludeComponent("simplecov:currency.rates", "", array(
    'SOURCE' => 'https://exchangeratesapi.io/api/',
    'HL_BLOCK_ID' => HL_CURRENCY
),
    false,
    Array('HIDE_ICONS' => 'Y')
);?>

<?$APPLICATION->IncludeComponent("simplecov:currency.rates.table", "", array(
    'HL_BLOCK_ID' => HL_CURRENCY
),
    false,
    Array('HIDE_ICONS' => 'Y')
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>