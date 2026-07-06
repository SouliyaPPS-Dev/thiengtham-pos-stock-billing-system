<!DOCTYPE html>
<html lang="lo" x-data="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ຮ້ານຄ້າອອນລາຍ') ?> - <?= htmlspecialchars(get_store_name()) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'ຮ້ານຄ້າອອນລາຍ') ?>">

    <link rel="icon" type="image/png" href="<?= url('/public/icon-192.png') ?>">

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
    </style>
</head>
<body x-data="{ mobileMenu: false, searchOpen: false, cartOpen: false }" class="bg-background min-h-screen flex flex-col">

    <!-- Top Header Bar -->
    <header class="bg-card shadow-sm border-b border-border sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="<?= url('/') ?>" class="flex items-center gap-2.5 flex-shrink-0">
                        <?php $__logo = get_store_logo(); if ($__logo): ?>
                        <div class="h-10 w-10 rounded-xl overflow-hidden flex-shrink-0 bg-card border border-border">
                            <img src="<?= htmlspecialchars($__logo) ?>" class="h-full w-full object-cover">
                        </div>
                        <?php else: ?>
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-store text-sm"></i>
                        </div>
                        <?php endif; ?>
                        <span class="font-black text-lg text-foreground hidden sm:block"><?= htmlspecialchars(get_store_name()) ?></span>
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
                    <a href="<?= isset($_SESSION['customer']) ? '#' : url('/login-customer') ?>"
                       @click="<?= isset($_SESSION['customer']) ? 'cartOpen = !cartOpen' : '' ?>"
                       class="hidden sm:flex items-center gap-2 h-10 px-3 rounded-xl hover:bg-muted transition-colors group relative">
                        <div class="h-8 w-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600 group-hover:bg-sky-100 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-bold text-foreground/85 hidden md:block">
                            <?= isset($_SESSION['customer']) ? htmlspecialchars($_SESSION['customer']['fullname']) : 'ເຂົ້າສູ່ລະບົບ' ?>
                        </span>
                        <?php if (isset($_SESSION['customer'])): ?>
                        <div x-show="cartOpen" @click.away="cartOpen = false" class="absolute top-full right-0 mt-1 w-56 bg-card rounded-xl border border-border shadow-xl p-2 z-50" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                            <div class="p-3 border-b border-border mb-1">
                                <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($_SESSION['customer']['fullname']) ?></p>
                                <p class="text-xs text-muted-foreground"><?= htmlspecialchars($_SESSION['customer']['email'] ?? $_SESSION['customer']['phone'] ?? '') ?></p>
                            </div>
                            <a href="<?= url('/account') ?>" class="flex items-center gap-3 px-3 py-2.5 text-foreground/85 hover:bg-gray-50 rounded-lg text-xs font-bold transition-all">
                                <i class="fas fa-user"></i>
                                <span>ບັນຊີຂອງຂ້ອຍ</span>
                            </a>
                            <a href="<?= url('/logout-customer') ?>" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-lg text-xs font-bold transition-all">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>ອອກຈາກລະບົບ</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </a>

                    <!-- Theme Toggle -->
                    <button @click="toggle()" class="h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors" :title="isDark ? 'ເປີດໂໝດກາງເວັນ' : 'ເປີດໂໝດກາງຄືນ'">
                        <i class="fas text-lg" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <!-- Cart -->
                    <a href="<?= url('/cart') ?>" class="relative h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-muted transition-colors">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="cart-count-badge"
                              class="absolute -top-1.5 -right-1.5 min-w-[22px] h-[22px] px-1.5 rounded-full bg-sky-500 text-white text-[11px] font-black flex items-center justify-center shadow-sm shadow-sky-200 <?= ((int)($cartCount ?? 0) > 0) ? '' : 'hidden' ?>"><?= (int)($cartCount ?? 0) ?></span>
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
                    <a href="<?= url('/') ?>" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' || ($_SERVER['REQUEST_URI'] ?? '/') === '/index.php' ? 'text-sky-600 bg-sky-50' : '' ?>">
                        <i class="fas fa-home mr-1.5"></i>ໜ້າຫຼັກ
                    </a>
                    <a href="<?= url('/products') ?>" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/products') === 0 ? 'text-sky-600 bg-sky-50' : '' ?>">
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
                        <button @click="catOpen = !catOpen" class="px-4 py-2 text-sm font-bold text-foreground/70 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all flex items-center gap-1.5">
                            <i class="fas fa-tags mr-0.5"></i>ໝວດສິນຄ້າ
                            <i class="fas fa-chevron-down text-[10px]" :class="catOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="catOpen" @click.away="catOpen = false"
                             class="absolute top-full left-0 mt-1 w-56 bg-card rounded-xl border border-border shadow-xl p-2 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <?php if (empty($navCategories)): ?>
                            <div class="px-3 py-4 text-sm text-muted-foreground text-center">ຍັງບໍ່ມີໝວດສິນຄ້າ</div>
                            <?php else: ?>
                            <?php foreach ($navCategories as $cat): ?>
                            <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-sky-50 text-sm font-bold text-foreground/85 hover:text-sky-600 transition-all">
                                <span class="w-7 h-7 rounded-lg bg-muted flex items-center justify-center text-muted-foreground text-xs"><i class="fas fa-tag"></i></span>
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
                <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-sky-50 hover:text-sky-600 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600"><i class="fas fa-home"></i></span>
                    ໜ້າຫຼັກ
                </a>
                <a href="<?= url('/products') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-sky-50 hover:text-sky-600 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-fuchsia-50 flex items-center justify-center text-fuchsia-600"><i class="fas fa-box"></i></span>
                    ສິນຄ້າທັງໝົດ
                </a>
                <?php foreach ($navCategories as $cat): ?>
                <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-foreground/85 hover:bg-sky-50 hover:text-sky-600 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600"><i class="fas fa-tag"></i></span>
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php endforeach; ?>
                <div class="border-t border-border my-2"></div>
                <?php if (isset($_SESSION['customer'])): ?>
                <div class="px-3 py-2 text-sm text-muted-foreground">
                    <span class="font-bold text-foreground"><?= htmlspecialchars($_SESSION['customer']['fullname']) ?></span>
                </div>
                <a href="<?= url('/logout-customer') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500"><i class="fas fa-sign-out-alt"></i></span>
                    ອອກຈາກລະບົບ
                </a>
                <?php else: ?>
                <a href="<?= url('/login-customer') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-sky-600 hover:bg-sky-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600"><i class="fas fa-user"></i></span>
                    ເຂົ້າສູ່ລະບົບ
                </a>
                <a href="<?= url('/register') ?>" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold text-emerald-600 hover:bg-emerald-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600"><i class="fas fa-user-plus"></i></span>
                    ສະໝັກສະມາຊິກ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section (optional) -->
    <?php if (isset($hero) && $hero): ?>
    <?php if (!empty($banners)): ?>
    <div x-data="{ current: 0, slides: <?= count($banners) ?> }" x-init="setInterval(() => { current = (current + 1) % slides }, 5000)" class="relative overflow-hidden bg-gradient-to-r from-sky-600 to-sky-800">
        <div class="max-w-7xl mx-auto">
            <?php foreach ($banners as $i => $banner): ?>
            <div x-show="current === <?= $i ?>" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8" class="relative">
                <div class="px-6 py-16 md:py-24 md:px-12 lg:px-16 text-center md:text-left">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight"><?= htmlspecialchars($banner['title'] ?? '') ?></h2>
                    <?php if (!empty($banner['subtitle'])): ?>
                    <p class="text-lg md:text-xl text-sky-100 mb-8 max-w-2xl mx-auto md:mx-0"><?= htmlspecialchars($banner['subtitle']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($banner['link'])): ?>
                    <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-white text-sky-700 font-bold px-8 py-3.5 rounded-xl hover:bg-sky-50 transition-all shadow-lg">
                        ສັ່ງຊື້ເລີຍ <i class="fas fa-arrow-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Dots -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <?php foreach ($banners as $i => $banner): ?>
            <button @click="current = <?= $i ?>" class="w-2.5 h-2.5 rounded-full transition-all" :class="current === <?= $i ?> ? 'bg-white w-8' : 'bg-white/40 hover:bg-white/60'"></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Default Hero -->
    <div class="bg-gradient-to-r from-sky-600 to-sky-800">
        <div class="max-w-7xl mx-auto px-6 py-16 md:py-24 md:px-12 lg:px-16 text-center">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight">ຍິນດີຕ້ອນຮັບສູ່ຮ້ານຄ້າຂອງພວກເຮົາ</h1>
            <p class="text-lg md:text-xl text-sky-100 mb-8 max-w-2xl mx-auto">ສິນຄ້າຄຸນນະພາບດີ ລາຄາຍຸດຕິທຳ ຈັດສົ່ງທົ່ວປະເທດ</p>
            <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-white text-sky-700 font-bold px-8 py-3.5 rounded-xl hover:bg-sky-50 transition-all shadow-lg">
                ເລີ່ມຊື້ເລີຍ <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>
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
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
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
    function initMapPicker(mapId, latInputId, lngInputId) {
        var isDark = document.documentElement.classList.contains('dark');
        var mapEl = document.getElementById('map-' + mapId);
        var map = L.map(mapEl).setView([17.97700, 102.63900], 15);
        L.tileLayer(isDark
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OSM</a>'
        }).addTo(map);
        var marker = null;
        var latInput = document.getElementById(latInputId);
        var lngInput = document.getElementById(lngInputId);
        var initialLat = parseFloat(latInput.value);
        var initialLng = parseFloat(lngInput.value);
        if (initialLat && initialLng) {
            marker = L.marker([initialLat, initialLng]).addTo(map);
            map.setView([initialLat, initialLng], 15);
        }
        map.on('click', function(e) {
            placeMarker(e.latlng);
        });
        function placeMarker(latlng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(latlng).addTo(map);
            latInput.value = latlng.lat.toFixed(6);
            lngInput.value = latlng.lng.toFixed(6);
        }
        window['__map_' + mapId] = { map: map, placeMarker: placeMarker };
        setTimeout(function() { map.invalidateSize(); }, 300);
    }

    function searchLocation(query, mapId) {
        if (query.length < 3) return;
        var ctx = window['__map_' + mapId];
        if (!ctx) return;
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=5&countrycodes=LA')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.length === 0) return;
                var loc = data[0];
                ctx.placeMarker([loc.lat, loc.lon]);
                ctx.map.setView([loc.lat, loc.lon], 15);
            })
            .catch(function() {});
    }

    function getCurrentLocation(mapId) {
        var ctx = window['__map_' + mapId];
        if (!ctx) return;
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
    </script>
</body>
</html>
