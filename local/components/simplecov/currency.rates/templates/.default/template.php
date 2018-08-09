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
        <button type="submit" class="btn btn-success">Запрос</button>
        <button type="submit" name="getstack" value="1" class="btn btn-warning">
            Курс за 30 дней
        </button>
    </form>
</div>

<div class="currency-block currency-filter-form">
    <h2>Фильтр валют по датам</h2>
    <form class="request-form" method="get">
        <div class="form-group">
            <div>
                <span class="form-text">Дата c</span>
                <input class="datepicker" name="dateFrom" type="date"
                       min="1999-01-01" value="<?= $dateFrom ?>">
            </div>
        </div>
        <div class="form-group">
            <div>
                <span class="form-text">Дата по</span>
                <input class="datepicker" name="dateTo" type="date"
                       min="1999-01-01" value="<?= $dateTo ?>">
            </div>
        </div>
        <input type="hidden" name="request" value="internal">
        <button type="submit" class="btn btn-success">Показать</button>
        <button type="submit" name="getstack" value="1" class="btn btn-warning">
            Курс за 30 дней
        </button>
    </form>
</div>

<div class="currency-block currency-table">
    <h2>Таблица курсов валют</h2>
    <? if (count($arResult['ELEMENTS']) > 0): ?>
        <div class="table-scroll">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">Дата</th>
                    <? foreach ($arResult['CURRENCY_CODE'] as $code): ?>
                        <th scope="col"><?= $code ?></th>
                    <? endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <? foreach ($arResult['BY_DATE'] as $key => $values): ?>
                    <tr>
                        <th scope="row"><?= $key ?></th>
                        <? foreach ($values as $value): ?>
                            <td><?= $value ?></td>
                        <? endforeach; ?>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif ?>
    <? if (count($arResult['ELEMENTS']) == 0): ?>
        <h3 class="alert alert-note">Элементы по заданным датам не найдены</h3>
    <? endif ?>

</div>





