<?php
require('./system/global.dat');
if($Config->uriRule == 1) { 
    if (strpos(REQUEST_URI, '?') !== false) {
        $URI = explode('/', substr(REQUEST_URI, 0, strpos(REQUEST_URI, '?')));
    } else {
        $URI = explode('/', REQUEST_URI);
    }
}
if($Config->uriRule == 2) { 
    $URI = explode('/', REQUEST_URI);
}
if($URI[1] == '') { $URI[1] = $Config->indexPage; }
$originalUri = $URI;
if (strpos($URI[1], '__') !== false) {
    $pageParts = explode('__', $URI[1]);
    $testPageName = $URI[1];
    if (Page::exists($testPageName)) {
        $remainingPath = array_slice($URI, 2);
        $newUrl = '/' . implode('/', $pageParts);
        if (!empty($remainingPath)) {
            $newUrl .= '/' . implode('/', $remainingPath);
        }
        header(PROTOCOL.' 301 Moved Permanently');
        header('Location: '.$newUrl); 
        ob_end_flush(); 
        exit();
    }
}

$maxDepth = 3;
$foundPage = null;
$remainingPath = array();

for ($depth = $maxDepth; $depth >= 1; $depth--) {
    if (count($URI) - 1 >= $depth) {
        $testName = implode('__', array_slice($URI, 1, $depth));
        if (Page::exists($testName)) {
            $foundPage = $testName;
            $remainingPath = array_slice($URI, $depth + 1);
            break;
        }
    }
}

if ($foundPage !== null) {
    $URI = array_merge(
        array_slice($URI, 0, 1),
        array($foundPage),
        $remainingPath
    );
}

$MODULE_URI = array();
for($i = 2, $c = count($URI); $i < $c; ++$i) {
    $MODULE_URI[] = $URI[$i];
}
$MODULE_URI = '/'.implode('/', $MODULE_URI);
$last_key_URI = count($URI) - 1;
if(isset($URI[$last_key_URI]) && strlen($URI[$last_key_URI]) == 0) {
    if($Config->slashRule == '1') {
        $redirect_URI = '';
        foreach($originalUri as $v) {
            if($v != '') $redirect_URI .= '/'.$v;
        }
        if($redirect_URI == '/'.$Config->indexPage) { $redirect_URI = '/'; }
        header(PROTOCOL.' 301 Moved Permanently');
        header('Location: '.$redirect_URI); ob_end_flush(); exit();
    }
    if($Config->slashRule == '2') {
        header(PROTOCOL.' 404 Not Found'); require('./pages/404.html'); ob_end_flush(); exit();
    }
}
if(Page::exists($URI[1])) {
    $Page = new Page($URI[1], $Config);
    $page = $Page;
    
    if($Page->show == '1' || 
       $Page->show == '2' && $User->preferences > 0 ||
       $status == 'admin') {
        if(strpos($URI[1], '__') !== false) {
            $canonicalParts = explode('__', $URI[1]);
            $canonicalUrl = $Config->protocol.'://'.SERVER.'/'.implode('/', $canonicalParts);
            if (!empty($remainingPath)) {
                $canonicalUrl .= '/'.implode('/', $remainingPath);
            }
            $Page->headhtml .= '<link rel="canonical" href="'.$canonicalUrl.'">';
        } elseif(!$Page->isIndexPage()) {
            $canonicalUrl = $Config->protocol.'://'.SERVER.'/'.$URI[1];
            if (!empty($remainingPath)) {
                $canonicalUrl .= '/'.implode('/', $remainingPath);
            }
            $Page->headhtml .= '<link rel="canonical" href="'.$canonicalUrl.'">';
        }
        
        if($Page->module != 'no/module') {
            if(Module::isIntegrationPage($Page->module)) {
                $Page->content .= require(Module::pathRun($Page->module, 'integration_page'));
            } else {
                $Page->content = $Page->error;
            }
        }
        
        foreach($RunModules->pages as $value) {
            if(Module::isIntegrationPages($value)) {
                $Page->content .= require(Module::pathRun($value, 'integration_pages'));
            }
        }
        
        header('Content-type: text/html; charset=utf-8');
        if($Page->template != 'def/template' && Module::isTemlate($Page->template)) {
            require(Module::pathRun($Page->template, 'template'));
        } elseif(Module::isTemlate($Config->template)) {
            require(Module::pathRun($Config->template, 'template'));
        } else {
            require('./pages/template_not_found.html');
        }
        
    } else {
        header(PROTOCOL.' 404 Not Found'); require('./pages/404.html');
    }
} else {
    header(PROTOCOL.' 404 Not Found'); require('./pages/404.html');
}

foreach($RunModules->end as $value) {
    if(Module::isIntegrationEnd($value)) {
        require(Module::pathRun($value, 'integration_end'));
    }
}
        
ob_end_flush();
?>