<?
include_once 'include/const.php';

function wwq($data)
{
    echo '<pre>';

    if(!empty($data))
        print_r($data);
    else
        echo 'Нет инфы';

    echo '</pre>';
}
?>