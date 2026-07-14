<?php
$__allowedLangs = ['lo', 'en', 'th', 'zh'];
$__lang = $_SESSION['lang'] ?? ($_COOKIE['lang'] ?? 'lo');
if (!in_array($__lang, $__allowedLangs, true)) {
    $__lang = 'lo';
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($__lang) ?>" x-data="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ຮ້ານຄ້າອອນລາຍ') ?> - <?= htmlspecialchars(get_store_name()) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'ຮ້ານຄ້າອອນລາຍ') ?>">

    <link rel="icon" type="image/png" href="<?= url('/public/icon-192.png') ?>">

    <link rel="manifest" href="<?= url('/public/manifest.json') ?>" crossorigin="use-credentials">
    <meta name="theme-color" content="#0ea5e9" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Stock">
    <link rel="apple-touch-icon" href="<?= url('/public/apple-touch-icon.png') ?>">

    <script>
    (function() {
        var t = localStorage.getItem('theme');
        if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link rel="stylesheet" href="<?= url('/public/css/app.css') ?>?v=<?= filemtime(dirname(__DIR__, 2) . '/public/css/app.css') ?>">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('theme', () => ({
                isDark: document.documentElement.classList.contains('dark'),
                toggle() {
                    this.isDark = !this.isDark;
                    document.documentElement.classList.toggle('dark', this.isDark);
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                }
            }));
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Noto Sans Lao', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ mobileMenu: false, searchOpen: false }" class="bg-background min-h-screen flex flex-col">

    <!-- Top Header Bar -->
    <header class="bg-card/90 backdrop-blur-md border-b border-border/80 sticky top-0 z-50 transition-all duration-300 shadow-[0_2px_20px_-3px_rgba(0,0,0,0.03)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="<?= url('/') ?>" class="flex items-center gap-2.5 flex-shrink-0 group">
                        <?php $__logo = get_store_logo(); if ($__logo): ?>
                        <div class="h-10 w-10 rounded-xl overflow-hidden flex-shrink-0 bg-card border border-border group-hover:scale-105 group-hover:rotate-3 duration-300 transition-all shadow-sm">
                            <img src="<?= htmlspecialchars($__logo) ?>" class="h-full w-full object-cover">
                        </div>
                        <?php else: ?>
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center text-white shadow-lg shadow-primary/20 group-hover:scale-105 group-hover:rotate-3 duration-300 transition-all">
                            <i class="fas fa-store text-sm"></i>
                        </div>
                        <?php endif; ?>
                        <span class="font-black text-lg text-foreground hidden sm:block tracking-tight group-hover:text-primary transition-colors"><?= htmlspecialchars(get_store_name()) ?></span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:flex flex-1 max-w-lg mx-8">
                    <form action="<?= url('/products') ?>" method="GET" class="w-full relative">
                        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາສິນຄ້າ..." class="w-full pl-11 pr-4 py-2.5 bg-muted border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition-all focus:bg-card">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2">

                    <!-- Search (Mobile) -->
                    <button @click="searchOpen = !searchOpen" class="lg:hidden h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-search text-lg"></i>
                    </button>

                    <!-- Account -->
                    <?php if (isset($_SESSION['customer'])): ?>
                    <div x-data="{ accountOpen: false }" class="hidden sm:relative sm:flex">
                        <button @click="accountOpen = !accountOpen" class="flex items-center gap-2 h-10 px-3 rounded-xl hover:bg-muted transition-colors group">
                            <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary/20 transition-colors">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="text-sm font-bold text-foreground/85 hidden md:block">
                                <?= htmlspecialchars($_SESSION['customer']['fullname']) ?>
                            </span>
                        </button>
                        <div x-show="accountOpen" @click.away="accountOpen = false"
                             class="absolute top-full right-0 mt-2 w-64 bg-card rounded-xl border border-border shadow-xl p-2.5 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-cloak>
                            <div class="px-4 py-3.5 border-b border-border mb-1.5">
                                <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($_SESSION['customer']['fullname']) ?></p>
                                <p class="text-xs text-muted-foreground mt-0.5"><?= htmlspecialchars($_SESSION['customer']['email'] ?? $_SESSION['customer']['phone'] ?? '') ?></p>
                            </div>
                            <a href="<?= url('/account') ?>" class="flex items-center gap-3.5 px-4 py-3.5 text-foreground/85 hover:bg-gray-50 rounded-lg text-sm font-bold transition-all">
                                <i class="fas fa-user w-4 text-center"></i>
                                <span>ບັນຊີຂອຍຂ້ອຍ</span>
                            </a>
                            <a href="<?= url('/logout-customer') ?>" class="flex items-center gap-3.5 px-4 py-3.5 text-red-500 hover:bg-red-50 rounded-lg text-sm font-bold transition-all">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                <span>ອອກຈາກລະບົບ</span>
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="<?= url('/login-customer') ?>" class="hidden sm:flex items-center gap-2 h-10 px-3 rounded-xl hover:bg-muted transition-colors group">
                        <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary/20 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-bold text-foreground/85 hidden md:block">ເຂົ້າສູ່ລະບົບ</span>
                    </a>
                    <?php endif; ?>

                    <!-- Install App Button -->
                    <button id="pwa-install-btn" onclick="showPwaInstallPopup()" class="hidden h-10 w-10 rounded-xl items-center justify-center text-muted-foreground hover:bg-primary/10 hover:text-primary transition-colors" title="ຕິດຕັ້ງແອັບ">
                        <i class="fas fa-download text-lg"></i>
                    </button>

                    <!-- Theme Toggle -->
                    <button @click="toggle()" class="h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors" :title="isDark ? 'ເປີດໂໝດກາງເວັນ' : 'ເປີດໂໝດກາງຄືນ'">
                        <i class="fas text-lg" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <!-- Language Switcher (Lao / English / Thai / Chinese) -->
                    <?php component('language_switcher'); ?>

                    <!-- Cart -->
                    <a href="<?= url('/cart') ?>" class="relative h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="cart-count-badge"
                              class="absolute -top-1.5 -right-1.5 min-w-[22px] h-[22px] px-1.5 rounded-full bg-primary text-white text-[11px] font-black flex items-center justify-center shadow-md shadow-primary/30 <?= ((int)($cartCount ?? 0) > 0) ? '' : 'hidden' ?>"><?= (int)($cartCount ?? 0) ?></span>
                    </a>

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Search -->
            <div x-show="searchOpen" x-collapse class="lg:hidden pb-3">
                <form action="<?= url('/products') ?>" method="GET" class="relative">
                        <input type="text" name="search" placeholder="ຄົ້ນຫາສິນຄ້າ..." class="w-full pl-11 pr-4 py-3 bg-muted border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground">
                        <i class="fas fa-search"></i>
                    </span>
                </form>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="hidden lg:block border-t border-border bg-card">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-1 h-12">
                    <a href="<?= url('/') ?>" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-primary hover:bg-primary/8 rounded-lg transition-all <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' || ($_SERVER['REQUEST_URI'] ?? '/') === '/index.php' ? 'text-primary bg-primary/10' : '' ?>">
                        <i class="fas fa-home mr-1.5"></i>ໜ້າຫຼັກ
                    </a>
                    <a href="<?= url('/products') ?>" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-primary hover:bg-primary/8 rounded-lg transition-all <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/products') === 0 ? 'text-primary bg-primary/10' : '' ?>">
                        <i class="fas fa-box mr-1.5"></i>ສິນຄ້າທັງໝົດ
                    </a>

                    <?php
                    $navCategories = [];
                    try {
                        $db = \App\Core\Database::getInstance()->getConnection();
                        $stmt = $db->query("SELECT * FROM categories WHERE status = 'Active' ORDER BY sort_order ASC, name ASC LIMIT 5");
                        $navCategories = $stmt->fetchAll();
                    } catch (\Exception $e) {}
                    ?>

                    <div x-data="{ catOpen: false }" class="relative">
                        <button @click="catOpen = !catOpen" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-primary hover:bg-primary/8 rounded-lg transition-all flex items-center gap-1.5">
                            <i class="fas fa-tags mr-0.5"></i>ໝວດສິນຄ້າ
                            <i class="fas fa-chevron-down text-[10px]" :class="catOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="catOpen" @click.away="catOpen = false"
                             class="absolute top-full mt-1 w-56 bg-card rounded-xl border border-border shadow-xl p-2 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             style="right: auto; left: 50%; transform: translateX(-50%);">
                            <?php if (empty($navCategories)): ?>
                            <div class="px-3 py-4 text-sm text-muted-foreground text-center">ຍັງບໍ່ມີໝວດສິນຄ້າ</div>
                            <?php else: ?>
                            <?php foreach ($navCategories as $cat): ?>
                            <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary/8 text-sm font-bold text-foreground/85 hover:text-primary transition-all">
                                <span class="w-7 h-7 rounded-lg bg-white flex items-center justify-center text-muted-foreground text-xs"><i class="fas fa-tag"></i></span>
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-collapse class="lg:hidden border-t border-border bg-card">
            <div class="px-4 py-3 space-y-1">
                <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-primary/8 hover:text-primary transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-primary"><i class="fas fa-home"></i></span>
                    ໜ້າຫຼັກ
                </a>
                <a href="<?= url('/products') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-primary/8 hover:text-primary transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-fuchsia-600"><i class="fas fa-box"></i></span>
                    ສິນຄ້າທັງໝົດ
                </a>
                <?php foreach ($navCategories as $cat): ?>
                <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-primary/8 hover:text-primary transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-purple-600"><i class="fas fa-tag"></i></span>
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php endforeach; ?>
                <div class="border-t border-border my-2"></div>
                <?php if (isset($_SESSION['customer'])): ?>
                <div class="px-3 py-2 text-sm text-muted-foreground">
                    <span class="font-bold text-foreground"><?= htmlspecialchars($_SESSION['customer']['fullname']) ?></span>
                </div>
                <a href="<?= url('/account') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-primary/8 hover:text-primary transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-sky-600"><i class="fas fa-user"></i></span>
                    ບັນຊີຂອຍຂ້ອຍ
                </a>
                <a href="<?= url('/logout-customer') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-red-500"><i class="fas fa-sign-out-alt"></i></span>
                    ອອກຈາກລະບົບ
                </a>
                <?php else: ?>
                <a href="<?= url('/login-customer') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-primary hover:bg-primary/8 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-primary"><i class="fas fa-user"></i></span>
                    ເຂົ້າສູ່ລະບົບ
                </a>
                <a href="<?= url('/register') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-emerald-600"><i class="fas fa-user-plus"></i></span>
                    ສະໝັກສະມາຊິກ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section (optional) -->
    <?php if (isset($hero) && $hero): ?>
    <?php if (!empty($banners)): ?>
    <div x-data="{ current: 0, slides: <?= count($banners) ?>, touchX: 0 }"
         x-init="let t = setInterval(() => { current = (current + 1) % slides }, 5000)"
         class="relative overflow-hidden bg-gray-900 select-none w-full">
        <div class="relative h-[300px] sm:h-[420px] lg:h-[560px] xl:h-[600px] w-full">
            <?php foreach ($banners as $i => $banner): ?>
            <div x-show="current === <?= $i ?>"
                 x-transition:enter="transition-all ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition-all ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0"
                 @touchstart="touchX = $event.touches[0].clientX"
                 @touchend="if ($event.changedTouches[0].clientX - touchX < -50) current = (current + 1) % slides; if ($event.changedTouches[0].clientX - touchX > 50) current = (current - 1 + slides) % slides">
                <div class="absolute inset-0 bg-black/40 z-10"></div>
                <?php if (!empty($banner['image'])): ?>
                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title'] ?? '') ?>" class="w-full h-full object-cover object-center">
                <?php endif; ?>
                <div class="absolute inset-0 z-20 flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                        <div class="max-w-3xl mx-auto space-y-4 sm:space-y-6">
                            <h2 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight drop-shadow-lg"><?= htmlspecialchars($banner['title'] ?? '') ?></h2>
                            <?php if (!empty($banner['subtitle'])): ?>
                            <p class="text-sm sm:text-lg md:text-xl text-white max-w-2xl mx-auto drop-shadow-md leading-relaxed"><?= htmlspecialchars($banner['subtitle']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($banner['link'])): ?>
                            <div class="pt-2 sm:pt-4">
                                <a href="<?= url($banner['link']) ?>" class="inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-8 py-3.5 rounded-2xl shadow-xl shadow-amber-500/20 transition-all hover:scale-105 active:scale-95 duration-200 text-sm md:text-base">
                                    ສັ່ງຊື້ເລີຍ <i class="fas fa-shopping-bag"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Arrows -->
        <button @click="current = (current - 1 + slides) % slides" class="absolute left-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
            <i class="fas fa-chevron-left text-sm md:text-base"></i>
        </button>
        <button @click="current = (current + 1) % slides" class="absolute right-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
            <i class="fas fa-chevron-right text-sm md:text-base"></i>
        </button>
        <!-- Dots -->
        <div class="absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 z-30 flex gap-2">
            <?php foreach ($banners as $i => $banner): ?>
            <button @click="current = <?= $i ?>" class="h-2 md:h-2.5 rounded-full transition-all duration-300" :class="current === <?= $i ?> ? 'bg-white w-6 md:w-8' : 'bg-white/40 hover:bg-white/60 w-2 md:w-2.5'"></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Default Hero -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary via-primary to-indigo-900 py-16 lg:py-24">
        <!-- Floating abstract decorative particles -->
        <div class="absolute top-10 left-10 w-24 h-24 rounded-full bg-white/5 blur-xl animate-pulse-soft"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-white/5 blur-2xl animate-float"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 rounded-full bg-white/5 blur-xl animate-float-delayed"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs font-bold tracking-wider animate-fade-in uppercase">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        ຮ້ານຄ້າອອນລາຍຍອດນິຍົມ
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight drop-shadow-sm">
                        ຍິນດີຕ້ອນຮັບສູ່ <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-300 to-amber-200">
                            <?= htmlspecialchars(get_store_name()) ?>
                        </span>
                    </h1>
                    <p class="text-base sm:text-lg text-white/95 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        ສິນຄ້າຄຸນນະພາບດີ ລາຄາຍຸດຕິທຳ ຈັດສົ່ງໄວທົ່ວປະເທດ. ຄົ້ນພົບປະສົບການການຊື້ເຄື່ອງທີ່ສະດວກສະບາຍ ແລະ ປອດໄພທີ່ສຸດ!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start items-center">
                        <a href="<?= url('/products') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-gray-900 font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-amber-500/20 transition-all hover:scale-105 active:scale-95 duration-200 text-base">
                            ເລີ່ມຊື້ເລີຍ <i class="fas fa-shopping-bag text-sm"></i>
                        </a>
                        <a href="#featured-section" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 text-white border border-white/25 font-bold px-6 py-4 rounded-2xl transition-all text-sm backdrop-blur-sm">
                            ສິນຄ້າແນະນຳ <i class="fas fa-chevron-down text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Graphic (Floating modern glassmorphic dashboard/cards) -->
                <div class="lg:col-span-5 hidden lg:block relative">
                    <div class="relative w-full aspect-square flex items-center justify-center">
                        <!-- Background glow -->
                        <div class="absolute w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
                        
                        <!-- Floating Glass Card 1 (Main Product Mock) -->
                        <div class="absolute w-72 bg-white/10 backdrop-blur-xl border border-white/25 rounded-3xl p-5 shadow-2xl shadow-black/20 animate-float">
                            <div class="w-full aspect-[4/3] bg-gradient-to-br from-white/15 to-white/5 rounded-2xl overflow-hidden mb-4 border border-white/10 flex items-center justify-center relative group">
                                <i class="fas fa-shopping-cart text-5xl text-white/40 group-hover:scale-110 duration-500 transition-all"></i>
                                <span class="absolute top-3 left-3 bg-emerald-500 text-white text-[10px] font-black px-2 py-0.5 rounded-lg shadow-md shadow-emerald-500/30">ພ້ອມສົ່ງ</span>
                            </div>
                            <div class="space-y-2">
                                <div class="h-4 w-2/3 bg-white/20 rounded-md"></div>
                                <div class="h-3 w-1/2 bg-white/10 rounded-md"></div>
                                <div class="flex items-center justify-between pt-2">
                                    <div class="h-6 w-24 bg-white/20 rounded-md"></div>
                                    <div class="w-8 h-8 rounded-xl bg-amber-400 flex items-center justify-center text-gray-900 shadow-lg shadow-amber-400/30"><i class="fas fa-plus text-xs"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Glass Card 2 (Sale Alert) -->
                        <div class="absolute top-10 right-4 w-40 bg-white/10 backdrop-blur-xl border border-white/25 rounded-2xl p-4 shadow-xl animate-float-delayed">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-rose-500/30 border border-rose-500/30 flex items-center justify-center text-rose-300 text-xs"><i class="fas fa-fire"></i></div>
                                <span class="text-[10px] font-black text-rose-300">Hot Sale</span>
                            </div>
                            <div class="h-3 w-3/4 bg-white/20 rounded-md mb-2"></div>
                            <span class="text-xs font-black text-white">-30% Discount</span>
                        </div>

                        <!-- Floating Glass Card 3 (Secure Pay) -->
                        <div class="absolute bottom-6 left-4 w-44 bg-white/10 backdrop-blur-xl border border-white/25 rounded-2xl p-4 shadow-xl animate-float-slow">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/30 flex items-center justify-center text-emerald-300 text-sm"><i class="fas fa-shield-alt"></i></div>
                                <div>
                                    <p class="text-[9px] font-bold text-white/60">ຄວາມປອດໄພ</p>
                                    <p class="text-[10px] font-black text-white">ຮັບປະກັນ 100%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_error'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-bold">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-bold">
            <i class="fas fa-check-circle text-lg"></i>
            <span><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Store Info -->
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-store text-sm"></i>
                        </div>
                        <span class="font-black text-lg text-white"><?= htmlspecialchars(get_store_name()) ?></span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed mb-4">ຮ້ານຄ້າອອນລາຍ ສິນຄ້າຄຸນນະພາບດີ ລາຄາຍຸດຕິທຳ ຈັດສົ່ງໄວທົ່ວປະເທດ</p>
                    <div class="flex gap-3">
                        <a href="#" class="h-9 w-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-sky-600 hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-sky-600 hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-sky-600 hover:text-white transition-all"><i class="fab fa-line"></i></a>
                        <a href="#" class="h-9 w-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-sky-600 hover:text-white transition-all"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">ເມນູດ່ວນ</h3>
                    <ul class="space-y-2.5">
                        <li><a href="<?= url('/') ?>" class="text-sm text-gray-400 hover:text-white transition-colors">ໜ້າຫຼັກ</a></li>
                        <li><a href="<?= url('/products') ?>" class="text-sm text-gray-400 hover:text-white transition-colors">ສິນຄ້າທັງໝົດ</a></li>
                        <li><a href="<?= url('/cart') ?>" class="text-sm text-gray-400 hover:text-white transition-colors">ກະຕ່າສິນຄ້າ</a></li>
                        <li><a href="<?= url('/login-customer') ?>" class="text-sm text-gray-400 hover:text-white transition-colors">ເຂົ້າສູ່ລະບົບ</a></li>
                        <li><a href="<?= url('/register') ?>" class="text-sm text-gray-400 hover:text-white transition-colors">ສະໝັກສະມາຊິກ</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">ໝວດສິນຄ້າ</h3>
                    <ul class="space-y-2.5">
                        <?php if (!empty($navCategories)): ?>
                        <?php foreach ($navCategories as $cat): ?>
                        <li><a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="text-sm text-gray-400 hover:text-white transition-colors"><?= htmlspecialchars($cat['name']) ?></a></li>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <li><span class="text-sm text-gray-500">ຍັງບໍ່ມີໝວດສິນຄ້າ</span></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">ຕິດຕໍ່ພວກເຮົາ</h3>
                    <ul class="space-y-3">
                        <?php
                        $storePhone = '';
                        $storeEmail = '';
                        $storeAddress = '';
                        try {
                            $db = \App\Core\Database::getInstance()->getConnection();
                            $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('store_phone', 'store_email', 'store_address')");
                            while ($row = $stmt->fetch()) {
                                if ($row['setting_key'] === 'store_phone') $storePhone = $row['setting_value'];
                                if ($row['setting_key'] === 'store_email') $storeEmail = $row['setting_value'];
                                if ($row['setting_key'] === 'store_address') $storeAddress = $row['setting_value'];
                            }
                        } catch (\Exception $e) {}
                        ?>
                        <?php if (!empty($storePhone)): ?>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fas fa-phone mt-0.5 text-sky-500"></i>
                            <span><?= htmlspecialchars($storePhone) ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($storeEmail)): ?>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fas fa-envelope mt-0.5 text-sky-500"></i>
                            <span><?= htmlspecialchars($storeEmail) ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($storeAddress)): ?>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fas fa-map-marker-alt mt-0.5 text-sky-500"></i>
                            <span><?= htmlspecialchars($storeAddress) ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; <?= date('Y') ?> <?= htmlspecialchars(get_store_name()) ?>. ສະຫງວນສິດທິທັງໝົດ.</p>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span>ຮ້ານຄ້າອອນລາຍ</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') || urlParams.has('error') || urlParams.has('registered')) {
            let icon = 'success';
            let title = 'ສຳເລັດ';
            let text = '';
            if (urlParams.has('registered')) text = 'ສະໝັກສະມາຊິກສຳເລັດ';
            else if (urlParams.has('error')) { icon = 'error'; title = 'ເກີດຂໍ້ຜິດພາດ'; text = urlParams.get('error_msg') || 'ກະລຸນາລອງໃໝ່'; }
            if (text) {
                Swal.fire({ icon: icon, title: title, text: text, timer: 2500, showConfirmButton: false });
            }
            const newUrl = window.location.pathname + (window.location.hash || '');
            window.history.replaceState({}, document.title, newUrl);
        }

        // Auto-init visible map pickers
        if (document.getElementById('map-checkout')) initMapPicker('checkout', 'shipping_latitude', 'shipping_longitude');
        if (document.getElementById('map-map-register')) initMapPicker('map-register', 'latitude', 'longitude');
    });

    function addToCart(productId, quantity) {
        quantity = quantity || 1;
        fetch('<?= url('/cart/add') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: 'product_id=' + productId + '&quantity=' + quantity
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var badge = document.getElementById('cart-count-badge');
                if (badge) {
                    badge.textContent = data.cartCount;
                    badge.classList.toggle('hidden', data.cartCount <= 0);
                }
                Swal.fire({
                    icon: 'success',
                    title: 'ສຳເລັດ',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    customClass: { popup: 'rounded-xl mt-12' }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: data.message });
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: 'ກະລຸນາລອງໃໝ່' });
        });
    }
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    var mapTileLayers = {};

    function initMapPicker(mapId, latInputId, lngInputId) {
        var ctx = { map: null, placeMarker: null, _searchTimer: null, _searchDropdown: null };
        window['__map_' + mapId] = ctx;

        var mapEl = document.getElementById('map-' + mapId);
        if (!mapEl) return;
        if (typeof L === 'undefined') return;

        try {
            var map = L.map(mapEl).setView([17.97700, 102.63900], 15);

            var streetLayer = L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                { maxZoom: 19, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' }
            );
            var satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                { maxZoom: 19, attribution: '&copy; <a href="https://www.esri.com">Esri</a>' }
            );
            var hybridLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
                { maxZoom: 19, attribution: '&copy; <a href="https://www.esri.com">Esri</a>' }
            );

            var layers = { street: streetLayer, satellite: satelliteLayer, hybrid: hybridLayer };
            var currentLayer = 'satellite';
            satelliteLayer.addTo(map);
            mapTileLayers[mapId] = layers;

            var marker = null;
            var latInput = document.getElementById(latInputId);
            var lngInput = document.getElementById(lngInputId);
            if (!latInput || !lngInput) { ctx.map = map; return; }
            var initialLat = parseFloat(latInput.value);
            var initialLng = parseFloat(lngInput.value);
            if (initialLat && initialLng) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
                map.setView([initialLat, initialLng], 15);
            }

            function placeMarker(latlng) {
                if (marker) map.removeLayer(marker);
                marker = L.marker(latlng).addTo(map);
                latInput.value = latlng.lat.toFixed(6);
                lngInput.value = latlng.lng.toFixed(6);
                var latDisp = document.getElementById('lat-display');
                var lngDisp = document.getElementById('lng-display');
                if (latDisp) latDisp.textContent = latlng.lat.toFixed(6);
                if (lngDisp) lngDisp.textContent = latlng.lng.toFixed(6);
                var gmaps = document.getElementById('gmaps-link');
                if (gmaps) gmaps.href = 'https://www.google.com/maps?q=' + latlng.lat.toFixed(6) + ',' + latlng.lng.toFixed(6);
            }

            map.on('click', function(e) { placeMarker(e.latlng); });

            function switchLayer(name) {
                if (name === currentLayer) return;
                Object.keys(layers).forEach(function(k) {
                    if (k === name) { map.addLayer(layers[k]); }
                    else { map.removeLayer(layers[k]); }
                });
                currentLayer = name;
                var btns = mapEl.querySelectorAll('.map-layer-btn');
                btns.forEach(function(b) {
                    b.classList.toggle('bg-sky-600', b.dataset.layer === name);
                    b.classList.toggle('text-white', b.dataset.layer === name);
                    b.classList.toggle('bg-white', b.dataset.layer !== name);
                    b.classList.toggle('text-gray-700', b.dataset.layer !== name);
                    b.classList.toggle('shadow-sm', b.dataset.layer !== name);
                });
                setTimeout(function() { map.invalidateSize(); }, 100);
            }

            // Custom layer toggle buttons (inside map area)
            var layerBar = document.createElement('div');
            layerBar.className = 'flex gap-0.5 rounded-lg overflow-hidden shadow-md border border-gray-200';
            layerBar.style.cssText = 'position:absolute;bottom:12px;left:12px;z-index:1001';
            var layerNames = ['street', 'satellite', 'hybrid'];
            var layerLabels = {'street':'ຖະໜົນ','satellite':'ດາວທຽມ','hybrid':'ປະສົມ'};
            layerNames.forEach(function(n) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'map-layer-btn px-2.5 py-1.5 text-[11px] font-bold cursor-pointer leading-none' +
                    (n === currentLayer ? ' bg-sky-600 text-white' : ' bg-white text-gray-700');
                btn.dataset.layer = n;
                btn.textContent = layerLabels[n];
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    switchMapLayer(mapId, n);
                });
                layerBar.appendChild(btn);
            });
            mapEl.style.position = 'relative';
            mapEl.appendChild(layerBar);

            // Current location button (inside map area)
            var locBtn = document.createElement('button');
            locBtn.className = 'w-9 h-9 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-all';
            locBtn.setAttribute('type', 'button');
            locBtn.setAttribute('title', 'ຊອກຫາທີ່ຢູ່ຂອງຂ້ອຍ');
            locBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>';
            locBtn.addEventListener('click', function() { getCurrentLocation(mapId); });
            mapEl.appendChild(locBtn);
            locBtn.style.cssText = 'position:absolute;top:12px;right:12px;z-index:1001';

            ctx.map = map;
            ctx.placeMarker = placeMarker;
            setTimeout(function() { map.invalidateSize(); }, 300);
        } catch(e) {}
    }

    function switchMapLayer(mapId, name) {
        var ctx = window['__map_' + mapId];
        if (!ctx || !ctx.map) return;
        var layers = mapTileLayers[mapId];
        if (!layers || !layers[name]) return;
        Object.keys(layers).forEach(function(k) {
            if (k === name) ctx.map.addLayer(layers[k]);
            else ctx.map.removeLayer(layers[k]);
        });
        var el = document.getElementById('map-' + mapId);
        if (el) {
            var btns = el.querySelectorAll('.map-layer-btn');
            btns.forEach(function(b) {
                b.classList.toggle('bg-sky-600', b.dataset.layer === name);
                b.classList.toggle('text-white', b.dataset.layer === name);
                b.classList.toggle('bg-white', b.dataset.layer !== name);
                b.classList.toggle('text-gray-700', b.dataset.layer !== name);
                b.classList.toggle('shadow-sm', b.dataset.layer !== name);
            });
        }
        setTimeout(function() { ctx.map.invalidateSize(); }, 100);
    }

    function searchLocation(query, mapId) {
        var ctx = window['__map_' + mapId];
        if (!ctx) return;
        if (ctx._searchTimer) clearTimeout(ctx._searchTimer);
        query = (query || '').trim();
        if (query.length < 3) {
            if (ctx._searchDropdown) { ctx._searchDropdown.remove(); ctx._searchDropdown = null; }
            return;
        }
        ctx._searchTimer = setTimeout(function() {
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=5&countrycodes=LA')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (ctx._searchDropdown) { ctx._searchDropdown.remove(); ctx._searchDropdown = null; }
                    if (data.length === 0) return;
                    var searchInput = document.getElementById('map-search-' + mapId);
                    if (!searchInput) return;
                    var dropdown = document.createElement('div');
                    dropdown.className = 'absolute z-50 mt-1 w-full bg-white border border-border rounded-xl shadow-xl max-h-48 overflow-y-auto';
                    dropdown.style.top = '100%';
                    dropdown.style.left = '0';
                    for (var i = 0; i < data.length; i++) {
                        (function(loc) {
                            var item = document.createElement('div');
                            item.className = 'px-4 py-2.5 cursor-pointer text-sm hover:bg-primary/10 hover:text-primary transition-colors text-gray-700 border-b border-gray-50 last:border-b-0';
                            item.textContent = loc.display_name;
                            item.addEventListener('click', function() {
                                var lat = parseFloat(loc.lat);
                                var lng = parseFloat(loc.lon);
                                if (ctx.placeMarker && ctx.map) {
                                    ctx.placeMarker({lat: lat, lng: lng});
                                    ctx.map.setView([lat, lng], 15);
                                }
                                searchInput.value = loc.display_name;
                                if (ctx._searchDropdown) { ctx._searchDropdown.remove(); ctx._searchDropdown = null; }
                                if (typeof autoFillAddress === 'function') autoFillAddress(loc);
                            });
                            dropdown.appendChild(item);
                        })(data[i]);
                    }
                    var parent = searchInput.parentElement;
                    parent.appendChild(dropdown);
                    ctx._searchDropdown = dropdown;
                })
                .catch(function() {});
        }, 400);
    }

    // Close search dropdown on outside click
    document.addEventListener('click', function(e) {
        for (var key in window) {
            if (key.indexOf('__map_') !== 0) continue;
            var ctx = window[key];
            if (!ctx._searchDropdown) continue;
            var mapId = key.replace('__map_', '');
            var searchInput = document.getElementById('map-search-' + mapId);
            if (ctx._searchDropdown.contains(e.target)) continue;
            if (searchInput && searchInput.contains(e.target)) continue;
            ctx._searchDropdown.remove();
            ctx._searchDropdown = null;
        }
    });

    function getCurrentLocation(mapId) {
        var ctx = window['__map_' + mapId];
        if (!ctx || !ctx.map) return;
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'warning', title: 'ບໍ່ສາມາດເຂົ້າເຖິງຕຳແໜ່ງ', text: 'ບຣາວເຊີຂອງທ່ານບໍ່ຮອງຮັບ' });
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var latlng = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                ctx.placeMarker(latlng);
                ctx.map.setView([latlng.lat, latlng.lng], 16);
            },
            function() {
                Swal.fire({ icon: 'warning', title: 'ບໍ່ສາມາດເຂົ້າເຖິງຕຳແໜ່ງ', text: 'ກະລຸນາອະນຸຍາດການເຂົ້າເຖິງຕຳແໜ່ງ' });
            }
        );
    }

    function autoFillAddress(loc) {
        var el = document.querySelector('[x-data^="addressPicker"]');
        if (!el || typeof Alpine === 'undefined') return;
        var data = Alpine.$data(el);
        var addr = loc.address || {};

        var province = matchLaosProvince(addr.state || '');
        if (province) {
            data.province = province;
            data.provinceSearch = province;
            data.district = '';
            data.districtSearch = '';
            data.village = '';
            data.villageSearch = '';
            data.villageResults = [];
        }

        if (province) {
            var district = matchLaosDistrict(addr.county || '', province);
            if (district) {
                data.district = district;
                data.districtSearch = district;
                data.village = '';
                data.villageSearch = '';
            }
        }

        var village = addr.village || addr.town || addr.city || addr.hamlet || addr.suburb || '';
        if (village) {
            data.village = village;
            data.villageSearch = village;
        }
    }

    function matchLaosProvince(state) {
        if (!state) return '';
        var s = state.toLowerCase().trim();
        var laoKeys = Object.keys(LAOS_ADDRESSES);
        for (var i = 0; i < laoKeys.length; i++) {
            if (s.indexOf(laoKeys[i].toLowerCase()) !== -1) return laoKeys[i];
        }
        var map = {
            'vientiane capital': laoKeys[0],
            'vientiane prefecture': 'ວຽງຈັນ',
            'vientiane province': 'ວຽງຈັນ',
            'phongsaly province': 'ຜົ້ງສາລີ',
            'luang namtha province': 'ຫຼວງນໍ້າທາ',
            'oudomxay province': 'ອຸດົມໄຊ',
            'bokeo province': 'ບໍ່ແກ້ວ',
            'luang prabang province': 'ຫຼວງພະບາງ',
            'huaphanh province': 'ຫົວພັນ',
            'sainyabuli province': 'ໄຊຍະບູລີ',
            'xieng khouang province': 'ຊຽງຂວາງ',
            'bolikhamxay province': 'ບໍລິຄຳໄຊ',
            'khammouane province': 'ຄຳມ່ວນ',
            'savannakhet province': 'ສະຫວັນນະເຂດ',
            'saravane province': 'ສາລະວັນ',
            'sekong province': 'ເຊກອງ',
            'champasack province': 'ຈຳປາສັກ',
            'champasak province': 'ຈຳປາສັກ',
            'attapeu province': 'ອັດຕະປື',
            'xaysomboune province': 'ໄຊສົມບູນ',
        };
        return map[s] || '';
    }

    function matchLaosDistrict(name, province) {
        if (!name || !province) return '';
        var n = name.toLowerCase().replace(/\s*district\s*/gi, '').trim();
        var districts = LAOS_ADDRESSES[province] || [];
        for (var i = 0; i < districts.length; i++) {
            if (districts[i].toLowerCase().indexOf(n) !== -1 || n.indexOf(districts[i].toLowerCase()) !== -1) {
                return districts[i];
            }
        }
        return '';
    }
    </script>

    <?php $whatsappNumber = get_store_whatsapp(); ?>
    <?php if (!empty($whatsappNumber)): ?>
    <?php
    $waClean = preg_replace('/[^0-9]/', '', $whatsappNumber);
    if (strlen($waClean) >= 9):
    ?>
    <a href="https://wa.me/<?= $waClean ?>?text=ສະບາຍດີ,%20ຂ້ອຍສົນໃຈສິນຄ້າ" target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;align-items:center;justify-content:center;width:60px;height:60px;border-radius:50%;background-color:#25D366;color:#fff;box-shadow:0 4px 20px rgba(37,211,102,0.4);transition:all 0.3s ease;text-decoration:none;"
       onmouseover="this.style.transform='scale(1.1)';this.style.boxShadow='0 8px 30px rgba(37,211,102,0.6)'"
       onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 20px rgba(37,211,102,0.4)'"
       title="ສົນທະນາຜ່ານ WhatsApp"
       aria-label="ສົນທະນາຜ່ານ WhatsApp">
        <i class="fa-brands fa-whatsapp" style="font-size:28px;"></i>
        <span style="position:absolute;top:-4px;right:-4px;width:20px;height:20px;border-radius:50%;background-color:#ef4444;border:2px solid #fff;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-comment-dots" style="font-size:8px;color:#fff;"></i>
        </span>
    </a>
    <style>
    @keyframes whatsapp-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    a[title="ສົນທະນາຜ່ານ WhatsApp"] {
        animation: whatsapp-float 2.5s ease-in-out infinite;
    }
    </style>
    <?php endif; ?>
    <?php endif; ?>

    <!-- PWA Install Script -->
    <script>
    (function() {
        var deferredPrompt = null;
        var installBtn = document.getElementById('pwa-install-btn');
        var popupShown = false;

        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
        if (isStandalone) return;

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(function() {});
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            if (installBtn) installBtn.classList.remove('hidden');
            if (!popupShown) {
                popupShown = true;
                setTimeout(function() { showPwaInstallPopup(); }, 3000);
            }
        });

        window.addEventListener('appinstalled', function() {
            deferredPrompt = null;
            popupShown = true;
            if (installBtn) installBtn.classList.add('hidden');
        });

        var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        var isMacSafari = navigator.userAgent.indexOf('Safari') > -1 && navigator.userAgent.indexOf('Chrome') === -1;
        if ((isIOS || isMacSafari) && !popupShown) {
            popupShown = true;
            setTimeout(function() { showPwaInstallPopup(); }, 3000);
        }

        window.showPwaInstallPopup = function() {
            var isStandaloneNow = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
            if (isStandaloneNow) return;

            if (deferredPrompt) {
                Swal.fire({
                    title: 'ຕິດຕັ້ງແອັບ',
                    html: '<div class="text-left space-y-3 text-sm">' +
                        '<div class="flex items-center gap-3 bg-sky-50 rounded-xl p-3">' +
                        '<div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-white border border-border shadow-sm"><img src="<?= url('/public/logo.png') ?>" alt="logo" class="w-full h-full object-cover"></div>' +
                        '<div><p class="font-bold text-foreground">ຕິດຕັ້ງ <?= htmlspecialchars(get_store_name()) ?></p><p class="text-xs text-muted-foreground mt-0.5">ເພີ່ມໄວ້ໜ້າຈໍເພື່ອເຂົ້າເຖິງໄວ</p></div>' +
                        '</div>' +
                        '<ul class="space-y-2 mt-3">' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span><span>ກົດປຸ່ມ <strong>"ຕິດຕັ້ງ"</strong> ຂ້າງລຸ່ມ</span></li>' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span><span>ກົດ <strong>"ເພີ່ມໃສ່ໜ້າຫຼັກ"</strong> ຫຼື <strong>"ຕິດຕັ້ງ"</strong></span></li>' +
                        '</ul></div>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#0ea5e9',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-download mr-1.5"></i>ຕິດຕັ້ງ',
                    cancelButtonText: 'ພາຍຫຼັງ',
                    customClass: { popup: 'rounded-3xl' }
                }).then(function(result) {
                    if (result.isConfirmed && deferredPrompt) {
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then(function(choice) {
                            deferredPrompt = null;
                        });
                    }
                });
            } else if (isIOS || isMacSafari) {
                Swal.fire({
                    title: 'ຕິດຕັ້ງແອັບ',
                    html: '<div class="text-left space-y-3 text-sm">' +
                        '<div class="flex items-center gap-3 bg-sky-50 rounded-xl p-3">' +
                        '<div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-white border border-border shadow-sm"><img src="<?= url('/public/logo.png') ?>" alt="logo" class="w-full h-full object-cover"></div>' +
                        '<div><p class="font-bold text-foreground">ຕິດຕັ້ງ <?= htmlspecialchars(get_store_name()) ?></p><p class="text-xs text-muted-foreground mt-0.5">ເພີ່ມໄວ້ໜ້າຈໍເພື່ອເຂົ້າເຖິງໄວ</p></div>' +
                        '</div>' +
                        '<ul class="space-y-2 mt-3">' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span><span>ກົດປຸ່ມ <strong>Share</strong> (<i class="fas fa-arrow-up-from-bracket text-primary"></i>)</span></li>' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span><span>ເລືອກ <strong>"Add to Home Screen"</strong></span></li>' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span><span>ກົດ <strong>"Add"</strong></span></li>' +
                        '</ul></div>',
                    icon: 'info',
                    confirmButtonColor: '#0ea5e9',
                    confirmButtonText: 'ເຂົ້າໃຈແລ້ວ',
                    customClass: { popup: 'rounded-3xl' }
                });
            } else {
                Swal.fire({
                    title: 'ຕິດຕັ້ງແອັບ',
                    html: '<div class="text-left space-y-3 text-sm">' +
                        '<div class="flex items-center gap-3 bg-sky-50 rounded-xl p-3">' +
                        '<div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-white border border-border shadow-sm"><img src="<?= url('/public/logo.png') ?>" alt="logo" class="w-full h-full object-cover"></div>' +
                        '<div><p class="font-bold text-foreground">ຕິດຕັ້ງ <?= htmlspecialchars(get_store_name()) ?></p><p class="text-xs text-muted-foreground mt-0.5">ເພີ່ມໄວ້ໜ້າຈໍເພື່ອເຂົ້າເຖິງໄວ</p></div>' +
                        '</div>' +
                        '<ul class="space-y-2 mt-3">' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span><span>ກົດ <strong>⋮</strong> (ມຸມ) ຢູ່ບຣາວເຊີ</span></li>' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span><span>ເລືອກ <strong>"Add to Home Screen"</strong></span></li>' +
                        '<li class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span><span>ກົດ <strong>"Install"</strong></span></li>' +
                        '</ul></div>',
                    icon: 'info',
                    confirmButtonColor: '#0ea5e9',
                    confirmButtonText: 'ເຂົ້າໃຈແລ້ວ',
                    customClass: { popup: 'rounded-3xl' }
                });
            }
        };
    })();
    </script>

    <!-- Background Google Translate engine (no visible UI) -->
    <?php component('translator'); ?>

    <!-- AI Chatbot (storefront) -->
    <?php component('chatbot'); ?>
</body>
</html>
