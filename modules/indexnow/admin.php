<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

$IndexNowStorage = new EngineStorage('module.indexnow');
$indexNowKey = $IndexNowStorage->iss('key')?$IndexNowStorage->get('key'):random(32);

?>
<script type="text/javascript">
function clearHistory(url){
return '<div class="a">Подтвердите очистку истории запросов</div>' +
	'<div class="b">' +
	'<button type="button" onClick="window.location.href = \''+url+'\';">Очистить</button> '+
	'<button type="button" onclick="closewindow(\'window\');">Отмена</button>'+
	'</div>';
}
</script>
<?php

if($act=='index'){
	echo'<div class="header"><h1>IndexNow от Яндекс</h1></div>
	<div class="menu_page"><a href="index.php">&#8592; Вернуться назад</a></div>
	<div class="content">
	<p>IndexNow - это протокол позволяющий автоматически сообщать поисковым системам об изменениях на сайте, включая появление новых страниц, обновление или удаление текущих. 
	С помощью IndexNow вы можете напрямую уведомить Яндекс об изменениях на сайте, не дожидаясь очередного обхода индексирующим роботом.</p>
	<form name="forma" action="module.php?module='.$MODULE.'" method="post">
	<INPUT TYPE="hidden" NAME="act" VALUE="add">
	<table class="tblform">
	<tr>
		<td>Ключ для проверки подлинности запроса:</td>
		<td><input type="text" name="indexNowKey" value="'.$indexNowKey.'"><br><span class="comment">С помощъю этого ключа Яндекс убедится, что именно Вы отправили запрос. Символы могут быть любыми.</span></td>
	</tr>
	<tr>
		<td>URL адрес страницы для индексации:</td>
		<td><input type="text" name="indexNowUrl" value="'.$Config->protocol.'://"><br><span class="comment">Укажите url адрес страницы которую нужно оперативно проиндексировать или переиндексировать.</span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="" value="Отправить"></td>
	</tr>
	</table>
	</form>
	';

	if($IndexNowStorage->iss('listHistory')){
		if(($listHistory = json_decode($IndexNowStorage->get('listHistory'), true)) == false){
				echo'<div class="msg">История запросов отсутствует</div>';
		}else{
			$listHistory = array_reverse($listHistory);//перевернули масив для вывода в обратном порядке 
			$nom = count($listHistory);
			
			if($nom == 0){
				echo'<div class="msg">История запросов отсутствует</div>';
			}else{
				echo'<p>
					<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', clearHistory(\'module.php?module='.$MODULE.'&amp;act=clear\'));">Выполнить очистку истории запросов</a>
					</p>
					<table class="tables">
					<tr>
						<td class="tables_head" colspan="2">URL</td>
						<td class="tables_head" style="text-align: center;">Статус</td>
						<td class="tables_head" style="text-align: center;">Дата запроса</td>
						<td class="tables_head" style="text-align: right;">&nbsp;</td>
					</tr>';
				foreach($listHistory as $value){

					echo'
					<tr>
						<td class="img"><img src="include/page.svg" alt=""></td>
						<td><a href="'.$value['url'].'" target="_blank">'.$value['url'].'</a></td>
						<td style="text-align: center;">'.($value['status']=='ok'?'<span class="g">':'<span class="r">').$value['status'].'</span></td>
						<td style="text-align: center;">'.date('d.m.Y H:i:s', $value['time']).'</td>
						<td><a href="module.php?module='.$MODULE.'&amp;act=add&amp;indexNowKey='.$indexNowKey.'&amp;indexNowUrl='.$value['url'].'" >Повторить запрос</a></td>
					</tr>';
				}
				echo'</table>
				<p>
				<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', clearHistory(\'module.php?module='.$MODULE.'&amp;act=clear\'));">Выполнить очистку истории запросов</a>
				</p>';
			}

		}
	}
	echo'</div>';
	
}

if($act=='clear'){
	$IndexNowStorage->set('listHistory', json_encode(array())); 
	echo'<div class="msg">Очистка успешно завершена</div>';
	System::notification('IndexNow: Выполнена очистка истории запросов.', 'g');
	?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>\';', 3000);
</script>
<?php
}

if($act=='add'){
	if(isset($_POST['indexNowKey'])){ $indexNowKey = htmlspecialchars(specfilter($_POST['indexNowKey'])); }
	if(isset($_POST['indexNowUrl'])){ $indexNowUrl = htmlspecialchars($_POST['indexNowUrl']); }

	if(isset($_GET['indexNowKey'])){ $indexNowKey = htmlspecialchars(specfilter($_GET['indexNowKey'])); }
	if(isset($_GET['indexNowUrl'])){ $indexNowUrl = htmlspecialchars($_GET['indexNowUrl']); }

	$responseStatus = 'error';
	if(!isset($indexNowKey, $indexNowUrl)){
		echo'<div class="msg">Данные некорректны</div>';
		System::notification('IndexNow: Данные некорректны.', 'r');
	}else{
		$IndexNowStorage->set('key', $indexNowKey);
		$response = file_get_contents('https://yandex.com/indexnow?url='.$indexNowUrl.'&key='.$indexNowKey);
		
		if($response !== false){
			$IdexNowResponse = json_decode($response);
			if(isset($IdexNowResponse->success) && $IdexNowResponse->success == true){
				echo'<div class="msg">Яндекс успешно принял запрос на индексирование</div>';
				$responseStatus = 'ok';
				System::notification('IndexNow: Яндекс успешно принял запрос на индексирование. Ответ: '.$response, 'g');
			}else{
				echo'<div class="msg">Яндекс не принял запрос</div>';
				System::notification('IndexNow: Яндекс не принял запрос. Ответ: '.$response, 'r');
			}
		}else{
			echo'<div class="msg">Запрос не отправлен</div>';
			System::notification('IndexNow: Не удалось отправить запрос "https://yandex.com/indexnow?url='.$indexNowUrl.'&key='.$indexNowKey.'"', 'r');
		}
	}

	if($IndexNowStorage->iss('listHistory')){
		$listHistory = json_decode($IndexNowStorage->get('listHistory'), true); // Получили список ввиде массива
	}
	if(!isset($listHistory) || !is_array($listHistory)){ 
		$listHistory = array(); 
	}
	$listHistory[] = array('url' => $indexNowUrl, 'status' => $responseStatus, 'time' => time()); // Добавили новый элемент массива в конец
	$IndexNowStorage->set('listHistory', json_encode($listHistory, JSON_FLAGS)); // Записали массив в виде json

?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>\';', 5000);
</script>
<?php
}

?>