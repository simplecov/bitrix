<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $baseCurrency;

$requestDate = $_GET['date'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];
?>

<div class="currency-block currency-submit-form">
    <h2>Запрос курса по дате</h2>
    <form class="request-form" method="get">
        <div class="form-group">
            <div>
                <span class="form-text">Дата</span>
                <input class="datepicker" name="date" type="date"
                       min="1999-01-01" value="<?= $requestDate ?>">
            </div>
        </div>
        <div class="form-group">
            <div>
                <span class="form-text">Валюта сайта</span>
                <b><?= $arResult['SITE_CURRENCY_CODE'] ?></b>
            </div>
        </div>
        <input type="hidden" name="request" value="external">
        <button type="submit" name="getstack" value="1" class="btn btn-warning">
            Курс за 30 дней
        </button>
    </form>
</div>











