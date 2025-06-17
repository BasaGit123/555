<?php
$GalleryStorage = new EngineStorage('module.gallery');
$gallery_dir = $GalleryStorage->iss('gallery_dir')?$GalleryStorage->get('gallery_dir'):'/files';
// $gallery_min_w='200'; // Ширина миниатюры
// $gallery_min_h='150'; // Высота миниатюры

if($GalleryStorage->get('fancybox') == '1'){ $page->headhtml.= '

<!-- Add jQuery local library -->
<script type="text/javascript" src="/modules/gallery/jquery-3.7.1.min.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/modules/gallery/fancybox-master/dist/jquery.fancybox.min.css" type="text/css">
<script src="/modules/gallery/fancybox-master/dist/jquery.fancybox.min.js"></script>
'; }
$page->headhtml.= '
<!--Включили стили для вывода превьюшек-->
<link rel="StyleSheet" type="text/css" href="/modules/gallery/style.css">
';



$return = '';
$return.= '<div class="mygallery">';
$gchdir = '';
if(isset($URI[2])){
	$return.= '<div class="gallery_navigat">';
	$return.= '<a href="/'.$URI[1].'">'.$page->name.'</a>';
	$new_dir_url= array();
	foreach($URI as $k => $v){
		if($k != 0 && $k != 1){
			
			$new_dir_url[] = htmlspecialchars($v);
			$gchdir = implode('/', $new_dir_url);
			$path = md5($gallery_dir.'/'.$gchdir);
			$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$v;
			$return.= ' / <a href="/'.$URI[1].'/'.$gchdir.'">'.$name.'</a>';
		}
	}
	$return.= '</div>';
}
		
		
if($gchdir != '') {
	$gchdir = '/'.str_replace('..','',$gchdir);
	$gallery_dir = $gallery_dir.$gchdir;
}
		

if(file_exists(DR.$gallery_dir)){
	
	if(isset($URI[2])){
		$path = md5($gallery_dir);
		$Page->name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):basename($gallery_dir);
	}

	$arr_dir = scandir(DR.$gallery_dir);
	$arr_img = array();
	$arr_folder = array();
	foreach($arr_dir as $value){
		if(is_dir(DR.$gallery_dir.'/'.$value)){
			if($value != '.' && $value != '..') $arr_folder[] = $value;
		}elseif(preg_match('/^[a-z0-9]+([\._-][a-z0-9]+)*\.(jpg|jpeg|png|gif)+$/i', $value)){
			$arr_img[$value] = filemtime(DR.$gallery_dir.'/'.$value);
		}
	}
	
	sort($arr_folder);
	if($GalleryStorage->get('gallery_sort') == 'arsort'){
		arsort($arr_img);
	}else{
		asort($arr_img);
	}
	
	
	$arr_folder_count = count($arr_folder);
	$arr_img_count = count($arr_img);
	
	if($arr_folder_count == 0 && $arr_img_count == 0){
		$return.= '<div class="error">Нет изображений или папок для вывода</div>';
	}
	
	if($arr_folder_count != 0){
		$return.= '<div class="gallery_list_folder">';
		foreach($arr_folder as $value){
			
			$path = md5($gallery_dir.'/'.$value);
			$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$value;

			$return.= '<div class="folder">
			<a href="/'.$URI[1].$gchdir.'/'.$value.'" title="'.$name.'">
			<img src="/modules/gallery/folder.png" alt="'.$name.'">
			<span>'.$name.'</span>
			</a>
			</div>';
		}
		$return.= '</div>';
	}
	
	if($arr_img_count != 0){
		$return.= '<div class="gallery_list_img">';
		foreach($arr_img as $key => $value){
			$path = md5($gallery_dir.'/'.$key);
			$name = $GalleryStorage->iss('name.'.$path)?$GalleryStorage->get('name.'.$path):$key;

			$img_url_full = $gallery_dir.'/'.$key;
			
			if(file_exists('./modules/gallery/data/'.$key)){
				$img_url_min = '/modules/gallery/data/'.$key;
			}else{
				$img_url_min = $img_url_full;
			}
			$return.= '
			<a href="'.$img_url_full.'" data-fancybox="gallery" class="fancybox" title="'.$name.'"><img src="'.$img_url_min.'" alt="'.$name.'" title="'.$name.'"></a>
			';
		}
		$return.= '</div>';
	}
}else{
	header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
}
$return.= '</div>';
return $return;
?>