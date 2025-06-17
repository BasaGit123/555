<?php
if (!class_exists('System')) exit; // Запрет прямого доступа
global $Page, $newsStorage, $newsConfig;
return NewsCategory($newsConfig->blokCat, $newsConfig->countInBlok, $newsConfig->blokTemplate);
?>