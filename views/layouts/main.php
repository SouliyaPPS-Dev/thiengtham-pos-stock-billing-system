<?php
$layout_pref = $layout_pref ?? get_layout_preference();
$manual_pref = $_SESSION['layout_pref'] ?? 'sidebar';
$lp = '?layout=' . $manual_pref;
$adminPrefix = '/admin';
?>
<!DOCTYPE html>
<html lang="lo" x-data="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? get_store_name()) ?> - <?= htmlspecialchars(get_store_name()) ?></title>

    <link rel="icon" type="image/png" href="<?= url('/public/icon-192.png') ?>">

    <link rel="manifest" href="<?= url('/public/manifest.json') ?>" crossorigin="use-credentials">
    <meta name="theme-color" content="#0ea5e9" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">

    <script>
    (function() {
        var t = localStorage.getItem('theme');
        if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
    </script>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Stock">
    <link rel="apple-touch-icon" href="<?= url('/public/icon-192.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= url('/public/css/app.css') ?>?v=<?= filemtime(dirname(__DIR__, 2) . '/public/css/app.css') ?>">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('theme', () => ({
                isDark: document.documentElement.classList.contains('dark'),
                toggle() {
                    this.isDark = !this.isDark;
                    document.documentElement.classList.toggle('dark', this.isDark);
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    document.querySelector('meta[name="theme-color"]').setAttribute('content', this.isDark ? '#0f172a' : '#0ea5e9');
                }
            }));
        });
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <script>
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        function previewImage(url) {
            if (!url) return;
            window.dispatchEvent(new CustomEvent('open-image-preview', { detail: { url: url } }));
        }

        function confirmLogout(event) {
            event.preventDefault();
            const href = event.currentTarget.getAttribute('href');
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ຕ້ອງການອອກຈາກລະບົບແທ້ບໍ່?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'ອອກຈາກລະບົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = href;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') || urlParams.has('updated') || urlParams.has('deleted') || urlParams.has('error')) {
                let icon = 'success';
                let title = 'ສຳເລັດ';
                let text = '';
                let timer = 2500;

                if (urlParams.has('success')) text = 'ບັນທຶກຂໍ້ມູນສຳເລັດ';
                else if (urlParams.has('updated')) text = 'ແກ້ໄຂຂໍ້ມູນຮຽບຮ້ອຍ';
                else if (urlParams.has('deleted')) text = 'ລຶບຂໍ້ມູນຮຽບຮ້ອຍ';
                else if (urlParams.has('error')) {
                    icon = 'error';
                    title = 'ເກີດຂໍ້ຜິດພາດ';
                    text = urlParams.get('error_msg') || 'ມີບາງຢ່າງຜິດພາດ, ກະລຸນາລອງໃໝ່';
                    timer = 4000;
                }

                if (text) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text,
                        timer: timer,
                        showConfirmButton: icon === 'error',
                        confirmButtonColor: '#0ea5e9'
                    });
                }

                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });
    </script>
</head>
<body x-data="{ sidebarOpen: false }" class="overflow-hidden">
    <div class="h-screen flex <?= $layout_pref === 'sidebar' ? 'flex-col md:flex-row' : 'flex-col' ?>">

        <?php if (!isset($no_nav) || !$no_nav): ?>

            <?php if ($layout_pref === 'sidebar'): ?>

            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm md:hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar backdrop-blur-xl border-r border-sidebar-border transition-all duration-300 ease-out transform md:translate-x-0 md:static md:inset-0 flex-shrink-0 flex flex-col">
                <div class="flex items-center justify-between h-16 px-4 border-b border-sidebar-border flex-shrink-0">
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-2.5">
                        <?php $__logo = get_store_logo(); if ($__logo): ?>
                            <div class="h-9 w-9 rounded-xl overflow-hidden flex-shrink-0 bg-card border border-border">
                                <img src="<?= htmlspecialchars($__logo) ?>" class="h-full w-full object-cover">
                            </div>
                        <?php else: ?>
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-cash-register text-sm"></i>
                        </div>
                        <?php endif; ?>
                        <span class="font-black text-base text-sidebar-foreground tracking-tight"><?= get_store_name() ?></span>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden h-8 w-8 rounded-lg flex items-center justify-center text-muted-foreground hover:bg-muted hover:text-foreground transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
                    <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest px-3 pb-2">ເມນູຫຼັກ</div>
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin') ?>">
                        <span class="w-8 h-8 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-xs"><i class="fas fa-chart-pie"></i></span>
                        <span>ໜ້າຫຼັກ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/quotations') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/quotations') ?>">
                        <span class="w-8 h-8 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 text-xs"><i class="fas fa-file-invoice"></i></span>
                        <span>ໃບສະເໜີລາຄາ</span>
                    </a>
                    <div class="border-t border-border my-2 mx-3"></div>
                    <a href="<?= url($adminPrefix . '/pos') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/pos') ?>">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fas fa-cash-register"></i></span>
                        <span>POS ຂາຍສິນຄ້າ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/products') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/products') ?>">
                        <span class="w-8 h-8 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-xs"><i class="fas fa-box"></i></span>
                        <span>ສິນຄ້າ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/categories') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/categories') ?>">
                        <span class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-xs"><i class="fas fa-tags"></i></span>
                        <span>ໝວດສິນຄ້າ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/sales') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/sales') ?>">
                        <span class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-xs"><i class="fas fa-receipt"></i></span>
                        <span>ປະຫວັດການຂາຍ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/orders') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/orders') ?>">
                        <span class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs"><i class="fas fa-shopping-cart"></i></span>
                        <span>ສັ່ງຊື້</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/customers') ?>">
                        <span class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-xs"><i class="fas fa-users"></i></span>
                        <span>ລູກຄ້າ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/suppliers') ?>">
                        <span class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-xs"><i class="fas fa-truck"></i></span>
                        <span>ຜູ້ສະໜອງ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/promotions') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/promotions') ?>">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-xs"><i class="fas fa-bullhorn"></i></span>
                        <span>ໂປຣໂມຊັ້ນ</span>
                    </a>

                    <div class="border-t border-border my-3 mx-3"></div>
                    <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest px-3 pb-2">ຮັບ-ຈ່າຍ</div>
                    <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/expenses') ?>">
                        <span class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-xs"><i class="fas fa-money-bill-wave"></i></span>
                        <span>ລາຍຈ່າຍ</span>
                    </a>

                    <div class="border-t border-border my-3 mx-3"></div>
                    <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest px-3 pb-2">ລະບົບ</div>
                    <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/users') ?>">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-xs"><i class="fas fa-users-cog"></i></span>
                        <span>ພະນັກງານ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/settings') ?>">
                        <span class="w-8 h-8 rounded-xl bg-muted flex items-center justify-center text-muted-foreground text-xs"><i class="fas fa-cog"></i></span>
                        <span>ຕັ້ງຄ່າ</span>
                    </a>
                </nav>

                <div class="p-3 border-t border-border flex-shrink-0">
                    <div class="flex items-center gap-3 px-2 mb-2">
                        <div class="relative flex-shrink-0">
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary to-sky-700 flex items-center justify-center text-white text-sm shadow-md">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="absolute -top-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-400 border-2 border-white"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-foreground truncate leading-tight"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">ຜູ້ຈັດການ</p>
                        </div>
                    </div>
                    <button @click="toggle()" class="flex items-center gap-3 px-3 py-2.5 text-muted-foreground hover:bg-muted rounded-xl transition-all text-sm font-bold w-full mb-1" :title="isDark ? 'ເປີດໂໝດກາງເວັນ' : 'ເປີດໂໝດກາງຄືນ'">
                        <span class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center text-muted-foreground">
                            <i class="fas" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                        </span>
                        <span x-text="isDark ? 'ໂໝດກາງເວັນ' : 'ໂໝດກາງຄືນ'"></span>
                    </button>
                    <a href="<?= url($adminPrefix . '/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-all text-sm font-bold">
                        <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500"><i class="fas fa-sign-out-alt text-xs"></i></span>
                        <span>ອອກຈາກລະບົບ</span>
                    </a>
                </div>
            </aside>

            <?php else: ?>

            <header class="bg-card/80 backdrop-blur-md border-b border-border sticky top-0 z-40 shadow-sm">
                <div class="w-full px-3 lg:px-6 flex items-center h-14 lg:h-16 justify-between">
                    <div class="flex items-center gap-2 lg:gap-6 min-w-0 flex-shrink">
                        <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-2 flex-shrink-0">
                            <?php $__logo = get_store_logo(); if ($__logo): ?>
                            <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full overflow-hidden flex-shrink-0 bg-card border border-border">
                                <img src="<?= htmlspecialchars($__logo) ?>" class="h-full w-full object-cover">
                            </div>
                            <?php else: ?>
                            <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs lg:text-sm shadow-lg shadow-sky-200">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <?php endif; ?>
                            <span class="font-black text-sm lg:text-base text-primary hidden sm:inline tracking-tight"><?= get_store_name() ?></span>
                        </a>

                        <nav class="hidden md:flex items-center gap-0.5 overflow-x-auto scrollbar-hide">
                            <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-sky-100 flex items-center justify-center text-sky-600 text-[9px]"><i class="fas fa-chart-pie"></i></span>ໜ້າຫຼັກ
                            </a>
                            <a href="<?= url($adminPrefix . '/quotations') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/quotations') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-teal-100 flex items-center justify-center text-teal-600 text-[9px]"><i class="fas fa-file-invoice"></i></span>ໃບສະເໜີ
                            </a>
                            <div class="w-px h-5 bg-border mx-0.5 flex-shrink-0"></div>
                            <a href="<?= url($adminPrefix . '/pos') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center text-emerald-600 text-[9px]"><i class="fas fa-cash-register"></i></span>POS
                            </a>
                            <a href="<?= url($adminPrefix . '/products') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-[9px]"><i class="fas fa-box"></i></span>ສິນຄ້າ
                            </a>
                            <a href="<?= url($adminPrefix . '/categories') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-purple-100 flex items-center justify-center text-purple-600 text-[9px]"><i class="fas fa-tags"></i></span>ໝວດ
                            </a>
                            <a href="<?= url($adminPrefix . '/sales') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-amber-100 flex items-center justify-center text-amber-600 text-[9px]"><i class="fas fa-receipt"></i></span>ປະຫວັດ
                            </a>
                            <a href="<?= url($adminPrefix . '/orders') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/orders') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-600 text-[9px]"><i class="fas fa-shopping-cart"></i></span>ສັ່ງຊື້
                            </a>
                            <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-violet-100 flex items-center justify-center text-violet-600 text-[9px]"><i class="fas fa-users"></i></span>ລູກຄ້າ
                            </a>
                            <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 text-[9px]"><i class="fas fa-truck"></i></span>ຜູ້ສະໜອງ
                            </a>
                            <a href="<?= url($adminPrefix . '/promotions') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/promotions') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-rose-100 flex items-center justify-center text-rose-600 text-[9px]"><i class="fas fa-bullhorn"></i></span>ໂປຣໂມຊັ້ນ
                            </a>
                            <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-red-100 flex items-center justify-center text-red-600 text-[9px]"><i class="fas fa-money-bill-wave"></i></span>ລາຍຈ່າຍ
                            </a>
                            <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-rose-100 flex items-center justify-center text-rose-600 text-[9px]"><i class="fas fa-users-cog"></i></span>ພະນັກງານ
                            </a>
                            <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground/80' ?>">
                                <span class="w-5 h-5 rounded-md bg-gray-100 flex items-center justify-center text-foreground/70 text-[9px]"><i class="fas fa-cog"></i></span>ຕັ້ງຄ່າ
                            </a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-1.5 lg:gap-3 flex-shrink-0">
                        <div class="flex bg-muted p-0.5 rounded-lg border border-border">
                            <a href="?layout=sidebar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'sidebar' ? 'bg-card shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground' ?>">
                                <i class="fas fa-columns mr-0.5"></i> SB
                            </a>
                            <a href="?layout=navbar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'navbar' ? 'bg-card shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground' ?>">
                                <i class="fas fa-window-maximize mr-0.5"></i> NB
                            </a>
                        </div>

                        <button @click="toggle()" class="hidden md:flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-xl bg-muted/80 text-muted-foreground hover:bg-sky-500 hover:text-white transition-all" :title="isDark ? 'ເປີດໂໝດກາງເວັນ' : 'ເປີດໂໝດກາງຄືນ'">
                            <i class="fas text-xs" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                        </button>
                        <button onclick="toggleFullscreen()" class="hidden md:flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-xl bg-muted/80 text-muted-foreground hover:bg-sky-500 hover:text-white transition-all" title="ເຕັມຈໍ">
                            <i class="fas fa-expand text-xs"></i>
                        </button>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 h-8 lg:h-9 px-2 lg:px-3 rounded-xl bg-muted hover:bg-primary/10 transition-all group">
                                <div class="h-6 w-6 lg:h-7 lg:w-7 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fas fa-user text-[10px] lg:text-xs"></i>
                                </div>
                                <span class="hidden sm:block text-xs font-bold text-foreground/80 max-w-[120px] truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                                <i class="fas fa-chevron-down text-[8px] text-muted-foreground hidden sm:block"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-1.5 w-64 bg-card rounded-xl border border-border shadow-xl p-2 z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <div class="p-3 border-b border-border mb-1">
                                    <p class="text-sm font-black text-foreground truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                                    <p class="text-[10px] font-bold text-primary uppercase tracking-wider mt-0.5">ຜູ້ຈັດການ</p>
                                </div>
                                <a href="<?= url($adminPrefix . '/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-lg text-xs font-black transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <span class="flex-1 text-left">ອອກຈາກລະບົບ</span>
                                </a>
                            </div>
                        </div>

                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden h-8 w-8 rounded-xl bg-muted/80 flex items-center justify-center text-muted-foreground hover:bg-primary/10 hover:text-primary transition-all">
                            <i class="fas fa-bars text-sm"></i>
                        </button>
                    </div>
                </div>

                <div x-show="sidebarOpen" class="md:hidden border-t border-border bg-card/95 backdrop-blur-md p-3 space-y-1 shadow-lg" @click.away="sidebarOpen = false">
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-sm"><i class="fas fa-chart-pie"></i></span> ໜ້າຫຼັກ
                    </a>
                    <a href="<?= url($adminPrefix . '/quotations') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/quotations') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 text-sm"><i class="fas fa-file-invoice"></i></span> ໃບສະເໜີລາຄາ
                    </a>
                    <div class="border-t border-border my-2 mx-3"></div>
                    <a href="<?= url($adminPrefix . '/pos') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fas fa-cash-register"></i></span> POS ຂາຍສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/products') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-sm"><i class="fas fa-box"></i></span> ສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/categories') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-sm"><i class="fas fa-tags"></i></span> ໝວດສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/sales') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fas fa-receipt"></i></span> ປະຫວັດການຂາຍ
                    </a>
                    <a href="<?= url($adminPrefix . '/orders') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/orders') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm"><i class="fas fa-shopping-cart"></i></span> ສັ່ງຊື້
                    </a>
                    <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-sm"><i class="fas fa-users"></i></span> ລູກຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-sm"><i class="fas fa-truck"></i></span> ຜູ້ສະໜອງ
                    </a>
                    <a href="<?= url($adminPrefix . '/promotions') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/promotions') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fas fa-bullhorn"></i></span> ໂປຣໂມຊັ້ນ
                    </a>
                    <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-sm"><i class="fas fa-money-bill-wave"></i></span> ລາຍຈ່າຍ
                    </a>
                    <div class="border-t border-border my-2"></div>
                    <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fas fa-users-cog"></i></span> ພະນັກງານ
                    </a>
                    <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-foreground/70 hover:bg-muted' ?>">
                        <span class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-foreground/70 text-sm"><i class="fas fa-cog"></i></span> ຕັ້ງຄ່າ
                    </a>
                </div>
            </header>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
            <?php if ($layout_pref === 'sidebar' && (!isset($no_nav) || !$no_nav)): ?>
            <header class="h-14 md:h-16 bg-card/90 backdrop-blur-md border-b border-border flex items-center justify-between px-4 md:px-6 flex-shrink-0 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden h-9 w-9 rounded-xl bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary/10 hover:text-primary transition-all">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    <div class="hidden md:flex items-center gap-2 text-sm text-muted-foreground">
                        <i class="fas fa-home text-[10px]"></i>
                        <span class="text-border">/</span>
                        <span class="text-foreground/80 font-medium"><?= $title ?? 'ໜ້າຫຼັກ' ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex bg-muted p-0.5 rounded-lg border border-border">
                        <a href="?layout=sidebar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'sidebar' ? 'bg-card shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground' ?>">
                            <i class="fas fa-columns mr-0.5"></i> SB
                        </a>
                        <a href="?layout=navbar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'navbar' ? 'bg-card shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground' ?>">
                            <i class="fas fa-window-maximize mr-0.5"></i> NB
                        </a>
                    </div>
                    <button onclick="toggleFullscreen()" class="h-9 w-9 rounded-xl bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary/10 hover:text-primary transition-all" title="ເຕັມຈໍ">
                        <i class="fas fa-expand text-xs"></i>
                    </button>
                    <button @click="toggle()" class="h-9 w-9 rounded-xl bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary/10 hover:text-primary transition-all" :title="isDark ? 'ເປີດໂໝດກາງເວັນ' : 'ເປີດໂໝດກາງຄືນ'">
                        <i class="fas text-xs" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <div class="h-6 w-px bg-border mx-1"></div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 h-9 px-3 rounded-xl bg-muted hover:bg-primary/5 transition-all group">
                            <div class="h-6 w-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-[10px] group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="hidden sm:block text-xs font-bold text-foreground/80 max-w-[100px] truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                            <i class="fas fa-chevron-down text-[8px] text-muted-foreground"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-56 bg-card rounded-2xl border border-border shadow-xl p-2 z-50 animate-scale-in"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="p-3 border-b border-border mb-1">
                                <p class="text-sm font-black text-foreground"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mt-0.5">ຜູ້ຈັດການ</p>
                            </div>
                            <a href="<?= url($adminPrefix . '/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-xl text-xs font-bold transition-all">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500"><i class="fas fa-sign-out-alt"></i></div>
                                <span>ອອກຈາກລະບົບ</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <?php endif; ?>

            <main class="flex-1 overflow-y-auto">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script>
    (function() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(function(err) {
                    console.log('SW reg failed', err);
                });
            });
        }
    })();
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
            var map = L.map(mapEl, { zoomControl: true, scrollWheelZoom: false }).setView([17.97700, 102.63900], 15);

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
        if (typeof LAOS_ADDRESSES === 'undefined') return '';
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
        if (typeof LAOS_ADDRESSES === 'undefined') return '';
        var n = name.toLowerCase().replace(/\s*district\s*/gi, '').trim();
        var districts = LAOS_ADDRESSES[province] || [];
        for (var i = 0; i < districts.length; i++) {
            if (districts[i].toLowerCase().indexOf(n) !== -1 || n.indexOf(districts[i].toLowerCase()) !== -1) {
                return districts[i];
            }
        }
        return '';
    }

    // Auto-init map-order-show on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('map-order-show')) initMapPicker('order-show', 'shipping_latitude', 'shipping_longitude');
    });
    </script>
</body>
</html>
