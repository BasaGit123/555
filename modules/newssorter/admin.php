<?php
if (!class_exists('System')) exit;

$settingsFile = __DIR__ . '/../../modules/newssorter/settings.dat';
$settings = [
    'lang' => 'de',
    'default_sort' => '0'
];

// Lade die Einstellungen
if (file_exists($settingsFile)) {
    $loaded = json_decode(file_get_contents($settingsFile), true);
    if (is_array($loaded)) $settings = array_merge($settings, $loaded);
}

// Statusnachricht nur setzen, wenn das Formular abgesendet wurde
$statusMessage = null;
$statusClass = null;

$language = $_GET['lang'] ?? $settings['lang'];
$supportedLanguages = ['de', 'en', 'ru'];
if (!in_array($language, $supportedLanguages)) $language = 'de';

// Lade die Sprachdatei
$langFile = __DIR__ . "/../../modules/newssorter/lang/{$language}.dat";
$lang = [];

if (file_exists($langFile)) {
    $content = file_get_contents($langFile);
    $content = trim($content);
    $lang = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error language File: " . json_last_error_msg());
    }
} else {
    $language = 'en';
    $langFile = __DIR__ . "/../../modules/newssorter/lang/en.dat";
    $content = file_get_contents($langFile);
    $lang = json_decode($content, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $statusMessage = $lang['settings_saved'] ?? 'Einstellungen gespeichert';
    $statusClass = 'success-msg';

    $settings['lang'] = $_POST['set_lang'] ?? $settings['lang'];
    $settings['default_sort'] = $_POST['set_sort'] ?? $settings['default_sort'];

    file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$listFile = __DIR__ . '/../../data/storage/module.news2/list.dat';
$categoriesFile = __DIR__ . '/../../data/storage/module.news2/category.dat';
$newsDir = __DIR__ . '/../../data/storage/module.news2/';

$newsList = [];
if (file_exists($listFile)) {
    $content = file_get_contents($listFile);
    $content = trim($content);
    $content = preg_replace('/,\s*\]/', ']', $content);
    $content = preg_replace('/\[\s*,/', '[', $content);
    $newsList = json_decode($content, true);
}

$categories = [];
if (file_exists($categoriesFile)) {
    $content = file_get_contents($categoriesFile);
    $content = trim($content);
    $categories = json_decode($content, true);
}

$newsDetails = [];
$allCategories = [];
foreach ($newsList as $item) {
    $file = $newsDir . 'news_' . $item . '.dat';
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $cats = $data['cat'] ?? [];
            foreach ((array)$cats as $cat) {
                $allCategories[$cat] = true;
            }
            $newsDetails[$item] = [
                'date' => $data['date'] ?? '',
                'header' => $data['header'] ?? '',
                'cat' => $cats,
                'img' => $data['img'] ?: ($data['img1'] ?? '')
            ];
        }
    }
}

$allCategories = array_keys($allCategories);
$sort = $_GET['sort'] ?? $settings['default_sort'];
$adminNewsList = array_reverse($newsList);

if (in_array($sort, ['header', 'date', 'category', 'alpha'])) {
    usort($adminNewsList, function($a, $b) use ($newsDetails, $sort) {
        $valA = $newsDetails[$a][$sort] ?? '';
        $valB = $newsDetails[$b][$sort] ?? '';

        if ($sort === 'alpha') {
            return strcasecmp(mb_substr($valA ?? '', 0, 1), mb_substr($valB ?? '', 0, 1));
        }

        if ($sort === 'date') {
            $dateA = DateTime::createFromFormat('d.m.Y', $valA);
            $dateB = DateTime::createFromFormat('d.m.Y', $valB);
            if (!$dateA) return 1;
            if (!$dateB) return -1;
            return $dateB <=> $dateA;
        }

        if (is_array($valA)) $valA = implode(',', $valA);
        if (is_array($valB)) $valB = implode(',', $valB);

        return strcasecmp($valA, $valB);
    });

    if (!empty($_GET['sort'])) {
        $toSave = array_reverse($adminNewsList);
        file_put_contents($listFile, json_encode($toSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

$filterCat = $_GET['filter'] ?? '';
if (!empty($filterCat)) {
    $adminNewsList = array_filter($adminNewsList, function($item) use ($newsDetails, $filterCat) {
        return in_array($filterCat, $newsDetails[$item]['cat'] ?? []);
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_order'])) {
    $newOrder = explode(',', $_POST['new_order']);
    $newOrderReversed = array_reverse($newOrder);
    $fullList = file_exists($listFile) ? json_decode(file_get_contents($listFile), true) : [];
    $remainingItems = array_diff($fullList, $newOrderReversed);
    $mergedList = array_merge($newOrderReversed, $remainingItems);
    file_put_contents($listFile, json_encode($mergedList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!-- HTML -->
<link rel="stylesheet" href="/modules/newssorter/css/style.css">
<script src="/modules/newssorter/js/Sortable.min.js"></script>

<div id="main">
    <div class="header">
        <div class="container">
            <div class="mobile-menu-wrapper">
                <ul class="nav">
                    <a class="link" href="index.php">&#8592; <?= ($language === 'ru' ? 'Вернуться назад' : ($language === 'en' ? 'Back to overview' : 'Zurück zur Übersicht')) ?></a>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="container-newssorter" class="container">
        <h1>Сортировка новостей</h1>
        
        <fieldset>
            <legend><strong>Настройки</strong></legend>
            <form method="post" action="">
                <label for="lang-select"><?= $lang['language'] ?? 'Sprache' ?>:</label>
                <select name="set_lang" id="lang-select">
                    <option value="de" <?= $settings['lang'] === 'de' ? 'selected' : '' ?>>Deutsch</option>
                    <option value="en" <?= $settings['lang'] === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="ru" <?= $settings['lang'] === 'ru' ? 'selected' : '' ?>>Русский</option>
                </select>

                <label for="sort-select"><?= $lang['default_sort'] ?? 'Standard-Sortierung' ?>:</label>
                <select name="set_sort" id="sort-select">
                    <option value="0" <?= $settings['default_sort'] === '0' ? 'selected' : '' ?>>🔁 <?= $lang['sort_default'] ?? 'Originalreihenfolge' ?></option>
                    <option value="alpha" <?= $settings['default_sort'] === 'alpha' ? 'selected' : '' ?>>🔤 <?= $lang['sort_alpha'] ?? 'Alphabetisch' ?></option>
                    <option value="date" <?= $settings['default_sort'] === 'date' ? 'selected' : '' ?>>📅 <?= $lang['sort_date'] ?? 'Nach Datum' ?></option>
                    <option value="header" <?= $settings['default_sort'] === 'header' ? 'selected' : '' ?>>📝 <?= $lang['sort_header'] ?? 'Nach Titel' ?></option>
                    <option value="category" <?= $settings['default_sort'] === 'category' ? 'selected' : '' ?>>🏷️ <?= $lang['sort_category'] ?? 'Nach Kategorie' ?></option>
                </select>

                <button type="submit" name="save_settings" class="save-btn">
                    💾 <?= $lang['save'] ?? 'Speichern' ?>
                </button>
            </form>
        </fieldset>

        <?php if ($statusMessage): ?>
            <div id="status-message" class="<?= htmlspecialchars($statusClass) ?>" style="display:none;">
                 <?= htmlspecialchars($statusMessage) ?>
            </div>
        <?php endif; ?>

        <p><strong><?= $lang['current_display'] ?? 'Aktuelle Anzeige' ?>:</strong> <?= count($adminNewsList) ?> News<?= $filterCat ? " in Kategorie \"$filterCat\"" : '' ?>.</p>

        <form method="get" id="filter-form">
            <input type="hidden" name="module" value="newssorter">
            <input type="hidden" name="lang" value="<?= htmlspecialchars($language) ?>">
            <label for="filter">Фильтр по категориям</label>
            <select name="filter" id="filter" onchange="document.getElementById('filter-form').submit();">
                <option value=""><?= htmlspecialchars($lang['filter_all'] ?? '-- Alle Kategorien --') ?></option>
                <?php foreach ($allCategories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= isset($_GET['filter']) && $_GET['filter'] === $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="search" placeholder="🔍 <?= htmlspecialchars($lang['sort_header'] ?? 'Titel durchsuchen') ?>">
        </form>

        <?php //echo '<pre>';
   //var_dump($newsDetails[$item]);
   //echo '</pre>'; 
?>

        <form method="post" id="sort-form">
            <div id="news-list">
                <?php foreach ($adminNewsList as $item): ?>
                    <div class="news-item" data-id="<?= htmlspecialchars($item, ENT_QUOTES) ?>">
                        <div class="drag-handle">⇅</div>
                        <?php if (!empty($newsDetails[$item]['img'])): ?>
                            <img src="<?= htmlspecialchars($newsDetails[$item]['img']) ?>" class="preview" alt="Preview">
                        <?php endif; ?>
                        <strong><?= htmlspecialchars($newsDetails[$item]['header'] ?? $item) ?></strong>
                        <div class="news-meta">
                            <div><strong><?= htmlspecialchars($lang['date'] ?? 'Datum') ?>:</strong> <?= htmlspecialchars($newsDetails[$item]['date'] ?? '-') ?></div>
                            <div><strong><?= htmlspecialchars($lang['category'] ?? 'Kategorien') ?>:</strong>
                                <?php foreach ($newsDetails[$item]['cat'] ?? [] as $cat): ?>
                                    <span class="tag"><?= htmlspecialchars($cat) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="new_order" id="new-order-input">
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsList = document.getElementById('news-list');
    const newOrderInput = document.getElementById('new-order-input');

    new Sortable(newsList, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'dragging',
        onEnd: function(evt) {
            const newOrder = Array.from(newsList.children).map(item => item.getAttribute('data-id'));
            newOrderInput.value = newOrder.join(',');
            evt.item.classList.add('recently-moved');
            setTimeout(() => {
                evt.item.classList.remove('recently-moved');
            }, 10000);
            document.getElementById('sort-form').submit();
        }
    });

    document.querySelectorAll('.news-item img.preview').forEach(img => {
        img.addEventListener('click', () => {
            const overlay = document.createElement('div');
            overlay.style = 'position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); display: flex; align-items:center; justify-content:center; z-index: 9999';
            const bigImg = new Image();
            bigImg.src = img.src;
            bigImg.style = 'max-width:90%; max-height:90%; border-radius:10px; box-shadow: 0 0 20px #fff;';
            overlay.appendChild(bigImg);
            overlay.addEventListener('click', () => document.body.removeChild(overlay));
            document.body.appendChild(overlay);
        });
    });

    document.getElementById('search').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.news-item').forEach(item => {
            const title = item.querySelector('strong')?.textContent.toLowerCase() || '';
            item.style.display = title.includes(term) ? '' : 'none';
        });
    });

    var statusMessage = document.getElementById('status-message');
    if (statusMessage && statusMessage.textContent.trim() !== "") {
        statusMessage.style.display = 'block';
        setTimeout(function() {
            statusMessage.style.display = 'none';
        }, 3000);
    }
});
</script>