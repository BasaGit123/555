<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

if($act=='index'){
	echo'

	<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						
						<a href="index.php">&#8592; Вернуться назад</a>
	
					</ul>

				</div>
			</div>
		</div>

	<div class="container">
		<h1>Генератор карты сайта</h1>';

	
	if(file_exists('../sitemap.xml')){
		echo'<p style="color:red;">В корневой папке сайта обнаружен файл sitemap.xml. Начиная с версии движка 5.1.24, sitemap.xml генерируется автоматически. Необходимо удалить этот файл из корневой папки сайта, чтобы модуль начал работать.</p>';
	}else{
		echo'<p>Ваш sitemap.xml находится по адресу <a href="/sitemap.xml" target="_blank">'.SERVER.'/sitemap.xml</a></p>
		<p>Для уменьшения нагрузки на сервер каждые 24 часа вывод sitemap.xml кешируется. Выможете сбросить кеш прямо сейчас, если необходимо вывести актуальный sitemap.xml.</p>
		<p><button class="btn btn-secondary" onclick="window.location.href = \'module.php?module='.$MODULE.'&act=add\';">Сбросить кэш сейчас</button> &nbsp; </p>';
	}
	echo'
	
	<form name="forma" action="module.php?module='.$MODULE.'" method="post">
		<INPUT TYPE="hidden" NAME="act" VALUE="add2">

			<div class="col-12 mb-3">
				<label class="form-label">Игнорируемые идентификаторы:</label>
				<textarea class="form-control" type="text" name="ignor" rows="10">'.($sitemapStorage->iss('ignor')?$sitemapStorage->get('ignor'):'').'</textarea>
				<small class="mt-2">
					Укажите идентификаторы страниц или новостей, которые нужно игнорировать при генерации карты сайта.
					Каждый идентификатор слудует указывать на новой строке.
				</small>
			</div>
			
		
		
			<input class="btn btn-primary" type="submit" name="submit" value="Сохранить">

	</form>
	</div>';
}
if($act=='add'){
	$sitemapStorage = new EngineStorage('module.sitemap');
	$sitemapStorage->delete('sitemap');
	System::notification('Сброс кеша sitemap.xml', 'g');
	echo'<div class="msg">Кеш карты сайта сброшен</div>';

?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>\';', 500);
</script>
<?php
}

if($act=='add2'){
	$sitemapStorage->set('ignor', htmlspecialchars($_POST['ignor']));
	echo'<div class="msg">Настройки успешно сохранены</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>\';', 500);
</script>
<?php
}
?>