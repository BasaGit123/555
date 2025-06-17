<?php
require('../system/global.dat');
require('./include/start.dat');
if($status=='admin'){
	if($act=='index'){?>
		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">
					<ul class="nav">
						<a class="nav-item" href="index.php">&#8592; Назад</a>
						<a class="nav-item" type="button" onClick="openwindow('window', 680, 'auto', clearlog);" title="Создать новый файл в текущей папке">Очистить уведомления</a> 
					</ul>
				</div>

			</div>
		</div>

		<div class="container">
		<script type="text/javascript">
		var clearlog = '<div class="a">' +
			'Уведомления автоматически чистятся как только размер их хранилища превысит 100кб.' +
			'</div><div class="b">' +
			'<div class="btn-gr"><a class="button btn btn-primary" href="notifications.php?act=clear">Очистить сейчас</a> <button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Закрыть</button></div>' +
			'</div>';

		</script>

		<h1>Уведомления системы</h1>
		
		<div class="slog" id="scroll" style="">
		<?php
			$file = file('../data/cfg/notifications.dat');
			foreach($file as $key => $value){
				$arr = explode('|',trim($value));
				echo'<div style="color: '.$arr[0].';">'.$arr[1].' - '.$arr[2].'</div>';
			}
		?>
		</div>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById('scroll').scrollTop = document.getElementById('scroll').scrollHeight;
			}
		</script>
		
		</div>
		
		<?php
		
	}
	if($act=='clear'){
		filefputs('../data/cfg/notifications.dat', '', 'w+');
		System::notification('Выполнена полная очистка уведомлений', 'y');
		echo'<div class="msg">Уведомления успешно очищены</div>';
		?>
		<script type="text/javascript">
			setTimeout('window.location.href = \'notifications.php?\';', 500);
		</script>
		<?php
	}
	
}else{
echo'<div class="msg">Необходимо выполнить авторизацию</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'index.php?\';', 500);
</script>
<?php
}
require('include/end.dat');
?>