<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => 'Вывод курсов валют',
    "DESCRIPTION" => 'Выводит курсы валют',
    "ICON" => "/images/breadcrumb.gif",
    "PATH" => array(
        "ID" => "utility",
        "CHILD" => array(
            "ID" => "navigation",
            "NAME" => GetMessage("MAIN_NAVIGATION_SERVICE")
        )
    ),
);

?>