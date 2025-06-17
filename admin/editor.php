<?php
require('../system/global.dat');
require('./include/start.dat'); ?>

<script>
var iframefiles = '<div class="a"><iframe src="iframefiles.php?id=inputimg" width="100%" height="300" style="border:0;">Ваш браузер не поддерживает плавающие фреймы!</iframe></div>'+
'<div class="b">'+
'<button class=""btn type="button" onclick="closewindow(\'window\');">Отмена</button>'+
'</div>';
</script>

<?php if($status=='admin'){
    if(isset($_GET['page'])){$page = $_GET['page'];}elseif(isset($_POST['page'])){$page = $_POST['page'];}else{$page = $Config->indexPage;}
    if(isset($_GET['new_page'])){$new_page = $_GET['new_page'];}elseif(isset($_POST['new_page'])){$new_page = $_POST['new_page'];}else{$new_page='off';}
    $dub = isset($_GET['dub'])?$_GET['dub']:0;
    $page = htmlspecialchars(specfilter($page));

    if($act=='index'){
        if(Page::exists($page) || $new_page=='on'){
            
            if($new_page=='on'){
                $cfg_page['name'] = '';
                $cfg_page['h1'] = '';
                $cfg_page['robots'] = '';
                $cfg_page['baner'] = '';
                $cfg_page['title'] = '';
                $cfg_page['sitemap'] = '';
                $cfg_page['description'] = '';
                $cfg_page['module'] = 'no/module';
                $cfg_page['show'] = 1;
                $cfg_page['template'] = 'def/template';
                $editor = '';
                $id_page = uniqid();
                echo'
                <div class="header">
                    <div class="container">
                        <div class="mobile-menu-wrapper">
                            <ul class="nav">
                                <a class="nav-item" href="pages.php">Вернуться назад</a>
                            <ul>
                        </div>
                    </div>
                </div>
                ';
            }else{
                $obj = new Page($page, $Config);
                $cfg_page['name'] = $obj->name;
                $cfg_page['h1'] = $obj->h1;
                $cfg_page['baner'] = $obj->baner;
                $cfg_page['robots'] = $obj->robots;
                $cfg_page['title'] = $obj->title;
                $cfg_page['sitemap'] = $obj->sitemap;
                $cfg_page['description'] = $obj->description;
                $cfg_page['module'] = $obj->module;
                $cfg_page['show'] = $obj->show;
                $cfg_page['template'] = $obj->template;
                $editor = $obj->content();
                $editor = htmlspecialchars($editor);
                $id_page = $dub==1?uniqid():$page;
                if($dub == 1){
                    echo'<div class="header">
                    <h1>Создание дубликата страницы</h1>
                    </div>';
                }else{
                    echo'
                    <div class="header">
                        <div class="container">
                            <div class="mobile-menu-wrapper">
                                <ul class="nav">
                                    <a class="nav-item" href="pages.php">Вернуться назад</a>
                                <ul>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }
            
            echo'
            <div class="container mb-3">
                <form name="form_name" action="editor.php?" method="post">
                    <INPUT TYPE="hidden" NAME="act" VALUE="add">
                    <INPUT TYPE="hidden" NAME="page" VALUE="'.$page.'">';
                    if($new_page=='on') echo'<INPUT TYPE="hidden" NAME="new_page" VALUE="on">';
                    if($dub == 1) echo'<INPUT TYPE="hidden" NAME="dub" VALUE="1">'; ?>

                    <?php echo' 
            
                    <div class="col-12 bs-br bg-white overflow-hidden mb-3">
                        <h3 class="bg-light p-4 m-0">' . ($new_page == 'on' ? 'Создание новой страницы' : 'Редактирование страницы') . '</h3>
                        <div class="p-4">

                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Название страницы</label>
                                <input type="text" class="form-control" name="new_cfg_name" id="name" value="'.$cfg_page['name'].'">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Содержимое страницы</label>
                                <TEXTAREA id="editor" class="editor" NAME="editor" ROWS="20" COLS="100">'.$editor.'</TEXTAREA>';
                                if($Config->wysiwyg){
                                    if(Module::isWysiwyg($Config->wysiwyg)){
                                        require Module::pathRun($Config->wysiwyg, 'wysiwyg');
                                    }
                                } echo'
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Изображение</label>
                                <div class="input-group m-0">
                                    <input type="text" class="form-control" name="new_cfg_baner" id="inputimg" value="'.$cfg_page['baner'].'">
                                    <button class="btn btn-secondary" type="button" onClick="openwindow(\'window\', 750, \'auto\', iframefiles);">Выбрать файл</button>
                                </div>
                                <img class="mt-3" src="'.$cfg_page['baner'].'" alt="" id="img" style="width: 180px;">
                            </div>
                        
                        </div>
                    </div>';?>


                    <div class="col-12 bs-br bg-white overflow-hidden mb-3">
                        <h3 class="bg-light p-4 m-0">SEO оптимизация</h3>
                        <div class="p-4">

                            <div class="mb-3">
                                <label class="form-label">Заголовок (H1)</label>
                                <input type="text" class="form-control" name="new_cfg_h1" id="h1" value="<?php echo $cfg_page['h1'];?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Титульный заголовок (title)</label>
                                <input type="text" class="form-control" name="new_cfg_title" value="<?php echo $cfg_page['title'];?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Описание (description)</label>
                                <textarea type="text" class="form-control" rows="4" name="new_cfg_description"><?php echo $cfg_page['description'];?></textarea>
                            </div>

                            <?php echo' 
                            <div class="mb-3">
                                <label class="form-label">Идентификатор (URL)</label>
                                <div class="input-group">
                                <input type="text" class="form-control" name="new_cfg_id_page" id="id" value="'.str_replace('__', '/', $id_page).'"'.(($id_page == $Config->indexPage || $id_page == 'uslugi')?' readonly':'').'>
                                </div>
                            </div>

                        
                            <div class="mb-3">
                                    <div class="mb-3">
                                    <label class="form-label">Индексирование</label>
                                    <SELECT class="form-select" NAME="new_cfg_robots">';
                                    echo'<OPTION VALUE="0" '.($cfg_page['robots'] == 0?'selected':'').'>Да';
                                    echo'<OPTION VALUE="1" '.($cfg_page['robots'] == 1?'selected':'').'>Нет';
                                    echo'</SELECT>
                                    </div>
                            </div>

                            <div class="mb-3">
                                    <div class="mb-3">
                                    <label class="form-label">Добавить в SiteMap</label>
                                    <SELECT class="form-select" NAME="new_cfg_sitemap">';
                                    echo'<OPTION VALUE="0" '.($cfg_page['sitemap'] == 0?'selected':'').'>Да';
                                    echo'<OPTION VALUE="1" '.($cfg_page['sitemap'] == 1?'selected':'').'>Нет';
                                    echo'</SELECT>
                                    </div>
                            </div>
                            
                            
                            ';?>

                        </div>
                    </div>

                    <div class="col-12 bs-br bg-white overflow-hidden mb-3">
                        <h3 class="bg-light p-4 m-0">Другое</h3>
                        <div class="p-4">

                            <?php echo '
                            <div class="mb-3">
                                <div class="mb-3">
                                <label class="form-label">Модуль для страницы</label>';
                                echo'<SELECT class="form-select" NAME="new_cfg_module">';
                                echo'<OPTION VALUE="no/module" '.($cfg_page['module'] == 'no/module'?'selected':'').'>Страница без модуля';
                                $listModules = System::listModules();
                                foreach($listModules as $value){
                                    if(Module::isIntegrationPage($value)){
                                        $info = Module::info($value);
                                        echo '<OPTION VALUE="'.$value.'" '.($cfg_page['module'] == $value?'selected':'').'>'.$info['name'].' '.$info['version'];
                                    }
                                }
                                echo'</SELECT>
                                </div>
                            </div>
                        

                            <div class="mb-3">
                                <div class="mb-3">
                                <label class="form-label">Доступность для просмотра</label>
                                    <SELECT class="form-select" NAME="new_cfg_show">';
                                    echo'<OPTION VALUE="1" '.($cfg_page['show'] == 1?'selected':'').'>Всем пользователям';
                                    echo'<OPTION VALUE="2" '.($cfg_page['show'] == 2?'selected':'').'>Пользователям с преференциями и администратору';
                                    echo'<OPTION VALUE="0" '.($cfg_page['show'] == 0?'selected':'').'>Только администратору';
                                    echo'</SELECT>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-3">
                                <label class="form-label">Шаблон для вывода</label>
                                    <SELECT class="form-select" NAME="new_cfg_template">
                                    <OPTION VALUE="def/template"'.($cfg_page['template'] == 'def/template'?' selected':'').'>Шаблон по умолчанию';
                                    $listModules = System::listModules();
                                    foreach($listModules as $value){
                                            if(Module::isTemlate($value)){
                                                $info = Module::info($value);
                                                echo'<OPTION VALUE="'.$value.'"'.($cfg_page['template'] == $value?' selected':'').'>'.$info['name'];
                                            }
                                    }
                                    echo'</SELECT>
                                </div>
                            </div>';?>
                        </div>
                    </div>
                    
                    <div class="btn-gr">
                        <button class="btn btn-primary" type="button" onClick="submit();">Сохранить</button>
                        <a class="btn btn-secondary" href="pages.php">Вернуться назад</a>
                    </div>

                    <?php echo'

            
                </form>
            </div>';

            } else {

            if($page == '')$page = 'НЕИЗВЕСТНО';
            System::notification('Ошибка при открытии страницы с идентификатором '.$page.', страница не найдена', 'r');
            echo'<div class="msg">Ошибка при открытии страницы</div>';?>

            <script type="text/javascript">
                setTimeout('window.location.href = \'pages.php?\';', 200)
            </script>

            <?php
        }
    }

    if($act=='add'){
        // Преобразуем слеши в подчеркивания перед сохранением
        $_POST['new_cfg_id_page'] = str_replace('/', '__', $_POST['new_cfg_id_page']);
        
        // Автосоздание родительских страниц
        if(strpos($_POST['new_cfg_id_page'], '__') !== false) {
            $parts = explode('__', $_POST['new_cfg_id_page']);
            $current_parent = '';
            
            foreach($parts as $i => $part) {
                if($i < count($parts) - 1) {
                    $current_parent = $current_parent ? $current_parent.'__'.$part : $part;
                    
                    if(!Page::exists($current_parent)) {
                        Page::add(
                            $current_parent,
                            'Раздел: '.str_replace('__', '/', $part),
                            'Раздел '.str_replace('__', '/', $part),
                            '', '', 1, 'no/module', 'def/template', '<p>Автоматически созданный раздел</p>'
                        );
                    }
                }
            }
        }

        if($new_page=='on' || isset($_POST['dub']) && $_POST['dub'] == 1){
            $page = $_POST['new_cfg_id_page'];
            
            if(true || System::validPath($page)){
                if(Page::exists($page)){
                    $page = $page.'_'.uniqid();
                    echo'<div class="msg">Страница успешно создана, но с другим идентификатором, т.к введенный уже занят</div>';
                }else{
                    echo'<div class="msg">Страница успешно создана</div>';
                }
            }else{
                $page = uniqid();
                echo'<div class="msg">Страница успешно создана, но с другим идентификатором, т.к введенный был некорректен</div>';
            }
            System::notification('Создана новая страница с идентификатором '.$page.', ссылка на страницу http://'.$_SERVER['SERVER_NAME'].'/'.str_replace('__', '/', $page), 'g');
        }else{
            if($page != $_POST['new_cfg_id_page'] && $page != $Config->indexPage){
                if (Page::rename($page, $_POST['new_cfg_id_page'])){
                    System::notification('Отредактирована страница со сменой идентификатора '.$page.' на идентификатор '.$_POST['new_cfg_id_page'].', ссылка на страницу http://'.$_SERVER['SERVER_NAME'].'/'.str_replace('__', '/', $_POST['new_cfg_id_page']).'', 'g');
                    
                    $page = $_POST['new_cfg_id_page'];
                    echo'<div class="msg">Страница успешно сохранена</div>';
                }else{
                    System::notification('Отредактирована страница с неудачной сменой идентификатора '.$page.' на идентификатор '.$_POST['new_cfg_id_page'].', ссылка на страницу http://'.$_SERVER['SERVER_NAME'].'/'.str_replace('__', '/', $page), 'g');
                    echo'<div class="msg">Страница успешно сохранена, но без смены идентификатора</div>';
                }
            }else{
                System::notification('Отредактирована страница с идентификатором '.$page.', ссылка на страницу http://'.$_SERVER['SERVER_NAME'].'/'.($page == $Config->indexPage?'':str_replace('__', '/', $page)), 'g');
                
                echo'<div class="msg">Страница успешно сохранена</div>';
            }
        }
        
        Page::add(
                $page, 
                $_POST['new_cfg_name'],
                $_POST['new_cfg_h1'],
                $_POST['new_cfg_robots'],
                $_POST['new_cfg_baner'],
                $_POST['new_cfg_title'], 
                $_POST['new_cfg_sitemap'], 
                $_POST['new_cfg_description'], 
                $_POST['new_cfg_show'], 
                $_POST['new_cfg_module'], 
                $_POST['new_cfg_template'], 
                ($page != $Config->indexPage) ? $_POST['editor'] : null
            );
        
        setcookie('lastEditPage',htmlspecialchars($_POST['new_cfg_id_page']),time()+32000000,'/');
?>
<script type="text/javascript">
setTimeout('window.location.href = \'editor.php?page=<?php echo htmlspecialchars($_POST['new_cfg_id_page']);?>\';', 200);
</script>
<?php    
    }
}else{
echo'<div class="msg">Необходимо выполнить авторизацию</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'index.php?\';', 200);
</script>

<?php
}

require('include/end.dat');
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
//Скрываем тег <img>
const imgElement = document.getElementById('img');

if (imgElement.src === '') {
  imgElement.style.display = 'none';
}
</script>


<script>
// Get the input field with the class "form-control" and name "header"
const headerInput = document.querySelector('.form-control[name="new_cfg_name"]');

// Get the input field with the class "form-control" and name "id"
const idInput = document.querySelector('.form-control[name="new_cfg_id_page"]');

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