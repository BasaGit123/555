<?php
require('../system/global.dat');
require('./include/start.dat');

if (file_exists('newpassword.dat')){
?>
<script type="text/javascript">
var newpassword = '<div class="a">' +
	'Нажмите "Продолжить" для сохранения нового пароля из файла<br>'+
	'</div>'+
	'<div class="b">'+
	'<button type="button" onclick="window.location.href = \'newpassword.php\';">Продолжить</button>'+
	'</div>';
openwindow('window', 650, 'auto', newpassword);
</script>
<?php

}else{

if($status == 'admin'){
?>
<script type="text/javascript">
var info = '<div class="d-flex flex-column gap-4">'+
	'<div class="a">' +
	'<span>Версия my-engine: <?php echo $version;?></span>'+
	'<span>Версия php: <?php echo phpversion();?></span>'+
	'<span>Https: <?php echo HTTPS?'true':'false'; echo isset($_SERVER['HTTPS'])?' "'.$_SERVER['HTTPS'].'"':'';?></span>'+
	'<span>Host name: <?php echo HOST;?></span>'+
	'<span>Server name: <?php echo SERVER;?></span>'+
	'<span>Server protocol: <?php echo PROTOCOL;?></span>'+
	'<span>Document root: <?php echo quotemeta(DR);?></span>'+
	'<span>Server Document root: <?php echo $_SERVER['DOCUMENT_ROOT'];?></span>'+
	'<span>User agent: <?php echo UA;?></span>'+
	'</div>'+
	'<div class="b">'+
	'<div class="btn-gr"><a class="btn btn-primary" href="notifications.php">Посмотреть уведомления системы</a> <button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Закрыть</button></div>'+
	'</div></div>';
</script>

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">
					<ul class="nav">
						<a class="nav-item" href="//my-engine.ru/download" target="_blank">Проверить обновления</a>
						<a class="nav-item" href="javascript:void(0);" onclick="openwindow('window', 650, 'auto', info);">Информация о системе</a>
						<a class="nav-item" href="license.php">Соглашение с пользователем</a>
						<a class="nav-item" href="notifications.php">Уведомления системы</a>
					</ul>
				</div>
			</div>
		</div>

		<div class="container d-flex flex-column gap-3">
			<h1>Панель управления</h1>
		<?php
		if (!is_writable('../data/cfg/config.dat')||
			!is_writable('../data/')||
			!is_writable('../modules/')){
			echo'<div class="error">Необходимо выставить нужные права доступа файлам и папкам. Какие права выставлять вы можете узнать на <a href="//my-engine.ru/" target="_blank" style="text-decoration: underline;">сайте</a> разработчиков.</div>';
		}
		if($Config->adminPassword == cipherPass(cipherPass('123', $Config->salt), $Config->salt)){
			echo'<div class="error">Необходимо изменить пароль от панели управления, перейдите в <a href="setting.php?act=pass">настройки</a> и введите новый пароль.</div>';
		}
		if($Config->ticketSalt == '123'){
			echo'<div class="error">Необходимо изменить соль шифрования, перейдите в <a href="setting.php">настройки</a> и введите любые другие символы.</div>';
		}
		if($Config->errorReporting){
			echo'<div class="error">Включено отображение PHP ошибок, перейдите в <a href="setting.php">настройки</a> для отключения.</div>';
		}
		if (file_exists('../admin/index.php')){
			echo'<div class="notification">Для повышения безопасности сайта, вы можете переименовать папку панели администратора</div>';
		}
		?>

		</div>
	
	<div class="container my-4">
		<h3 class="mb-3">Установленные модули</h3>
		<div class="modules-list">
			
		<?php
			$integration = array();
			
			$listModules = System::listModules();
			
			foreach($listModules as $value){
				
				if(Module::isAdminPage($value)){
					
					if(($icon = Module::icon($value)) === false){
						$icon = 'include/indexmodule.svg';
					}
					
					$info = Module::info($value);
					
					echo'
						<a class="module" href="module.php?module='.$value.'">
							<img src="'.$icon.'" alt=""><br>'.$info['indexname'].'
						</a>
					';
				}
				
				if(Module::isIntegrationAdminIndex($value)){
					$integration[] = Module::pathRun($value, 'integration_admin');
				}
				
			}
		?>
		</div>
		
		
		<h3 class="my-3">Лог действий на сайте</h3>
		<div class="integration mb-4">

			<?php
				foreach($integration as $value){
					include($value);
				}
			?>
		</div>
		
	</div>
<?php	
}else{
?>
<script type="text/javascript">
var enterform = '<form action="in.php?" method="post">'+
	'<div class="p-4">' +
	'<div class="form-label mb-2">Введите пароль</div><input class="form-control" type="password" name="password_form" value="" autofocus>' +
	'<div class="btn-gr">' +
	'<a class="btn btn-secondary" href="/">На главную страницу сайта</a> <input class="btn btn-primary" type="submit" name="" value="Вход">' +
	'</div></form>';
setTimeout(function(){
	openwindow('window', 20, 'auto', enterform);
}, 1000);
</script>
<?php
}
}
require('./include/end.dat');
?>