<?php
session_start();


$available_languages = [
    'en' => ['name' => 'English', 'flag' => '🇺🇸', 'dir' => 'ltr'],
    'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'dir' => 'ltr'],
    'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'dir' => 'ltr'],
    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'dir' => 'ltr'],
    'ar' => ['name' => 'العربية', 'flag' => '🇸🇦', 'dir' => 'rtl'],
    'zh' => ['name' => '中文', 'flag' => '🇨🇳', 'dir' => 'ltr'],
];


$default_language = 'en';


$current_language = isset($_SESSION['language']) ? $_SESSION['language'] : $default_language;

if (!array_key_exists($current_language, $available_languages)) {
    $current_language = $default_language;
    $_SESSION['language'] = $default_language;
}


if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $available_languages)) {
    $current_language = $_GET['lang'];
    $_SESSION['language'] = $current_language;
    
    // Redirect to remove query parameter
    $current_url = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: " . $current_url);
    exit();
}


$lang_file = __DIR__ . "/../languages/{$current_language}.php";
if (file_exists($lang_file)) {
    $lang = include $lang_file;
} else {
    $lang = include __DIR__ . "/../languages/en.php";
}


function __($key, $default = '') {
    global $lang;
    return isset($lang[$key]) ? $lang[$key] : ($default ?: $key);
}


function getCurrentLanguage() {
    global $current_language, $available_languages;
    return [
        'code' => $current_language,
        'name' => $available_languages[$current_language]['name'],
        'flag' => $available_languages[$current_language]['flag'],
        'dir' => $available_languages[$current_language]['dir'],
    ];
}


function getAvailableLanguages() {
    global $available_languages;
    return $available_languages;
}


function isRTL() {
    global $current_language, $available_languages;
    return $available_languages[$current_language]['dir'] === 'rtl';
}


?>
