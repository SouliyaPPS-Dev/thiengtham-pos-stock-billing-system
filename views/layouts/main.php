<?php
$layout_pref = $layout_pref ?? get_layout_preference();
$manual_pref = $_SESSION['layout_pref'] ?? 'sidebar';
$lp = '?layout=' . $manual_pref;
$adminPrefix = '/admin';
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS & Stock - ລະບົບຂາຍ ແລະ ຈັດການສາງສິນຄ້າ' ?></title>

    <link rel="icon" type="image/png" href="<?= url('/public/icon-192.png') ?>">

    <link rel="manifest" href="<?= url('/public/manifest.json') ?>" crossorigin="use-credentials">
    <meta name="theme-color" content="#0ea5e9">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Stock">
    <link rel="apple-touch-icon" href="<?= url('/public/icon-192.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= url('/public/css/app.css') ?>">

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
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-white/95 backdrop-blur-xl border-r border-gray-100 transition-all duration-300 ease-out transform md:translate-x-0 md:static md:inset-0 flex-shrink-0 flex flex-col">
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100 flex-shrink-0">
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-cash-register text-sm"></i>
                        </div>
                        <span class="font-black text-base text-gray-800 tracking-tight"><?= get_store_name() ?></span>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden h-8 w-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 pb-2">ເມນູຫຼັກ</div>
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin') ?>">
                        <span class="w-8 h-8 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-xs"><i class="fas fa-chart-pie"></i></span>
                        <span>ໜ້າຫຼັກ</span>
                    </a>
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
                    <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/customers') ?>">
                        <span class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-xs"><i class="fas fa-users"></i></span>
                        <span>ລູກຄ້າ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/suppliers') ?>">
                        <span class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-xs"><i class="fas fa-truck"></i></span>
                        <span>ຜູ້ສະໜອງ</span>
                    </a>

                    <div class="border-t border-gray-100 my-3 mx-3"></div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 pb-2">ຮັບ-ຈ່າຍ</div>
                    <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/expenses') ?>">
                        <span class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-xs"><i class="fas fa-money-bill-wave"></i></span>
                        <span>ລາຍຈ່າຍ</span>
                    </a>

                    <div class="border-t border-gray-100 my-3 mx-3"></div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-3 pb-2">ລະບົບ</div>
                    <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/users') ?>">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-xs"><i class="fas fa-users-cog"></i></span>
                        <span>ພະນັກງານ</span>
                    </a>
                    <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-bold <?= get_menu_active_class('/admin/settings') ?>">
                        <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 text-xs"><i class="fas fa-cog"></i></span>
                        <span>ຕັ້ງຄ່າ</span>
                    </a>
                </nav>

                <div class="p-3 border-t border-gray-100 flex-shrink-0">
                    <div class="flex items-center gap-3 px-2 mb-2">
                        <div class="relative flex-shrink-0">
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary to-sky-700 flex items-center justify-center text-white text-sm shadow-md">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="absolute -top-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-400 border-2 border-white"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate leading-tight"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ຜູ້ຈັດການ</p>
                        </div>
                    </div>
                    <a href="<?= url($adminPrefix . '/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-all text-sm font-bold">
                        <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500"><i class="fas fa-sign-out-alt text-xs"></i></span>
                        <span>ອອກຈາກລະບົບ</span>
                    </a>
                </div>
            </aside>

            <?php else: ?>

            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 shadow-sm">
                <div class="w-full px-3 lg:px-6 flex items-center h-14 lg:h-16 justify-between">
                    <div class="flex items-center gap-2 lg:gap-6 min-w-0 flex-shrink">
                        <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-2 flex-shrink-0">
                            <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs lg:text-sm shadow-lg shadow-sky-200">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <span class="font-black text-sm lg:text-base text-primary hidden sm:inline tracking-tight"><?= get_store_name() ?></span>
                        </a>

                        <nav class="hidden md:flex items-center gap-0.5 overflow-x-auto scrollbar-hide">
                            <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-sky-100 flex items-center justify-center text-sky-600 text-[9px]"><i class="fas fa-chart-pie"></i></span>ໜ້າຫຼັກ
                            </a>
                            <a href="<?= url($adminPrefix . '/pos') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center text-emerald-600 text-[9px]"><i class="fas fa-cash-register"></i></span>POS
                            </a>
                            <a href="<?= url($adminPrefix . '/products') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-[9px]"><i class="fas fa-box"></i></span>ສິນຄ້າ
                            </a>
                            <a href="<?= url($adminPrefix . '/categories') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-purple-100 flex items-center justify-center text-purple-600 text-[9px]"><i class="fas fa-tags"></i></span>ໝວດ
                            </a>
                            <a href="<?= url($adminPrefix . '/sales') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-amber-100 flex items-center justify-center text-amber-600 text-[9px]"><i class="fas fa-receipt"></i></span>ປະຫວັດ
                            </a>
                            <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-violet-100 flex items-center justify-center text-violet-600 text-[9px]"><i class="fas fa-users"></i></span>ລູກຄ້າ
                            </a>
                            <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 text-[9px]"><i class="fas fa-truck"></i></span>ຜູ້ສະໜອງ
                            </a>
                            <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-red-100 flex items-center justify-center text-red-600 text-[9px]"><i class="fas fa-money-bill-wave"></i></span>ລາຍຈ່າຍ
                            </a>
                            <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-rose-100 flex items-center justify-center text-rose-600 text-[9px]"><i class="fas fa-users-cog"></i></span>ພະນັກງານ
                            </a>
                            <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/admin/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-gray-100 flex items-center justify-center text-gray-600 text-[9px]"><i class="fas fa-cog"></i></span>ຕັ້ງຄ່າ
                            </a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-1.5 lg:gap-3 flex-shrink-0">
                        <div class="flex bg-gray-100/80 p-0.5 rounded-lg border border-gray-200/50">
                            <a href="?layout=sidebar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'sidebar' ? 'bg-white shadow-sm text-primary' : 'text-gray-400 hover:text-gray-600' ?>">
                                <i class="fas fa-columns mr-0.5"></i> SB
                            </a>
                            <a href="?layout=navbar" class="px-2 lg:px-2.5 py-1 rounded-md text-[9px] font-black transition-all <?= $layout_pref === 'navbar' ? 'bg-white shadow-sm text-primary' : 'text-gray-400 hover:text-gray-600' ?>">
                                <i class="fas fa-window-maximize mr-0.5"></i> NB
                            </a>
                        </div>

                        <button onclick="toggleFullscreen()" class="hidden md:flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-xl bg-gray-100/80 text-gray-400 hover:bg-sky-500 hover:text-white transition-all" title="ເຕັມຈໍ">
                            <i class="fas fa-expand text-xs"></i>
                        </button>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 h-8 lg:h-9 px-2 lg:px-3 rounded-xl bg-gray-100/80 hover:bg-primary/10 transition-all group">
                                <div class="h-6 w-6 lg:h-7 lg:w-7 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fas fa-user text-[10px] lg:text-xs"></i>
                                </div>
                                <span class="hidden sm:block text-xs font-bold text-gray-700 max-w-[120px] truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                                <i class="fas fa-chevron-down text-[8px] text-gray-400 hidden sm:block"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-1.5 w-64 bg-white rounded-xl border border-gray-100 shadow-xl p-2 z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <div class="p-3 border-b mb-1">
                                    <p class="text-sm font-black text-gray-800 truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
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

                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden h-8 w-8 rounded-xl bg-gray-100/80 flex items-center justify-center text-gray-500 hover:bg-primary/10 hover:text-primary transition-all">
                            <i class="fas fa-bars text-sm"></i>
                        </button>
                    </div>
                </div>

                <div x-show="sidebarOpen" class="md:hidden border-t border-gray-50 bg-white/95 backdrop-blur-md p-3 space-y-1 shadow-lg" @click.away="sidebarOpen = false">
                    <a href="<?= url($adminPrefix . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-sm"><i class="fas fa-chart-pie"></i></span> ໜ້າຫຼັກ
                    </a>
                    <a href="<?= url($adminPrefix . '/pos') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fas fa-cash-register"></i></span> POS ຂາຍສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/products') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-sm"><i class="fas fa-box"></i></span> ສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/categories') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-sm"><i class="fas fa-tags"></i></span> ໝວດສິນຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/sales') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fas fa-receipt"></i></span> ປະຫວັດການຂາຍ
                    </a>
                    <a href="<?= url($adminPrefix . '/customers') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-sm"><i class="fas fa-users"></i></span> ລູກຄ້າ
                    </a>
                    <a href="<?= url($adminPrefix . '/suppliers') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-sm"><i class="fas fa-truck"></i></span> ຜູ້ສະໜອງ
                    </a>
                    <a href="<?= url($adminPrefix . '/expenses') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center text-red-600 text-sm"><i class="fas fa-money-bill-wave"></i></span> ລາຍຈ່າຍ
                    </a>
                    <div class="border-t my-2"></div>
                    <a href="<?= url($adminPrefix . '/users') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fas fa-users-cog"></i></span> ພະນັກງານ
                    </a>
                    <a href="<?= url($adminPrefix . '/settings') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/admin/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 text-sm"><i class="fas fa-cog"></i></span> ຕັ້ງຄ່າ
                    </a>
                </div>
            </header>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
            <?php if ($layout_pref === 'sidebar' && (!isset($no_nav) || !$no_nav)): ?>
            <header class="h-14 md:h-16 bg-white/90 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-4 md:px-6 flex-shrink-0 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden h-9 w-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-primary/10 hover:text-primary transition-all">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    <div class="hidden md:flex items-center gap-2 text-sm text-gray-400">
                        <i class="fas fa-home text-[10px]"></i>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-700 font-medium"><?= $title ?? 'ໜ້າຫຼັກ' ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleFullscreen()" class="h-9 w-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary/10 hover:text-primary transition-all" title="ເຕັມຈໍ">
                        <i class="fas fa-expand text-xs"></i>
                    </button>
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 h-9 px-3 rounded-xl bg-gray-50 hover:bg-primary/5 transition-all group">
                            <div class="h-6 w-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-[10px] group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="hidden sm:block text-xs font-bold text-gray-700 max-w-[100px] truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                            <i class="fas fa-chevron-down text-[8px] text-gray-400"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 p-2 z-50 animate-scale-in"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="p-3 border-b border-gray-100 mb-1">
                                <p class="text-sm font-black text-gray-800"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
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
</body>
</html>
