<?php
if (!class_exists('System')) exit; // Запрет прямого доступа
$return = '';
if(Module::exists('news')){
	require(DR.'/modules/news/cfg.php');
	$listIdCat = json_decode($newsStorage->get('category'), true);
	$listIdAll = json_decode($newsStorage->get('list'), true);
	foreach($newsConfig->cat as $key => $value){
		$return.= '<div class="link link_category"><a href="/'.$newsConfig->idPage.'/'.$key.'">'.$value.' <span>('.(count(listIdCat($listIdCat, $key))).')</span></a></div>';
	}
	if($newsConfig->indexCat == 0){
		$return.= '<div class="link link_category link_category_all"><a href="/'.($newsConfig->idPage != $Config->indexPage?$newsConfig->idPage:'').'">Все записи <span>('.count($listIdAll).')</span></a></div>';
	}
}else{
	$return.= '<p>Модуль news не найден</p>';
}
return $return;
?>