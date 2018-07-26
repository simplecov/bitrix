<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?$APPLICATION->IncludeComponent("simplecov:currency.rates", "", array(
    'HL_BLOCK_ID' => HL_CURRENCY,
    'CURRENCY_CODES' => array(
        'USD',
        'EUR',
        'RUB'
    ),
    'SOURCE' => 'https://exchangeratesapi.io/api/'
),
    false,
    Array('HIDE_ICONS' => 'Y')
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>