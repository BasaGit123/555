<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

$IndexNowStorage = new EngineStorage('module.indexnow');
$indexNowKey = $IndexNowStorage->iss('key')?$IndexNowStorage->get('key'):false;

if(strstr(REQUEST_URI.'?', '?', true) == '/'.$indexNowKey.'.txt'){
	echo $indexNowKey;
	System::notification('IndexNow: Робот Яндекс обратился к сайту для проверки ключа - проверка прошла успешно.', 'g');
	ob_end_flush(); exit;
}
?>