<?php
require('../system/global.dat');
require('./include/start.dat');
?>
<script type="text/javascript">
function dell(page, n, u){
return '<div class="a">Подтвердите удаление страницы: <i>' + n + ' (<a href="//' + u + '" target="_blank">' + u + '</a>)</i>' +
    '<div class="btn-gr">' +
    '<button class="btn btn-danger" type="button" onClick="window.location.href = \'pages.php?act=dell&amp;page='+page+'\';">Удалить</button> '+
    '<button class="btn btn-secondary" type="button" onclick="closewindow(\'window\');">Отмена</button>'+
    '</div></div>';
}
</script>
<?php
if($status=='admin'){
    if($act=='index'){

        echo'
        <div class="header">
            <div class="container">
	            <div class="mobile-menu-wrapper">
		            <ul class="nav">
                        <a class="nav-item" href="editor.php?new_page=on">Создать новую</a>
                        <a class="nav-item" href="editor.php?page='.$Config->indexPage.'">Редактировать главную</a>';
                        if(isset($_COOKIE['lastEditPage'])){
                            echo'<a class="nav-item" href="editor.php?page='.htmlspecialchars($_COOKIE['lastEditPage']).'">Редактировать последнюю измененную страницу</a>';
                        }
                        echo'
                    </ul>
                </div>    
            </div>
        </div>

        

        <div class="container mb-4">

            <h1>Управление страницами</h1>

            <form action="pages.php?act=search" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="" placeholder="Поиск по страницам">
                    <input class="btn btn-primary" type="submit" name="" value="Поиск"> 
                </div>
            </form>

            <h2>Список всех страниц</h2>

            <div class="item-list">';

                $pages = System::listPages();
                $pages = array_reverse($pages);
                $nom = count($pages);
                
                $navigation = 50;
                $kol_page = ceil($nom / $navigation); 
                
                if(isset($_GET['nom_page'])){$nom_page = $_GET['nom_page'];}else{ $nom_page = 1; }
                if(!is_numeric($nom_page) || $nom_page <= 0 || $nom_page > $kol_page){ $nom_page = 1; }
                
                $i = ($nom_page - 1) * $navigation;
                $var = $i + $navigation;
                
                $allPages = [];
                foreach ($pages as $pageId) {
                    $page = new Page($pageId, $Config);
                    $parentId = (strpos($pageId, '__') !== false) ? explode('__', $pageId)[0] : '';
                    $allPages[$parentId][] = $page;
                }

                uksort($allPages, function($a, $b) {
                    if ($a === '') return 1;
                    if ($b === '') return -1;
                    return strcmp($a, $b);
                });

                foreach ($allPages as $parentId => $children) {
                    $isParent = ($parentId !== '');
                    
                    if ($isParent) {
                        $parent = new Page($parentId, $Config);
                        $displayParentId = str_replace('__', '/', $parentId);
                        echo '
                        <div class="parent-page">
                                <div class="parent-header" onclick="toggleChildren(this)">
                                    <span class="toggle">+</span>
                                    <img src="include/page.svg" alt="">
                                    <a href="editor.php?page='.$parentId.'">'.$parent->name.'</a>
                                </div>
                                <div class="children" style="display:none;">
                        ';
                    }

                    foreach ($children as $page) {
                        if ($page->id === $parentId) continue;
                        
                        $showStatus = ($page->show == 1) ? '<span class="g">Всем</span>' : 
                                    (($page->show == 2) ? '<span class="r">С преференциями</span>' : '<span class="r">Только админ</span>');
                        $displayId = str_replace('__', '/', $page->id);
                        $url = $page->id != $Config->indexPage ? $displayId : '';
                        
                        echo '
                            <div class="card">
                                <div class="content-card gap-3"> 
                                    <div class="d-flex flex-column justify-content-center gap-3 px-3">
                                        <h5 class="m-0">
                                            <a href="editor.php?page='.$page->id.'">'.$page->name.'</a>
                                        </h5>
                                        <small>
                                            <div>URL: <a href="//'.SERVER.'/'.$url.'" target="_blank">'.SERVER.'/'.$url.'</a></div>
                                            <div>Доступна: '.$showStatus.'</div>
                                            <div>Дата публикации: '.date("d.m.Y H:i", $page->time).'</div>
                                        </small>
                                        <div class="d-flex gap-2">
                                            <a href="editor.php?page='.$page->id.'">
                                            <svg id="edit" enable-background="new 0 0 426.589 426.589" viewBox="0 0 426.589 426.589" xmlns="http://www.w3.org/2000/svg"><g><g id="layer9"><g id="g1195" transform="translate(-34.396 -307.584)"><g id="path914-4"><path d="m98.316 307.589c-35.094 0-63.917 28.823-63.917 63.917v298.75c0 35.093 28.823 63.917 63.917 63.917h298.75c35.093 0 63.917-28.823 63.917-63.917v-170.709c-.261-11.782-10.025-21.121-21.807-20.86-11.414.253-20.606 9.446-20.86 20.86v170.708c0 12.035-9.215 21.25-21.25 21.25h-298.75c-12.035 0-21.25-9.215-21.25-21.25v-298.75c0-12.035 9.215-21.25 21.25-21.25h191.958c11.782.264 21.548-9.072 21.812-20.854.265-11.782-9.072-21.548-20.854-21.812-.319-.007-.639-.007-.958 0zm280.625 3.584c-5.644.089-11.023 2.411-14.959 6.458l-225.208 232.625c-3.138 3.218-5.179 7.345-5.833 11.792l-9.417 66.167c-1.647 11.667 6.476 22.461 18.143 24.109 1.554.219 3.127.267 4.69.141l69.917-5.833c5.147-.442 9.959-2.737 13.542-6.458l225.209-232.458c8.071-8.392 7.923-21.706-.333-29.917l-60.292-60.293c-4.078-4.125-9.659-6.411-15.459-6.333zm.625 51.833 30.083 30.083-204.875 211.667-35.292 2.958 4.75-32.75z"></path></g></g></g></g></svg>
                                            </a>';?>

                                            <?php if ($page->id != 'uslugi') {
                                            echo '
                                            <a href="javascript:void(0)" onclick="openwindow(\'window\', 650, \'auto\', dell(\'' . $page->id . '\', \'' . $page->name . '\', \'' . SERVER . '/' . $url . '\'));">
                                            <svg id="del" viewBox="-51 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m410.269531 139.730469v-80.265625h-137.414062v-59.464844h-135.445313v59.464844h-137.410156v80.265625zm-242.859375-109.730469h75.445313v29.464844h-75.445313zm0 0"></path><path d="m61.945312 512h286.375l41.195313-342.269531h-368.761719zm205.410157-281.671875 29.976562 1.230469-7.734375 188.433594-30.03125.175781zm-77.222657.535156h30v190h-30zm-47.222656-.535156 7.792969 189.84375-29.976563-.167969-7.789062-188.445312zm0 0"></path></svg>
                                            </a>';
                                            } ?>
                                        <?php echo '	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }

                    if ($isParent) echo '</div></div>';
                }
                
                if($kol_page > 1){
                    echo'<div style="margin-top: 25px; text-align: center;">';
                    echo'Страницы: ';
                    
                    for($i = 1; $i <= $kol_page; ++$i){
                        if($nom_page == $i){
                            echo'<b>('.$i.')</b> ';
                        }else{
                            echo'<a href="?nom_page='.$i.'">'.$i.'</a> ';
                        }
                    }
                    echo'</div>';
                }
                echo'</div>';
    }
    

    if($act=='search'){
        if(!function_exists('mb_stripos')){
            echo'<div class="header">
                <h1>Поиск по страницам</h1>
            </div>
            <div class="menu_page"><a href="pages.php">&#8592; Вернуться назад</a></div>
            <div class="content">
                <p>На сервере не установлено php расширение "mbstring". Это расширение позволяет производить поиск по русскоязычным символам. Обратитесь к администратору вашего сервера для установки данного расширения. 
                Администраторы могут воспользоваться <a href="https://www.php.net/manual/ru/book.mbstring.php" target="_blank">документацией</a>.</p>
            </div>';
        }else{
            
            $q = isset($_POST['q'])?htmlspecialchars(trim($_POST['q'])):'';
            
            echo'
            <div class="header">
                <div class="container">
				    <div class="mobile-menu-wrapper">
		                <ul class="nav">
                            <a class="nav-item"href="pages.php">Вернуться назад</a>
                        <ul>
                    </div>
                </div>
            </div>
            
            <div class="container">
                <h1>Поиск по страницам</h1>
                
                <form name="form_name" action="pages.php?act=search" method="post">
                    <div class="input-group">
                        <input class="form-control" type="text" name="q" value="'.$q.'" placeholder="Введите запрос" autofocus>
                        <input class="btn btn-primary" type="submit" name="" value="Поиск">
                    </div>
                </form>
                
                <h3>Результаты поиска:</h3>
                ';
                
                if($q != ''){
                    
                    $pages = System::listPages();
                    $pages = array_reverse($pages);
                    
                    $pSearchName = array();
                    $pSearchTitle = array();
                    
                    $pSearchDescription = array();
                    $pSearchID = array();
                    
                    $iRes = 0;
                    foreach($pages as $value){
                        if(Page::exists($value)){
                            $Page = new Page($value);
                            if(mb_stripos($Page->name, $q, 0, 'UTF-8') !== false){
                                $pSearchName[] = array('name' => $Page->name, 'time' => $Page->time, 'show' => $Page->show, 'id' => $value);
                                ++$iRes; 
                            }elseif(mb_stripos($Page->title, $q, 0, 'UTF-8') !== false){
                                $pSearchTitle[] = array('name' => $Page->name, 'time' => $Page->time, 'show' => $Page->show, 'id' => $value);
                                ++$iRes; 
                            }elseif(mb_stripos($Page->description, $q, 0, 'UTF-8') !== false){
                                $pSearchDescription[] = array('name' => $Page->name, 'time' => $Page->time, 'show' => $Page->show, 'id' => $value);
                                ++$iRes; 
                            }elseif(mb_stripos($value, $q, 0, 'UTF-8') !== false){
                                $pSearchID[] = array('name' => $Page->name, 'time' => $Page->time, 'show' => $Page->show, 'id' => $value);
                                ++$iRes; 
                            }
                        }
                        if($iRes == 100) break;
                    }

                    
                    $pSearchResult = array_merge($pSearchName, $pSearchTitle, $pSearchDescription, $pSearchID);
                    
                    
                    foreach($pSearchResult as $value){
                        $displayId = str_replace('__', '/', $value['id']);
                        $tmp_url = $value['id']!=$Config->indexPage?$displayId:'';

                        if($value['show'] == 1){
                            $tmp_34 = '<span class="g">Доступно всем</span>';
                        }elseif($value['show'] == 2){
                            $tmp_34 = '<span class="r">Доступно пользователям с преференциями</span>';
                        }elseif($value['show'] == 0){
                            $tmp_34 = '<span class="r">Доступно только администратору</span>';
                        }else{
                            $tmp_34 = '<span class="r">Error</span>';
                        }

                        echo'
                            <div class="item-list">

                                <div class="card">
                                    <div class="content-card gap-3"> 
                                        <div class="d-flex flex-column justify-content-center gap-3 px-3">
                                            <h5 class="m-0">
                                                <a href="editor.php?page='.$value['id'].'">'.preg_replace('#('.$q.')#ius', '<span class="r">\1</span>', $value['name']).'</a>
                                            </h5>
                                            <small>
                                                <div>URL: <a href="//'.SERVER.'/'.$tmp_url.'" target="_blank">'.SERVER.'/'.preg_replace('#('.$q.')#ius', '<span class="r">\1</span>', $tmp_url).'</a></div>
                                                <div>Доступна: '.$tmp_34.'</div>
                                                <div>Дата редактирования: '.date("d.m.Y H:i", $value['time']).'</div>
                                            </small>
                                            <div class="d-flex gap-2">
                                                <a href="editor.php?page='.$value['id'].'">
                                                <svg id="edit" enable-background="new 0 0 426.589 426.589" viewBox="0 0 426.589 426.589" xmlns="http://www.w3.org/2000/svg"><g><g id="layer9"><g id="g1195" transform="translate(-34.396 -307.584)"><g id="path914-4"><path d="m98.316 307.589c-35.094 0-63.917 28.823-63.917 63.917v298.75c0 35.093 28.823 63.917 63.917 63.917h298.75c35.093 0 63.917-28.823 63.917-63.917v-170.709c-.261-11.782-10.025-21.121-21.807-20.86-11.414.253-20.606 9.446-20.86 20.86v170.708c0 12.035-9.215 21.25-21.25 21.25h-298.75c-12.035 0-21.25-9.215-21.25-21.25v-298.75c0-12.035 9.215-21.25 21.25-21.25h191.958c11.782.264 21.548-9.072 21.812-20.854.265-11.782-9.072-21.548-20.854-21.812-.319-.007-.639-.007-.958 0zm280.625 3.584c-5.644.089-11.023 2.411-14.959 6.458l-225.208 232.625c-3.138 3.218-5.179 7.345-5.833 11.792l-9.417 66.167c-1.647 11.667 6.476 22.461 18.143 24.109 1.554.219 3.127.267 4.69.141l69.917-5.833c5.147-.442 9.959-2.737 13.542-6.458l225.209-232.458c8.071-8.392 7.923-21.706-.333-29.917l-60.292-60.293c-4.078-4.125-9.659-6.411-15.459-6.333zm.625 51.833 30.083 30.083-204.875 211.667-35.292 2.958 4.75-32.75z"></path></g></g></g></g></svg>
                                                </a>
                                                <a href="javascript:void(0);" onclick="openwindow(\'window\', 650, \'auto\', dell(\''.$value['id'].'\', \''.$value['name'].'\', \''.SERVER.'/'.$tmp_url.'\'));">
                                                <svg id="del" viewBox="-51 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m410.269531 139.730469v-80.265625h-137.414062v-59.464844h-135.445313v59.464844h-137.410156v80.265625zm-242.859375-109.730469h75.445313v29.464844h-75.445313zm0 0"></path><path d="m61.945312 512h286.375l41.195313-342.269531h-368.761719zm205.410157-281.671875 29.976562 1.230469-7.734375 188.433594-30.03125.175781zm-77.222657.535156h30v190h-30zm-47.222656-.535156 7.792969 189.84375-29.976563-.167969-7.789062-188.445312zm0 0"></path></svg>
                                                </a>	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        ';
                    }
                    if($iRes == 0){
                        echo'
                         <div class="alert alert-danger" role="alert">
                        Ничего не найдено
                        </div>
                        ';
                    }
                    if($iRes == 100){
                        echo'
                        <div class="alert alert-danger" role="alert">
                        Поиск прекращен т.к. слишком много результатов. Уточните запрос.
                        </div>
                        ';
                    }
                    echo'</div>';
                    
                }else{echo'
                    <div class="alert alert-danger" role="alert">
                    Ошибка в запросе
                    </div>
                    ';}
        }
    }

    if($act=='dell'){
        $page = htmlspecialchars(specfilter($_GET['page']));
        if(Page::exists($page) && $page != $Config->indexPage){
            Page::delete($page);
            System::notification('Удалена страница с идентификатором '.$page.'', 'g');
            echo'<div class="msg">Страница успешно удалена</div>';
        }else{
            System::notification('Ошибка при удалении страницы с идентификатором '.$page.', страница не найдена или запрос некорректен', 'r');
            echo'<div class="msg">Ошибка при удалении страницы</div>';
        }
?>
<script type="text/javascript">
setTimeout('window.location.href = \'pages.php?\';', 3000);
</script>
<?php
    }
}else{
echo'<div class="msg">Необходимо выполнить авторизацию</div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'index.php?\';', 3000);
</script>
<?php
}
?>
<style>
.parent-page {
    margin-bottom: 15px;
    border: 1px solid #eee;
    border-radius: 4px;
    padding: 10px;
}
.parent-header {
    font-weight: bold;
    color: #2c3e50;
}
.children {
    margin-left: 20px;
    border-left: 2px solid #eee;
    padding-left: 15px;
}
.parent-header { 
    cursor: pointer;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
    margin: 5px 0;
    display: flex;
    align-items: center;
}
.parent-header:hover {
    background: #eee;
}
.toggle {
    font-weight: bold;
    margin-right: 8px;
    width: 25px;
}
</style>

<script>
function toggleChildren(element) {
    const children = element.nextElementSibling;
    const toggle = element.querySelector('.toggle');
    children.style.display = children.style.display === 'none' ? 'block' : 'none';
    toggle.textContent = children.style.display === 'none' ? '+' : '-';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.children').forEach(el => {
        el.style.display = 'none';
    });
});
</script>
<?php
require('include/end.dat');
?>