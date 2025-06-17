<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

$GalleryStorage = new EngineStorage('module.gallery');

$gallery_dir = $GalleryStorage->iss('gallery_dir')?$GalleryStorage->get('gallery_dir'):'/files';
$gallery_min_w='200'; // Ширина миниатюры
$gallery_min_h='150'; // Высота миниатюры



function resize($file_input, $file_output, $w_o, $h_o, $percent = false) { 
	list($w_i, $h_i, $type) = getimagesize($file_input); 
	if (!$w_i || !$h_i) { 
		// echo 'Невозможно получить длину и ширину изображения при уменьшении'; 
		return false; 
	} 
	$types = array('','gif','jpeg','png'); 
	$ext = $types[$type]; 
	if ($ext) { 
		$func = 'imagecreatefrom'.$ext; 
		$img = $func($file_input); 
	} else { 
		// echo 'Некорректный формат файла'; 
		return false; 
	} 
	if ($percent) { 
		$w_o *= $w_i / 100; 
		$h_o *= $h_i / 100; 
	} 
	if (!$h_o) $h_o = $w_o/($w_i/$h_i); 
	if (!$w_o) $w_o = $h_o/($h_i/$w_i); 
	$img_o = imagecreatetruecolor($w_o, $h_o); 
	imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); 
	if ($type == 2) { 
		return imagejpeg($img_o,$file_output,100); 
	} else { 
		$func = 'image'.$ext; 
		return $func($img_o,$file_output); 
	} 
} 

?>
<script type="text/javascript">
var allgen = '<div class="a"><span style="color:red;">Внимание!</span> Генерирование большого количества миниатюр может быть долгим и создавать большую нагрузку на сервер.</div>' +
	'<div class="b">' +
	'<button type="button" onClick="closewindow(\'window\'); openwindow(\'window2\', 300, \'auto\', allgengo); window.location.href = \'module.php?module=<?php echo $MODULE;?>&amp;act=allgen\';">Начать</button> '+
	'<button type="button" onclick="closewindow(\'window\');">Отмена</button>'+
	'</div>';
var allgengo = '<div style="font-weight: bold; padding: 10px;">Идет обработка...</div>';	

</script>
<?php


	// $gchdir = (isset($_GET['gchdir']))?htmlspecialchars(specfilter(str_replace('..','',$_GET['gchdir']))):'';
	// if($gchdir != '') {
	// 	$gallery_dir = $gallery_dir.$gchdir;
	// }

	if(isset($_GET['dir'])){$dir = htmlspecialchars($_GET['dir']);}
	if(isset($_POST['dir'])){$dir = htmlspecialchars($_POST['dir']);}
	if(isset($_GET['file'])){$file = htmlspecialchars($_GET['file']);}
	if(isset($_POST['file'])){$file = htmlspecialchars($_POST['file']);}
	if(empty($dir)){ $dir=$gallery_dir; }

	if($act=='index')
	{
		echo'<div class="header"><h1>Галерея</h1></div>
		<div class="menu_page"><a class="link" href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', allgen);">Сгенерировать миниатюры из всех имеющихся изображений</a><a class="link" href="module.php?module='.$MODULE.'&amp;act=cfg">Настройки галереи</a></div>
		';
		echo'<div class="menu_page">
		<span>Директория:</span> <a href="module.php?module='.$MODULE.'">'.SERVER.'</a>';
		$new_dir_url= array();
		$dir_url_arr = explode('/', $dir);
		foreach($dir_url_arr as $value){
			if($value == '..') continue;
			$new_dir_url[] = $value;
			echo'<a href="module.php?module='.$MODULE.'&amp;dir='.implode('/', $new_dir_url).'">'.$value.'</a> / ';
		}
		echo'</div>
		<div class="content">';
		if(file_exists(DR.$dir)){
			
			
			
			
			$arr_dir = scandir(DR.$dir);
			$arr_img = array();
			$arr_folder = array();
			foreach($arr_dir as $value){
				if(is_dir(DR.$dir.'/'.$value)){
					if($value != '.' && $value != '..') $arr_folder[] = $value;
				}elseif(preg_match('/^[a-z0-9]+([\._-][a-z0-9]+)*\.(jpg|jpeg|png|gif)+$/i', $value)){
					$arr_img[$value] = filemtime(DR.$dir.'/'.$value);
				}
			}
			
			sort($arr_folder);
			arsort($arr_img);
			
			$arr_folder_count = count($arr_folder);
			$arr_img_count = count($arr_img);
			
			
			echo'<div style="margin-top:10px;">';
			if($arr_folder_count == 0 && $arr_img_count == 0){
				echo '<div class="msg">Нет изображений или папок для вывода</div>';
			}
			
			if($arr_folder_count != 0){
				foreach($arr_folder as $value){
					
					
					$path = md5($dir.'/'.$value);
					$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$value;
					if($name == '')$name = $value;
					
					
					echo'<div style="float: left; box-shadow: 0 0 5px #ccc; border: 1px solid #aaa; background-color: #eee; margin: 0 10px 10px 0;">
						<div style="padding: 5px 10px;"><span style="color: black; font-weight: bold;">'.$name.'</span> <span style="float:right; margin-left: 5px;"><a href="module.php?module='.$MODULE.'&amp;act=edit&amp;dir='.$dir.'&amp;file='.$value.'">Ред.</a></span></div>
						<div style="width: 300px; height: 200px; background-color: #fff; display: table-cell; vertical-align: middle; text-align: center; padding: 10px;">
							<a href="module.php?module='.$MODULE.'&amp;dir='.$dir.'/'.$value.'" title="'.$name.'"><img style="width: 300px; height: 200px;" src="/modules/gallery/folder.png" alt="'.$folder_name.'"></a>
						</div>
						<div style="padding: 5px 10px;">Размер не определен</div>
					</div>';
				}
				
				
			}
			
			if($arr_img_count != 0){
				foreach($arr_img as $key => $value){
					

					$path = md5($dir.'/'.$key);
					$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$key;
					if($name == '')$name = $key;

					if(file_exists('../modules/gallery/data/'.$key)){
						$img_bar = '<span style="color: green;">Есть миниатюра</span> (<a href="module.php?module='.$MODULE.'&amp;act=gen&amp;file='.$key.'&amp;dir='.$dir.'">Обновить</a>)';
						$img_url = '/modules/gallery/data/'.$key;
					}else{
						$img_bar = '<span style="color: red;">Нет миниатюры</span> (<a href="module.php?module='.$MODULE.'&amp;act=gen&amp;file='.$key.'&amp;dir='.$dir.'">Создать</a>)';
						$img_url = $dir.'/'.$key;
					}
					
					
					
					echo'<div style="float: left; box-shadow: 0 0 5px #ccc; border: 1px solid #aaa; background-color: #eee; margin: 0 10px 10px 0;">
						<div style="padding: 5px 10px;"><span style="color: black; font-weight: bold;">'.$name.'</span> <span style="float:right; margin-left: 5px;"><a href="module.php?module='.$MODULE.'&amp;act=edit&amp;dir='.$dir.'&amp;file='.$key.'">Ред.</a></span></div>
						<div style="width: 300px; height: 200px; background-color: #fff; display: table-cell; vertical-align: middle; text-align: center; padding: 10px;"><img style="width: 300px; height: 200px;"  src="'.$img_url.'" alt=""></div>
						<div style="padding: 5px 10px;">'.convert_size(filesize(DR.$dir.'/'.$key)).' '.$img_bar.'</div>
					</div>';
				}
				
			}
			echo'</div>';
		}else{
			echo'<div class="msg">Папка с изображениями не найдена.<br>Укажите верный путь к папке с изображениями в настройках модуля.</div>';
		}
		echo'</div>';
		
	}
	
	if($act=='allgen'){
		if(!is_readable('../modules/gallery/data')){
			echo'<div class="msg">Папка для миниатюр недоступна для записи.<br>Установите папке <b>modules/gallery/data</b> права доступа разрешающие запись.</div>';
		}elseif(file_exists(DR.$gallery_dir)){
			$start_time = microtime(true);
			
			
			
			function asdfg($dir){
				global $gallery_min_w, $gallery_min_h;
				$arr_dir = scandir($dir);
				$i = 0;
				foreach($arr_dir as $value){
					if($value != '.' && $value != '..'){
						if(is_dir($dir.'/'.$value)){
							asdfg($dir.'/'.$value);
						}
						if(preg_match('/^[a-z0-9]+([\._-][a-z0-9]+)*\.(jpg|jpeg|png|gif)+$/i', $value)){
							++$i;
							resize($dir.'/'.$value, '../modules/gallery/data/'.$value, $gallery_min_w, $gallery_min_h);
						}
					}
				}
				return $i;
			}
			$i = asdfg(DR.$gallery_dir);
			
			
			
			$time = round(microtime(true) - $start_time, 4);
			System::notification('Выполнена генерация миниатюр в галереи, обработано '.$i.' миниатюр за '.$time.' sek.', 'g');
			echo'<div class="msg">Генерирование миниатюр завершено</div>';
		}else{
			echo'<div class="msg">Папка с изображениями не найдена.<br>Укажите верный путь к папке с изображениями в настройках модуля.</div>';
		}	
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?=$MODULE?>\';', 3000);
</script>
<?php	
	}
	
	if($act=='gen'){
		
		
		if(!is_readable('../modules/gallery/data')){
			echo'<div class="msg">Папка для миниатюр недоступна для записи.<br>Установите папке <b>modules/gallery/data</b> права доступа разрешающие запись.</div>';
		}elseif(file_exists(DR.$dir.'/'.$file)){
			resize(DR.$dir.'/'.$file, '../modules/gallery/data/'.$file, $gallery_min_w, $gallery_min_h);
			System::notification('Сгенерирована миниатюра для изображения '.$dir.'/'.$file.'', 'g');
			echo'<div class="msg">Генерирование миниатюры завершено</div>';
		}else{
			System::notification('Ошибка при генерации миниатюры '.$dir.'/'.$file.', исходное изображение не найдено', 'r');
			echo'<div class="msg">Исходное изображение не найдено</div>';
		}	
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?=$MODULE?>&dir=<?=$dir;?>\';', 3000);
</script>
<?php	
	}
	
	if($act=='cfg'){
		
		echo'<div class="header"><h1>Настройки галереи</h1></div>
		<div class="menu_page"><a href="module.php?module='.$MODULE.'">&#8592; Вернуться назад</a></div> 
		<div class="content">
		<form name="form_name" action="module.php?module='.$MODULE.'" method="post" style="margin:0px; padding:0px;">
		<INPUT TYPE="hidden" NAME="act" VALUE="addcfg">
		<table class="tblform">
		
		<tr>
			<td>Путь к папке с изображениями:</td>
			<td><input type="text" name="new_cfg_gallery_dir" value="'.$gallery_dir.'"></td>
		</tr>
		<tr>
			<td>Сортировка:</td>
			<td>
				<select name="gallery_sort">
					<option value="asort" '.($GalleryStorage->get('gallery_sort') == 'asort'?'selected':'').'>По порядку загрузки
					<option value="arsort" '.($GalleryStorage->get('gallery_sort') == 'arsort'?'selected':'').'>Реверс порядка загрузки (новые вверху)
				</select>
			</td>
		</tr>
		<tr>
			<td>JS библиотека Fancybox:</td>
			<td>
				<select name="fancybox">
					<option value="0" '.($GalleryStorage->get('fancybox') == '0'?'selected':'').'>Выключить
					<option value="1" '.($GalleryStorage->get('fancybox') == '1'?'selected':'').'>Включить
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><button type="button" onClick="submit();">Сохранить</button></td>
		</tr>
		</table>
		</form>
		</div>';
	}
	
	if($act=='addcfg'){
		$GalleryStorage->set('gallery_dir', htmlspecialchars(specfilter($_POST['new_cfg_gallery_dir'])));
		$GalleryStorage->set('gallery_sort', htmlspecialchars(specfilter($_POST['gallery_sort'])));
		$GalleryStorage->set('fancybox', htmlspecialchars(specfilter($_POST['fancybox'])));
		echo'<div class="msg">Настройки успешно сохранены</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?=$MODULE?>&act=cfg\';', 3000);
</script>
<?php	
	}
	
	if($act=='edit')
	{
		
		
		if(file_exists(DR.$dir.'/'.$file) && $file != ''){
			
			
			
			$path = md5($dir.'/'.$file);
			$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$file;

			
			
			echo'<div class="header"><h1>Настройки галереи</h1></div>
			<div class="menu_page"><a href="module.php?module='.$MODULE.'&amp;dir='.$dir.'">&#8592; Вернуться назад</a></div> 
			<div class="content">
			<form name="form_name" action="module.php?module='.$MODULE.'&amp;act=addedit" method="post" style="margin:0px; padding:0px;">
			<INPUT TYPE="hidden" NAME="dir" VALUE="'.$dir.'">
			<INPUT TYPE="hidden" NAME="file" VALUE="'.$file.'">
			<table class="tblform">
			<tr>
				<td>Название изображения или папки:</td>
				<td><input type="text" name="name" value="'.$name.'"><br><span class="comment">Можно использовать русские буквы</span></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><button type="button" onClick="submit();">Сохранить</button> &nbsp; <a href="module.php?module='.$MODULE.'&amp;dir='.$dir.'">Вернуться назад</a></td>
			</tr>
			</table>
			</form>
			</div>';
			
		}else{
			echo'<div class="msg">Произошла ошибка</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?=$MODULE?>\';', 3000);
</script>
<?php
		}
	}
	
	if($act=='addedit')
	{
		$name = htmlspecialchars(specfilter($_POST['name']));
		if(file_exists(DR.$dir.'/'.$file) && $file != '' && $name != ''){
			$path = md5($dir.'/'.$file);
			$GalleryStorage->set('name.'.$path, $name);
			
			echo'<div class="msg">Имя успешно сохранено</div>';
		}else{
			echo'<div class="msg">Произошла ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'module.php?module=<?=$MODULE?>&dir=<?=$dir?>\';', 3000);
</script>
<?php
	}
	
	

?>