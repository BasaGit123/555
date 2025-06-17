<?php
require('../system/global.dat');
require('./include/start.dat');

if($status=='admin'){
	if($act=='index'){
?>
<script type="text/javascript">
var template = '<div class="a"><div style="min-height: 111px; max-height: 221px; overflow: auto;"><?php
$listModules = System::listModules();
foreach($listModules as $value){
		
		
		if(Module::isTemlate($value)){
			if(($skr = Module::skrTemlate($value)) === false){
				$skr = 'include/noimgtemplate.png';
			}
			$info = Module::info($value);
			echo'<div style="position: relative; height: 40px; border: 1px solid #e5e5e5; background-color: #f9f9f9; margin: 1px 1px; padding: 6px;"><img style="float:left; margin: 0 6px 0 0;" src="'.$skr.'" width="60" height="40" alt="">'.$info['name'].'<br><span class="comment">Версия: '.$info['version'].'</span><a class="button0" style="position: absolute; top: 6px; right: 6px;" href="javascript:void(0);" onclick="document.getElementById(\\\'template\\\').value = \\\''.$value.'\\\'; document.getElementById(\\\'name_template\\\').value = \\\''.$info['name'].'\\\'; closewindow(\\\'window\\\');">Выбрать</a></div>';
		}
}
?></div></div>'+
'<div class="b" style="clear:both;">'+
'<button type="button" onclick="closewindow(\'window\');">Закрыть</button>'+
'</div>';

var badchoice = '<div class="a"><span class="r">Внимание!</span> Даже через длительный промежуток времени, любой пользователь данного устройства будет считаться администратором</div>'+
'<div class="b">'+
'<button type="button" onclick="closewindow(\'window\');">Закрыть</button>'+
'</div>';

function random(n)
{
	var r = '';
	var arr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var al = arr.length
	for( var i=0; i < n; i++ ){
		r += arr[Math.floor(Math.random() * al)];
	}
	return r;
}

</script>

<script>
var iframefiles = '<div class="a"><iframe src="iframefiles.php?id=inputimg-icon" width="100%" height="300" style="border:0;">Ваш браузер не поддерживает плавающие фреймы!</iframe></div>'+
'<div class="d-flex justify-content-end p-3">'+
'<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
'</div>';

var iframefiles2 = '<div class="a"><iframe src="iframefiles2.php?id=inputimg-logo" width="100%" height="300" style="border:0;">Ваш браузер не поддерживает плавающие фреймы!</iframe></div>'+
'<div class="d-flex justify-content-end p-3">'+
'<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
'</div>';
</script>

<?php
		$info = Module::info($Config->template);
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						
						<a class="nav-item" href="setting.php?">Основные настройки системы</a>
						<a class="nav-item" href="files.php?act=editor&amp;dir=../modules/'.$Config->template.'&amp;file=../modules/'.$Config->template.'/template.php&amp;header='.urlencode('Редактирование файла шаблона').'&amp;linkback='.urlencode('setting.php').'">Правка текущего шаблона</a>
						<a class="nav-item" href="setting.php?act=pass">Смена пароля администратора</a>
						<a class="nav-item" href="users.php?act=cfg">Настройки пользователей</a>
	
					</ul>

				</div>
			</div>
		</div>
		
		<div class="container">

			<form name="settingform" action="setting.php?" method="post">
				<INPUT TYPE="hidden" name="act" VALUE="addsetting">
				<INPUT TYPE="hidden" name="template" id="template" VALUE="'.$Config->template.'">

				<div class="col-lg-6 bs-br bg-white overflow-hidden">
					<h3 class="bg-light p-4 m-0">Общие настройки</h3>
					<div class="p-4">

							<div class="mb-3">
								<label class="form-label me-3">Иконка</label>
								<input type="text" name="icon" id="inputimg-icon" value="'.$Config->icon.'"> 
								<a class="btn btn-outline-secondary me-3" onClick="openwindow(\'window\', 750, \'auto\', iframefiles);">Загрузить</a>
								<img src="'.$Config->icon.'" alt="" id="img-icon" style="width: 380px;">
							</div>

							<div class="mb-3">
								<label class="form-label me-3">Логотип</label>
								<input type="text" name="logo" id="inputimg-logo" value="'.$Config->logo.'"> 
								<a class="btn btn-outline-secondary me-3" onClick="openwindow(\'window\', 750, \'auto\', iframefiles2);">Загрузить</a>
								<img src="'.$Config->logo.'" alt="" id="img-logo" style="width: 380px;">
							</div>

							<div class="mb-3">
								<label class="form-label">Название сайта/компании</label>
								<input type="text" class="form-control" name="header" value="'.$Config->header.'">
							</div>

							<div class="mb-3">
								<label class="form-label">Слоган сайта</label>
								<input type="text" class="form-control" name="slogan" value="'.$Config->slogan.'">
							</div>

							<div class="mb-3">
								<label class="form-label me-3">Шаблон</label>
								<input type="text" class="form-control" name="name_template" id="name_template" value="'.$info['name'].'"> 
								<a href="javascript:void(0);" onclick="openwindow(\'window\', 600, \'auto\', template);">Выбрать шаблон</a>
							</div>
						
							<div class="mb-3">
								<label class="form-label">Яндекс вебмастер, код подтверждение домена</label>
								<input type="text" class="form-control" name="yaweb" value="'.$Config->yaweb.'">
								<small class="mt-2">Вставте просто код. Пример: d09ff2fa5c8ac882</small>
							</div>

							<div class="mb-3">
								<label class="form-label">Код яндекс метрики</label>
								<textarea type="text" class="form-control" rows="10" name="ya">'.$Config->ya.'</textarea>
							</div>

					</div>
				</div>
							


				<div class="col-lg-6 bs-br bg-white overflow-hidden">
					<h3 class="bg-light p-4 m-0">Системные настройки</h3>
					<div class="p-4">
					
							<div class="mb-3">
								<label class="form-label">Временная зона работы сайта</label>
							
								<select name="timeZone" class="form-select">';
								$timezones = System::getTimeZones();
								echo'<option value="default" '.($Config->timeZone == 'default'?'selected':'').'>Использовать настройки сервера';
								foreach($timezones as $value){
									echo'<option value="'.$value.'" '.($Config->timeZone == $value?'selected':'').'>'.$value;
								}
								echo'</select>
								<small class="mt-2">Текущее дата и время сайта: '.date("d.m.Y H:i").'</small>
							</div>
								
								
							<div class="mb-3">
								<label class="form-label">Визуальный редактор</label>
								<select name="wysiwyg" class="form-select">';
								echo'<option value="0" '.($Config->wysiwyg == '0'?'selected':'').'>Без визуального редактора';
								foreach($listModules as $value){
									if(Module::isWysiwyg($value)){
										$info = Module::info($value);
										echo'<option value="'.$value.'" '.($Config->wysiwyg == $value?'selected':'').'>'.$info['name'].' '.$info['version'];
									}
								}
								echo'</select>
							</div>

							<div class="mb-3">
								<label class="form-label">GZIP Сжатие страниц</label>
								<select class="form-select" name="gzip">';
								if($Config->gzip == '1'){
									echo'<option value="1" selected>Включено';
									echo'<option value="0">Отключено';
								}else{
									echo'<option value="0" selected>Отключено';
									echo'<option value="1">Включено';
								}
								echo'</select>
							</div>


							<div class="mb-3">
								<label class="form-label">Время сохранения авторизации</label>
								<select class="form-select" name="timeAuth" id="sel" onchange="if(document.getElementById(\'sel\').value == 32000000) openwindow(\'window\', 600, \'auto\', badchoice);">
								<option value="1800" '.($Config->timeAuth == '1800'?'selected':'').'>30 минут (Рекомендуется)
								<option value="10800" '.($Config->timeAuth == '10800'?'selected':'').'>3 часа
								<option value="86400" '.($Config->timeAuth == '86400'?'selected':'').'>24 часа
								<option value="259200" '.($Config->timeAuth == '259200'?'selected':'').'>3 дня (Не рекомендуется)
								<option value="32000000" '.($Config->timeAuth == '32000000'?'selected':'').'>Всегда (Очень опасно)
								</select>
								<small class="mt-2">Чем меньше значение, тем безопасней</small>
							</div>


							<div class="mb-3">
								<label class="form-label">Соль для шифрования</label>
								<input type="text" class="form-control" name="ticketSalt" id="salt" value="'.$Config->ticketSalt.'">
								<small class="mt-2">Используется для шифрования отправляемых форм от пользователей (например, капчи). Вам не нужно запоминать этот параметр, просто поменяйте его, если начали появляться фейковые аккаунты, созданные роботами.</small><br>
								<a href="javascript:void(0);" onclick="document.getElementById(\'salt\').value = random(16)">Сгенерировать новую соль</a>
							</div>


							<div class="mb-3">
								<label class="form-label">Идентификатор главной страницы</label>
								<input type="text" class="form-control" name="indexPage" value="'.$Config->indexPage.'">
							</div>


							<div class="mb-3">
								<label class="form-label">Отображение PHP ошибок</label>
								<select class="form-select" name="errorReporting">
								<option value="0" '.($Config->errorReporting == '0'?'selected':'').'>Не отображать PHP ошибки (Рекомендуется)
								<option value="1" '.($Config->errorReporting == '1'?'selected':'').'>Отображать все PHP ошибки (Только для отладки)
								</select>	
							</div>

					</div>
				</div>
					





					
				<div class="col-lg-6 bs-br bg-white overflow-hidden">
					<h3 class="bg-light p-4 m-0">Настройки ссылок</h3>
					<div class="p-4">

						<div class="mb-3">
							<label class="form-label">Символ пробела идентификаторов</label>
							<input type="text" class="form-control" name="spaceCharacter" value="'.$Config->spaceCharacter.'" maxlength="1">
							<small class="mt-2">Символ пробела идентификаторов при генерации из заголовков</small>
						</div>

						<div class="mb-3">
							<label class="form-label">Регистр идентификаторов при генерации</label>
							<select class="form-select" name="idToLowerCase">
							<option value="0" '.($Config->idToLowerCase == '0'?'selected':'').'>Оставлять как есть
							<option value="1" '.($Config->idToLowerCase == '1'?'selected':'').'>Переводить все символы в нижний регистр
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label">Правила ссылок со слешем в конце</label>
							<select class="form-select" name="slashRule">';
							if($Config->slashRule == '1'){
							echo'<option value="1" selected>Перенаправлять на ту же ссылку но без слеша';
							echo'<option value="2">Показывать ошибку 404 (Страница не найдена)';
							}else{
							echo'<option value="2" selected>Показывать ошибку 404 (Страница не найдена)';
							echo'<option value="1">Перенаправлять на ту же ссылку но без слеша';
							}
							echo'</select>
						</div>

						<div class="mb-3">
							<label class="form-label">Правила GET параметров в адресах</label>
							<select class="form-select" name="uriRule">';
							if($Config->uriRule == '1'){
							echo'<option value="1" selected>Разрешить работу произвольных GET параметров';
							echo'<option value="2">Запретить произвольные GET параметры';
							}else{
							echo'<option value="2" selected>Запретить произвольные GET параметры';
							echo'<option value="1">Разрешить работу произвольных GET параметров';
							}
							echo'</select>
						</div>

						<div class="mb-3">
							<label class="form-label">Протокол формирования ссылок</label>
							<select class="form-select" name="protocol">
							<option value="http" '.($Config->protocol == 'http'?'selected':'').'>http
							<option value="https" '.($Config->protocol == 'https'?'selected':'').'>https
							</select>
							<small class="mt-2">Используется для статических данных, например sitemap.xml или переадресаций.</small>
						</div>

						<div class="mb-3">
							<label class="form-label">Правила переадресации с http на https</label>
							<select class="form-select" name="httpsRule">
							<option value="0" '.($Config->httpsRule == '0'?'selected':'').'>Не переадресовывать, оставить на усмотрение сервера
							<option value="1" '.($Config->httpsRule == '1'?'selected':'').'>Переадресовывать с протокола http на https
							</select>
							<small class="mt-2">Если есть возможность настроить переадресацию в панели сервера, то лучше это сделать там.</small>
						</div>

						<div class="mb-3">
							<label class="form-label">Правила переадресации с www домена</label>
							<select class="form-select" name="wwwRule">
							<option value="0" '.($Config->wwwRule == '0'?'selected':'').'>Не переадресовывать, оставить на усмотрение сервера
							<option value="1" '.($Config->wwwRule == '1'?'selected':'').'>Переадресовывать с домена www на домен без www
							</select>
							<small class="mt-2">Если есть возможность настроить переадресацию в панели сервера, то лучше это сделать там.</small>
						</div>

					</div>
				</div>
					
				<input class="btn btn-primary my-3" type="submit" name="" value="Сохранить">
		
			</form>
		</div>';
	}
	
	if($act=='pass'){
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						
						<a class="nav-item" href="setting.php?">Основные настройки системы</a>
						<a class="nav-item" href="files.php?act=editor&amp;dir=../modules/'.$Config->template.'&file=../modules/'.$Config->template.'/template.php">Правка текущего шаблона</a>
						<a class="nav-item" href="setting.php?act=pass">Смена пароля администратора</a>
						<a class="nav-item" href="users.php?act=cfg">Настройки пользователей</a>
	
					</ul>

				</div>
			</div>
		</div>

		<div class="container">
			<form name="settingform" action="setting.php?" method="post">
				<INPUT TYPE="hidden" name="act" VALUE="addpass">

				<div class="col-lg-4">
					<label class="form-label">Новый пароль администратора:</label>
					<div class="input-group">
						<input type="password" class="form-control" name="new_cfg_password" value="" id="passwordInput">
						<button class="btn btn-outline-secondary" type="button" id="togglePassword">
							<i class="bi bi-eye"></i>
						</button>
					</div>
				</div>
			
				<input class="btn btn-primary" type="submit" name="" value="Сохранить">
			</form>
		</div>';
		
		
	
	}
	
	if($act=='addsetting'){
		if( ($_POST['header'] == '') ||
			($_POST['slogan'] == '')||
			($_POST['indexPage'] == '')){
			echo'<div class="msg">Не все поля заполнены</div>';
		}else{
			$Config->version = htmlspecialchars(specfilter($version));
			
			$Config->template = htmlspecialchars(specfilter($_POST['template']));
			$Config->header = htmlspecialchars(specfilter($_POST['header']));
			$Config->slogan = htmlspecialchars(specfilter($_POST['slogan']));

			$Config->icon = htmlspecialchars(specfilter($_POST['icon']));
			$Config->logo = htmlspecialchars(specfilter($_POST['logo']));
			
			$Config->wysiwyg = htmlspecialchars(specfilter($_POST['wysiwyg']));
			$Config->timeZone = htmlspecialchars(specfilter($_POST['timeZone']));
			$Config->slashRule = (int) htmlspecialchars(specfilter($_POST['slashRule']));
			$Config->gzip = (int) htmlspecialchars(specfilter($_POST['gzip']));

			$Config->ya = $_POST['ya'];
			
			$Config->yaweb = htmlspecialchars(specfilter($_POST['yaweb']));

			// $Config->adminStyleFile = htmlspecialchars(specfilter($_POST['adminStyleFile'])); // 5.1.0 удалено 
			$Config->timeAuth = (int) htmlspecialchars(specfilter($_POST['timeAuth']));
			$Config->ticketSalt = htmlspecialchars(specfilter($_POST['ticketSalt']));
			$Config->uriRule = (int) htmlspecialchars(specfilter($_POST['uriRule']));
			$Config->protocol = htmlspecialchars(specfilter($_POST['protocol']));
			
			$Config->httpsRule = (int) htmlspecialchars(specfilter($_POST['httpsRule']));
			$Config->wwwRule = (int) htmlspecialchars(specfilter($_POST['wwwRule']));
			$Config->errorReporting = (int) htmlspecialchars(specfilter($_POST['errorReporting']));
			$Config->idToLowerCase = (int) htmlspecialchars(specfilter($_POST['idToLowerCase']));
			$Config->spaceCharacter = htmlspecialchars(specfilter($_POST['spaceCharacter']));

			$Config->indexPage = Page::exists($_POST['indexPage']) ? $_POST['indexPage'] : $Config->indexPage;
			
			if(System::saveConfig($Config)){
				echo '<div class="msg">Настройки успешно сохранены</div>';
				System::notification('Изменена конфигурация системы', 'g');
			}else{
				echo'<div class="msg">Ошибка при сохранении настроек</div>';
				System::notification('Ошибка при сохранении конфигурации системы', 'r');
			}
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'setting.php?\';', 200);
</script>
<?php
	}
	
	if($act=='addpass'){
		if($_POST['new_cfg_password'] == ''){
			echo'<div class="msg">Не все поля заполнены</div>';
		}else{
			$Config->salt = random(100); // Меняем соль для шифрования
			
			$new_cfg_password = cipherPass($_POST['new_cfg_password'], $Config->salt);
			setcookie('password',$new_cfg_password,time()+32000000,'/');
			
			$Config->adminPassword = cipherPass($new_cfg_password, $Config->salt); // Еще раз шифруем для записи в настройки
			System::notification('Изменен пароль от панели управления IP '.IP.' UA '.UA.'', 'g');
			
			if(System::saveConfig($Config)){
				echo '<div class="msg">Пароль успешно изменен</div>';
			}else{
				echo'<div class="msg">Ошибка при сохранении настроек</div>';
			}
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'setting.php?act=pass\';', 200);
</script>
<?php
	}
	
	
	
	
	
	
}else{
echo'<div class="msg">Необходимо выполнить авторизацию</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'index.php?\';', 200);
</script>
<?php
}

require('./include/end.dat');
?>

<script type="text/javascript">
var inputimg = document.getElementById('inputimg-icon');
var lastinputimg = inputimg.value;
setInterval(function(){
	if (inputimg.value != lastinputimg) {
		document.getElementById('img-icon').src = inputimg.value;
		lastinputimg = inputimg.value;
	}
}, 500);
//Скрываем тег <img>
const imgElement = document.getElementById('img-icon');

if (imgElement.src === '') {
  imgElement.style.display = 'none';
};

var inputimg2 = document.getElementById('inputimg-logo');
var lastinputimg2 = inputimg2.value;
setInterval(function(){
	if (inputimg2.value != lastinputimg2) {
		document.getElementById('img-logo').src = inputimg2.value;
		lastinputimg2 = inputimg2.value;
	}
}, 500);
//Скрываем тег <img>
const imgElement2 = document.getElementById('img-logo');

if (imgElement2.src === '') {
  imgElement2.style.display = 'none';
}
</script>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>

