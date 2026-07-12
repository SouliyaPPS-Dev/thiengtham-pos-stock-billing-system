<?php
// Language switcher (Lao / English / Thai / Chinese) with national flag icons.
// Uses the Google Translate engine (window.setLang -> googtrans cookie).
$allowedLangs = ['lo', 'en', 'th', 'zh'];
$currentLang = 'lo';
if (!empty($_COOKIE['googtrans']) && preg_match('#/lo/([a-z\-]+)#i', $_COOKIE['googtrans'], $m)) {
    $g = strtolower($m[1]);
    $rev = ['lo' => 'lo', 'en' => 'en', 'th' => 'th', 'zh-cn' => 'zh'];
    if (isset($rev[$g])) {
        $currentLang = $rev[$g];
    }
}
$langs = [
    'lo' => ['name' => 'ລາວ',     'flag' => '🇱🇦'],
    'en' => ['name' => 'English',  'flag' => '🇬🇧'],
    'th' => ['name' => 'ไทย',      'flag' => '🇹🇭'],
    'zh' => ['name' => '中文',      'flag' => '🇨🇳'],
];
?>
<div x-data="{ open: false }" data-notranslate notranslate class="relative">
    <button @click="open = !open" type="button"
            class="h-10 px-3 rounded-xl flex items-center gap-1.5 text-muted-foreground hover:bg-muted transition-colors"
            title="ພາສາ / Language">
        <span class="text-lg leading-none"><?= $langs[$currentLang]['flag'] ?></span>
        <span class="text-sm font-bold hidden md:block"><?= htmlspecialchars($langs[$currentLang]['name']) ?></span>
        <i class="fas fa-chevron-down text-[10px]"></i>
    </button>
    <div x-show="open" @click.away="open = false" x-cloak
         class="absolute top-full right-0 mt-2 w-52 bg-card rounded-xl border border-border shadow-xl p-1.5 z-50"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <p class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-muted-foreground">Language / ພາສາ</p>
        <?php foreach ($langs as $code => $l): ?>
        <button type="button" @click="open = false; window.setLang('<?= $code ?>')"
           class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-bold transition-all hover:bg-primary/8 <?= $code === $currentLang ? 'text-primary bg-primary/10' : 'text-foreground/85' ?>">
            <span class="text-lg leading-none"><?= $l['flag'] ?></span>
            <span class="flex-1 text-left"><?= htmlspecialchars($l['name']) ?></span>
            <?php if ($code === $currentLang): ?><i class="fas fa-check text-primary text-xs"></i><?php endif; ?>
        </button>
        <?php endforeach; ?>
        <div class="border-t border-border my-1.5"></div>
        <button type="button" @click="open = false; window.setLang('lo')"
           class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-bold text-muted-foreground hover:bg-muted transition-all">
            <i class="fas fa-language text-base"></i>
            <span class="flex-1 text-left">ພາສາເດີມ (Show original)</span>
        </button>
    </div>
</div>
