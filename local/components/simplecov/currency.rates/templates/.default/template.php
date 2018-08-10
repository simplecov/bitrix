<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<div class="currency-block currency-submit-form">
    <h2>Запрос курса по дате</h2>
    <form class="request-form" method="get">
        <div class="form-group">
            <div>
                <span class="form-text">Валюта сайта</span>
                <b><?= $arResult['SITE_CURRENCY_CODE'] ?></b>
            </div>
        </div>
        <input type="hidden" name="request" value="external">
        <button type="submit" class="btn btn-warning">
            Курс за 30 дней
        </button>
    </form>
</div>











