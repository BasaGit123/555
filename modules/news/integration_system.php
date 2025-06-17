<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

require(DR.'/modules/news/cfg.php');

function listIdCat($array, $cat){ // аналог array_keys 
	$return = array();
	foreach($array as $key => $value){
		if(is_array($value)){
			if(in_array($cat, $value) || $cat == '' && count($value) == 0){
				$return[] = $key;
			}
		}else{
			if($value == $cat){ // Для новостей созданных на <=5.1.38
				$return[] = $key;
			}
		}
	}
	return $return;
}

function NewsIdCat($value){ // фикс для совместимости // возвращает первый элемент, если тип массив
		if(is_array($value)){
			if(isset($value[0])){
				$return = $value[0];
			}else{
				$return = '';
			}
		}else{
			$return = $value;
		}
	return $return;
}

function NewsGenHtmlCat($arr, $length = 0){
	global $URI, $Page;
	$return = '';
	if(is_array($arr)){
		if($length){
			$arr = array_slice($arr, 0, $length);
		}
		if(count($arr) == 0){
			$return.= '<a href="'.($Page->isIndexPage()?'/':'/'.$URI[1]).'">Без категории</a>';
		}
		foreach($arr as $value){
			$return.= '<a href="/'.$URI[1].'/'.$value.'">'.NewsCategoryName($value).'</a> ';
		}
	}else{
		if($arr == ''){
			$return.= '<a href="'.($Page->isIndexPage()?'/':'/'.$URI[1]).'">Без категории</a>';
		}else{
			$return.= '<a href="/'.$URI[1].'/'.$arr.'">'.NewsCategoryName($arr).'</a>';
		}
	}
	return $return;
}



function NewsBBCode($html){
	$html = trim($html[1]);
	$html = str_replace("\t",'&nbsp;&nbsp;&nbsp;',$html);
	$html = str_replace('  ',' &nbsp;',$html);
	$html = preg_replace('/&quot;(.*?)&quot;/', '<span class="quot">&quot;\1&quot;</span>', $html);
	$html = preg_replace('/\'(.*?)\'/', '<span class="quot">\'\1\'</span>', $html);
	$html = str_replace("\n",'<br>', $html);
	$html = specfilter($html);
	return '<pre><code>'.$html.'</code></pre>';
}

function NewsFormatText($text){
	$text = preg_replace_callback('#\[code\](.*?)\[/code\]#si', 'NewsBBCode', $text);
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<span style="font-weight: bold;">\1</span>', $text);
	$text = preg_replace('#\[red\](.*?)\[/red\]#si', '<span style="color: #E53935;">\1</span>', $text);
	$text = '<p>'.str_replace("\n",'</p><p>', trim($text)).'</p>';
	$text = specfilter($text);
	$text = str_replace('<p></p>', '', $text);
	return $text;
}

function NewsCategoryName($id){
	global $newsConfig;
	$return = false;
	foreach($newsConfig->cat as $key => $value){
		if ($id == $key){
			$return = $value;
		}
	}
	return $return;
}

function NewsCategory($cat, $col, $tpl = false, $tplNoNews = false, $start = false, $sort = 'reverse'){
	global $Config, $Page, $Snippet, $newsStorage, $newsConfig;
	$return = '';
	if($tpl == false){
		$tpl = $newsConfig->blokTemplate;
	}
	if($tplNoNews == false){
		$tplNoNews = '<p>Записей пока нет</p>';
	}
	if($cat || $cat === ''){
		$listIdCat = json_decode($newsStorage->get('category'), true);
		$listIdNews = listIdCat($listIdCat, $cat); // аналог array_keys 
		
	}else{
		$listIdNews = json_decode($newsStorage->get('list'), true); 
	}
	if($listIdNews == false){
		$return.= $tplNoNews;
	}else{
		if($sort == 'reverse'){
			//перевернули масив для вывода новостей в обратном порядке
			$listIdNews = array_reverse($listIdNews);
		}
		if($sort == 'random'){
			shuffle($listIdNews);
		}
		if(!$start){
			$start = 0;
		}
		for($i = 0 + $start; $i < $col + $start; ++$i){
			if(isset($listIdNews[$i])){
				$newsParam = json_decode($newsStorage->get('news_'.$listIdNews[$i]));
			}else{
				$newsParam = false;
			}
			if ($newsParam != false){
				$CanPage = new Page($newsConfig->idPage, $Config);
				$canPageName = $CanPage->name;
			
				if(!isset($newsParam->cat)){
					$newsParam->cat = '';
				}
				$newsIdCat = NewsIdCat($newsParam->cat);

				$categoryname = NewsCategoryName($newsIdCat);
				if(!$categoryname) $categoryname = 'Без категории';
				$categoryuri = $newsIdCat != ''?'/'.$newsConfig->idPage.'/'.$newsIdCat:($Config->indexPage == $newsConfig->idPage?'/':'/'.$newsConfig->idPage);

				$out_prev = str_replace('#content#', $newsParam->prev, $tpl);
				$out_prev = str_replace('#header#', $newsParam->header, $out_prev);
				$out_prev = str_replace('#canpagename#', $canPageName, $out_prev);
				$out_prev = str_replace('#date#', date($newsConfig->formatDate, isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)), $out_prev);
				$out_prev = str_replace('#time#', date('H:i', isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)), $out_prev);
				$out_prev = str_replace('#com#', $newsStorage->iss('count_'.$listIdNews[$i])?$newsStorage->get('count_'.$listIdNews[$i]):0, $out_prev);
				$out_prev = str_replace('#img#', $newsParam->img, $out_prev);
				$out_prev = str_replace('#categoryname#', $categoryname, $out_prev);
				$out_prev = str_replace('#categoryuri#', $categoryuri, $out_prev);
				$out_prev = str_replace('#category1#', NewsGenHtmlCat($newsParam->cat, 1), $out_prev);
				$out_prev = str_replace('#category2#', NewsGenHtmlCat($newsParam->cat, 2), $out_prev);
				$out_prev = str_replace('#category3#', NewsGenHtmlCat($newsParam->cat, 3), $out_prev);
				$out_prev = str_replace('#category4#', NewsGenHtmlCat($newsParam->cat, 4), $out_prev);
				$out_prev = str_replace('#category#', NewsGenHtmlCat($newsParam->cat), $out_prev);
				$out_prev = str_replace('#uri#', '/'.$newsConfig->idPage.'/'.$listIdNews[$i], $out_prev);
				$out_prev = str_replace('#home#','/'.($newsConfig->idPage != $Config->indexPage?$newsConfig->idPage:''), $out_prev);
				$out_prev = str_replace('#index#', $i, $out_prev);

				if(Module::exists('snippets')){
					foreach($Snippet as $key => $value){
						$out_prev = str_replace('#'.$key.'#', $value, $out_prev);
					}
				}
				foreach($newsConfig->custom as $value){
					$out_prev = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_prev);
				}
				if(Module::exists('snippets')){
					foreach($Snippet as $key => $value){
						$out_prev = str_replace('#'.$key.'#', $value, $out_prev);
					}
				}
				foreach($newsConfig->custom as $value){
					$out_prev = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_prev);
				}

				$return.= $out_prev;
			}
		}
	}
	return $return;
}
?>