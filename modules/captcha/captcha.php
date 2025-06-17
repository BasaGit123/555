<?php
require('../../system/global.dat');

Error_Reporting(0);

// создаем картинку размером 150X50
$img = imagecreatetruecolor(150, 50);
imagefill($img, 0, 0, imagecolorallocate($img, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255)));

// Формируем фон (Элипсы)
for($i = 0; $i < 10; ++$i){
	imagefilledellipse($img, mt_rand(0, 150),mt_rand(0, 50), mt_rand(25, 100),mt_rand(25, 100), 
	imagecolorallocate($img, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255)));
}

// фильтр размытия 
imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);

// Формируем символы на картинке
$str = str_split('abdhksxyz23456789');//Переменная для формировки цифр на картинке
$rezult = '';
$x=12;//Начальная координата оси X для текста
for($i = 0; $i < 5; ++$i){//выводим одну цифру за один проход цикла (всего 5 цифр)
	$size = mt_rand(30,35);  // размер шрифта в пикселях
	$angle = mt_rand(-10,10); // угол поворота текста
	$y = 35+mt_rand(0,5); // координата y, соответствующие левому нижнему
	$color = imagecolorallocate($img, mt_rand(0,80), mt_rand(0,80), mt_rand(0,80));  // цвет шрифта
	$fontfile = dirname(__FILE__).'/font1.ttf'; // имя файла со шрифтом
	$rnd = $str[mt_rand(0,count($str)-1)]; // Случайный символ

	imagettftext($img, $size, $angle, $x, $y, $color, $fontfile, $rnd);
	//imagechar($img, 5, $x, $y, $rnd, $color);

	$x += 25;//увеличили отступ для следующего символа
	$rezult.= $rnd;// Собираем в одну строку все символы на картинке
}

// Сохраняем куку
setcookie('captcha',md5($rezult.$Config->ticketSalt),0,'/');

//Тип содержимого – картинка формата PNG 
header('Content-type: image/png');
imagepng($img);// выводим готовую картинку в формате PNG
imagedestroy($img);// освобождаем память, выделенную для картинки
?>