<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

if(!isset($_COOKIE['fz152cookie'])):
	
$Page->headhtml.= '

<!-- Load Cookie Module -->
<script>
'.file_get_contents(DR.'/modules/cookies/cookie.js').'
</script>
<style>
'.file_get_contents(DR.'/modules/cookies/style.css').'
</style>

';

$Page->endhtml.= '

<!-- Load Cookie Module -->
<script>
function fz152cookie() {
	setCookie("fz152cookie", "true", options = {\'max-age\': 2419200});
	document.getElementById("fz152cookie").style.display = \'none\';
}
</script>
<div id="fz152cookie">
    <div>Мы используем файлы cookies для улучшения работы сайта. Оставаясь на нашем сайте, вы соглашаетесь с условиями
        использования файлов cookies. Чтобы ознакомиться с нашими Положениями о конфиденциальности и об использовании
        файлов cookie, <a href="/fz152" target="_blank">нажмите здесь</a>.</div>
    <div><button class="button" onclick="fz152cookie()">Я согласен</button></div>
</div>

';
endif;
return null;
?>