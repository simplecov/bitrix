<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
                <input class="datepicker" name="date" type="date" min="1999-01-01" value="<?=$requestDate?>">
            </div>
        </div>
        <div class="form-group">
            <div>
                <span class="form-text">Валюта сайта</span>
                <b><?=$baseCurrency?></b>
            </div>
        </div>
        <input type="hidden" name="request" value="external">
        <button type="submit" class="btn btn-success">Запрос</button>
        <button type="submit" value="reset" class="btn btn-primary">Очистить</button>
    </form>
</div>

<div class="currency-block currency-table">
    <h2>Курсы валют</h2>
    <form class="request-form" method="get">
        <div class="form-group">
            <div>
                <span class="form-text">Дата c</span>
                <input class="datepicker" name="dateFrom" type="date" min="1999-01-01" value="<?=$dateFrom?>">
            </div>
        </div>
        <div class="form-group">
            <div>
                <span class="form-text">Дата по</span>
                <input class="datepicker" name="dateTo" type="date" min="1999-01-01" value="<?=$dateTo?>">
            </div>
        </div>
        <input type="hidden" name="request" value="internal">
        <button type="submit" class="btn btn-success">Показать</button>
        <button type="submit" value="reset" class="btn btn-primary">Очистить</button>
    </form>
    <table class="table table-sm">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
        </tr>
        </tbody>
    </table>
</div>





