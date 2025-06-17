<?php
if (!class_exists('System')) exit; // Запрет прямого доступа
?>

<script type="text/javascript">

var iframefiles = '<div class="a"><iframe src="iframefiles.php?id=inputimg" width="100%" height="300" style="border:0;">Ваш браузер не поддерживает плавающие фреймы!</iframe></div>'+
'<div class="b">'+
'<button type="button" onclick="closewindow(\'window\');">Отмена</button>'+
'</div>';

var gotocfgcat = '<div class="a">Несохраненные данные могут быть утеряны</div>'+
'<div class="b">'+
'<button type="button" onclick="window.location.href = \'module.php?module=<?php echo $MODULE;?>&amp;act=cat\';">Перейти к управлению категориями</button> '+
'<button type="button" onclick="closewindow(\'window\');">Отмена</button>'+
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

function dell(url, n, u){
return '<div class="a">Подтвердите удаление новости: <i>' + n + ' (<a href="//' + u + '" target="_blank">' + u + '</a>)</i>' +
	'<div class="btn-gr">' +
	'<button class="btn btn-danger" type="button" onClick="window.location.href = \''+url+'\';">Удалить</button> '+
	'<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
	'</div></div>';
}

</script>
<?php
	$menu_page = '
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;">Добавить новость</a>
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;act=edit">Все новости</a>
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;act=cat">Категории</a>
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;act=comment">Комментарии пользователей</a>
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;act=cfg">Настройки модуля</a>
		<a class="nav-item" href="module.php?module='.$MODULE.'&amp;act=info">RSS информация</a>
	';

	if($act=='info'){
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>

		
		<div class="container">
		<h1>RSS информация</h1>
			<p>Ваш RSS канал новостей находится по адресу <a href="/'.$newsConfig->idPage.'/rss.xml" target="_blank">'.SERVER.'/'.$newsConfig->idPage.'/rss.xml</a></p>
			<p>Для корректной работы с некоторыми агрегаторами необходимо, чтобы в <a href="setting.php" target="_blank">настройках движка</a> были разрешены "Произвольные GET параметры".</p>
			<p>RSS канал новостей был разработан согласно документации <a href="https://zen.yandex.ru/" target="_blank">Яндекс.Дзен</a> и других rss агрегаторов. Вы можете без проблем подключить свой сайт к группам vk.com, чтобы новости автоматически загружались в ленту.</p>
			';
			// Торбо страницы удалены 5.1.40


		
	}


	
	// Торбо страницы удалены 5.1.40


	


	if($act=='index')
	{
		if(isset($_GET['dub'])){
			if($_GET['dub'] == '1'){
				$news = htmlspecialchars(specfilter($_GET['news']));
				if(($newsParam = json_decode($newsStorage->get('news_'.$news))) != false){
					$param['header'] = 'Дубликат новости';
				}
			}
		}
		
		
		
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>

		<div class="container mb-3">

			<form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post">
			<input type="hidden" name="act" id="act" value="addnews">

				<div class="d-flex flex-column gap-3">
					<div class="col-12 bs-br bg-white overflow-hidden">
						<h3 class="bg-light p-4 m-0">Добавление новости</h3>
						<div class="p-4">

							<div class="col-lg-6 mb-3">
								<label class="form-label">Заголовок новости:</label>
								<input class="form-control" type="text" name="header" id="header" value="">
							</div>

							<div class="col-12 mb-3">
								<label class="form-label">Содержимое новости:</label>
								<textarea class="form-control" name="content" rows="20" cols="100" style="height:250px;">'.htmlspecialchars('Содержимое новости').'</textarea>
							</div>';

							if($Config->wysiwyg){
								if(Module::isWysiwyg($Config->wysiwyg)){
									require Module::pathRun($Config->wysiwyg, 'wysiwyg');
								}
							}

							echo '

							<div class="col-12 mb-3">
								<label class="form-label">Превью новости:</label>
								<textarea class="form-control" name="prev" rows="4" placeholder="Краткое содержание новости"></textarea>
							</div>

							<div class="col-12 mb-3">
								<label class="form-label">Категория</label>';
									foreach($newsConfig->cat as $key => $value){
											echo'
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="cat[]" value="'.$key.'">
												<label class="form-check-label">'.$value.'</label>
											</div>
											';
									}
									
									echo' 
							</div>

							

							<div class="col-lg-6">
								<label class="form-label">Изображение</label>
								<div class="input-group mb-0">
									<input type="text" class="form-control" name="img" id="inputimg" value="">
									<button class="btn btn-secondary" type="button" onClick="openwindow(\'window\', 750, \'auto\', iframefiles);">Выбрать файл</button>
								</div>
								<img class="mt-3" src="" alt="" id="img" style="width: 180px;">
							</div>
						</div>
					</div>


					<div class="col-12 bs-br bg-white overflow-hidden">
						<h3 class="bg-light p-4 m-0">Кастомные поля</h3>
						<div class="p-4">';
								//Custom
								foreach($newsConfig->custom as $value){
								echo'
								<div class="col-lg-6 mb-3">
									<label class="form-label">'.$value->name.'</label>
									'.($value->type == 'input'?'<input class="form-control col-lg-6" name="custom['.$value->id.']">':
									'<textarea class="form-control col-lg-6"  name="custom['.$value->id.']" ></textarea>').'
								</div>';
								}
						echo '		
						</div>
					</div>


					<div class="col-12 bs-br bg-white overflow-hidden">
						<h3 class="d-flex align-items-center gap-2 bg-light p-4 m-0">SEO оптимизация <small>( Для поисковиков )</small></h3>
						<div class="p-4">

							<div class="col-lg-6 mb-3">
								<label class="form-label">Заголовок (Title):</label>
								<input class="form-control" type="text" name="title" value="">
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Ключевые слова (Keywords):</label>
								<input class="form-control" type="text" name="keywords" value="">
							</div>

							<div class="col-12 mb-3">
								<label class="form-label">Описание (Description):</label>
								<textarea class="form-control" name="description" rows="4" placeholder="Краткое содержание новости для поисковиков"></textarea>
							</div>

							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" name="comments" value="y">
								<label class="form-check-label">
									Разрешить комментирование
								</label>
							</div>

							<div class="col-lg-4">
								<label class="form-label">Идентификатор (URL)</label>
								<div class="input-group mb-0">
									<input type="text" class="form-control" name="id" id="id" value="'.uniqid('n').'">
								</div>
							</div>
							<div class="col-lg-4 mb-3">
                                    <div class="mb-3">
                                    <label class="form-label">Индексировать</label>
										<SELECT class="form-select" name="robots">
											<OPTION VALUE="0">Да
											<OPTION VALUE="1">Нет
										</SELECT>
                                    </div>
                            	</div>
						</div>
					</div>

					<div class="btn-gr mb-4">
						<button class="btn btn-primary" type="button" onClick="submit();">Опубликовать</button>
						<button class="btn btn-secondary" type="button" onClick="document.getElementById(\'act\').value = \'adddraft\'; submit();">Сохранить в черновик</button>
					</div>
					
				</div>
			</form>
		</div>';

		?>
		<script type="text/javascript">
		var inputimg = document.getElementById('inputimg');
		var lastinputimg = inputimg.value;
		setInterval(function(){
			if (inputimg.value != lastinputimg) {
				document.getElementById('img').src = inputimg.value;
				lastinputimg = inputimg.value;
			}
		}, 500);
		</script>

		<?php
				
				
			}
	

	if($act=='addnews' || $act=='adddraft'){
		$param = array();
		$param['header'] = ($_POST['header'] == '')?'Без названия':htmlspecialchars(specfilter($_POST['header']));
		$param['title'] = htmlspecialchars(specfilter($_POST['title']));
		$param['keywords'] = htmlspecialchars(specfilter($_POST['keywords']));
		$param['robots'] = htmlspecialchars(specfilter($_POST['robots']));
		$param['description'] = htmlspecialchars(specfilter($_POST['description']));
		$param['img'] = htmlspecialchars(specfilter($_POST['img']));
		$param['prev'] = $_POST['prev'];
		$param['content'] = $_POST['content'];
		//$param['date'] = htmlspecialchars(specfilter($_POST['date'])); // удалено в 5.1.14
		$param['comments'] = (isset($_POST['comments']) && $_POST['comments'] == 'y')?'1':'0';
		// 5.1.14
		$param['time'] = time();
		$param['date'] = date($newsConfig->formatDate, $param['time']);
		// 5.1.18
		$param['cat'] = isset($_POST['cat'])?$_POST['cat']:array();
		// 5.1.20
		$array = array();
		if(isset($_POST['custom'])){
			foreach($_POST['custom'] as $key => $value){
				$array[htmlspecialchars($key)] = $value;
			}
		}
		$param['custom'] = $array;
		//

		$news = isset($_POST['news'])?htmlspecialchars(specfilter($_POST['news'])):'';

		if($act=='addnews')
		{
			$id = ($newsStorage->iss('news_'.$_POST['id']) == false && System::validPath($_POST['id']))?$_POST['id']:uniqid('n');
			if($newsStorage->set('news_'.$id, json_encode($param, JSON_FLAGS))){
				// Добавляем ID новости в список
				$listIdNews = json_decode($newsStorage->get('list'), true); // Получили список ввиде массива
				$listIdNews[] = $id; // Добавили новый элемент массива в конец
				$newsStorage->set('list', json_encode($listIdNews, JSON_FLAGS)); // Записали массив в виде json
				// Добавляем в категории 
				$listIdCat = json_decode($newsStorage->get('category'), true); // Получили список ввиде массива
				$listIdCat[$id] = $param['cat']; // Добавили новый элемент массива в конец
				$newsStorage->set('category', json_encode($listIdCat, JSON_FLAGS)); // Записали массив в виде json
				echo'<div class="msg">Новость успешно опубликована</div>';
				System::notification('Добавлена новость с заголовком "'.$param['header'].'"');
			}else{
				echo'<div class="msg">Произошла ошибка при добавлении новости</div>';
				System::notification('Произошла ошибка при добавлении новости', 'r');
			}

			// Удаление новости из черновика при публикации
			if($newsStorage->delete('draft_'.$news)){
				// Удаляем страницу из черновиков
				$draftListIdNews = json_decode($newsStorage->get('draftList'), true); // Получили список ввиде массива
				if(($key = array_search($news, $draftListIdNews)) !== false){
					unset($draftListIdNews[$key]); // Удалили найденый элемент массива
				}
				$draftListIdNews = array_values($draftListIdNews); // Переиндексировали числовые индексы 
				$newsStorage->set('draftList', json_encode($draftListIdNews, JSON_FLAGS)); // Записали массив в виде json
				System::notification('Удалена новость из черновика с идентификатором '.$news.'', 'g');
			}
		}

		if($act=='adddraft'){
			$id = $newsStorage->iss('draft_'.$_POST['id']) == false && System::validPath($_POST['id'])?$_POST['id']:uniqid('n');
			if($newsStorage->set('draft_'.$id, json_encode($param, JSON_FLAGS))){
				// Добавляем ID новости в список
				$draftListIdNews = json_decode($newsStorage->get('draftList'), true); // Получили список ввиде массива
				$draftListIdNews[] = $id;// Добавили новый элемент массива в конец
				$newsStorage->set('draftList', json_encode($draftListIdNews, JSON_FLAGS)); // Записали массив в виде json
				echo'<div class="msg">Новость добавлена в черновик</div>';
				System::notification('В черновик добавлена новость с заголовком "'.$param['header'].'"');
			}else{
				echo'<div class="msg">Произошла ошибка при добавлении новости в черновик</div>';
				System::notification('Произошла ошибка при добавлении новости в черновик', 'r');
			}
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=edit\';', 3000);
</script>
<?php
	}

	

	
	
	if($act=='cfg'){
		
		
		
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>

		

		<div class="container">
		
		<form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post" style="margin:0px; padding:0px;">
		<INPUT TYPE="hidden" NAME="act" VALUE="addcfg">
			<div class="d-flex flex-column gap-3">

			<div class="col-12 bs-br bg-white overflow-hidden">
				<h3 class="bg-light p-4 m-0">Настройки модуля</h3>
					<div class="p-4">

						<div class="col-lg-6 mb-3">
							<label class="form-label">Количество превью записей на странице:</label>
							<input class="form-control" type="text" name="navigation" value="'.$newsConfig->navigation.'" maxlength="3">
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Количество превью при выводе в блоке:</label>
							<input class="form-control" type="text" name="countInBlok" value="'.$newsConfig->countInBlok.'" maxlength="3">
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Формат вывода даты (Формат функции date):</label>
							<input class="form-control" type="text" name="formatDate" value="'.$newsConfig->formatDate.'">
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Идентификатор страницы с новостями:</label>
							<input class="form-control" type="text" name="idPage" value="'.$newsConfig->idPage.'">
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Идентификатор страницы пользователей:</label>
							<input class="form-control" type="text" name="idUser" value="'.$newsConfig->idUser.'">
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Шаблон для вывода превью:</label>
							'.(file_exists(Module::pathRun($Config->template, 'news.prev.template'))?'<a class="link" target="_blank" href="files.php?act=editor&amp;dir=../modules/'.$Config->template.'&file=../modules/'.$Config->template.'/news.prev.template.php&linkback='.urlencode('module.php?module=news&act=cfg').'">Открыть редактор для правки шаблона</a>':'<span class="comment">Не предусмотрен</span>').'
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Шаблон для вывода новости</label>
							'.(file_exists(Module::pathRun($Config->template, 'news.content.template'))?'<a class="link" target="_blank" href="files.php?act=editor&amp;dir=../modules/'.$Config->template.'&file=../modules/'.$Config->template.'/news.content.template.php&linkback='.urlencode('module.php?module=news&act=cfg').'">Открыть редактор для правки шаблона</a>':'<span class="comment">Не предусмотрен</span>').'
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Шаблон для вывода превью в боковом блоке:</label>
							'.(file_exists(Module::pathRun($Config->template, 'news.blok.template'))?'<a class="link" target="_blank" href="files.php?act=editor&amp;dir=../modules/'.$Config->template.'&file=../modules/'.$Config->template.'/news.blok.template.php&linkback='.urlencode('module.php?module=news&act=cfg').'">Открыть редактор для правки шаблона</a>':'<span class="comment">Не предусмотрен</span>').'
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Категории новостей:</label>
							<a href="module.php?module='.$MODULE.'&amp;act=cat">Перейти к управлению категориями</a>
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Дополнительные поля:</label>
							<a href="module.php?module='.$MODULE.'&amp;act=custom">Перейти к настройкам дополнительных полей</a>
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Выводимая категория на начальной странице:</label>
							<select class="form-select" name="indexCat">
								<option value="0"'.($newsConfig->indexCat == '0'?' selected':'').'>Выводить из всех категорий
								<option value=""'.($newsConfig->indexCat == ''?' selected':'').'>Выводить только новости без категорий';
								foreach($newsConfig->cat as $key => $value){
									echo'<option value="'.$key.'"'.($newsConfig->indexCat === $key?' selected':'').'>'.$value;
								}
								echo'<option value="-1"'.($newsConfig->indexCat == '-1'?' selected':'').'>Не выводить новости на начальной странице
								<option value="-2"'.($newsConfig->indexCat == '-2'?' selected':'').'>Не выводить новости на начальной странице, а также на страницах категорий
							</select>
						</div>


						<div class="col-lg-6 mb-3">
							<label class="form-label">Выводимая категория в боковой блок:</label>
							<select class="form-select" name="blokCat">
								<option value="0"'.($newsConfig->blokCat === '0'?' selected':'').'>Выводить из всех категорий
								<option value=""'.($newsConfig->blokCat == ''?' selected':'').'>Выводить только новости без категорий';
								
								foreach($newsConfig->cat as $key => $value){
									echo'<option value="'.$key.'"'.($newsConfig->blokCat === $key?' selected':'').'>'.$value;
								}

								$checked = ($newsConfig->commentEngine == 1)?'checked':'';
								echo'
							</select>
						</div>

						<div class="col-lg-6 mb-3">
							<label class="form-label">Разрешить просмотр новости:</label>
							<select class="form-select" name="indexPost">
								<option value="0"'.($newsConfig->indexPost == '0'?' selected':'').'>Разрешить
								<option value="1"'.($newsConfig->indexPost == '1'?' selected':'').'>Запретить
								
							</select>
						</div>

						<div class="form-check">
							<input class="form-check-input" type="checkbox" NAME="commentEngine" VALUE="y" id="checkbox" '.$checked.'>
							<label class="form-check-label">
								Использовать собственный сервис комментариев
							</label>
						</div>

						<div class="col-lg-6 mb-3" id="trCommentTemplate">
							<label class="form-label">Код сервиса комментариев</label>
							<textarea class="form-control" name="commentTemplate" id="textareaCommentTemplate" rows="20" cols="100" style="height:150px;">'.htmlspecialchars($newsConfig->commentTemplate).'</textarea>
							<small class="comment mt-1">Подробнее о сервисах комментариев <a href="http://my-engine.ru/newscomments">тут</a></small>
						</div>

						<div class="btn-gr">
							<button class="btn btn-primary" type="button" onClick="submit();">Сохранить</button>
							<a class="btn btn-secondary" href="modules.php?">Вернуться назад</a>
						</div>
						
					</div>
				</div>
			</div>
		</form>
		
		</div>';
		?>
		<script type="text/javascript">
		function checked(){
			document.getElementById('trCommentTemplate').style.display = (document.getElementById('checkbox').checked)?'none':'';
		}
		document.getElementById('checkbox').onclick  = function(){
			checked();
			if(!document.getElementById('checkbox').checked){
				document.getElementById('textareaCommentTemplate').focus();
			}
		}
		checked();
		</script>
		<?php
		
		
		
		
	}
	
	if($act=='addcfg'){
		
		if( !is_numeric($_POST['navigation']) || 
			!is_numeric($_POST['countInBlok']) || 
			$_POST['formatDate'] == ''||
			!System::validPath($_POST['idPage']) || 
			!System::validPath($_POST['idUser'])
		){
			echo'<div class="msg">Не все поля заполнены, или заполнены неправильно</div>';
		}else{ 
			$newsConfig->navigation = htmlspecialchars(specfilter($_POST['navigation']));
			$newsConfig->countInBlok = htmlspecialchars(specfilter($_POST['countInBlok']));
			$newsConfig->formatDate = htmlspecialchars(specfilter($_POST['formatDate']));
			$newsConfig->idPage = htmlspecialchars(specfilter($_POST['idPage']));
			$newsConfig->idUser = htmlspecialchars(specfilter($_POST['idUser']));
			// $newsConfig->prevTemplate = $_POST['prevTemplate'];
			// $newsConfig->contentTemplate = $_POST['contentTemplate'];
			$newsConfig->commentTemplate = $_POST['commentTemplate'];
			$newsConfig->commentEngine = ($_POST['commentEngine'] == 'y')?'1':'0';
			$newsConfig->blokCat = htmlspecialchars(specfilter($_POST['blokCat']));
			$newsConfig->indexCat = htmlspecialchars(specfilter($_POST['indexCat']));
			$newsConfig->indexPost = htmlspecialchars(specfilter($_POST['indexPost']));
			
			if($newsStorage->set('newsConfig', json_encode($newsConfig, JSON_FLAGS))){
				echo'<div class="msg">Настройки успешно сохранены</div>';
				System::notification('Изменены параметры модуля новостей');
			}else{
				echo'<div class="msg">Произошла ошибка записи настроек</div>';
				System::notification('Произошла ошибка при сохранении параметров модуля новостей', 'r');
			}
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=cfg\';', 500);
</script>
<?php	
	}
	
	if($act=='edit') {

		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>

		<div class="container">

			<form action="module.php?module='.$MODULE.'&amp;act=search" method="post">
				<div class="input-group">
				<input class="form-control" type="text" name="q" value="" placeholder="Поиск по публикациям" autocomplete="off">
				<input class="btn btn-primary" type="submit" name="" value="Поиск">
				</div> 
			</form>';
		
		

			if(isset($_GET['nom_page']) == false || $_GET['nom_page'] == 1 || $_GET['nom_page'] == ''){
				
				if(($draftListIdNews = json_decode($newsStorage->get('draftList'), true)) == true){

					echo'<h3>Черновики</h3>';
					echo'
					<div class="item-list">';
					
						//перевернули масив для вывода новостей в обратном порядке
						$draftListIdNews = array_reverse($draftListIdNews);

						foreach($draftListIdNews as $value){
							if($newsStorage->iss('draft_'.$value)){
								$newsParam = json_decode($newsStorage->get('draft_'.$value));
									
								$comments = ($newsParam->comments == '1')?'<span style="color: green;">Включено</span>':'<span style="color: red;">Выключено</span>';
								
								echo'
								<div class="card mb-3" style="padding:25px">
									<div class="d-flex flex-column justify-content-center gap-3">
										<h5 class="m-0"><a href="module.php?module='.$MODULE.'&amp;act=editdraft&amp;news='.$value.'&amp;nom_page=1">'.$newsParam->header.'</a></h5>
										<small>
											<div class="text-danger">Неопубликованно!</div>
											<div>Дата редактирования: '.(isset($newsParam->time)?date($newsConfig->formatDate, $newsParam->time):$newsParam->date).'</div>
											<div>URL: <a href="//'.SERVER.'/'.$newsConfig->idPage.'/'.$value.'" target="_blank">'.SERVER.'/'.$newsConfig->idPage.'/'.$value.'</a></div>
										</small>

										<div class="d-flex gap-2">
											<a href="module.php?module='.$MODULE.'&amp;act=editdraft&amp;news='.$value.'">
											<svg id="edit" enable-background="new 0 0 426.589 426.589" viewBox="0 0 426.589 426.589" xmlns="http://www.w3.org/2000/svg"><g><g id="layer9"><g id="g1195" transform="translate(-34.396 -307.584)"><g id="path914-4"><path d="m98.316 307.589c-35.094 0-63.917 28.823-63.917 63.917v298.75c0 35.093 28.823 63.917 63.917 63.917h298.75c35.093 0 63.917-28.823 63.917-63.917v-170.709c-.261-11.782-10.025-21.121-21.807-20.86-11.414.253-20.606 9.446-20.86 20.86v170.708c0 12.035-9.215 21.25-21.25 21.25h-298.75c-12.035 0-21.25-9.215-21.25-21.25v-298.75c0-12.035 9.215-21.25 21.25-21.25h191.958c11.782.264 21.548-9.072 21.812-20.854.265-11.782-9.072-21.548-20.854-21.812-.319-.007-.639-.007-.958 0zm280.625 3.584c-5.644.089-11.023 2.411-14.959 6.458l-225.208 232.625c-3.138 3.218-5.179 7.345-5.833 11.792l-9.417 66.167c-1.647 11.667 6.476 22.461 18.143 24.109 1.554.219 3.127.267 4.69.141l69.917-5.833c5.147-.442 9.959-2.737 13.542-6.458l225.209-232.458c8.071-8.392 7.923-21.706-.333-29.917l-60.292-60.293c-4.078-4.125-9.659-6.411-15.459-6.333zm.625 51.833 30.083 30.083-204.875 211.667-35.292 2.958 4.75-32.75z"></path></g></g></g></g></svg>
											</a>

											<a href="module.php?module='.$MODULE.'&amp;act=editdraft&amp;news='.$value.'&amp;dub=1">
											<svg version="1.1" id="edit" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 472 472" style="enable-background:new 0 0 472 472;" xml:space="preserve"><path d="M321,118.787V0H51v362h100v110h270V218.787L321,118.787z M321,161.213L369.787,210H321V161.213z M81,332V30h210v80H151v222 H81z M181,442V140h110v100h100v202H181z" fill="#000000" style="fill: rgb(185, 153, 34);"></path></svg>
											</a>

											<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\'module.php?module='.$MODULE.'&amp;act=delldraft&amp;news='.$value.'&amp;nom_page=1\', \''.$newsParam->header.'\', \''.SERVER.'/'.$newsConfig->idPage.'/'.$value.'\'));">
											<svg id="del" viewBox="-51 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m410.269531 139.730469v-80.265625h-137.414062v-59.464844h-135.445313v59.464844h-137.410156v80.265625zm-242.859375-109.730469h75.445313v29.464844h-75.445313zm0 0"></path><path d="m61.945312 512h286.375l41.195313-342.269531h-368.761719zm205.410157-281.671875 29.976562 1.230469-7.734375 188.433594-30.03125.175781zm-77.222657.535156h30v190h-30zm-47.222656-.535156 7.792969 189.84375-29.976563-.167969-7.789062-188.445312zm0 0"></path></svg>
											</a>
										</div>
									</div>
								</div>
								';
							}else{
								echo'
								<div class="card mb-3" style="padding:25px">
								<div style="color: red;">Error: индекс не связан ни с одной страницей</div>
								<div>Index: '.$value.'</div>
								<div class="right_menu">
								<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\'module.php?module='.$MODULE.'&amp;act=delldraft&amp;news='.$value.'\', \''.$newsParam->header.'\', \''.SERVER.'/'.$newsConfig->idPage.'/'.$value.'\'));">Удалить</a>
								</div>
								</div>
								';
							}
						}
					echo'
					</div>';

				}
			}
		
		
		

		echo'
		<h3>Опубликованные новости</h3>
		';
		
		$category = isset($_GET['category'])?htmlspecialchars($_GET['category']):'0'; // 0 обязательно строкой
		
		$listIdCat = json_decode($newsStorage->get('category'), true);


		echo'<div class="items-category">';
		echo'<a href="module.php?module='.$MODULE.'&amp;act=edit" class="'.($category==='0'?'c':'').'">Все новости</a>';
		echo'<a href="module.php?module='.$MODULE.'&amp;act=edit&amp;category=" class="'.($category===''?'c':'').'">Без категорий <span>('.(count(listIdCat($listIdCat, ''))).')</span></a>';
		foreach($newsConfig->cat as $key => $value) {
			echo'<a href="module.php?module='.$MODULE.'&amp;act=edit&amp;category='.$key.'" class="'.($category===$key?'c':'').'">'.$value.' <span>('.(count(listIdCat($listIdCat, $key))).')</span></a> ';
		}
		echo'</div>';

		if(($listIdNews = json_decode($newsStorage->get('list'), true)) == false) {

			echo'<div class="alert alert-warning" role="alert">Услуги ещё не созданы</div>';

		} else {

			echo'<div class="item-list">';
			
			if($category !== '0'){
				$listIdNews = listIdCat($listIdCat, $category); // аналог array_keys
				if(!count($listIdNews)){
					echo'<div class="alert alert-warning" role="alert">Категория пуста</div>';
				}
			}
			
			//перевернули масив для вывода новостей в обратном порядке
			$listIdNews = array_reverse($listIdNews);
			
			//
			$nom = count($listIdNews);
			
			//определили количество страниц
			$kol_page = ceil($nom / 50); 
			
			//проверка правельности переменной с номером страницы
			if(isset($_GET['nom_page'])){$nom_page = $_GET['nom_page'];}else{ $nom_page = 1; }
			if(!is_numeric($nom_page) || $nom_page <= 0 || $nom_page > $kol_page){ $nom_page = 1; }
			
			//начало навигации
			if($nom_page > 0){$i = ($nom_page - 1) * 50;}
			$var = $i + 50;
			
			while($i < $var){
				if($i < $nom){
					if($newsStorage->iss('news_'.$listIdNews[$i])){
						$newsParam = json_decode($newsStorage->get('news_'.$listIdNews[$i]));
						
						$comments = ($newsParam->comments == '1')?'<span style="color: green;">Включено</span>':'<span style="color: red;">Выключено</span>';
						
						echo'

						<div class="card" style="' . ($newsParam->img ? '' : 'padding: 25px;') . '">
							' . ($newsParam->img ? '<img class="img-item" src="'.$newsParam->img.'" alt="">' : '') . '
							<div class="content-card gap-3">
								<div class="d-flex flex-column justify-content-center gap-3">

									<h5 class="m-0">
										<a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$listIdNews[$i].'&amp;category='.$category.'&amp;nom_page='.$nom_page.'">'.$newsParam->header.'</a>
									</h5>

									<small>
										<div>Дата публикации: '.(isset($newsParam->time)?date($newsConfig->formatDate, $newsParam->time):$newsParam->date).'</div>
										<div>URL: <a href="//'.SERVER.'/'.$newsConfig->idPage.'/'.$listIdNews[$i].'" target="_blank">'.SERVER.'/'.$newsConfig->idPage.'/'.$listIdNews[$i].'</a></div>
									</small>

									<div class="d-flex gap-2">
										<a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$listIdNews[$i].'&amp;category='.$category.'&amp;nom_page='.$nom_page.'">
										<svg id="edit" enable-background="new 0 0 426.589 426.589" viewBox="0 0 426.589 426.589" xmlns="http://www.w3.org/2000/svg"><g><g id="layer9"><g id="g1195" transform="translate(-34.396 -307.584)"><g id="path914-4"><path d="m98.316 307.589c-35.094 0-63.917 28.823-63.917 63.917v298.75c0 35.093 28.823 63.917 63.917 63.917h298.75c35.093 0 63.917-28.823 63.917-63.917v-170.709c-.261-11.782-10.025-21.121-21.807-20.86-11.414.253-20.606 9.446-20.86 20.86v170.708c0 12.035-9.215 21.25-21.25 21.25h-298.75c-12.035 0-21.25-9.215-21.25-21.25v-298.75c0-12.035 9.215-21.25 21.25-21.25h191.958c11.782.264 21.548-9.072 21.812-20.854.265-11.782-9.072-21.548-20.854-21.812-.319-.007-.639-.007-.958 0zm280.625 3.584c-5.644.089-11.023 2.411-14.959 6.458l-225.208 232.625c-3.138 3.218-5.179 7.345-5.833 11.792l-9.417 66.167c-1.647 11.667 6.476 22.461 18.143 24.109 1.554.219 3.127.267 4.69.141l69.917-5.833c5.147-.442 9.959-2.737 13.542-6.458l225.209-232.458c8.071-8.392 7.923-21.706-.333-29.917l-60.292-60.293c-4.078-4.125-9.659-6.411-15.459-6.333zm.625 51.833 30.083 30.083-204.875 211.667-35.292 2.958 4.75-32.75z"></path></g></g></g></g></svg>
										</a>

										<a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$listIdNews[$i].'&amp;category='.$category.'&amp;dub=1">
										<svg version="1.1" id="edit" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 472 472" style="enable-background:new 0 0 472 472;" xml:space="preserve"><path d="M321,118.787V0H51v362h100v110h270V218.787L321,118.787z M321,161.213L369.787,210H321V161.213z M81,332V30h210v80H151v222 H81z M181,442V140h110v100h100v202H181z" fill="#000000" style="fill: rgb(185, 153, 34);"></path></svg>
										</a>

										<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\'module.php?module='.$MODULE.'&amp;act=dell&amp;news='.$listIdNews[$i].'&amp;category='.$category.'&amp;nom_page='.$nom_page.'\', \''.$newsParam->header.'\', \''.SERVER.'/'.$newsConfig->idPage.'/'.$listIdNews[$i].'\'));">
										<svg id="del" viewBox="-51 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m410.269531 139.730469v-80.265625h-137.414062v-59.464844h-135.445313v59.464844h-137.410156v80.265625zm-242.859375-109.730469h75.445313v29.464844h-75.445313zm0 0"></path><path d="m61.945312 512h286.375l41.195313-342.269531h-368.761719zm205.410157-281.671875 29.976562 1.230469-7.734375 188.433594-30.03125.175781zm-77.222657.535156h30v190h-30zm-47.222656-.535156 7.792969 189.84375-29.976563-.167969-7.789062-188.445312zm0 0"></path></svg>
										</a>
									</div>

								</div>
							</div>
						</div>';

					} else {

						echo'<div class="item item_page">
						<div style="color: red;">Error: индекс не связан ни с одной страницей</div>
						<div>Index: '.$listIdNews[$i].'</div>
						<div class="right_menu">
						<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\'module.php?module='.$MODULE.'&amp;act=dell&amp;news='.$listIdNews[$i].'&amp;category='.$category.'&amp;nom_page='.$nom_page.'\', \''.$newsParam->header.'\', \''.SERVER.'/'.$newsConfig->idPage.'/'.$listIdNews[$i].'\'));">Удалить</a>
						</div>
						</div>';
					}
				}
				++$i;
			}
			echo'</div>';
			
			//навигация по номерам страниц
			if($kol_page > 1){//Если количество страниц больше 1, то показываем навигацию
				echo'<div style="margin-top: 25px; text-align: center;">';
				echo'Страницы: ';
				for($i = 1; $i <= $kol_page; ++$i){
					if($nom_page == $i){
						echo'<b>('.$i.')</b> ';
					}else{
						echo'<a href="module.php?module='.$MODULE.'&amp;act=edit&amp;category='.$category.'&amp;nom_page='.$i.'">'.$i.'</a> ';
					}
				}
				echo'</div>';
			}
			//конец навигации
		}
		echo'</div>';
	}


	if($act=='search'){
		if(!function_exists('mb_stripos')){
			echo'

			<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">

						<ul class="nav">
							<a href="module.php?module='.$MODULE.'&amp;act=edit">&#8592; Назад</a>
							'.$menu_page.'
						</ul>

					</div>
				</div>
			</div>

			<div class="container">
				<h3>Поиск по публикациям</h3>
				<p>На сервере не установлено php расширение "mbstring". Это расширение позволяет производить поиск по русскоязычным символам. Обратитесь к администратору вашего сервера для установки данного расширения. 
				Администраторы могут воспользоваться <a href="https://www.php.net/manual/ru/book.mbstring.php" target="_blank">документацией</a>.</p>
			</div>';

		} else {
			
			$q = isset($_POST['q'])?htmlspecialchars(trim($_POST['q'])):'';

			echo'

			<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">

						<ul class="nav">
							<a href="module.php?module='.$MODULE.'&amp;act=edit">&#8592; Назад</a>
							'.$menu_page.'
						</ul>

					</div>
				</div>
			</div>
			
			<div class="container">
			
				<form name="form_name" action="module.php?module='.$MODULE.'&amp;act=search" method="post">
					<div class="input-group">
						<input class="form-control" type="text" name="q" value="'.$q.'" placeholder="Введите запрос" autofocus>
						<input class="btn btn-primary" type="submit" name="" value="Поиск">
					</div>
				</form>';

			if(($listIdNews = json_decode($newsStorage->get('list'), true)) == false){
				echo'
				<div class="alert alert-warning" role="alert">Новости ещё не созданы</div>
				';

			} elseif ($q != ''){
				
				// $pages = System::listPages();
				$listIdNews = array_reverse($listIdNews);//перевернули масив
				
				$pSearchName = array(); // Пустой массив результатов
				$pSearchKeywords = array(); // Пустой массив результатов
				$pSearchDescription = array(); // Пустой массив результатов
				$pSearchID = array(); // Пустой массив результатов
				
				$iRes = 0; // Счетчик положительных результатов
				foreach($listIdNews as $value){
					if($newsStorage->iss('news_'.$value)){
						$newsParam = json_decode($newsStorage->get('news_'.$value));

						if(mb_stripos($newsParam->header, $q, 0, 'UTF-8') !== false){
							$pSearchName[] = array('header' => $newsParam->header, 'time' => $newsParam->time, 'id' => $value);
							++$iRes; 
						}elseif(mb_stripos($newsParam->keywords, $q, 0, 'UTF-8') !== false){
							$pSearchKeywords[] = array('header' => $newsParam->header, 'time' => $newsParam->time, 'id' => $value);
							++$iRes; 
						}elseif(mb_stripos($newsParam->description, $q, 0, 'UTF-8') !== false){
							$pSearchDescription[] = array('header' => $newsParam->header, 'time' => $newsParam->time, 'id' => $value);
							++$iRes; 
						}elseif(mb_stripos($value, $q, 0, 'UTF-8') !== false){
							$pSearchID[] = array('header' => $newsParam->header, 'time' => $newsParam->time, 'id' => $value);
							++$iRes; 
						}	
					}
					if($iRes == 100) break; // Ограничение результатов
				}

				
				$pSearchResult = array_merge($pSearchName, $pSearchKeywords, $pSearchDescription, $pSearchID);
				
				echo'
				<h3>Результаты поиска:</h3>
				<div class="item-list">';

				foreach($pSearchResult as $value){
					echo'
					<div class="card" style="padding: 25px;">
						<div class="d-flex flex-column justify-content-center gap-3">

							<h5 class="m-0"><a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$value['id'].'&amp;">'.preg_replace('#('.$q.')#ius', '<span class="r">\1</span>', $value['header']).'</a></h5>
							<small>
								<div>URL: <a href="//'.SERVER.'/'.$newsConfig->idPage.'/'.$value['id'].'" target="_blank">'.SERVER.'/'.$newsConfig->idPage.'/'.preg_replace('#('.$q.')#ius', '<span class="r">\1</span>', $value['id']).'</a></div>
								<div>Дата публикации: '.(isset($value['time'])?date($newsConfig->formatDate, $value['time']):$value['date']).'</div>
							</small>

							<div class="d-flex gap-2">
								<a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$value['id'].'&amp;">
								<svg id="edit" enable-background="new 0 0 426.589 426.589" viewBox="0 0 426.589 426.589" xmlns="http://www.w3.org/2000/svg"><g><g id="layer9"><g id="g1195" transform="translate(-34.396 -307.584)"><g id="path914-4"><path d="m98.316 307.589c-35.094 0-63.917 28.823-63.917 63.917v298.75c0 35.093 28.823 63.917 63.917 63.917h298.75c35.093 0 63.917-28.823 63.917-63.917v-170.709c-.261-11.782-10.025-21.121-21.807-20.86-11.414.253-20.606 9.446-20.86 20.86v170.708c0 12.035-9.215 21.25-21.25 21.25h-298.75c-12.035 0-21.25-9.215-21.25-21.25v-298.75c0-12.035 9.215-21.25 21.25-21.25h191.958c11.782.264 21.548-9.072 21.812-20.854.265-11.782-9.072-21.548-20.854-21.812-.319-.007-.639-.007-.958 0zm280.625 3.584c-5.644.089-11.023 2.411-14.959 6.458l-225.208 232.625c-3.138 3.218-5.179 7.345-5.833 11.792l-9.417 66.167c-1.647 11.667 6.476 22.461 18.143 24.109 1.554.219 3.127.267 4.69.141l69.917-5.833c5.147-.442 9.959-2.737 13.542-6.458l225.209-232.458c8.071-8.392 7.923-21.706-.333-29.917l-60.292-60.293c-4.078-4.125-9.659-6.411-15.459-6.333zm.625 51.833 30.083 30.083-204.875 211.667-35.292 2.958 4.75-32.75z"></path></g></g></g></g></svg>
								</a>

								<a href="module.php?module='.$MODULE.'&amp;act=editnews&amp;news='.$value['id'].'&amp;dub=1">
								<svg version="1.1" id="edit" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 472 472" style="enable-background:new 0 0 472 472;" xml:space="preserve"><path d="M321,118.787V0H51v362h100v110h270V218.787L321,118.787z M321,161.213L369.787,210H321V161.213z M81,332V30h210v80H151v222 H81z M181,442V140h110v100h100v202H181z" fill="#000000" style="fill: rgb(185, 153, 34);"></path></svg>
								</a>

								<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\'module.php?module='.$MODULE.'&amp;act=dell&amp;news='.$value['id'].'&amp;\', \''.$value['header'].'\', \''.SERVER.'/'.$newsConfig->idPage.'/'.$value['id'].'\'));">
								<svg id="del" viewBox="-51 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m410.269531 139.730469v-80.265625h-137.414062v-59.464844h-135.445313v59.464844h-137.410156v80.265625zm-242.859375-109.730469h75.445313v29.464844h-75.445313zm0 0"></path><path d="m61.945312 512h286.375l41.195313-342.269531h-368.761719zm205.410157-281.671875 29.976562 1.230469-7.734375 188.433594-30.03125.175781zm-77.222657.535156h30v190h-30zm-47.222656-.535156 7.792969 189.84375-29.976563-.167969-7.789062-188.445312zm0 0"></path></svg>
								</a>
							</div>
							
						</div>
					</div>';
				}
				if($iRes == 0){
					echo'
					<div class="alert alert-warning" role="alert">Ничего не найдено</div>
					';
				}
				if($iRes == 100){
					echo'
					<div class="alert alert-warning" role="alert">Поиск прекращен т.к. слишком много результатов. Уточните запрос.</div>
					';
				}
				echo'</div>';
				
			}else{echo'<div class="alert alert-warning" role="alert">Ошибка в запросе</div>';}

			echo'</div>';
		}
	}
	

	if($act=='dell' || $act == 'delldraft')
	{
		$news = htmlspecialchars(specfilter($_GET['news']));
		$category = isset($_GET['category'])?htmlspecialchars(specfilter($_GET['category'])):0;
		$nom_page = isset($_GET['nom_page'])?htmlspecialchars(specfilter($_GET['nom_page'])):1;
		$prefix = ($act == 'dell')?'news_':'draft_';
		if($newsStorage->delete($prefix.$news)){ // Удадляем новость
			if($act=='dell'){
				//Удаляем страницу из списка
				$listIdNews = json_decode($newsStorage->get('list'), true); // Получили список ввиде массива
				if(($key = array_search($news, $listIdNews)) !== false){
					unset($listIdNews[$key]); // Удалили найденый элемент массива
				}
				$listIdNews = array_values($listIdNews); // Переиндексировали числовые индексы 
				$newsStorage->set('list', json_encode($listIdNews, JSON_FLAGS)); // Записали массив в виде json

				// Удаляем страницу из категорий
				$listIdCat = json_decode($newsStorage->get('category'), true); // Получили список ввиде массива
				unset($listIdCat[$news]);
				$newsStorage->set('category', json_encode($listIdCat, JSON_FLAGS)); // Записали массив в виде json
				
				$newsStorage->delete('comments_'.$news); // Удаляем комментарии
				$newsStorage->delete('count_'.$news); // Удаляем счетчик комментариев
				
				System::notification('Удалена новость с идентификатором '.$news.'', 'g');
				echo'<div class="msg">Новость успешно удалена</div>';
			}
			
			if($act == 'delldraft'){
				// Удаляем страницу из черновиков
				$draftListIdNews = json_decode($newsStorage->get('draftList'), true); // Получили список ввиде массива
				if(($key = array_search($news, $draftListIdNews)) !== false){
					unset($draftListIdNews[$key]); // Удалили найденый элемент массива
				}
				$draftListIdNews = array_values($draftListIdNews); // Переиндексировали числовые индексы 
				$newsStorage->set('draftList', json_encode($draftListIdNews, JSON_FLAGS)); // Записали массив в виде json

				System::notification('Удалена новость из черновика с идентификатором '.$news.'', 'g');
				echo'<div class="msg">Новость из черновика успешно удалена</div>';
			}

			
		}else{
			System::notification('Ошибка при удалении новости с идентификатором '.$news.', страница не найдена или запрос некорректен', 'r');
			echo'<div class="msg">Ошибка при удалении новости</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=edit&category=<?php echo $category;?>&nom_page=<?php echo $nom_page; ?>\';', 500);
</script>
<?php	
	}

	if($act=='editnews' || $act == 'editdraft')
	{
		$news = htmlspecialchars(specfilter($_GET['news']));
		$category = isset($_GET['category'])?htmlspecialchars(specfilter($_GET['category'])):0;
		$nom_page = isset($_GET['nom_page'])?htmlspecialchars(specfilter($_GET['nom_page'])):1;
		$prefix = ($act == 'editnews')?'news_':'draft_';
		$DUB = isset($_GET['dub'])?$_GET['dub']:0;

		if(($newsParam = json_decode($newsStorage->get($prefix.$news))) != false){
			echo'

			<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">

						<ul class="nav">
							<a href="module.php?module='.$MODULE.'&amp;act=edit&amp;category='.$category.'&amp;nom_page='.$nom_page.'">&#8592; Назад</a>
							'.$menu_page.'
						</ul>

					</div>
				</div>
			</div>
			
			<div class="container">
				<form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post" style="margin:0px; padding:0px;">
					<input type="hidden" name="act" id="act" value="'.($DUB == '1'?'addnews':'addedit').'">
					<input type="hidden" name="public" id="public" value="1">
					<input type="hidden" name="news" value="'.$news.'">
					<input type="hidden" name="category" value="'.$category.'">
					<input type="hidden" name="nom_page" value="'.$nom_page.'">
					<input type="hidden" name="time" value="'.(isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)).'">
				
					<div class="d-flex flex-column gap-3">

						<div class="col-12 bs-br bg-white overflow-hidden">
							<h3 class="bg-light p-4 m-0">'.($DUB == '1'?'Создание дубликата новости':'Редактирование новости').'</h3>
							<div class="p-4">
								<div class="col-lg-6 mb-3">
									<label class="form-label">Заголовок новости:</label>
									<input class="form-control" type="text" name="header" id="header" value="'.$newsParam->header.'">
								</div>

								<div class="col-12 mb-3">
									<label class="form-label">Содержимое новости:</label>
									<textarea class="form-control" name="content" rows="20" cols="100" style="height: 250px;">'.htmlspecialchars($newsParam->content).'</textarea>
								</div>';

								if($Config->wysiwyg){
									if(Module::isWysiwyg($Config->wysiwyg)){
										require Module::pathRun($Config->wysiwyg, 'wysiwyg');
									}
								}

								echo'

								<div class="col-12 mb-3">
									<label class="form-label">Превью новости:</label>
									<textarea class="form-control" NAME="prev" rows="4" placeholder="Краткое содержание новости">'.htmlspecialchars($newsParam->prev).'</textarea>
								</div>

								<div class="col-12 mb-3">
									<label class="form-label">Категория</label>';
										foreach($newsConfig->cat as $key => $value){
											if(isset($newsParam->cat)){ // Проверка существования для новостей написанных на <=5.1.17
												if(is_string($newsParam->cat)){ // Проверка на строку для новостей насписанных на <=5.1.17
													$checked = $newsParam->cat == $key?true:false;
												}else{
													$checked = in_array($key, $newsParam->cat)?true:false; // 5.1.18
												}
											}
											echo'
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="cat[]" value="'.$key.'" '.($checked?' checked':'').'>
												<label class="form-check-label">'.$value.'</label>
											</div>';
											} echo'
								</div>
							
								<div class="col-lg-6">
									<label class="form-label">Изображение</label>
										<div class="input-group mb-0">
											<input class="form-control" type="text" name="img" id="inputimg" value="'.$newsParam->img.'"> 
											<button class="btn btn-secondary" type="button" onClick="openwindow(\'window\', 700, \'auto\', iframefiles);">Выбрать файл</button>
										</div>
									<img class="mt-3" src="'.$newsParam->img.'" alt="" id="img" style="width: 180px;">
								</div>
							</div>
						</div>


						<div class="col-12 bs-br bg-white overflow-hidden">
							<h3 class="bg-light p-4 m-0">Кастомные поля</h3>
							<div class="p-4 pt-1">';
								//Custom
								foreach($newsConfig->custom as $value){
									echo'
									<div class="col-lg-6 mt-3">
										<label class="form-label">'.$value->name.'</label>
										'.($value->type == 'input'?'<input class="form-control col-lg-6" name="custom['.$value->id.']" value="'.(isset($newsParam->custom->{$value->id})?htmlspecialchars($newsParam->custom->{$value->id}):'').'">':
										'<textarea class="form-control name="custom['.$value->id.']">'.(isset($newsParam->custom->{$value->id})?htmlspecialchars($newsParam->custom->{$value->id}):'').'</textarea>').'
									</div>';
								} echo '		
							</div>
						</div>


						<div class="col-12 bs-br bg-white overflow-hidden">
							<h3 class="d-flex align-items-center gap-2 bg-light p-4 m-0">SEO оптимизация <small>( Для поисковиков )</small></h3>
							<div class="p-4">

								<div class="col-lg-6 mb-3">
									<label class="form-label">Заголовок (Title):</label>
									<input class="form-control" type="text" name="title" value="'.$newsParam->title.'">
								</div>

								<div class="col-lg-6 mb-3">
									<label class="form-label">Ключевые слова (Keywords):</label>
									<input class="form-control" type="text" name="keywords" value="'.$newsParam->keywords.'">
								</div>

								<div class="col-12 mb-3">
									<label class="form-label">Описание (Description):</label>
									<textarea class="form-control" type="text" name="description" rows="4" placeholder="Краткое содержание новости для поисковиков">'.$newsParam->description.'</textarea>
								</div>';

								$checked = ($newsParam->comments == 1)?'checked':'';
								echo'
								<div class="form-check mb-3">
									<input class="form-check-input" type="checkbox" name="comments" value="y" '.$checked.'>
									<label class="form-check-label">
										Разрешить комментирование
									</label>
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label">Идентификатор (URL)</label>
									<div class="input-group mb-0">
										<input type="text" class="form-control" name="id" id="id" value="'.($DUB == '1'?uniqid('n'):$news).'">
									</div>
								</div>

								 <div class="col-lg-4 mb-3">
                                    <div class="mb-3">
                                    <label class="form-label">Индексировать</label>
                                    <SELECT class="form-select" name="robots">';
                                    echo'<OPTION VALUE="0" '.($newsParam->robots == 0?'selected':'').'>Да';
                                    echo'<OPTION VALUE="1" '.($newsParam->robots == 1?'selected':'').'>Нет';
                                    echo'</SELECT>
                                    </div>
                            	</div>

							</div>
						</div>
						';

						if($act == 'editnews') {

							echo'
							<div class="btn-gr mb-4">
								<button class="btn btn-primary" type="button" onClick="submit();">'.($DUB == '1'?'Опубликовать':'Сохранить').'</button>
								<button class="btn btn-secondary" type="button" onClick="document.getElementById(\'act\').value = \'adddraft\'; submit();">Сохранить в черновик</button>
							</div>';

						}

						if($act == 'editdraft'){
							echo '<div class="btn-gr mb-4">';
							if ($newsStorage->iss('news_'.$news)){
								echo'
									<button class="btn btn-primary" type="button" onClick="document.getElementById(\'act\').value = \'addnews\'; submit();" title="Опубликовать как новую новость">Опубликовать</button>
									<button class="btn btn-secondary" type="button" onClick="document.getElementById(\'act\').value = \'addedit\'; submit();">Опубликовать с заменой существующей</button></td>
								';
							}else{
								echo'
									<button class="btn btn-primary" type="button" onClick="document.getElementById(\'act\').value = \'addnews\'; submit();">Опубликовать</button>
								';
							}
					
					
							if($DUB == '1'){
								echo'
									<button class="btn btn-secondary" type="button" onClick="document.getElementById(\'act\').value = \'adddraft\'; submit();">Сохранить в черновик</button></td>
								';
							}else{
								echo'
									<button class="btn btn-secondary" type="button" onClick="document.getElementById(\'act\').value = \'addeditdraft\'; submit();">Сохранить черновик</button>
								';
							}
							
						} echo' </div>

					</div>
				</form>
			</div>';?>

			<script type="text/javascript">
			var inputimg = document.getElementById('inputimg');
			var lastinputimg = inputimg.value;
			setInterval(function(){
				if (inputimg.value != lastinputimg) {
					document.getElementById('img').src = inputimg.value;
					lastinputimg = inputimg.value;
				}
			}, 500);
			</script>

			<?php
						
				} else {
						echo'<div class="msg">Не удалось получить параметры записи</div>';
			?>

			<script type="text/javascript">
			setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=edit&nom_page=<?php echo $nom_page;?>\';', 3000);
			</script>
	<?php
		}

	}
	  
	if($act == 'addedit' || $act == 'addeditdraft'){
		$news = htmlspecialchars(specfilter($_POST['news']));
		$category = isset($_POST['category'])?htmlspecialchars(specfilter($_POST['category'])):0;
		$nom_page = isset($_POST['nom_page'])?htmlspecialchars(specfilter($_POST['nom_page'])):1;
		$id_news = htmlspecialchars(specfilter($_POST['id'])); // Новый id для новости
		$cat = isset($_POST['cat'])?$_POST['cat']:array();
		$prefix = ($act == 'addedit')?'news_':'draft_';
		
			if(($newsParam = json_decode($newsStorage->get($prefix.$news))) != false){
				
				$newsParam->header = ($_POST['header'] == '')?'Без названия':htmlspecialchars(specfilter($_POST['header']));
				$newsParam->img = htmlspecialchars(specfilter($_POST['img']));
				$newsParam->title = htmlspecialchars(specfilter($_POST['title']));
				$newsParam->keywords = htmlspecialchars(specfilter($_POST['keywords']));
				$newsParam->robots = htmlspecialchars(specfilter($_POST['robots']));
				$newsParam->description = htmlspecialchars(specfilter($_POST['description']));
				$newsParam->prev = $_POST['prev'];
				$newsParam->content = $_POST['content'];
				//$param['date'] = htmlspecialchars(specfilter($_POST['date'])); // удалено в 5.1.14
				$newsParam->comments = (isset($_POST['comments']) && $_POST['comments'] == 'y')?'1':'0';
				// 5.1.14
				if(!isset($newsParam->time)){
					$newsParam->time = strtotime($newsParam->date);
					$newsParam->date = date($newsConfig->formatDate, $newsParam->time);
				}
				// 5.1.18
				$newCat = json_encode($newsParam->cat) != json_encode($cat)?true:false;
				$newsParam->cat = $cat;
				// 5.1.20
				$array = array();
				if(isset($_POST['custom'])){
					foreach($_POST['custom'] as $key => $value){
						$array[htmlspecialchars($key)] = $value;
					}
				}
				$newsParam->custom = $array;
				// 5.1.25
				if($act == 'addeditdraft'){$newsParam->time = time();} // обновляемая дата для черновика
				//


				
				if($newsStorage->set($prefix.$news, json_encode($newsParam, JSON_FLAGS))){
					if($act == 'addedit'){
						if($newCat){
							// Замена категории
							$listIdCat = json_decode($newsStorage->get('category'), true); // Получили список ввиде массива
							$listIdCat[$news] = $newsParam->cat;
							$newsStorage->set('category', json_encode($listIdCat, JSON_FLAGS)); // Записали массив в виде json
						}
						// Удаление новости из черновика при публикации
						if($newsStorage->delete('draft_'.$news)){
							// Удаляем страницу из черновиков
							$draftListIdNews = json_decode($newsStorage->get('draftList'), true); // Получили список ввиде массива
							if(($key = array_search($news, $draftListIdNews)) !== false){
								unset($draftListIdNews[$key]); // Удалили найденый элемент массива
							}
							$draftListIdNews = array_values($draftListIdNews); // Переиндексировали числовые индексы 
							$newsStorage->set('draftList', json_encode($draftListIdNews, JSON_FLAGS)); // Записали массив в виде json
							System::notification('Удалена новость из черновика с идентификатором '.$news.'', 'g');
						}
					}

					if($id_news != $news){
						if($newsStorage->iss($prefix.$id_news) == false && System::validPath($id_news)){
							
							if($newsStorage->set($prefix.$id_news, json_encode($newsParam, JSON_FLAGS)) == false){
								System::notification('Ошибка при записи ключа '.$prefix.$id_news.'', 'r');
							}

							if($newsStorage->delete($prefix.$news) == false){
								System::notification('Ошибка при удалении ненужного ключа '.$prefix.$news.'', 'r');
							}

							if($act == 'addedit'){
								// Замена страницы в списке
								$listIdNews = json_decode($newsStorage->get('list'), true); // Получили список ввиде массива
								if(($key = array_search($news, $listIdNews)) !== false){
									$listIdNews[$key] = $id_news; // Заменили найденый элемент массива
								}
								$listIdNews = array_values($listIdNews); // Переиндексировали числовые индексы 
								$newsStorage->set('list', json_encode($listIdNews, JSON_FLAGS)); // Записали массив в виде json

								// Замена страницы в категории
								$listIdCat = json_decode($newsStorage->get('category'), true); // Получили список ввиде массива
								$newArr = array();
								foreach($listIdCat as $key => $value){
									if($news == $key){
										$newArr[$id_news] = $value;
									}else{
										$newArr[$key] = $value;
									}
								}
								$newsStorage->set('category', json_encode($newArr, JSON_FLAGS)); // Записали массив в виде json
							}

							if($act == 'addeditdraft'){
								// Замена страницы в списке
								$draftListIdNews = json_decode($newsStorage->get('draftList'), true); // Получили список ввиде массива
								if(($key = array_search($news, $draftListIdNews)) !== false){
									$draftListIdNews[$key] = $id_news; // Заменили найденый элемент массива
								}
								$draftListIdNews = array_values($draftListIdNews); // Переиндексировали числовые индексы 
								$newsStorage->set('draftList', json_encode($draftListIdNews, JSON_FLAGS)); // Записали массив в виде json
							}

							System::notification('Отредактирована новость со сменой идентификатора '.$news.' на идентификатор '.$id_news.', ссылка на страницу '.$Config->protocol.'://'.SERVER.'/'.$newsConfig->idPage.'/'.$id_news, 'g');
							echo'<div class="msg">Новость успешно сохранена</div>';
						}else{
							System::notification('Отредактирована новость с неудачной попыткой смены идентификатора '.$news.' на идентификатор '.$id_news.', идентификатор '.$id_news.' уже существует или некорректен, ссылка на страницу '.$Config->protocol.'://'.SERVER.'/'.$newsConfig->idPage.'/'.$news, 'g');
							echo'<div class="msg">Новость сохранена но идентификатор изменить не удалось</div>';
						}
					}else{
						System::notification('Отредактирована новость с идентификатором '.$id_news.', ссылка на страницу '.$Config->protocol.'://'.SERVER.'/'.$newsConfig->idPage.'/'.$id_news, 'g');
						echo'<div class="msg">Новость успешно сохранена</div>';
					}
				}else{
					System::notification('Ошибка при сохранении страницы с идентификатором '.$news.', ошибка записи', 'r');
					echo'<div class="msg">Ошибка при сохранении страницы</div>';
					
				}
			}else{
				System::notification('Ошибка при сохранении страницы с идентификатором '.$news.', страница ненайдена', 'r');
				echo'<div class="msg">Неудалось получить параметры записи</div>';
			}
		
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=edit&category=<?php echo $category;?>&nom_page=<?php echo $nom_page; ?>\';', 200);
</script>
<?php
	}
	  
	if($act=='comment')
	{
		
		?>
		<script type="text/javascript">
		var dell = '<div class="a">Подтвердите удаление выделенных комментариев</div>' +
			'<div class="b">' +
			'<button class="btn btn-outline-danger btn-sm" btn-sm" type="button" onClick="submitDell();">Удалить</button> '+
			'<button class="btn btn-outline-primary btn-sm" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
			'</div>';
			
		var listDell = '<div class="a"><span class="r">Внимание!</span> Очистится только список в панели администратора, комментарии опубликованные на страницах останутся не тронутыми</div>' +
			'<div class="b">' +
			'<button class="btn btn-outline-danger btn-sm" type="button" onClick="window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=listdellcoment\';">Очистить</button> '+
			'<button class="btn btn-outline-primary btn-sm" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
			'</div>';
			
		var wDell = '<div class="a"><span class="r">Внимание!</span> Список последних комментариев переполнен. Рекомендуется очистить список, что-бы разгрузить систему.</div>' +
			'<div class="b">' +
			'<button class="btn btn-outline-danger btn-sm" type="button" onClick="window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=listdellcoment\';">Очистить сейчас</button> '+
			'<button class="btn btn-outline-primary btn-sm" type="button" onclick="closewindow(\'window\');">Закрыть</button>'+
			'</div>';
			
		function submitDell(){
			document.form.act.value = "dellcoment";
			form.submit();
		}
		</script>
		<?php
		
		echo'

		<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">

						<ul class="nav">
							'.$menu_page.'
						</ul>

					</div>
				</div>
			</div>';
		
		
		if ($newsConfig->commentEngine){
			
			
			echo'
			<div class="container">
			<h1>Комментарии пользователей</h1>';
			 
			
			
			
			if(($lastComments = json_decode($newsStorage->get('lastComments'), true)) == false){
				echo'
				
					<a class="button" href="module.php?module='.$MODULE.'&amp;act=cfgcomment">Опубликованые комментарии</a>
	
				<div class="msg">Нет ни одного комментария</div>';

			}else{
				
				
				
				echo'
				<form name="form" action="module.php?module='.$MODULE.'" method="post">
					<INPUT TYPE="hidden" NAME="act" VALUE="pubcoment">
					<div class="btn-gr mb-4">
					<input class="btn btn-outline-primary btn-sm" type="submit" name="" value="Опубликовать выделенные" title="Опубликовать выделенные комментарии">
					<button class="btn btn-outline-danger btn-sm" type="button" onClick="openwindow(\'window\', 650, \'auto\', dell);" title="Удалить выделенные комментарии">Удалить выделенные</button>
					<button class="btn btn-outline-secondary btn-sm" type="button" onClick="openwindow(\'window\', 650, \'auto\', listDell);" title="Очистить список последних комментариев">Очистить список</button>
					<a class="btn btn-outline-secondary btn-sm" href="module.php?module='.$MODULE.'&amp;act=cfgcomment">Настройки комментариев</a>
					</div>
				';
				
				//перевернули масив для вывода новостей в обратном порядке
				$lastComments = array_reverse($lastComments);
				
				//
				$nom = count($lastComments);
				
				if ($nom > 3000){
					echo'<script type="text/javascript">openwindow(\'window\', 650, \'auto\', wDell);</script>';
				}
				
				//определили количество страниц
				$kol_page = ceil($nom / 50); 
				
				//проверка правельности переменной с номером страницы
				if(isset($_GET['nom_page'])){$nom_page = $_GET['nom_page'];}else{ $nom_page = 1; }
				if(!is_numeric($nom_page) || $nom_page <= 0 || $nom_page > $kol_page){ $nom_page = 1; }
				
				//начало навигации
				if($nom_page > 0){$i = ($nom_page - 1) * 50;}
				$var = $i + 50;
				echo'<div class="item-list">';
				while($i < $var){
					if($i < $nom){
						
						
						echo'
						<div class="card flex-column gap-1 justify-content-between" style="padding: 25px;">
							<div>
							<INPUT TYPE="checkbox" NAME="comment[]" VALUE="'.$lastComments[$i]['idComment'].'">
								<h3 class="mb-2"><img src="include/user.svg" alt=""> Пользователь: '.$lastComments[$i]['login'].'</h3>
								<div class="comment d-flex">Комментарий: '.NewsFormatText($lastComments[$i]['text']).'</div>
							</div>
							<div class="comment-date">
								<div>'.human_time(time() - $lastComments[$i]['time']).' назад ( '.date("d.m.Y H:i", $lastComments[$i]['time']).' ) ; '.($lastComments[$i]['status']=='user'?'Зарегистрированный':'Гость').'; IP '.$lastComments[$i]['ip'].'</div>
								<div>'.($lastComments[$i]['published']?'':'<span class="r">Не опубликованно</span>').' Страница: <a href="//'.SERVER.'/'.$newsConfig->idPage.'/'.$lastComments[$i]['idNews'].'" target="_blank">'.SERVER.'/'.$newsConfig->idPage.'/'.$lastComments[$i]['idNews'].'</a></div>
							</div>
						</div>';
						
					}
					++$i;
				}
				echo'</div>';
				echo'<div class="btn-gr mt-4">
					<input class="btn btn-outline-primary btn-sm" type="submit" name="" value="Опубликовать отмеченные" title="Опубликовать выделенные комментарии">
					<button class="btn btn-outline-danger btn-sm" type="button" onClick="openwindow(\'window\', 650, \'auto\', dell);" title="Удалить выделенные комментарии">Удалить выделенные</button>
					</div>
				</form>';
				
				//навигация по номерам страниц
				if($kol_page > 1){//Если количество страниц больше 1, то показываем навигацию
					echo'<div style="margin-top: 25px; text-align: center;">';
					echo'Страницы: ';
					for($i = 1; $i <= $kol_page; ++$i){
						if($nom_page == $i){
							echo'<b>('.$i.')</b> ';
						}else{
							echo'<a href="module.php?module='.$MODULE.'&amp;act=comment&amp;nom_page='.$i.'">'.$i.'</a> ';
						}
					}
					echo'</div>';
				}
				//конец навигации
				
			}
			echo'</div>';
			
		}else{
			echo'<div class="msg">Используется сторонний сервис комментариев</div>';
		}
			
	}
	
	
	
	if($act=='pubcoment'){
		// Даже и не пытайтесь разобраться ;)
		if(($lastComments = json_decode($newsStorage->get('lastComments'), true)) == false){
				echo'<div class="msg">Ошибка. Нет ни одного сообщения.</div>';
		}elseif(!isset($_POST['comment'])){
			echo'<div class="msg">Ошибка. Нет выбранных элементов.</div>';
		}else{
			$addComment = array();
			$countPP = 0;
			foreach($lastComments as $key => $value){
				if(in_array($value['idComment'], $_POST['comment']) && $value['published'] == 0){
						++$countPP;
						$lastComments[$key]['published'] = 1;
						$addComment[$value['idNews']][] = array(
													'id' => $value['idComment'],
													'login' => $value['login'],
													'text' => $value['text'],
													'ip' => $value['ip'],
													'status' => $value['status'],
													'time' => $value['time']);
				}
			}
			$newsStorage->set('lastComments', json_encode($lastComments, JSON_FLAGS));
			unset($lastComments);
			
			
			foreach($addComment as $key => $value){
				$arrayComments = json_decode($newsStorage->get('comments_'.$key), true);
				
				foreach($value as $row){
					$arrayComments[] = $row;
					
					if(($CUser = User::getConfig($row['login'])) != false){
						++$CUser->numPost;
						User::setConfig($row['login'], $CUser);
					}
				}
				
				
				$arrayCount = count($arrayComments);
				if($arrayCount >= $newsConfig->commentMaxCount){
					$arrayStart = $arrayCount -  round($newsConfig->commentMaxCount / 1.5);
					$arrayComments = array_slice($arrayComments, $arrayStart, $arrayCount);
				}
				
				if($newsStorage->set('comments_'.$key, json_encode($arrayComments, JSON_FLAGS))){
					
					$count = $newsStorage->iss('count_'.$key)?$newsStorage->get('count_'.$key):0;
					$count+= $countPP;
					$newsStorage->set('count_'.$key, $count);
					
				}
			}
			echo'<div class="msg">Публикация успешно завершена</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=comment\';', 3000);
</script>
<?php	
	}
	
	
	
	if($act=='dellcoment'){
		if(($lastComments = json_decode($newsStorage->get('lastComments'), true)) == false){
				echo'<div class="msg">Ошибка. Нет ни одного сообщения.</div>';
		}else{
			$dellComment = array();
			foreach($lastComments as $key => $value){
				if(in_array($value['idComment'], $_POST['comment'])){
					$dellComment[$value['idNews']][] = $value['idComment'];
					unset($lastComments[$key]);
				}
			}
			// Переиндексировали числовые индексы 
			$lastComments = array_values($lastComments); 
			$newsStorage->set('lastComments', json_encode($lastComments, JSON_FLAGS));
			unset($lastComments);
			
			
			foreach($dellComment as $key => $value){
				$arrayComments = json_decode($newsStorage->get('comments_'.$key), true);
				foreach($arrayComments as $i => $row){
					if (in_array($row['id'], $value)){
						unset($arrayComments[$i]);
					}
				}
				// Переиндексировали числовые индексы 
				$arrayComments = array_values($arrayComments); 
				$newsStorage->set('comments_'.$key, json_encode($arrayComments, JSON_FLAGS));
			}
			echo'<div class="msg">Удаление успешно завершено</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=comment\';', 3000);
</script>
<?php	
	}
	
	if($act=='listdellcoment'){
		$newsStorage->set('lastComments', json_encode(array()));
		echo'<div class="msg">Очистка успешно завершена</div>';

?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=comment\';', 3000);
</script>
<?php	
	}
	
	
	if($act=='cfgcomment'){
		
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						<a href="module.php?module='.$MODULE.'&amp;act=comment">&#8592; Вернуться назад</a>
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>
		
		<div class="container mb-4">
			<form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post">
				<INPUT TYPE="hidden" NAME="act" VALUE="addcfgcomment">

				<div class="d-flex flex-column gap-3">
					<div class="col-12 bs-br bg-white overflow-hidden">
						<h3 class="bg-light p-4 m-0">Настройки комментариев</h3>
						<div class="p-4">

							<div class=" col-lg-6 mb-3">
								<label class="form-label">Работа комментариев</label>
								<select class="form-select" NAME="commentEnable">
									<OPTION VALUE="0" '.($newsConfig->commentEnable == '0'?'selected':'').'>Выключено
									<OPTION VALUE="1" '.($newsConfig->commentEnable == '1'?'selected':'').'>Включено
								</select>
								<small class="mt-2">Эта настройка глобальна для всех новостей</small>
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Кто может писать комментарии:</label>
								<select class="form-select" NAME="commentRules">
									<OPTION VALUE="0" '.($newsConfig->commentRules == '0'?'selected':'').'>Все пользователи
									<OPTION VALUE="1" '.($newsConfig->commentRules == '1'?'selected':'').'>Только зарегистрированные пользователи
									<OPTION VALUE="2" '.($newsConfig->commentRules == '2'?'selected':'').'>Только пользователи с преференциями
									<OPTION VALUE="3" '.($newsConfig->commentRules == '3'?'selected':'').'>Только администратор
								</select>
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Модерация перед публикацией:</label>
								<select class="form-select" NAME="commentModeration">
									<OPTION VALUE="0" '.($newsConfig->commentModeration == '0'?'selected':'').'>Не модерировать, публиковать сразу
									<OPTION VALUE="1" '.($newsConfig->commentModeration == '1'?'selected':'').'>Модерировать незарегистрированных пользователей и новичков
									<OPTION VALUE="2" '.($newsConfig->commentModeration == '2'?'selected':'').'>Модерировать всех кроме пользователей с преференциями
								</select>
							</div>



				
							<div class="col-lg-6 mb-3">
								<label class="form-label">Количество сообщений новичка:</label>
								<input class="form-control" type="text" name="commentModerationNumPost" value="'.$newsConfig->commentModerationNumPost.'">
								<small class="mt-2">Максимальное количество сообщений, при котором пользователь считается новичком</small>
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Макс. символов для одного комментария:</label>
								<input class="form-control" type="text" name="commentMaxLength" value="'.$newsConfig->commentMaxLength.'">
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Кол-во выводимых комментариев за раз:</label>
								<input class="form-control" type="text" name="commentNavigation" value="'.$newsConfig->commentNavigation.'">
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Макс. комментариев для одной новости:</label>
								<input class="form-control" type="text" name="commentMaxCount" value="'.$newsConfig->commentMaxCount.'">
							</div>

							<div class="col-lg-6 mb-3">
								<label class="form-label">Задержка на проверку новых комментарий:</label>
								<input class="form-control" type="text" name="commentCheckInterval" value="'.$newsConfig->commentCheckInterval.'">
								<small class="mt-2">Задержка указывается в милисекундах (1 секунда = 1000 милисекунд). Если указать "0", то проверка на наличие новых комментариев выполняться не будет.</small>
							</div>
				
							<div class="btn-gr">
								<button class="btn btn-primary" type="button" onClick="submit();">Сохранить</button>
								<a class="btn btn-secondary" href="module.php?module='.$MODULE.'&amp;act=comment">Вернуться назад</a>
							</div>
								
						</div>
					</div>		
				</div>
			</form>
		</div>';
	}
	
	if($act=='addcfgcomment'){
		
		if( !is_numeric($_POST['commentEnable'])||
			!is_numeric($_POST['commentRules'])||
			!is_numeric($_POST['commentModeration'])||
			!is_numeric($_POST['commentModerationNumPost'])||
			!is_numeric($_POST['commentMaxLength'])||
			!is_numeric($_POST['commentNavigation'])||
			!is_numeric($_POST['commentMaxCount'])||
			!is_numeric($_POST['commentCheckInterval'])){
			echo'<div class="msg">Не все поля заполнены, или заполнены неправильно</div>';
		}else{ 
			
			$newsConfig->commentEnable = $_POST['commentEnable'];
			$newsConfig->commentRules = $_POST['commentRules'];
			$newsConfig->commentModeration = $_POST['commentModeration'];
			$newsConfig->commentModerationNumPost = $_POST['commentModerationNumPost'];
			$newsConfig->commentMaxLength = $_POST['commentMaxLength'];
			$newsConfig->commentNavigation = $_POST['commentNavigation'];
			$newsConfig->commentMaxCount = $_POST['commentMaxCount'];
			$newsConfig->commentCheckInterval = $_POST['commentCheckInterval'];
			
			if($newsStorage->set('newsConfig', json_encode($newsConfig, JSON_FLAGS))){
				echo'<div class="msg">Настройки успешно сохранены</div>';
				System::notification('Изменены параметры комментарий модуля новостей');
			}else{
				echo'<div class="msg">Произошла ошибка записи настроек</div>';
				System::notification('Произошла ошибка при сохранении параметров комментарий модуля новостей', 'r');
			}
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=cfgcomment\';', 3000);
</script>
<?php	
	}
	
	
	
	
		// Добавление категорий
if($act=='cat') { ?>
<script type="text/javascript">
function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function upcat(elem) {
    elem.closest('.cat-row').previousElementSibling?.before(elem.closest('.cat-row'));
}

function downcat(elem) {
    elem.closest('.cat-row').nextElementSibling?.after(elem.closest('.cat-row'));
}

// Улучшенная функция транслитерации
function transliterate(text) {
    const ru = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
        'ч': 'ch', 'ш': 'sh', 'щ': 'shch', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 
        'я': 'ya',
        ' ': '-', '_': '-', ',': '-', '.': '-', '!': '', '?': '', ':': '', ';': '',
        '@': '', '#': '', '$': '', '%': '', '^': '', '&': '', '*': '', '(': '', ')': '',
        '=': '', '+': '', '[': '', ']': '', '{': '', '}': '', '|': '', '\\': '', '/': '-'
    };
    
    let result = '';
    text = text.toString().toLowerCase();
    
    for (let i = 0; i < text.length; i++) {
        result += ru[text[i]] || (text[i].match(/[a-z0-9-]/) ? text[i] : '');
    }
    
    // Удаляем двойные дефисы и дефисы в начале/конце
    result = result.replace(/--+/g, '-').replace(/^-|-$/g, '');
    
    return result;
}

// Автоматическое заполнение идентификатора
function updateSlug(event) {
    const row = event.target.closest('.cat-row');
    const nameInput = event.target;
    const idInput = row.querySelector('input[name="idCat[]"]');
    
    // Обновляем только если идентификатор еще не редактировался вручную
    if (!idInput.dataset.edited) {
        const transliterated = transliterate(nameInput.value);
        idInput.value = transliterated;
    }
}

// Отслеживаем ручное редактирование идентификатора
function markAsEdited(event) {
    event.target.dataset.edited = 'true';
}

function addcat() {
    const cat = document.getElementById("cat");
    const ranndCat = Math.floor(Math.random() * 1000);
    const inner = `
    <div class="card cat-row d-flex gap-3 p-3">
        <div class="col-lg-4 mb-2">
            <label class="form-label small text-muted">Название</label>
            <input type="text" class="form-control mb-3" name="nameCat[]" value="" oninput="updateSlug(event)">

			<label class="form-label small text-muted">Заголовок (Title)</label>
            <input type="text" class="form-control mb-3" name="titleCat[]" value="">

			 <label class="form-label small text-muted">Идентификатор</label>
            <input type="text" class="form-control" name="idCat[]" value="" oninput="markAsEdited(event)">
        </div>
       
        <div class="col-lg-4 mb-2">
            <label class="form-label small text-muted">Описание (Description)</label>
            <textarea class="form-control" rows="8" name="descCat[]"></textarea>
        </div>
        
        <div class="mb-2 d-flex align-items-center justify-content-end">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary" onclick="upcat(this)" title="Вверх">
                    <i class="bi bi-arrow-up"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="downcat(this)" title="Вниз">
                    <i class="bi bi-arrow-down"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="dellcat(this)" title="Удалить">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>`;
    cat.insertAdjacentHTML('beforeend', inner);
}

function dellcat(elem) {
    if(confirm('Вы уверены, что хотите удалить эту категорию?')) {
        elem.closest('.cat-row').remove();
    }
}
</script>

<?php
    echo '

	<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						
						<a href="module.php?module='.$MODULE.'&act=cfg"><i class="bi bi-arrow-left"></i> Назад</a>
	
					</ul>

				</div>
			</div>
		</div>



    <div class="container">
	
		<h1 class="h3">Категории новостей</h1>
		<div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Не используйте в качестве идентификаторов категорий только числовые символы, 
            это может вызвать конфликт навигации по страницам.
        </div>
        
        <form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post">
        <input type="hidden" name="act" value="addcat">
        
        <div class="mb-4">
            <div class="card-body">
                <div id="cat" class="d-flex flex-column gap-3">';
                
                if (!isset($newsConfig->cat)) $newsConfig->cat = new stdClass();
                if (!isset($newsConfig->cat_title)) $newsConfig->cat_title = new stdClass();
                if (!isset($newsConfig->cat_desc)) $newsConfig->cat_desc = new stdClass();
                
                foreach($newsConfig->cat as $key => $value) {
                    $title = isset($newsConfig->cat_title->$key) ? $newsConfig->cat_title->$key : '';
                    $desc = isset($newsConfig->cat_desc->$key) ? $newsConfig->cat_desc->$key : '';
                    
                    echo '
                    <div class="card cat-row d-flex gap-3 p-3">
                        <div class="col-lg-4 mb-2">
                            <label class="form-label small text-muted">Название</label>
                            <input type="text" class="form-control mb-3" name="nameCat[]" 
                                   value="'.htmlspecialchars($value).'"
                                   oninput="updateSlug(event)">

                            <label class="form-label small text-muted">Заголовок (Title)</label>
                            <input type="text" class="form-control mb-3" name="titleCat[]" value="'.htmlspecialchars($title).'">
                            
                            <label class="form-label small text-muted">Идентификатор</label>
                            <input type="text" class="form-control" name="idCat[]" 
                                   value="'.htmlspecialchars($key).'"
                                   oninput="markAsEdited(event)">
                        </div>
                        
                        <div class="col-lg-4 mb-2">
                            <label class="form-label small text-muted">Описание (Description)</label>
                            <textarea class="form-control" rows="8" name="descCat[]">'.htmlspecialchars($desc).'</textarea>
                        </div>
                        
                        <div class="mb-2 d-flex align-items-center justify-content-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" onclick="upcat(this)" title="Вверх">
                                    <i class="bi bi-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="downcat(this)" title="Вниз">
                                    <i class="bi bi-arrow-down"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="dellcat(this)" title="Удалить">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>';
                }
                
                echo '
                </div>
            </div>
        </div>

        <div class="card-footer bg-transparent">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Сохранить изменения</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="addcat()"><i class="bi bi-plus-lg"></i> Добавить категорию</button>
        </div>
        </form>
        
        
    </div>';
}

// Остальная часть кода (addcat) остается без изменений

if($act=='addcat') {
    $countCat = isset($_POST['idCat']) ? count($_POST['idCat']) : 0;
    $catArray = new stdClass();
    $catTitleArray = new stdClass();
    $catDescArray = new stdClass();
    
    for($i=0; $i<$countCat; ++$i) {
        if(System::validPath($_POST['idCat'][$i])) {
            $idCat = is_numeric($_POST['idCat'][$i]) ? 'c'.$i.uniqid() : htmlspecialchars($_POST['idCat'][$i]);
            $nameCat = htmlspecialchars($_POST['nameCat'][$i]);
            $titleCat = htmlspecialchars($_POST['titleCat'][$i] ?? '');
            $descCat = htmlspecialchars($_POST['descCat'][$i] ?? '');
            
            $catArray->$idCat = $nameCat;
            $catTitleArray->$idCat = $titleCat;
            $catDescArray->$idCat = $descCat;
        }
    }
    
    $newsConfig->cat = $catArray;
    $newsConfig->cat_title = $catTitleArray;
    $newsConfig->cat_desc = $catDescArray;
    
    if($newsStorage->set('newsConfig', json_encode($newsConfig, JSON_FLAGS))) {
        echo '<div class="container py-4">
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                Настройки успешно сохранены
            </div>
        </div>';
        System::notification('Изменены категории модуля новостей');
    } else {
        echo '<div class="container py-4">
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Произошла ошибка записи настроек
            </div>
        </div>';
        System::notification('Произошла ошибка при сохранении категорий модуля новостей', 'r');
    }
    ?>
    <script type="text/javascript">
    setTimeout(function() {
        window.location.href = 'module.php?module=<?php echo $MODULE;?>&act=cat';
    }, 200);
    </script>
    <?php
}







//Custom
	if($act=='custom'){?>
<script type="text/javascript">
function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
function upCustom(elem) {
    elem.parentNode.parentNode.parentNode.insertBefore(elem.parentNode.parentNode, elem.parentNode.parentNode.previousSibling);
}
function downCustom(elem) {
    elem.parentNode.parentNode.parentNode.insertBefore(elem.parentNode.parentNode, elem.parentNode.parentNode.nextSibling.nextSibling);
}
function addCustom(){
	var custom = document.getElementById("custom");
	var ranndCustom = random(5);
	var inner = '<tr><td>'+
				'<span class="comment">Идентификатор</span><br>'+
				'<input type="text" name="idCustom[]" value="'+ ranndCustom +'">'+
			'</td><td>'+
				'<span class="comment">Тип поля</span><br>'+
				'<select name="typeCustom[]">'+
					'<option value="input" selected>Однострочное поле'+
					'<option value="textarea">Многострочное поле'+
				'</select>'+
			'</td><td>'+
				'<span class="comment">Название поля</span><br>'+
				'<input type="text" name="nameCustom[]" value="Поле '+ ranndCustom +'">'+
			'</td><td>'+
				'<span class="comment"></span><br>'+
				'<button type="button" onClick="upCustom(this);">Вверх</button> <button type="button" onClick="downCustom(this);">Вниз</button> <button type="button" onClick="dellCustom(this);">Удалить</button>'+
			'</td></tr>';
	custom.insertAdjacentHTML('beforeend', inner);
}
function dellCustom(elem){
	elem.parentNode.parentNode.parentNode.removeChild(elem.parentNode.parentNode);
}
</script>
<?php	
		// var_dump($newsConfig->custom);
		echo'
		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">

					<ul class="nav">
						<a href="module.php?module='.$MODULE.'&amp;act=cfg">&#8592; Назад</a>
						'.$menu_page.'
					</ul>

				</div>
			</div>
		</div>

	
		<div class="container">
		<h1>Настройка дополнительных полей</h1>
		<p>В этом разделе вы можете задать какие дополнительные поля нужно вводить при добавлении новости. 
		Идентификаторы, которые вы тут укажете, будут работать как дополнительные хештеги для шаблонов. Информацию про хештеги вы можете узнать на <a href="http://my-engine.ru/" target="_blank">нашем сайте</a>.</p>
		<form name="form_name" action="module.php?module='.$MODULE.'&amp;" method="post">
		<INPUT TYPE="hidden" NAME="act" VALUE="addcustom">
		<table class="tables">
		<tbody id="custom">';
		
		foreach($newsConfig->custom as $value){
			echo '<tr><td>
				<span class="comment">Идентификатор</span><br>
				<input type="text" name="idCustom[]" value="'.$value->id.'">
			</td><td>
				<span class="comment">Тип поля</span><br>
				<select name="typeCustom[]">
					<option value="input" '.($value->type == 'input'?'selected':'').'>Однострочное поле
					<option value="textarea" '.($value->type == 'textarea'?'selected':'').'>Многострочное поле
				</select>
			</td><td>
				<span class="comment">Название поля</span><br>
				<input type="text" name="nameCustom[]" value="'.$value->name.'">
			</td><td>
				<span class="comment"></span><br>
				<button type="button" onClick="upCustom(this);">Вверх</button> <button type="button" onClick="downCustom(this);">Вниз</button> <button type="button" onClick="dellCustom(this);">Удалить</button>
			</td></tr>';
		}

		echo'</tbody>
		</table>
		
		<div class="btn-gr">
		<button class="btn btn-primary" type="button" onClick="addCustom();">Добавить новое поле</button>
		<button class="btn btn-secondary" type="button" onClick="submit();">Сохранить изменения</button>
		</div>
		</form>
		</div>';
	}

	if($act=='addcustom'){
		if (isset($_POST['idCustom'])){
			$countCat = count($_POST['idCustom']);
		}else{
			$countCat = 0;
		}
		$array = array();
		for($i=0; $i<$countCat; ++$i){
			$array[$i]['id'] = htmlspecialchars($_POST['idCustom'][$i]);
			$array[$i]['type'] = htmlspecialchars($_POST['typeCustom'][$i]);
			$array[$i]['name'] = htmlspecialchars($_POST['nameCustom'][$i]);
		}
		$newsConfig->custom = $array;
		// print_r($newsConfig);
		if($newsStorage->set('newsConfig', json_encode($newsConfig, JSON_FLAGS))){
				echo'<div class="msg">Настройки успешно сохранены</div>';
				System::notification('Изменены дополнительные поля модуля новостей');
		}else{
				echo'<div class="msg">Произошла ошибка записи настроек</div>';
				System::notification('Произошла ошибка при сохранении дополнительных полей модуля новостей', 'r');
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?php echo $MODULE;?>&act=custom\';', 3000);
</script>
<?php	
	}

?>

<script>
// Get the input field with the class "form-control" and name "header"
const headerInput = document.querySelector('.form-control[name="header"]');

// Get the input field with the class "form-control" and name "id"
const idInput = document.querySelector('.form-control[name="id"]');

// Add an event listener to the "header" input field
headerInput.addEventListener('input', function() {
  // Get the value of the "header" input field
  const headerValue = this.value;

  // Convert the Russian text to English transliteration and apply the additional requirements
  const idValue = formatIdValue(transliterateRussian(headerValue));

  // Set the value of the "id" input field
  idInput.value = idValue;
});

/**
 * Transliterates Russian text to English.
 * @param {string} russianText - The Russian text to be transliterated.
 * @returns {string} The English transliteration of the Russian text.
 */
function transliterateRussian(russianText) {
  const transliterationMap = {
    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo',
    'ж': 'zh', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm',
    'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
    'ф': 'f', 'х': 'h', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'shch',
    'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
  };

  let transliteratedText = '';
  for (let i = 0; i < russianText.length; i++) {
    const char = russianText[i].toLowerCase();
    transliteratedText += transliterationMap[char] || char;
  }
  return transliteratedText;
}

/**
 * Formats the ID value based on the additional requirements.
 * @param {string} idValue - The ID value to be formatted.
 * @returns {string} The formatted ID value.
 */
function formatIdValue(idValue) {
  // Remove special characters, dots, commas, and the 'ъ' and 'ь' symbols
  let formattedValue = idValue.replace(/[^a-zA-Z0-9\s]/g, '');

  // Replace multiple spaces with a single space
  formattedValue = formattedValue.replace(/\s+/g, ' ');

  // Remove leading and trailing spaces
  formattedValue = formattedValue.trim();

  // Replace spaces with hyphens
  formattedValue = formattedValue.replace(/\s/g, '-');

  return formattedValue;
}
</script>