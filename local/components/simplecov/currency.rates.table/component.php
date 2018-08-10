<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!$this->InitComponentTemplate()) {
    return;
}

$this->GetElements($_GET['dateFrom'], $_GET['dateTo']);

$this->IncludeComponentTemplate();



