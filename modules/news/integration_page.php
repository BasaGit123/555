<?php
if (!class_exists('System')) exit; // Запрет прямого доступа


// rss для Дзена
if($MODULE_URI == '/rss.xml'){
header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
	xmlns:yandex="http://news.yandex.ru"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:georss="http://www.georss.org/georss">
    <channel>
		<title>'.$Config->header.'</title>
		<atom:link href="'.$Config->protocol.'://'.SERVER.'/'.$URI[1].'/rss.xml" rel="self" type="application/rss+xml" />
        <link>'.$Config->protocol.'://'.SERVER.($Page->isIndexPage()?'':'/'.$URI[1]).'</link>
        <description>'.$Page->description.'</description>
		<language>ru</language>';
		if(($listIdNews = json_decode($newsStorage->get('list'), true)) != false){
			//перевернули масив для вывода новостей в обратном порядке
			$listIdNews = array_reverse($listIdNews);

			for($i = 0; $i < 10; $i++){
				if(isset($listIdNews[$i])){
					if($newsStorage->iss('news_'.$listIdNews[$i])){
						$newsParam = json_decode($newsStorage->get('news_'.$listIdNews[$i]));

						$out_content = $newsParam->content;
						if(Module::exists('snippets')){
							foreach($Snippet as $key => $value){
								$out_content = str_replace('#'.$key.'#', $value, $out_content);
							}
						}
						foreach($newsConfig->custom as $value){
							$out_content = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_content);
						}
						if(Module::exists('snippets')){
							foreach($Snippet as $key => $value){
								$out_content = str_replace('#'.$key.'#', $value, $out_content);
							}
						}
						foreach($newsConfig->custom as $value){
							$out_content = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_content);
						}

						$out_prev = $newsParam->prev;
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
		echo'
		<item>
			<title>'.$newsParam->header.'</title>
			<link>'.$Config->protocol.'://'.SERVER.'/'.$URI[1].'/'.$listIdNews[$i].'</link>
			<guid>'.$Config->protocol.'://'.SERVER.'/'.$URI[1].'/'.$listIdNews[$i].'</guid>
			<media:rating scheme="urn:simple">nonadult</media:rating>
			<pubDate>'.date("D, d M Y H:i:s O", isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)).'</pubDate>
			<enclosure url="'.$Config->protocol.'://'.SERVER.$newsParam->img.'" type="image/jpeg" length="'.filesize(DR.'/'.$newsParam->img).'"/>
			<description>
				<![CDATA['.trim(strip_tags($out_prev)).']]>
			</description>
			<content:encoded>
				<![CDATA['.trim($out_content).']]>
			</content:encoded>
			<yandex:full-text>
				<![CDATA['.trim(strip_tags($out_content)).']]>
			</yandex:full-text>
		</item>';
					}
				}
			}
		}
echo'
	</channel>
</rss>';
ob_end_flush(); exit;
}




// Турбо страницы с разбитием
// Удалено 5.1.40


// Обработка ajax
if(isset($URI[3]) && isset($URI[4])){
	if($URI[3] == 'ajax'){
		header("Cache-Control: no-store, no-cache, must-revalidate");// не даем кешировать ajax тупым браузерам (IE)
		switch ($URI[4]) {
			
			case 'newcommentcheck':
				if($newsStorage->iss('count_'.$URI[2])){
					echo $newsStorage->get('count_'.$URI[2]);
				}else{
					echo 0;
				}
				break;
			
			case 'addcomment':
				// if (md5($_POST['ticket'].$Config->ticketSalt) != $_COOKIE['ticket_'.$URI[2]]){
				// 	echo'Ticket';
				// }
				if (parse_url(REFERER, PHP_URL_HOST) != SERVER){
					echo'Ticket';
				}elseif($User->authorized){
					
					if($newsConfig->commentRules > 1 && $User->preferences == 0){
						// ошибка если нехватает префов
						echo'Error';
					}else{
						// Обрабатываем форму от авторизированных
						
						if($newsStorage->iss('news_'.$URI[2])){
							
								// Обрабатываем форму от пользователя
								$textForm = trim(htmlspecialchars($_POST['text']));
								
								if(strlen($textForm) <= $newsConfig->commentMaxLength && strlen($textForm) > 0){
									
									
									$idComment = $newsStorage->iss('idComment')?$newsStorage->get('idComment'):0;
									++$idComment;
									$newsStorage->set('idComment', $idComment);
									
									
									if($newsConfig->commentModeration == 0){$published = 1;}
									elseif($newsConfig->commentModeration == 1){$published = ($User->numPost >= $newsConfig->commentModerationNumPost)?1:0;}
									elseif($newsConfig->commentModeration == 2){$published = ($User->preferences > 0)?1:0;}
									else{$published = 0;}
									
									if ($published){
										$arrayComments = json_decode($newsStorage->get('comments_'.$URI[2]), true);
										$arrayComments[] = array(
													'id' => $idComment,
													'login' => $User->login,
													'text' => $textForm,
													'ip' => IP,
													'status' => 'user',
													'time' => time());
										
										$arrayCount = count($arrayComments);
										if($arrayCount >= $newsConfig->commentMaxCount){
											$arrayStart = $arrayCount -  round($newsConfig->commentMaxCount / 1.5);
											$arrayComments = array_slice($arrayComments, $arrayStart, $arrayCount);
										}
										
										if($newsStorage->set('comments_'.$URI[2], json_encode($arrayComments, JSON_FLAGS))){
											
											++$User->numPost;
											$User->save();
											
											$count = $newsStorage->iss('count_'.$URI[2])?$newsStorage->get('count_'.$URI[2]):0;
											++$count;
											$newsStorage->set('count_'.$URI[2], $count);
											
											echo $count;
											
										}else{
											echo'Error';
										}
										unset($arrayComments);
										
									}else{
										echo'Moderation';
									}
									
									
									
									
									
									
									// в список последних 
									$lastComments = json_decode($newsStorage->get('lastComments'), true);
									$lastComments[] = array(
												'idComment' => $idComment,
												'idNews' => $URI[2],
												'login' => $User->login,
												'text' => $textForm,
												'ip' => IP,
												'status' => 'user',
												'published' => $published,
												'time' => time());
									$newsStorage->set('lastComments', json_encode($lastComments, JSON_FLAGS));
									
									
								}else{
									echo'Error';
								}
							
						}else{
							echo'Error';
						}
					}
				}else{
					if($newsStorage->iss('news_'.$URI[2])){
						if(array_search(IP, $Config->ipBan)){
							echo'Ban';
						}elseif($newsConfig->commentRules > 0){
							// ошибка необходимости авторизироваться
							echo'Error';
						}else{
							// Обрабатываем форму от гостей
							$loginForm = htmlspecialchars(specfilter($_POST['login']));
							$textForm = trim(htmlspecialchars($_POST['text']));
							
							if (md5(strtolower($_POST['captcha']).$Config->ticketSalt) != $_COOKIE['captcha']){
								echo'Captcha';
							}elseif(System::validPath($loginForm) && strlen($loginForm) < 36 && strlen($textForm) <= $newsConfig->commentMaxLength && strlen($textForm) > 0){
								if (User::exists($loginForm)){
									echo'Exists';
								}else{
									
									
									$idComment = $newsStorage->iss('idComment')?$newsStorage->get('idComment'):0;
									++$idComment;
									$newsStorage->set('idComment', $idComment);
									
									
									
									$published = ($newsConfig->commentModeration == 0)?1:0;
									
									
									if ($published){
										$arrayComments = json_decode($newsStorage->get('comments_'.$URI[2]), true);
										$arrayComments[] = array(
													'id' => $idComment,
													'login' => $loginForm,
													'text' => $textForm,
													'ip' => IP,
													'status' => 'gost',
													'time' => time());
										
										$arrayCount = count($arrayComments);
										if($arrayCount >= $newsConfig->commentMaxCount){
											$arrayStart = $arrayCount -  round($newsConfig->commentMaxCount / 1.5);
											$arrayComments = array_slice($arrayComments, $arrayStart, $arrayCount);
										}
										
										if($newsStorage->set('comments_'.$URI[2], json_encode($arrayComments, JSON_FLAGS))){
											
											$count = $newsStorage->iss('count_'.$URI[2])?$newsStorage->get('count_'.$URI[2]):0;
											++$count;
											$newsStorage->set('count_'.$URI[2], $count);
											echo $count;
											
										}else{
											echo'Error';
										}
										unset($arrayComments);
										
									}else{
										echo'Moderation';
									}
									
									
									// в список последних 
									$lastComments = json_decode($newsStorage->get('lastComments'), true);
									$lastComments[] = array(
												'idComment' => $idComment,
												'idNews' => $URI[2],
												'login' => $loginForm,
												'text' => $textForm,
												'ip' => IP,
												'status' => 'gost',
												'published' => $published,
												'time' => time());
									$newsStorage->set('lastComments', json_encode($lastComments, JSON_FLAGS));
									
								}
							}else{
								echo'Error';
							}
							setcookie('captcha','',time(),'/');// Обнулили куки
						}
					}else{
						echo'Error';
					}
				}
				break;
				
			case 'validlogin':
				if (System::validPath($_POST['login'])){
					if (User::exists($_POST['login'])){
						echo $_POST['login'].' уже существует';
					}
				}else{
					echo 'Недопустимые символы';
				}
				break;
			
			
			
			case 'dellcomments':
				// if (md5($_POST['ticket'].$Config->ticketSalt) != $_COOKIE['ticket_'.$URI[2]]){
				// 	echo'Ticket';
				// }
				if (parse_url(REFERER, PHP_URL_HOST) != SERVER){
					echo'Ticket';
				}elseif($newsStorage->iss('news_'.$URI[2]) && ($User->preferences > 0 || $status == 'admin')){
					$arrayComments = json_decode($newsStorage->get('comments_'.$URI[2]), true);
					$count = 0;
					
					// foreach($_POST['comment'] as $value){
						// if(isset($arrayComments[$value])){
							// unset($arrayComments[$value]);
							// ++$count;
						// }
					// }
					
					foreach($arrayComments as $i => $row){
						if (in_array($row['id'], $_POST['comment'])){
							unset($arrayComments[$i]);
							++$count;
						}
					}
					
					if($count > 0){
						// Переиндексировали числовые индексы 
						$arrayComments = array_values($arrayComments); 
						// сохраняем массив комментов
						if($newsStorage->set('comments_'.$URI[2], json_encode($arrayComments, JSON_FLAGS))){
							echo $count;
						}else{ echo'Error'; }
					}else{ echo'Error'; }
				}else{ echo'Error'; }
				break;
			
			case 'loadcomments':
				if (is_numeric($URI[5]) && $URI[5] >= 0){
					if($newsStorage->iss('comments_'.$URI[2])){
						$arrayComments = json_decode($newsStorage->get('comments_'.$URI[2]), true);
						
						for($i = count($arrayComments) - $URI[5] - 1, $x = $i - $newsConfig->commentNavigation, $count = 0; $i >= $x; --$i){
							if($i < 0) break;
							if(file_exists(DR.'/'.$Config->userAvatarDir.'/'.$arrayComments[$i]['login'].'.jpg')){
								$avatar_file = '/'.$Config->userAvatarDir.'/'.$arrayComments[$i]['login'].'.jpg?'.filemtime(DR.'/'.$Config->userAvatarDir.'/'.$arrayComments[$i]['login'].'.jpg');
							}else{
								$avatar_file = '/modules/users/avatar.png';
							}
							echo'<div class="comment" id="comment'.$arrayComments[$i]['id'].'">
								<div class="avatar"><a href="/'.$newsConfig->idUser.'/'.$arrayComments[$i]['login'].'"><img src="'.$avatar_file.'" alt="avatar" id="avatar"></a></div>
								<div class="commentHead">
									<a href="/'.$newsConfig->idUser.'/'.$arrayComments[$i]['login'].'" class="author">'.$arrayComments[$i]['login'].'</a>
									'.($arrayComments[$i]['status'] == 'gost'?'<span class="gost">Гость</span>':'').'
									<span class="time" title="'.date("d.m.Y H:i:s", $arrayComments[$i]['time']).'">'.human_time(time() - $arrayComments[$i]['time']).' назад</span>
									'.($newsConfig->commentRules == 0 || $User->authorized ? '<a href="javascript:void(0);"  onClick="Comments.toUser(\''.$arrayComments[$i]['login'].'\')" class="re">Ответить</a>':'').'
									'.($User->preferences > 0 || $status == 'admin' ? '<input type="checkbox" onClick="Comments.commentDellCheck();" name="comment[]" value="'.$arrayComments[$i]['id'].'">':'').'
									
								</div>
								<div class="commentContent">'.NewsFormatText($arrayComments[$i]['text']).'</div>
							</div>';
							++$count;
						}
						if ($x > 0){
							echo'<button type="button" id="loadCommentsButton" onclick="Comments.loadComments('.($URI[5] + $newsConfig->commentNavigation + 1).');">Загрузить ещё</button>';
						}
						if ($count == 0){
							echo'<div class="noComments">Нет ни одного комментария</div>';
						}
						
					}else{
						echo'<div class="noComments">Нет ни одного комментария</div>';
					}
				}else{
					echo 'Ошибка при загрузки сообщений';
				}
				break;
				
			default :
				echo'Error';
				break;
		}
		 ob_end_flush(); exit;
	}
}


// Встраивание в страницу
$canPageName = $Page->name;

$return = '';



if(isset($URI[2]) && $newsParam = json_decode($newsStorage->get('news_'.$URI[2]))){ // Если страница существует, начинаем ее выводить

	$URI[2] = htmlspecialchars(specfilter($URI[2]));

	 if ($newsConfig->indexPost == '1') { // Если разрешен показ самой новости

		header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();

	 } else {

		
		//Переопределение переменных страницы

		$Page->robots = $newsParam->robots;

		if(!empty($newsParam->title)) {
			$Page->title = $newsParam->title;
		} else {
			$Page->title = $newsParam->header;
		}
		
		$Page->name = $newsParam->header;
		$Page->description = $newsParam->description;

		//Вывод параметров в Head страницы

		$Page->headhtml.= '
		<meta property="og:url" content="/'.$URI[1].'/'.$URI[2].'"/>
		<meta property="og:type" content="article"/>
		<meta property="og:image" content="'.$newsParam->img.'" />
		';
		$Page->headhtml.= '<script type="text/javascript">'.file_get_contents('modules/'.$page->module.'/jloader/jloader.min.js').'</script>';
		$Page->headhtml.= '<script type="text/javascript">'.file_get_contents('modules/'.$page->module.'/comments.min.js').'</script>';
		$Page->headhtml.= '<style type="text/css">'.file_get_contents('modules/'.$page->module.'/style.css').'</style>';
		
		$Page->clear();// Очистили страницу перед выводом


		if(!isset($newsParam->cat)){
			$newsParam->cat = '';
		}
		$newsIdCat = NewsIdCat($newsParam->cat); // фикс для совместимости // массив категорий в одну категорию

		$categoryname = NewsCategoryName($newsIdCat);
		if(!$categoryname) $categoryname = 'Без категории';
		$categoryuri = $newsIdCat != ''?'/'.$URI[1].'/'.$newsIdCat:($Page->isIndexPage()?'/':'/'.$URI[1]);

		$out_content = str_replace('#content#', $newsParam->content, $newsConfig->contentTemplate);
		$out_content = str_replace('#header#', $newsParam->header, $out_content);
		$out_content = str_replace('#canpagename#', $canPageName, $out_content);
		$out_content = str_replace('#date#', date($newsConfig->formatDate, isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)), $out_content);
		$out_content = str_replace('#com#', $newsStorage->iss('count_'.$URI[2])?$newsStorage->get('count_'.$URI[2]):0, $out_content);
		$out_content = str_replace('#img#', $newsParam->img, $out_content);
		$out_content = str_replace('#categoryname#', $categoryname, $out_content);
		$out_content = str_replace('#categoryuri#', $categoryuri, $out_content);
		$out_content = str_replace('#category1#', NewsGenHtmlCat($newsParam->cat, 1), $out_content);
		$out_content = str_replace('#category2#', NewsGenHtmlCat($newsParam->cat, 2), $out_content);
		$out_content = str_replace('#category3#', NewsGenHtmlCat($newsParam->cat, 3), $out_content);
		$out_content = str_replace('#category4#', NewsGenHtmlCat($newsParam->cat, 4), $out_content);
		$out_content = str_replace('#category#', NewsGenHtmlCat($newsParam->cat), $out_content);
		$out_content = str_replace('#uri#', '/'.$URI[1].'/'.$URI[2], $out_content);
		$out_content = str_replace('#home#','/'.($URI[1] != $Config->indexPage?$URI[1]:''), $out_content);
		if(Module::exists('snippets')){
			foreach($Snippet as $key => $value){
				$out_content = str_replace('#'.$key.'#', $value, $out_content);
			}
		}
		foreach($newsConfig->custom as $value){
			$out_content = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_content);
		}
		if(Module::exists('snippets')){
			foreach($Snippet as $key => $value){
				$out_content = str_replace('#'.$key.'#', $value, $out_content);
			}
		}
		foreach($newsConfig->custom as $value){
			$out_content = str_replace('#'.$value->id.'#', (isset($newsParam->custom->{$value->id})?$newsParam->custom->{$value->id}:''), $out_content);
		}

		$return.= $out_content;

	 }
	
	





	if($newsConfig->commentEngine && $newsConfig->commentEnable && $newsParam->comments){
		
		$ticket = random(100);
		// setcookie('ticket_'.$URI[2], md5($ticket.$Config->ticketSalt),time()+32000000, '/');
				
		$return.= '
			<div id="moduleNewsComments">
			<h3 id="commentsHeader">Комментарии</h3>';
		
		if($User->authorized){
			
			if($newsConfig->commentRules > 1 && $User->preferences == 0){
				// Показываем сообщение об ошибки если нехватает префов
				$return.= '<div id="errorPref">В данный момент Вы не можете оставлять сообщения</div>';
				
			}else{
				// Показываем форму для авторизированных
				
				$return.= '
				<form name="commentForm" action="#" method="post" onsubmit="return false;">
				<INPUT TYPE="hidden" NAME="ticket" VALUE="'.$ticket.'">
				<INPUT TYPE="hidden" NAME="act" VALUE="add">
				<div id="commentForm">
					<p>Сообщение: <span id="textReport"></span><br><textarea id="textForm" name="text" required></textarea></p>
					<p><button type="button" onclick="Comments.submitCommentForm();">Отправить</button></p>	
				</div>
				</form>
				';
			}
			
		}else{
			
			if($newsConfig->commentRules > 0){
				// Показываем сообщение об необходимости авторизироваться
				$return.= '<div id="errorAuth">Чтобы оставлять сообщения необходимо авторизоваться</div>';
			}else{
				// Показываем форму для гостей
				$return.= '
				<form name="commentForm" action="#" method="post" onsubmit="return false;">
				<INPUT TYPE="hidden" NAME="ticket" VALUE="'.$ticket.'">
				<INPUT TYPE="hidden" NAME="act" VALUE="add">
				<div id="commentForm">
					<p class="p_login">Логин: <span id="loginReport"></span><br><input id="loginForm" type="text" name="login" value="" required></p>
					<p class="p_text">Сообщение: <span id="textReport"></span><br><textarea id="textForm" name="text" required></textarea></p>'
					.(Module::exists('captcha')?'
						<p class="p_captcha_img" style="line-height:1;"><img id="captcha" src="/modules/captcha/captcha.php?rand='.rand(0, 99999).'" alt="captcha"  onclick="document.getElementById(\'captcha\').src = \'/modules/captcha/captcha.php?\' + Math.random()" style="cursor:pointer;">
							<br><span style="font-size:12px; opacity: 0.7;">Для обновления символов нажмите на картинку</span>
						</p>
						<p class="p_captcha_input">Символы с картинки:<br><input id="captchaForm" type="text" name="captcha" value=""  autocomplete="off" required></p>

					':'').
					'<p class="p_roscomnadzor"><input type="checkbox" name="roscomnadzor" value="ok" id="roscomnadzor"> <label for="roscomnadzor">Я согласен на <a href="/fz152" target="_blank">обработку моих персональных данных</a></label></p>
					<p class="p_submit"><button type="submit" onclick="addcommentgost();">Отправить</button></p>
				</div>
				</form>
				<script>
				function addcommentgost(){
					if(document.getElementById(\'roscomnadzor\').checked){
						Comments.submitCommentForm();
						document.getElementById(\'roscomnadzor\').checked = false;
					}else{alert(\'Нужно дать согласие на обработку персональных данных\');}
				}
				</script>
				';
				
				
			}
			
		}
		
		
		$return.= '
			<div id="requestReport"></div>
			<div id="comments">Загрузка...</div>
		';
		
		if($User->preferences > 0 || $status == 'admin'){
			$return.= '<button type="button" id="commentDellButton" onclick="Comments.commentDell();">Удалить выделенное</button>';
		}
		$return.= '
			<script>
				Comments.run({
					id: "'.$URI[2].'",
					ticket: "'.$ticket.'",
					newCommentCheckInterval: '.$newsConfig->commentCheckInterval.',
					commentMaxLength: '.$newsConfig->commentMaxLength.'
				});
			</script>
		</div>';
		
	}else{
		$return.= ($newsParam->comments == '1')?$newsConfig->commentTemplate:'';
	}



}else{ // Если страницы не существует , начинаем выводить листинг 

	if($newsConfig->indexCat == '-2'){ // Если запрет листинга новостей 
		header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
	}

	if(isset($URI[4]) || isset($URI[2]) && is_numeric($URI[2]) && isset($URI[3])){ // показываем ошибку, если есть ненужные продолжения uri 
		header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
	}

	if(isset($URI[2])){
		$Page->clear(); 
	}



			////// Если выводится категория //////

			if(isset($URI[2]) && !is_numeric($URI[2])) { 
    if(!isset($newsConfig->cat->{$URI[2]})) {
        header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
    }

    // Проверяем, есть ли новости в категории
    $listIdCat = $newsStorage->iss('category') ? json_decode($newsStorage->get('category'), true) : array();
    $listIdNews = listIdCat($listIdCat, $URI[2]);
    
    // Если категория пуста (нет новостей), возвращаем 404
    if(empty($listIdNews)) {
        header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
    }

    $Page->name = NewsCategoryName($URI[2]);

    // Получаем Title категории
    $categoryTitle = '';
    if (isset($newsConfig->cat_title->{$URI[2]})) {
        $categoryTitle = $newsConfig->cat_title->{$URI[2]};
    }
    // Получаем Description категории
    $categoryDescription = '';
    if (isset($newsConfig->cat_desc->{$URI[2]})) {
        $categoryDescription = $newsConfig->cat_desc->{$URI[2]};
    }

    $Page->title = (!empty($categoryTitle) ? $categoryTitle : $Page->name); // Выводим Title категории
    $Page->description = (!empty($categoryDescription) ? $categoryDescription : $Page->name); // Выводим Description категории
    
    if($newsConfig->indexCat != '-1') $return.= '';
} else {
    if($newsConfig->indexCat == '-1') {
        header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
    } elseif($newsConfig->indexCat || $newsConfig->indexCat === '') {
        $listIdCat = json_decode($newsStorage->get('category'), true);
        $listIdNews = listIdCat($listIdCat, $newsConfig->indexCat);
        
        // Проверяем, есть ли новости в индексной категории
        if(empty($listIdNews)) {
            header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
        }
    } else {
        $listIdNews = json_decode($newsStorage->get('list'), true);
        
        // Проверяем, есть ли новости вообще
        if(empty($listIdNews)) {
            header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
        }
    }
}




	if($listIdNews == false){
		if($newsConfig->indexCat != '-1') $return.= '<p>Записей пока нет</p>';
	}else{
		
		//перевернули масив для вывода новостей в обратном порядке
		$listIdNews = array_reverse($listIdNews);
		
		//
		$nom = count($listIdNews);
		
		//определили количество страниц
		$countPage = ceil($nom / $newsConfig->navigation); 
		
		//проверка правbльности переменной с номером страницы
		// 5.1.41
		if(isset($URI[2]) && is_numeric($URI[2])){
			$nom_page = $URI[2];
		}elseif(isset($URI[3])){
			$nom_page = $URI[3];
		}else{ 
			$nom_page = 1; 
		} 

		if(!is_numeric($nom_page) || $nom_page <= 0 || $nom_page > $countPage){ // проверка корректности номера страницы
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
		}

		// Канонические URL для страниц с навигациями
		if($nom_page == 1){
			$Page->headhtml.= '<link rel="canonical" href="'.$Config->protocol.'://'.SERVER.($Page->isIndexPage() && isset($URI[2]) && is_numeric($URI[2])?'':'/'.$URI[1]).(isset($URI[2]) && !is_numeric($URI[2])?'/'.$URI[2]:'').'">';
		}else{
			$Page->headhtml.= '<link rel="canonical" href="'.$Config->protocol.'://'.SERVER.'/'.$URI[1].'/'.$URI[2].(isset($URI[3])?'/'.$URI[3]:'').'">';
		}

		// добавление к мета тегам при пагинации 
		if($nom_page > 1){
			$Page->title.= ' (Страница '.$nom_page.')';
			
			$Page->description.= ' (Страница '.$nom_page.')';
		}


		
		$return.= '<div class="news_prev_list">'; 

		// Выводим категории

$out_content = '
<div class="category-wrap">';

if (Module::exists('news')) {
    require(DR.'/modules/news/cfg.php');
    $listIdCat = json_decode($newsStorage->get('category'), true);
    $listIdAll = json_decode($newsStorage->get('list'), true);
    
    $hasVisibleCategories = false;
    
    // Обрабатываем каждую категорию
    foreach ($newsConfig->cat as $key => $value) {
        $items = listIdCat($listIdCat, $key);
        $count = (is_array($items) && !empty($items)) ? count($items) : 0;
        
        // Выводим только непустые категории
        if ($count > 0) {
            $out_content .= '<div class="link"><a href="/'.$newsConfig->idPage.'/'.$key.'">'.$value.' <span>('.$count.')</span></a></div>';
            $hasVisibleCategories = true;
        }
    }
    
    // Добавляем "Все новости" если нужно и если есть хотя бы одна категория
    if ($newsConfig->indexCat == 0 && (is_array($listIdAll) && (count($listIdAll) > 0 || $hasVisibleCategories))) {
        $allCount = is_array($listIdAll) ? count($listIdAll) : 0;
        $out_content .= '<div class="link"><a href="/'.($newsConfig->idPage != $Config->indexPage ? $newsConfig->idPage : '').'">Все новости <span>('.$allCount.')</span></a></div>';
        $hasVisibleCategories = true;
    }
    
    // Если ничего не вывели
    if (!$hasVisibleCategories) {
        $out_content .= '<p>Нет доступных категорий</p>';
    }
} else {
    $out_content .= '<p>Модуль категории услуг не найден</p>';
}

$out_content .= '
</div>';

		$return.= $out_content;

		
             

		//начало навигации
		$i = ($nom_page - 1) * $newsConfig->navigation;
		$var = $i + $newsConfig->navigation;
		
		while($i < $var){
			if($i < $nom){
				if($newsStorage->iss('news_'.$listIdNews[$i])){
					$newsParam = json_decode($newsStorage->get('news_'.$listIdNews[$i]));

					if(!isset($newsParam->cat)){
						$newsParam->cat = '';
					}
					$newsIdCat = NewsIdCat($newsParam->cat);

					$categoryname = NewsCategoryName($newsIdCat);
					if(!$categoryname) $categoryname = 'Без категории';
					$categoryuri = $newsIdCat != ''?'/'.$URI[1].'/'.$newsIdCat:($Page->isIndexPage()?'/':'/'.$URI[1]);

					$out_prev = str_replace('#content#', $newsParam->prev, $newsConfig->prevTemplate);
					$out_prev = str_replace('#header#', $newsParam->header, $out_prev);
					$out_prev = str_replace('#canpagename#', $canPageName, $out_prev);
					$out_prev = str_replace('#date#', date($newsConfig->formatDate, isset($newsParam->time)?$newsParam->time:strtotime($newsParam->date)), $out_prev);
					$out_prev = str_replace('#com#', $newsStorage->iss('count_'.$listIdNews[$i])?$newsStorage->get('count_'.$listIdNews[$i]):0, $out_prev);
					$out_prev = str_replace('#img#', $newsParam->img, $out_prev);
					$out_prev = str_replace('#categoryname#', $categoryname, $out_prev);
					$out_prev = str_replace('#categoryuri#', $categoryuri, $out_prev);
					$out_prev = str_replace('#category1#', NewsGenHtmlCat($newsParam->cat, 1), $out_prev);
					$out_prev = str_replace('#category2#', NewsGenHtmlCat($newsParam->cat, 2), $out_prev);
					$out_prev = str_replace('#category3#', NewsGenHtmlCat($newsParam->cat, 3), $out_prev);
					$out_prev = str_replace('#category4#', NewsGenHtmlCat($newsParam->cat, 4), $out_prev);
					$out_prev = str_replace('#category#', NewsGenHtmlCat($newsParam->cat), $out_prev);
					$out_prev = str_replace('#uri#', '/'.$URI[1].'/'.$listIdNews[$i], $out_prev);
					$out_prev = str_replace('#home#','/'.($URI[1] != $Config->indexPage?$URI[1]:''), $out_prev);
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
			++$i;
		}
		
		$return.= '</div>';

		if($countPage > 1){	  
			//навигация по номерам страниц
			$return.= '<div class="navigation"><span class="navigation_header">Страницы: </span>';
			
			$a = $nom_page - 3;
			$b = $nom_page + 3;
			
			if($a > 1){
				$return.= '<a href="/'.$URI[1].'/'.(isset($URI[2]) && !is_numeric($URI[2])?$URI[2].'/':'').'1" class="link first">1</a>';
				if($a > 2){ $return.= '<span class="space">&nbsp;</span>'; }
			}
			while($a <= $b){
				if(($a > 0) && ($a <= $countPage)){
					if($nom_page == $a){
						$return.= '<span class="this">'.$a.'</span>';
					}else{
						$return.= '<a href="/'.$URI[1].'/'.(isset($URI[2]) && !is_numeric($URI[2])?$URI[2].'/':'').$a.'" class="link">'.$a.'</a>';
					}
				}
			++$a;
			}
			if($b < $countPage){
				if($b < ($countPage - 1)){ $return.= '<span class="space">&nbsp;</span>'; }
				$return.= '<a href="/'.$URI[1].'/'.(isset($URI[2]) && !is_numeric($URI[2])?$URI[2].'/':'').$countPage.'" class="link last">'.$countPage.'</a>';
			}
			
			$return.= '</div>';
			//конец навигации*/
		}
		
		
		
	}
	
	
}
return $return;
?>