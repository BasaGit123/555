<?php
$ACTIVMENU = 'bloks';
require('../system/global.dat');
require('./include/start.dat');

if($status=='admin'){
	if($act=='index'){
		$info = Module::info($Config->template);
		$info['column1_menu'] = 1;
    $info['column2_menu'] = 1;
		$info['column3_menu'] = 1;
?>
<script type="text/javascript">
function dellblok(str_blok, l_o_r){
return '<div class="h5 d-flex flex-column justify-content-center mb-0 p-4">Подтвердите удаление блока' +
	'<div class="btn-gr justify-content-center">' +
	'<button class="btn btn-primary" type="button" onClick="window.location.href = \'bloks.php?act=dell_blok&amp;str_blok='+str_blok+'&amp;l_o_r='+l_o_r+'\';">Удалить</button> '+
	'<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
	'</div>';
}

function delllink(link_file, str_link){
return '<div class="h5 d-flex flex-column justify-content-center mb-0 p-4">Подтвердите удаление ссылки' +
	'<div class="btn-gr justify-content-center">' +
	'<button class="btn btn-primary" type="button" onClick="window.location.href = \'bloks.php?act=dell_link&amp;link_file='+link_file+'&amp;str_link='+str_link+'&amp;rkt=left\';">Удалить</button> '+
	'<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
	'</div>';
}
// Сворачивание/разворачивание подпунктов
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tables tr').forEach(row => {
        // Находим ячейку с названием пункта
        const nameCell = row.querySelector('td:nth-child(2)');
        if (nameCell && nameCell.textContent.trim() && !nameCell.querySelector('img')) {
            // Добавляем курсор-указатель для родительских пунктов
            nameCell.style.cursor = 'pointer';
            
            nameCell.addEventListener('click', function() {
                // Находим все следующие строки до следующего родительского пункта
                let nextRow = row.nextElementSibling;
                while(nextRow && nextRow.querySelector('td:nth-child(2) img')) {
                    nextRow.style.display = nextRow.style.display === 'none' ? '' : 'none';
                    nextRow = nextRow.nextElementSibling;
                }
            });
        }
    });
});
// Сворачивание/разворачивание всей колонки по клику на заголовок
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tables').forEach(table => {
        const header = table.querySelector('.tables_head');
        if (header && !header.querySelector('.button')) {
            header.style.cursor = 'pointer';
            
            header.addEventListener('click', function() {
                const contentRows = table.querySelectorAll('tr:not(:first-child)');
                const isCollapsed = contentRows[0]?.style.display === 'none';
                
                contentRows.forEach(row => {
                    row.style.display = isCollapsed ? '' : 'none';
                });
                
                table.classList.toggle('collapsed', !isCollapsed);
            });
        }
    });
});
</script>
<?php
		echo'
		<div class="header">
			
		</div>
		
		<div class="container">
		<h1>Управление меню</h1>';

if($info['gorizont_menu'] == 0 && $info['left_menu'] == 0 && $info['right_menu'] == 0 
   && $info['column1_menu'] == 0 && $info['column2_menu'] == 0 && $info['column3_menu'] == 0) 
    echo'<div class="msg">Шаблоном не предусмотрено редактировать меню</div>';
		
		
	if($info['gorizont_menu']){
		echo'
		<div class="tables-header bs-br p-4 bg-white">

			<div class="d-flex align-items-center bg-light mb-3">
				<strong>Главное меню</strong>
				<a href="bloks.php?act=new_link&link_file=gorizont" class="button addlink" title="Добавить ссылку">Добавить ссылку</a>
			</div>';

			if(file_exists('../data/bloks/links_gorizont.dat')){
				$links = file('../data/bloks/links_gorizont.dat');
				$menu_items = [];
				$index_map = []; // Для сохранения оригинальных индексов
				
				// Собираем и индексируем все пункты
				foreach($links as $i => $link){
					$parts = explode('<||>', $link);
					$menu_items[$i] = [
						'type' => $parts[0],
						'page' => $parts[1],
						'name' => $parts[2],
						'level' => isset($parts[3]) ? trim($parts[3]) : '0',
						'parent' => isset($parts[4]) ? trim($parts[4]) : '0',
						'order' => isset($parts[5]) ? trim($parts[5]) : '0',
						'original_index' => $i // Сохраняем оригинальный индекс
					];
					$index_map[$i] = $i;
				}

				// Группируем по родителям
				$grouped_menu = [];
				foreach($menu_items as $item) {
					if($item['level'] == 0) {
						$grouped_menu[$item['page']] = [
							'main' => $item,
							'children' => []
						];
					}
				}
				
				// Добавляем детей
				foreach($menu_items as $item) {
					if($item['level'] == 1 && isset($grouped_menu[$item['parent']])) {
						$grouped_menu[$item['parent']]['children'][] = $item;
					}
				}

				// Выводим сгруппированное меню
				foreach($grouped_menu as $menu_group) {
					// Основной пункт
					$i = $menu_group['main']['original_index'];
					echo'
					<div class="border-bottom mt-3">

						<div class="mb-2">
							<img src="include/link.svg" alt="">
							<span class="h6 ms-2">'.$menu_group['main']['name'].'</span>
						</div>

						<div class="mb-3">
							<a href="bloks.php?act=up_link&amp;link_file=gorizont&amp;str_link='.$i.'">Вверх</a> &nbsp; 
							<a href="bloks.php?act=down_link&amp;link_file=gorizont&amp;str_link='.$i.'">Вниз</a> &nbsp; 
							<a href="bloks.php?act=editor_link&amp;link_file=gorizont&amp;str_link='.$i.'">Редактировать</a> &nbsp; 
							<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\'gorizont\', \''.$i.'\'));">Удалить</a>
						</div>';

						// Подпункты
						foreach($menu_group['children'] as $child) {
							$i = $child['original_index'];
							echo'
							<div class="d-flex my-2">

								<div class="me-3">
									
									<div class="menu-item ms-2 level-' . $child['level'] . '">
										<img src="include/link-child.svg" alt="">
										<span class="submenu-item">' . $child['name'] . '</span>
									</div>
								</div>

								<div>
									<a href="bloks.php?act=up_link&amp;link_file=gorizont&amp;str_link='.$i.'">Вверх</a> &nbsp; 
									<a href="bloks.php?act=down_link&amp;link_file=gorizont&amp;str_link='.$i.'">Вниз</a> &nbsp; 
									<a href="bloks.php?act=editor_link&amp;link_file=gorizont&amp;str_link='.$i.'">Редактировать</a> &nbsp; 
									<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\'gorizont\', \''.$i.'\'));">Удалить</a>
								</div>

							</div>';
						}

					echo '</div>';
				
				}
			}
			echo'
		</div>';
	}
		
		
		
		if($info['left_menu']){
			echo'
			<div class="bs-br p-4 mt-3 bg-white" >
				<div class="col-gr bg-light">
					<strong>Левая колонка</strong>
					<a href="bloks.php?act=new_blok&amp;l_o_r=left" class="button addlink" title="Добавить новый блок">Добавить блок</a>
				</div>';
			if(file_exists('../data/bloks/left_bloks.dat')){
				$blok_data = file('../data/bloks/left_bloks.dat');
				$nom = count($blok_data);
				if($nom == 0){
					echo'
					<div class="link-danger">
						Блоки ещё не созданы
					</div>
					';
				}
				for($i = 0; $i < $nom; ++$i){
					$blok_cfg = explode('<||>',$blok_data[$i]);
					$lin = '
							
								<a href="bloks.php?act=up_blok&amp;str_blok='.$i.'&amp;l_o_r=left" title="Переместить блок вверх">Вверх</a> &nbsp; 
								<a href="bloks.php?act=down_blok&amp;str_blok='.$i.'&amp;l_o_r=left" title="Переместить блок вниз">Вниз</a> &nbsp; 
								'.($info['right_menu']?'<a href="bloks.php?act=go_to_blok&amp;str_blok='.$i.'&amp;l_o_r=right" title="Переместить блок в правую колонку">Вправо</a> &nbsp; ':'').'
								<a href="bloks.php?act=editor_blok&amp;str_blok='.$i.'&amp;l_o_r=left" title="Редактировать блок">Редактировать</a> &nbsp; 
								<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dellblok(\''.$i.'\',\'left\'));" title="Удалить блок">Удалить</a>
							';

					if($blok_cfg[1] == 'links'){//если блок имеет тип Ссылки
						echo'

						<div class="col-gr">

							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>

							<div class="d-flex gap-1 mt-2">
							'.$lin.'
							 <a href="bloks.php?act=new_link&amp;link_file='.$blok_cfg[0].'&amp;rkt=left" class="ms-2" title="Добавить ссылку в этот блок">Добавить ссылку</a>
							</div>

						</div>';

						if(file_exists('../data/bloks/links_'.$blok_cfg[0].'.dat')){
							$link_data = file('../data/bloks/links_'.$blok_cfg[0].'.dat');
							$nom_0067 = count($link_data);
							if($nom_0067 == 0){
								echo'
								<div class="link-danger">Ссылки еще не созданы</div>
								';
							}
							for($q = 0; $q < $nom_0067; ++$q){
								$link_cfg = explode('<||>',$link_data[$q]);
								if($link_cfg[0] == 'page'){//если ссылка имеет тип На страницу движка
									echo'

									<div class="link1 ms-3 pb-3 5555">
										<img src="include/link.svg" alt="">
										<span class="mx-2">'.$link_cfg[2].'</span>
										<a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a>
									</div>';

								}elseif($link_cfg[0] == 'http'){//если ссылка имеет тип Простая http ссылка
									echo'
									<div class="link2 ms-3 pb-3 5555">
									<img src="include/link.svg" alt=""> 
									<span class="mx-2">'.$link_cfg[2].'</span>
									<a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a>
									</div>';

								}
							}
						}else{
							echo'Ошибка';
						}
					}elseif($blok_cfg[1] == 'html'){//если блок имеет тип HTML
						echo'

						<div class="col-gr">
							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>
							<div class="">
								'.$lin.'
							</div>
						</div>';

					}elseif($blok_cfg[1] == 'module'){//если блок имеет тип Модуль
						echo'

						<div class="col-gr">
							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>

							<div class="">'.$lin.'</div>
						</div>';

					}else{
						echo'
						<div class="mb-2">
							<img src="include/blok.svg" alt="">
							<a href="bloks.php?act=dell_blok&amp;str_blok='.$i.'&amp;l_o_r=left" >Удалить</a>
						</div>';
					}
				}
			}else{
				echo'
				<div> Ошибка </div>';
			}
			echo'</div>';
		}
		
		if($info['right_menu']){
			echo'

			<div class="bs-br p-4 mt-3 bg-white" >
				<div class="col-gr bg-light">
					<strong>Правая колонка</strong>
					<a href="bloks.php?act=new_blok&amp;l_o_r=right" class="button addlink" title="Добавить новый блок">Добавить блок</a>
				</div>';

			if(file_exists('../data/bloks/right_bloks.dat')){
				$blok_data = file('../data/bloks/right_bloks.dat');
				$nom = count($blok_data);
				if($nom == 0){
					echo'<div class="link-danger">Блоки ещё не созданы</div>';
				}
				for($i = 0; $i < $nom; ++$i){
					$blok_cfg = explode('<||>',$blok_data[$i]);
					$lin = '
							<a href="bloks.php?act=up_blok&amp;str_blok='.$i.'&amp;l_o_r=right" title="Переместить блок вверх">Вверх</a> &nbsp; 
							<a href="bloks.php?act=down_blok&amp;str_blok='.$i.'&amp;l_o_r=right" title="Переместить блок вниз">Вниз</a> &nbsp; 
							'.($info['left_menu']?'<a href="bloks.php?act=go_to_blok&amp;str_blok='.$i.'&amp;l_o_r=left" title="Переместить блок в левую колонку">Влево</a> &nbsp; ':'').'
							<a href="bloks.php?act=editor_blok&amp;str_blok='.$i.'&amp;l_o_r=right" title="Редактировать блок">Редактировать</a> &nbsp; 
							<a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dellblok(\''.$i.'\',\'right\'));" title="Удалить блок">Удалить</a>
							
							';
					if($blok_cfg[1] == 'links'){//если блок имеет тип Ссылки
						echo'

						<div class="col-gr">

							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>

							<div class="d-flex gap-1 mt-2">
							'.$lin.'
							 <a href="bloks.php?act=new_link&amp;link_file='.$blok_cfg[0].'&amp;rkt=right" class="ms-2" title="Добавить ссылку в этот блок">Добавить ссылку</a></td></tr>
							</div>

						</div>

						';
						if(file_exists('../data/bloks/links_'.$blok_cfg[0].'.dat')){
							$link_data = file('../data/bloks/links_'.$blok_cfg[0].'.dat');
							$nom_0067 = count($link_data);
							if($nom_0067 == 0){
								echo'

								<div class="link-danger">Ссылки еще не созданы</div>

								';
							}
							for($q = 0; $q < $nom_0067; ++$q){
								$link_cfg = explode('<||>',$link_data[$q]);
								if($link_cfg[0] == 'page'){//если ссылка имеет тип На страницу движка
									echo'

									<div class="link1 ms-3 pb-3 5555">
										<img src="include/link.svg" alt="">
										<span class="mx-2">'.$link_cfg[2].'</span>
										<a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a>
									</div>

									
									
									 
									 ';
								}elseif($link_cfg[0] == 'http'){//если ссылка имеет тип Простая http ссылка
									echo'

									<div class="link2 ms-3 pb-3 5555">
									<img src="include/link.svg" alt=""> 
									<span class="mx-2">'.$link_cfg[2].'</span>
									<a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a>
									</div>
									
									';
								}
							}
						}else{
							echo'<div>Ошибка</div><';
						}
					}elseif($blok_cfg[1] == 'html'){//если блок имеет тип HTML
						echo'

						<div class="col-gr">
							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>
							<div class="">
								'.$lin.'
							</div>
						</div>

						';

					}elseif($blok_cfg[1] == 'module'){//если блок имеет тип Модуль
						echo'

						<div class="col-gr">
							<div>
								<img src="include/blok.svg" alt="">
								<span class="h6 ms-2">'.$blok_cfg[2].'</span>
							</div>

							<div class="">'.$lin.'</div>
						</div>
						
						';

					}else{

						echo'

						<div class="mb-2">
							<img src="include/blok.svg" alt="">
							<a href="bloks.php?act=dell_blok&amp;str_blok='.$i.'&amp;l_o_r=left" >Удалить</a>
						</div>

						';

					}
				}
			}else{
				echo'<div>Ошибка</div>';
			}
			echo'</div>';
		}




// Колонка 1
if($info['column1_menu']){
 echo'<table class="tables">
    <tr>
        <td class="tables_head" colspan="2">Доп. колонка 1 <small style="font-weight:normal;"> <code>&lt;?php $Page->get_column(\'column1\'); ?&gt;</code></small></td>
        <td class="tables_head"><a href="bloks.php?act=new_blok&amp;l_o_r=column1" class="button addlink">Добавить</a></td>
    </tr>';
    if(file_exists('../data/bloks/column1_bloks.dat')){
        $blok_data = file('../data/bloks/column1_bloks.dat');
        $nom = count($blok_data);
        if($nom == 0){
            echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td>Блоки ещё не созданы</td><td style="text-align: right;">---</td></tr>';
        }
        for($i = 0; $i < $nom; ++$i){
            $blok_cfg = explode('<||>',$blok_data[$i]);
            $lin = '<a href="bloks.php?act=up_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" title="Переместить блок вверх">Вверх</a> &nbsp; 
                    <a href="bloks.php?act=down_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" title="Переместить блок вниз">Вниз</a> &nbsp; 
                    <a href="bloks.php?act=editor_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" title="Редактировать блок">Редактировать</a> &nbsp; 
                    <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dellblok(\''.$i.'\',\'column1\'));" title="Удалить блок">Удалить</a>';
            if($blok_cfg[1] == 'links'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.' &nbsp; <a href="bloks.php?act=new_link&amp;link_file='.$blok_cfg[0].'&amp;rkt=column1" class="button addlink" title="Добавить ссылку в этот блок">Добавить</a></td></tr>';
                if(file_exists('../data/bloks/links_'.$blok_cfg[0].'.dat')){
                    $link_data = file('../data/bloks/links_'.$blok_cfg[0].'.dat');
                    $nom_0067 = count($link_data);
                    if($nom_0067 == 0){
                        echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; Ссылки еще не созданы</td><td>---</td></tr>';
                    }
                    for($q = 0; $q < $nom_0067; ++$q){
                        $link_cfg = explode('<||>',$link_data[$q]);
                        if($link_cfg[0] == 'page'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }elseif($link_cfg[0] == 'http'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }
                    }
                }else{
                    echo'<tr><td class="img">&nbsp;</td><td> &nbsp; Ошибка</td><td>---</td></tr>';
                }
            }elseif($blok_cfg[1] == 'html'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }elseif($blok_cfg[1] == 'module'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }else{
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;"><a href="bloks.php?act=dell_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" >Удалить</a></td></tr>';
            }
        }
    }else{
        echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;">---</td></tr>';
    }
    echo'</table>';
}


// Колонка 2
if($info['column2_menu']){
 echo'<table class="tables">
    <tr>
        <td class="tables_head" colspan="2">Доп. колонка 2 <small style="font-weight:normal;"> <code>&lt;?php $Page->get_column(\'column2\'); ?&gt;</code></small></td>
        <td class="tables_head"><a href="bloks.php?act=new_blok&amp;l_o_r=column2" class="button addlink">Добавить</a></td>
    </tr>';
    if(file_exists('../data/bloks/column2_bloks.dat')){
        $blok_data = file('../data/bloks/column2_bloks.dat');
        $nom = count($blok_data);
        if($nom == 0){
            echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td>Блоки ещё не созданы</td><td style="text-align: right;">---</td></tr>';
        }
        for($i = 0; $i < $nom; ++$i){
            $blok_cfg = explode('<||>',$blok_data[$i]);
            $lin = '<a href="bloks.php?act=up_blok&amp;str_blok='.$i.'&amp;l_o_r=column2" title="Переместить блок вверх">Вверх</a> &nbsp; 
                    <a href="bloks.php?act=down_blok&amp;str_blok='.$i.'&amp;l_o_r=column2" title="Переместить блок вниз">Вниз</a> &nbsp; 
                    <a href="bloks.php?act=editor_blok&amp;str_blok='.$i.'&amp;l_o_r=column2" title="Редактировать блок">Редактировать</a> &nbsp; 
                    <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dellblok(\''.$i.'\',\'column2\'));" title="Удалить блок">Удалить</a>';
            if($blok_cfg[1] == 'links'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.' &nbsp; <a href="bloks.php?act=new_link&amp;link_file='.$blok_cfg[0].'&amp;rkt=column2" class="button addlink" title="Добавить ссылку в этот блок">Добавить</a></td></tr>';
                if(file_exists('../data/bloks/links_'.$blok_cfg[0].'.dat')){
                    $link_data = file('../data/bloks/links_'.$blok_cfg[0].'.dat');
                    $nom_0067 = count($link_data);
                    if($nom_0067 == 0){
                        echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; Ссылки еще не созданы</td><td>---</td></tr>';
                    }
                    for($q = 0; $q < $nom_0067; ++$q){
                        $link_cfg = explode('<||>',$link_data[$q]);
                        if($link_cfg[0] == 'page'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }elseif($link_cfg[0] == 'http'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }
                    }
                }else{
                    echo'<tr><td class="img">&nbsp;</td><td> &nbsp; Ошибка</td><td>---</td></tr>';
                }
            }elseif($blok_cfg[1] == 'html'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }elseif($blok_cfg[1] == 'module'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }else{
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;"><a href="bloks.php?act=dell_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" >Удалить</a></td></tr>';
            }
        }
    }else{
        echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;">---</td></tr>';
    }
    echo'</table>';
}


// Колонка 3
if($info['column3_menu']){
 echo'<table class="tables">
    <tr>
        <td class="tables_head" colspan="2">Доп. колонка 3 <small style="font-weight:normal;"> <code>&lt;?php $Page->get_column(\'column3\'); ?&gt;</code></small></td>
        <td class="tables_head"><a href="bloks.php?act=new_blok&amp;l_o_r=column3" class="button addlink">Добавить</a></td>
    </tr>';
    if(file_exists('../data/bloks/column3_bloks.dat')){
        $blok_data = file('../data/bloks/column3_bloks.dat');
        $nom = count($blok_data);
        if($nom == 0){
            echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td>Блоки ещё не созданы</td><td style="text-align: right;">---</td></tr>';
        }
        for($i = 0; $i < $nom; ++$i){
            $blok_cfg = explode('<||>',$blok_data[$i]);
            $lin = '<a href="bloks.php?act=up_blok&amp;str_blok='.$i.'&amp;l_o_r=column3" title="Переместить блок вверх">Вверх</a> &nbsp; 
                    <a href="bloks.php?act=down_blok&amp;str_blok='.$i.'&amp;l_o_r=column3" title="Переместить блок вниз">Вниз</a> &nbsp; 
                    <a href="bloks.php?act=editor_blok&amp;str_blok='.$i.'&amp;l_o_r=column3" title="Редактировать блок">Редактировать</a> &nbsp; 
                    <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dellblok(\''.$i.'\',\'column1\'));" title="Удалить блок">Удалить</a>';
            if($blok_cfg[1] == 'links'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.' &nbsp; <a href="bloks.php?act=new_link&amp;link_file='.$blok_cfg[0].'&amp;rkt=column1" class="button addlink" title="Добавить ссылку в этот блок">Добавить</a></td></tr>';
                if(file_exists('../data/bloks/links_'.$blok_cfg[0].'.dat')){
                    $link_data = file('../data/bloks/links_'.$blok_cfg[0].'.dat');
                    $nom_0067 = count($link_data);
                    if($nom_0067 == 0){
                        echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; Ссылки еще не созданы</td><td>---</td></tr>';
                    }
                    for($q = 0; $q < $nom_0067; ++$q){
                        $link_cfg = explode('<||>',$link_data[$q]);
                        if($link_cfg[0] == 'page'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }elseif($link_cfg[0] == 'http'){
                            echo'<tr><td class="img">&nbsp;</td><td><img src="include/link.svg" alt=""> &nbsp; '.$link_cfg[2].'</td><td style="text-align: right;"><a href="bloks.php?act=up_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вверх">Вверх</a> &nbsp; <a href="bloks.php?act=down_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Переместить ссылку вниз">Вниз</a> &nbsp; <a href="bloks.php?act=editor_link&amp;link_file='.$blok_cfg[0].'&amp;str_link='.$q.'" title="Редактировать ссылку">Редактировать</a> &nbsp; <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', delllink(\''.$blok_cfg[0].'\', \''.$q.'\'));"  title="Удалить ссылку">Удалить</a></td></tr>';
                        }
                    }
                }else{
                    echo'<tr><td class="img">&nbsp;</td><td> &nbsp; Ошибка</td><td>---</td></tr>';
                }
            }elseif($blok_cfg[1] == 'html'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }elseif($blok_cfg[1] == 'module'){
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td><b>'.$blok_cfg[2].'</b></td><td style="text-align: right;">'.$lin.'</td></tr>';
            }else{
                echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;"><a href="bloks.php?act=dell_blok&amp;str_blok='.$i.'&amp;l_o_r=column1" >Удалить</a></td></tr>';
            }
        }
    }else{
        echo'<tr><td class="img"><img src="include/blok.svg" alt=""></td><td style="color: red;">Ошибка</td><td style="text-align: right;">---</td></tr>';
    }
    echo'</table>';
}



		echo'</div>';
	}
	
	if($act=='new_blok2'){
		$name_blok = htmlspecialchars(specfilter($_POST['name_blok']));
		$type_blok = htmlspecialchars(specfilter($_POST['type_blok']));
		$l_o_r = htmlspecialchars(specfilter($_POST['l_o_r']));
		$editor = $_POST['editor'];
		
		if($name_blok != '' && $type_blok != '' && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			if($type_blok == 'links'){
				$new_id = time();
				$kod=''.$new_id.'<||>links<||>'.$name_blok.'<||>';
				filefputs('../data/bloks/'.$l_o_r.'_bloks.dat', $kod."\n", 'a+');
				filefputs('../data/bloks/links_'.$new_id.'.dat', '', 'w+');
			}elseif($type_blok == 'html'){
				//if(get_magic_quotes_gpc()){$editor = stripslashes ($editor);}//Удаляем слеши сами если magic_quotes включены
				$new_id = time();
				$kod=''.$new_id.'<||>html<||>'.$name_blok.'<||>';
				filefputs('../data/bloks/'.$l_o_r.'_bloks.dat', $kod."\n", 'a+');
				filefputs('../data/bloks/html_'.$new_id.'.dat', $editor, 'w+');
			}else{
				$kod=''.$type_blok.'<||>module<||>'.$name_blok.'<||>';
				filefputs('../data/bloks/'.$l_o_r.'_bloks.dat', $kod."\n", 'a+');
			}
			echo'<div class="msg">Блок успешно добавлен</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
	
	if($act=='new_blok'){
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">
					<ul class="nav">
						<a class="nav-item" href="bloks.php">&#8592; Вернуться назад</a>
					</ul>
				</div>
			</div>
		</div>
	
		<div class="container">
			<h1>Добавление нового блока</h1>
			<form name="forma" action="bloks.php?" method="post">
				<INPUT TYPE="hidden" NAME="act" VALUE="new_blok2">
				<INPUT TYPE="hidden" NAME="l_o_r" VALUE="'.htmlspecialchars($_GET['l_o_r']).'">
			
				<div class="col-lg-4">
					<div class="mb-3">
						<label class="form-label">Название блока:</label>
						<input class="form-control" type="text" name="name_blok" value="">
					</div>

					<div class="mb-3">
						<label class="form-label">Тип блока:</label>
						
							<SELECT class="form-select" NAME="type_blok" onChange="document.getElementById(\'ed\').style.display = (document.getElementById(\'sel\').selected)?\'\':\'none\';">
							<OPTION VALUE="links" selected>Блок из ссылок на страницы сайта
							<OPTION VALUE="html" id="sel">Блок html кода';
							$listModules = System::listModules();
							foreach($listModules as $value){
								if(Module::isIntegrationBlok($value)){
									$info = Module::info($value);
									echo '<OPTION VALUE="'.$value.'">Модуль "'.$info['name'].' '.$info['version'].'"';
								}
							}
							echo'</SELECT>
						
					</div>

					<div id="ed" style="display: none;">
						<TEXTAREA class="form-control" id="editor" NAME="editor" ROWS="20" COLS="100" class="editor">'.htmlspecialchars('Содержимое блока').'</TEXTAREA>
					</div>

					<div>
						<input class="btn btn-primary mt-2" type="submit" name="" value="Добавить блок">
					</div>
				</div>
			
			</form>
		</div>';
	}

	
	if($act=='dell_blok'){
		$str_blok = htmlspecialchars(specfilter($_GET['str_blok']));
		$l_o_r = htmlspecialchars(specfilter($_GET['l_o_r']));
		
		if(is_numeric($str_blok) && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			$bloks_list = file('../data/bloks/'.$l_o_r.'_bloks.dat');
			$cfg_blok = explode('<||>',$bloks_list[$str_blok]);
			if($cfg_blok[1] == 'links'){ unlink('../data/bloks/links_'.$cfg_blok[0].'.dat'); }
			if($cfg_blok[1] == 'html'){ unlink('../data/bloks/html_'.$cfg_blok[0].'.dat'); }
			//Удаляем из списка
			$nom = count($bloks_list);
			$f = fopen('../data/bloks/'.$l_o_r.'_bloks.dat', 'w+');
			for($i = 0; $i < $nom; ++$i){
				if($str_blok != $i){
					fputs($f,$bloks_list[$i]);
				}
			}
			fclose($f);
			echo'<div class="msg">Блок успешно удален</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
		?>
		<script type="text/javascript">
		setTimeout('window.location.href = \'bloks.php?\';', 1);
		</script>
		<?php
	}
	
	
	if($act=='editor_blok'){
		$str_blok = htmlspecialchars(specfilter($_GET['str_blok']));
		$l_o_r = htmlspecialchars(specfilter($_GET['l_o_r']));
		
		if(is_numeric($str_blok) && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			$bloks_list = file('../data/bloks/'.$l_o_r.'_bloks.dat');
			$cfg_blok = explode('<||>',$bloks_list[$str_blok]);
			echo'
			
			<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">
						<ul class="nav">
							<a class="nav-item" href="bloks.php">&#8592; Вернуться назад</a>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="container">
				<h1>Редактирование блока</h1>
				
				<form name="forma" action="bloks.php?" method="post">
					<INPUT TYPE="hidden" NAME="act" VALUE="editor_blok2">
					<INPUT TYPE="hidden" NAME="str_blok" VALUE="'.$str_blok.'">
					<INPUT TYPE="hidden" NAME="l_o_r" VALUE="'.$l_o_r.'">
					
					<div class="col-lg-4">

						<div class="mb-3">
							<label class="form-label">Название блока:</label>
							<input class="form-control" type="text" name="name_blok" value="'.$cfg_blok[2].'">
						</div>

						<div class="mb-3">
							<label class="form-label">Тип блока:</label>';
							echo'<SELECT class="form-select" NAME="type_blok" onChange="document.getElementById(\'ed\').style.display = (document.getElementById(\'sel\').selected)?\'\':\'none\';">';
							if($cfg_blok[1] == 'links'){
								echo'<OPTION VALUE="links" selected>Блок из ссылок на страницы сайта';
								echo'<OPTION VALUE="html" id="sel">Блок html кода';
							}elseif($cfg_blok[1] == 'html'){
								echo'<OPTION VALUE="links">Блок из ссылок на страницы сайта';
								echo'<OPTION VALUE="html" id="sel" selected>Блок html кода';
							}elseif($cfg_blok[1] == 'module'){
								echo'<OPTION VALUE="links">Блок из ссылок на страницы сайта';
								echo'<OPTION VALUE="html" id="sel">Блок html кода';
								if(!Module::exists($cfg_blok[0])){
									echo'<OPTION VALUE="'.$cfg_blok[0].'" selected> - Подключенный модуль не найден';
								}
							}
					
							$listModules = System::listModules();
							foreach($listModules as $value){
								if(Module::isIntegrationBlok($value)){
									$info = Module::info($value);
									echo '<OPTION VALUE="'.$value.'" '.($cfg_blok[0] == $value?'selected':'').'>Модуль "'.$info['name'].' '.$info['version'].'"';
								}
							}
						
							echo'</SELECT>
						</div>';
						
						if($cfg_blok[1] == 'html'){
							$blok_content = (file_exists('../data/bloks/html_'.$cfg_blok[0].'.dat'))?htmlspecialchars(file_get_contents('../data/bloks/html_'.$cfg_blok[0].'.dat')):'';
						}else{$blok_content = '';}
						echo'
						<div class="mb-3" id="ed" style="display: none;">
							<TEXTAREA class="form-control" id="editor" NAME="editor" ROWS="20" COLS="100" class="editor">'.$blok_content.'</TEXTAREA>
						</div>';
						?>
						<script type="text/javascript">
						document.getElementById('ed').style.display = (document.getElementById('sel').selected)?'':'none';
						</script>
						<?php
						echo'
						<div class="mb-3">
							<input class="btn btn-primary" type="submit" name="" value="Сохранить">
						</div>
					</div
				</form>
			</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
	}
      
      
	if($act=='editor_blok2'){
		$str_blok = htmlspecialchars(specfilter($_POST['str_blok']));
		$name_blok = htmlspecialchars(specfilter($_POST['name_blok']));
		$l_o_r = htmlspecialchars(specfilter($_POST['l_o_r']));
        $type_blok = htmlspecialchars(specfilter($_POST['type_blok']));
        $editor = $_POST['editor'];
		//if(get_magic_quotes_gpc()){$editor = stripslashes ($editor);}//Удаляем слеши сами если magic_quotes включены
		
		if($name_blok != '' && $type_blok != '' && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			$bloks_list = file('../data/bloks/'.$l_o_r.'_bloks.dat');
			$cfg_blok = explode('<||>',$bloks_list[$str_blok]);
			
			//$new_id = ($cfg_blok[1] == 'module')?time():$cfg_blok[0];
			
			if($type_blok == 'links'){
				if(file_exists('../data/bloks/html_'.$cfg_blok[0].'.dat')){ unlink('../data/bloks/html_'.$cfg_blok[0].'.dat');}
				if(file_exists('../data/bloks/links_'.$cfg_blok[0].'.dat')){
					$inset = $cfg_blok[0].'<||>links<||>'.$name_blok.'<||>';
				}else{
					$new_id = time();
					filefputs('../data/bloks/links_'.$new_id.'.dat', '', 'w+');
					$inset = $new_id.'<||>links<||>'.$name_blok.'<||>';
				}
			}elseif($type_blok == 'html'){
				if(file_exists('../data/bloks/links_'.$cfg_blok[0].'.dat')){ unlink('../data/bloks/links_'.$cfg_blok[0].'.dat');}
				if(file_exists('../data/bloks/html_'.$cfg_blok[0].'.dat')){
					filefputs('../data/bloks/html_'.$cfg_blok[0].'.dat', $editor, 'w+');
					$inset = $cfg_blok[0].'<||>html<||>'.$name_blok.'<||>';
				}else{
					$new_id = time();
					filefputs('../data/bloks/html_'.$new_id.'.dat', $editor, 'w+');
					$inset = $new_id.'<||>html<||>'.$name_blok.'<||>';
				}
			}else{
				if(file_exists('../data/bloks/html_'.$cfg_blok[0].'.dat')){ unlink('../data/bloks/html_'.$cfg_blok[0].'.dat');}
				if(file_exists('../data/bloks/links_'.$cfg_blok[0].'.dat')){ unlink('../data/bloks/links_'.$cfg_blok[0].'.dat');}
				$inset = $type_blok.'<||>module<||>'.$name_blok.'<||>';
			}
				
			//Изменяем строку
			$bloks_list[$str_blok] = str_replace($bloks_list[$str_blok],$inset."\n",$bloks_list[$str_blok]);
			
			//перезаписываем файл
			$nom = count($bloks_list);
			$f = fopen('../data/bloks/'.$l_o_r.'_bloks.dat', 'w+');
			for($i = 0; $i < $nom; ++$i){
				fputs($f,$bloks_list[$i]);
			}
			fclose($f);
			
			echo'<div class="msg">Блок успешно изменен</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
      
	if($act=='go_to_blok'){
		$str_blok = htmlspecialchars(specfilter($_GET['str_blok']));
		$l_o_r = htmlspecialchars(specfilter($_GET['l_o_r']));
		
		if(is_numeric($str_blok) && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			$from_l_o_r = ($l_o_r == 'right')?'left':'right';
			//Удаляем блок из списка
			$bloks_list = file('../data/bloks/'.$from_l_o_r.'_bloks.dat');
			$nom = count($bloks_list);
			$f = fopen('../data/bloks/'.$from_l_o_r.'_bloks.dat', 'w+');
			for($i = 0; $i < $nom; ++$i){
				if($str_blok != $i){
					fputs($f,$bloks_list[$i]);
				}
			}
			fclose($f);
			//Записываем новый блок в список
			filefputs('../data/bloks/'.$l_o_r.'_bloks.dat', $bloks_list[$str_blok], 'a+');
			echo'<div class="msg">Блок успешно перенесен</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
	
	if($act=='up_blok'){
		$str_blok = htmlspecialchars(specfilter($_GET['str_blok']));
		$l_o_r = htmlspecialchars(specfilter($_GET['l_o_r']));
		
		if(is_numeric($str_blok) && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			
			$bloks_list = file('../data/bloks/'.$l_o_r.'_bloks.dat');
			$nom = count($bloks_list);
			
			if($str_blok > 0){
				$up_str_blok = $str_blok - 1;
				
				$tmp_str = $bloks_list[$up_str_blok];//Верхнюю строку сохраняем во временную переменную
				$bloks_list[$up_str_blok] = $bloks_list[$str_blok];//Верхнюю строку заменяем на нижнюю
				$bloks_list[$str_blok] = $tmp_str;//нижнюю заменяем на верхнюю, которая была сохранена
				
				//перезаписываем файл
				$f = fopen('../data/bloks/'.$l_o_r.'_bloks.dat', 'w+');
				for($i = 0; $i < $nom; ++$i){
					fputs($f,$bloks_list[$i]);
				}
				fclose($f);
				echo'<div class="msg">Блок успешно перенесен</div>';
			}else{
				echo'<div class="msg">Ошибка, выше переносить нельзя</div>';
			}
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
	
	
	if($act=='down_blok'){
		$str_blok = htmlspecialchars(specfilter($_GET['str_blok']));
		$l_o_r = htmlspecialchars(specfilter($_GET['l_o_r']));
		
		if(is_numeric($str_blok) && ($l_o_r == 'left' || $l_o_r == 'right' || $l_o_r == 'column1'  || $l_o_r == 'column2'  || $l_o_r == 'column3')){
			
			$bloks_list = file('../data/bloks/'.$l_o_r.'_bloks.dat');
			$nom = count($bloks_list);
			
			if($str_blok < ($nom - 1)){
				$down_str_blok = $str_blok + 1;
			
				$tmp_str = $bloks_list[$down_str_blok];//Нижнюю строку сохраняем во временную переменную
				$bloks_list[$down_str_blok] = $bloks_list[$str_blok];//Нижнюю строку заменяем на верхнюю
				$bloks_list[$str_blok] = $tmp_str;//верхнюю заменяем на нижнюю которая была сохранена
				
				//перезаписываем файл
				$f = fopen('../data/bloks/'.$l_o_r.'_bloks.dat', 'w+');
				for($i = 0; $i < $nom; ++$i){
					fputs($f,$bloks_list[$i]);
				}
				fclose($f);
				echo'<div class="msg">Блок успешно перенесен</div>';
			}else{
				echo'<div class="msg">Ошибка, ниже переносить нельзя</div>';
			}
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}

//////////////////////////////////////////////////--Управление ссылками!--////////////////////////////////////////////////////

     
	if($act=='new_link'){
		$link_file = htmlspecialchars(specfilter($_GET['link_file']));
		if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
		?>

		<script type="text/javascript">
		function sethttp(){
		document.getElementById('page').style.display = 'none';
		document.getElementById('http').style.display = '';
		document.getElementById('type_link').value = 'http';
		}
		function setpage(){
		document.getElementById('page').style.display = '';
		document.getElementById('http').style.display = 'none';
		document.getElementById('type_link').value = 'page';
		}
		</script>

		<?php
			echo'

			<div class="header">
				<div class="container">
					<div class="mobile-menu-wrapper">
						<ul class="nav">
							<a class="nav-item" href="bloks.php">&#8592; Вернуться назад</a>
						</ul>
					</div>
				</div>
			</div>

			<div class="container">
				<h1>Создание новой ссылки</h1>
				<form name="forma" action="bloks.php?" method="post">
				<INPUT TYPE="hidden" NAME="act" VALUE="new_link2">
				<INPUT TYPE="hidden" NAME="link_file" VALUE="'.$link_file.'">
				<INPUT TYPE="hidden" NAME="type_link" id="type_link" VALUE="page">

					<div class="col-lg-4">
						<div class="mb-3">
                            <label class="form-label">Название ссылки:</label>
							<input class="form-control" type="text" name="name_link" value="" size="25">
                        </div>
						
						<div class="mb-3" id="page">
							<label class="form-label">Ссылка на:</label>
							<SELECT class="form-select" NAME="page_link">';
							$listPages = System::listPages();
							$listPages = array_reverse($listPages);
							$nom = count($listPages);
							if($nom == 0){echo'<OPTION VALUE="">Страницы ещё не созданы';}
							for($i = 0; $i < $nom; ++$i){
								if(Page::exists($listPages[$i])){
									$Page = new Page($listPages[$i], $Config);
									echo'<OPTION VALUE="'.$listPages[$i].'">'.$Page->name.'';
								}
							}
							echo'
							</SELECT>
							<a href="javascript:void(0);" onclick="sethttp();">Ввести ссылку вручную</a>
						</div>

						<div class="mb-3" id="http" style="display: none;">
							<label class="form-label">Ссылка на:</label>
							<input class="form-control" type="text" name="url_link" value="http://" size="25">
							<a href="javascript:void(0);" onclick="setpage();">Выбрать страницу из списка</a>
						</div>

						<div class="mb-3">
							<label class="form-label">Родительский пункт:</label>
							<SELECT class="form-select" NAME="parent_link">
								<OPTION VALUE="0">-- Основной пункт меню --';
								if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
									$links = file('../data/bloks/links_'.$link_file.'.dat');
									foreach($links as $link){
										$parts = explode('<||>', $link);
										if(trim($parts[3] ?? '') == '0'){
											echo'<OPTION VALUE="'.$parts[1].'">'.$parts[2];
										}
									}
								}
							echo'</SELECT>
						</div>

						<div class="btn-gr">
							<input class="btn btn-primary" type="submit" name="" value="Создать ссылку">
						</div>

					</div>
				</form>
			</div>';
		} else {
			echo'<div class="msg">Запрос неверен</div>';
			?>
			<script type="text/javascript">
			setTimeout('window.location.href = \'bloks.php?\';', 1);
			</script>
			<?php
			}
	}
	
	if($act=='new_link2'){
		$link_file = htmlspecialchars(specfilter($_POST['link_file']));
		$type_link = htmlspecialchars(specfilter($_POST['type_link']));
		$name_link = htmlspecialchars(specfilter($_POST['name_link']));
		$page_link = htmlspecialchars(specfilter($_POST['page_link']));
		$url_link = htmlspecialchars(specfilter($_POST['url_link']));
		$parent_link = htmlspecialchars(specfilter($_POST['parent_link']));
		
		if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
			$level = ($parent_link == '0') ? 0 : 1;
			$order = 0; // Порядок по умолчанию
			
			if($type_link == 'page'){
				$inset = 'page<||>'.$page_link.'<||>'.$name_link.'<||>'.$level.'<||>'.$parent_link.'<||>'.$order;
			} elseif ($type_link == 'http'){
				$inset = 'http<||>'.$url_link.'<||>'.$name_link.'<||>'.$level.'<||>'.$parent_link.'<||>'.$order;
			} else {
				echo'<div class="msg">Ошибка</div>';
			}
			
			filefputs('../data/bloks/links_'.$link_file.'.dat', $inset."\n", 'a+');
			echo'<div class="msg">Ссылка успешно создана</div>';
		} else {
			echo'<div class="msg">Ошибка</div>';
		} ?>
		<script type="text/javascript">
		setTimeout('window.location.href = \'bloks.php?\';', 1);
		</script>
		<?php
	}

	
	
	if($act=='dell_link'){
		$link_file = htmlspecialchars(specfilter($_GET['link_file']));
		$str_link = htmlspecialchars(specfilter($_GET['str_link']));
		if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
			$links_list = file('../data/bloks/links_'.$link_file.'.dat');
			$nom = count($links_list);
			$f = fopen('../data/bloks/links_'.$link_file.'.dat', 'w+');
			for($i = 0; $i < $nom; ++$i){
				if($str_link != $i){
					fputs($f,$links_list[$i]);
				}
			}
			fclose($f);
			echo'<div class="msg">Ссылка успешно удалена</div>';
		}else{
			echo'<div class="msg">Ошибка</div>';
		}?>
		<script type="text/javascript">
		setTimeout('window.location.href = \'bloks.php?\';', 1);
		</script>
		<?php
	}
	
	
	if($act=='editor_link'){
		$link_file = htmlspecialchars(specfilter($_GET['link_file']));
		$str_link = htmlspecialchars(specfilter($_GET['str_link']));
		if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
		$links_list = file('../data/bloks/links_'.$link_file.'.dat');
		$cfg_link = explode('<||>',$links_list[$str_link]);
		echo'

		<div class="header">
			<div class="container">
				<div class="mobile-menu-wrapper">
					<ul class="nav">
						<a class="nav-item" href="bloks.php">&#8592; Вернуться назад</a>
					</ul>
				</div>
			</div>
		</div>

		<div class="container">
			<h1>Редактирование ссылки</h1>
				<form name="forma" action="bloks.php?" method="post">
				<INPUT TYPE="hidden" NAME="act" VALUE="editor_link2">
				<INPUT TYPE="hidden" NAME="link_file" VALUE="'.$link_file.'">
				<INPUT TYPE="hidden" NAME="str_link" VALUE="'.$str_link.'">
				<INPUT TYPE="hidden" NAME="type_link" id="type_link" VALUE="'.$cfg_link[0].'">

				<div class="col-lg-4">

					<div class="mb-3">
						<label class="form-label">Название ссылки:</label>
						<input class="form-control" type="text" name="name_link" value="'.$cfg_link[2].'" size="25">
					</div>';

					// Блок выбора страницы или URL
					if($cfg_link[0] == 'page') {
						echo'
						<div class="mb-3" id="page">
							<label class="form-label">Ссылка на:</label>
							<SELECT class="form-select" NAME="page_link">';
							$listPages = System::listPages();
							$listPages = array_reverse($listPages);
							$nom = count($listPages);
							if($nom == 0){echo'<OPTION VALUE="">Страницы ещё не созданы';}
							for($i = 0; $i < $nom; ++$i){
								if(Page::exists($listPages[$i])){
									$Page = new Page($listPages[$i], $Config);
									$selected = ($cfg_link[1] == $listPages[$i])?'selected':'';
									echo'<OPTION VALUE="'.$listPages[$i].'" '.$selected.'>'.$Page->name.'';
								}
							}
						echo'</SELECT>
							
						</div>

						<div class="mb-3" id="http" style="display:none;">
							<label class="form-label">Ссылка на:</label>
							<input class="form-control" type="text" name="url_link" value="http://" size="25">
							<a href="javascript:void(0);" onclick="setpage();">Выбрать страницу из списка</a>
						</div>';

						} else {

						echo'
						<div class="mb-3" id="page" style="display:none;">
							<label class="form-label">Ссылка на:</label>
							<SELECT class="form-select" NAME="page_link">';
							$listPages = System::listPages();
							foreach($listPages as $page){
								if(Page::exists($page)){
									$Page = new Page($page, $Config);
									echo'<OPTION VALUE="'.$page.'">'.$Page->name.'';
								}
							}
						echo'</SELECT>
							<a href="javascript:void(0);" onclick="sethttp();">Ввести ссылку вручную</a>
						</div>

						<div class="mb-3" id="http">
							<label class="form-label">Ссылка на:</label>
							<input class="form-control" type="text" name="url_link" value="'.$cfg_link[1].'">
							
						</tr>';
					}

					// Блок выбора родителя
					echo'
					<div class="mb-3">
						<label class="form-label">Родительский пункт:</label>
						<SELECT class="form-select" NAME="parent_link">
							<OPTION VALUE="0" '.(($cfg_link[3] == '0') ? 'selected' : '').'>-- Основной пункт меню --';
							
							if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
								$all_links = file('../data/bloks/links_'.$link_file.'.dat');
								foreach($all_links as $link){
									$parts = explode('<||>', $link);
									if(trim($parts[3] ?? '') == '0' && $parts[1] != $cfg_link[1]){ // Только пункты 1 уровня
										$selected = (isset($cfg_link[4]) && trim($cfg_link[4]) == trim($parts[1])) ? 'selected' : '';
										echo'<OPTION VALUE="'.$parts[1].'" '.$selected.'>'.$parts[2];
									}
								}
							}
						echo'</SELECT>
					</div>

					<div class="btn-gr">
						<input class="btn btn-primary" type="submit" name="" value="Сохранить">
					</div>

				</div>

			</form>
		</div>';


			if($cfg_link[0] == 'page'){
			?>
			<script type="text/javascript">
			document.getElementById('page').style.display = '';
			document.getElementById('http').style.display = 'none';
			</script>
			<?php
			}
			if($cfg_link[0] == 'http'){
			?>
			<script type="text/javascript">
			document.getElementById('page').style.display = 'none';
			document.getElementById('http').style.display = '';
			</script>
			<?php
			}

		} else { echo'<div class="msg">Ошибка</div>'; }
	}

if($act=="editor_link2"){
    $link_file = htmlspecialchars(specfilter($_POST['link_file']));
    $str_link = htmlspecialchars(specfilter($_POST['str_link']));
    $type_link = htmlspecialchars(specfilter($_POST['type_link']));
    $name_link = htmlspecialchars(specfilter($_POST['name_link']));
    $page_link = htmlspecialchars(specfilter($_POST['page_link']));
    $url_link = htmlspecialchars(specfilter($_POST['url_link']));
    $parent_link = htmlspecialchars(specfilter($_POST['parent_link']));
    
    if(file_exists('../data/bloks/links_'.$link_file.'.dat')){
        $links_list = file('../data/bloks/links_'.$link_file.'.dat');
        
        $level = ($parent_link == '0') ? 0 : 1;
        $order = isset($links_list[$str_link]) ? 
                 (($tmp = explode('<||>', $links_list[$str_link])) && isset($tmp[5]) ? trim($tmp[5]) : '0') : '0';
        
        if($type_link == 'page'){
            $inset = 'page<||>'.$page_link.'<||>'.$name_link.'<||>'.$level.'<||>'.$parent_link.'<||>'.$order;
        }elseif($type_link == 'http'){
            $inset = 'http<||>'.$url_link.'<||>'.$name_link.'<||>'.$level.'<||>'.$parent_link.'<||>'.$order;
        }
        
        // Прямая замена строки по индексу вместо str_replace
        if(isset($links_list[$str_link])) {
            $links_list[$str_link] = $inset."\n";
        }
        
        //перезаписываем файл
        $f = fopen('../data/bloks/links_'.$link_file.'.dat', 'w+');
        foreach($links_list as $line) {
            if(trim($line) != '') { // Пропускаем пустые строки
                fputs($f, $line);
            }
        }
        fclose($f);
        echo'<div class="msg">Ссылка успешно изменена</div>';
    }else{
        echo'<div class="msg">Ошибка</div>';
    }
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}



	if($act=='up_link'){
		$link_file = htmlspecialchars(specfilter($_GET['link_file']));
		$str_link = htmlspecialchars(specfilter($_GET['str_link']));
		if(is_numeric($str_link)){
			$links_list = file('../data/bloks/links_'.$link_file.'.dat');
			$nom = count($links_list);
			if($str_link > 0){
				$up_str_link = $str_link - 1;
				$tmp_str = $links_list[$up_str_link];//Верхнюю строку сохраняем в временную переменную
				$links_list[$up_str_link] = $links_list[$str_link];//Верхнюю строку заменяем на нижнюю
				$links_list[$str_link] = $tmp_str;//нижнюю заменяем на верхнюю которая была сохранена
				//перезаписываем файл
				$f = fopen('../data/bloks/links_'.$link_file.'.dat', 'w+');
				for($i = 0; $i < $nom; ++$i){
					fputs($f,$links_list[$i]);
				}
				fclose($f);
				echo'<div class="msg">Ссылка успешно перенесена</div>';
			}else{
				echo'<div class="msg">Ошибка</div>';
			}
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
	
	if($act=='down_link'){
		$link_file = htmlspecialchars(specfilter($_GET['link_file']));
		$str_link = htmlspecialchars(specfilter($_GET['str_link']));
		if(is_numeric($str_link)){
			$links_list = file('../data/bloks/links_'.$link_file.'.dat');
			$nom = count($links_list);
			if($str_link < ($nom - 1)){
				$down_str_link = $str_link + 1;
				$tmp_str = $links_list[$down_str_link];//Нижнюю строку сохраняем во временную переменную
				$links_list[$down_str_link] = $links_list[$str_link];//Нижнюю строку заменяем на верхнюю
				$links_list[$str_link] = $tmp_str;//верхнюю заменяем на нижнюю, которая была сохранена
				//перезаписываем файл
				$f = fopen('../data/bloks/links_'.$link_file.'.dat', 'w+');
				for($i = 0; $i < $nom; ++$i){
					fputs($f,$links_list[$i]);
				}
				fclose($f);
				echo'<div class="msg">Ссылка успешно перенесена</div>';
			}else{
				echo'<div class="msg">Ошибка</div>';
			}
		}else{
			echo'<div class="msg">Ошибка</div>';
		}
?>
<script type="text/javascript">
setTimeout('window.location.href = \'bloks.php?\';', 1);
</script>
<?php
	}
	

}else{
echo'<div class="msg">Необходимо выполнить авторизацию</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'index.php?\';', 1);
</script>
<?php
}

// Г.код из 2007
// Пишите на support@my-engine.ru ;)

require('include/end.dat');
?>