<?php
if (!class_exists('System')) exit; // Запрет прямого доступа

if (Module::exists('news')) {
    require(DR.'/modules/news/cfg.php');
}

$sitemapStorage = new EngineStorage('module.sitemap');

if ($sitemapStorage->iss('ignor')){
    $sitemapIgnorArray = $sitemapStorage->getArray('ignor');
}else{
    $sitemapIgnorArray = array();
}

// Вывод карты сайта если обратились по site.ru/sitemap.xml
if(preg_match ('/^\/sitemap\.xml(\?.*)?$/', REQUEST_URI)){
    $listPages = System::listPages();
    $listPages = array_diff($listPages, $sitemapIgnorArray);



    $genSitemap = false;
    if ($sitemapStorage->iss('sitemap')){
        if ($sitemapStorage->time('sitemap') + 86400 < time()){
            $genSitemap = true;
        }
    }else{
        $genSitemap = true;
    }

    header('Content-Type: text/xml; charset=utf-8');
    if($genSitemap){
        $inner = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $inner.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        
        foreach($listPages as $value){

			$inner.= '<url><loc>'.$Config->protocol.'://'.SERVER.'/'.str_replace($Config->indexPage, '', $value).'</loc></url>'."\n";

        }

        if(Module::exists('news') && $newsConfig->indexPost != 1){
            if(($listIdNews = json_decode($newsStorage->get('list'), true)) != false){
                $listIdNews = array_diff($listIdNews, $sitemapIgnorArray);
                foreach($listIdNews as $value){
                    $inner.= '<url><loc>'.$Config->protocol.'://'.SERVER.'/'.$newsConfig->idPage.'/'.$value.'</loc></url>'."\n";
                }
            }
        }
        
        $inner.= '</urlset>';
        $sitemapStorage->set('sitemap', $inner); // записали кеш
        echo $inner; // вывели кеш
    }else{
        echo $sitemapStorage->get('sitemap'); // вывели кеш
    }

    ob_end_flush(); exit;
}
?>

