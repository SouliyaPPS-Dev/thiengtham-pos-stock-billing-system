<?php
$layout_pref = $layout_pref ?? get_layout_preference();
$manual_pref = $_SESSION['layout_pref'] ?? 'sidebar';
$lp = '?layout=' . $manual_pref;
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

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r transition-transform duration-300 transform md:translate-x-0 md:static md:inset-0 flex-shrink-0">
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-center h-20 border-b flex-shrink-0">
                        <a href="<?= url('/' . $lp) ?>" class="flex items-center space-x-2">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-sm shadow-lg shadow-sky-200">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <span class="font-bold text-lg text-primary"><?= get_store_name() ?></span>
                        </a>
                    </div>

                    <nav class="flex-grow px-3 py-2 space-y-0.5 overflow-y-auto">
                        <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/') ?>">
                            <span class="w-8 h-8 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-xs"><i class="fas fa-chart-pie"></i></span>
                            <span>ໜ້າຫຼັກ</span>
                        </a>
                        <a href="<?= url('/pos?layout=navbar') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/pos') ?>">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fas fa-cash-register"></i></span>
                            <span>POS ຂາຍສິນຄ້າ</span>
                        </a>
                        <a href="<?= url('/products' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/products') ?>">
                            <span class="w-8 h-8 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-xs"><i class="fas fa-box"></i></span>
                            <span>ສິນຄ້າ</span>
                        </a>
                        <a href="<?= url('/categories' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/categories') ?>">
                            <span class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-xs"><i class="fas fa-tags"></i></span>
                            <span>ໝວດສິນຄ້າ</span>
                        </a>
                        <a href="<?= url('/sales' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/sales') ?>">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-xs"><i class="fas fa-receipt"></i></span>
                            <span>ປະຫວັດການຂາຍ</span>
                        </a>
                        <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/customers') ?>">
                            <span class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-xs"><i class="fas fa-users"></i></span>
                            <span>ລູກຄ້າ</span>
                        </a>
                        <a href="<?= url('/suppliers' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/suppliers') ?>">
                            <span class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-xs"><i class="fas fa-truck"></i></span>
                            <span>ຜູ້ສະໜອງ</span>
                        </a>
                        <div class="border-t my-2 mx-3"></div>
                        <a href="<?= url('/users' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/users') ?>">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-xs"><i class="fas fa-users-cog"></i></span>
                            <span>ພະນັກງານ</span>
                        </a>
                        <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-sm font-bold <?= get_menu_active_class('/settings') ?>">
                            <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 text-xs"><i class="fas fa-cog"></i></span>
                            <span>ຕັ້ງຄ່າ</span>
                        </a>
                    </nav>

                    <div class="p-4 border-t space-y-4 flex-shrink-0">
                        <div class="px-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Layout Mode</p>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="?layout=sidebar" class="flex flex-col items-center justify-center p-2 rounded-lg border text-[10px] font-bold <?= $layout_pref === 'sidebar' ? 'border-primary text-primary bg-sky-50' : 'text-gray-400 bg-gray-50' ?>">
                                    <i class="fas fa-columns mb-1"></i> Sidebar
                                </a>
                                <a href="?layout=navbar" class="flex flex-col items-center justify-center p-2 rounded-lg border text-[10px] font-bold <?= $layout_pref === 'navbar' ? 'border-primary text-primary bg-sky-50' : 'text-gray-400 bg-gray-50' ?>">
                                    <i class="fas fa-window-maximize mb-1"></i> Navbar
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 px-4">
                            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div class="flex-grow overflow-hidden text-sm">
                                <p class="font-medium truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                                <p class="text-xs text-gray-500">ຜູ້ຈັດການ</p>
                            </div>
                        </div>
                        <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-6"></i>
                            <span>ອອກຈາກລະບົບ</span>
                        </a>
                    </div>
                </div>
            </aside>
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            <?php else: ?>

            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 shadow-sm">
                <div class="w-full px-3 lg:px-6 flex items-center h-14 lg:h-16 justify-between">
                    <div class="flex items-center gap-2 lg:gap-6 min-w-0 flex-shrink">
                        <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-2 flex-shrink-0">
                            <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs lg:text-sm shadow-lg shadow-sky-200">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <span class="font-black text-sm lg:text-base text-primary hidden sm:inline tracking-tight"><?= get_store_name() ?></span>
                        </a>

                        <nav class="hidden md:flex items-center gap-0.5 overflow-x-auto scrollbar-hide">
                            <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-sky-100 flex items-center justify-center text-sky-600 text-[9px]"><i class="fas fa-chart-pie"></i></span>ໜ້າຫຼັກ
                            </a>
                            <a href="<?= url('/pos?layout=navbar') ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center text-emerald-600 text-[9px]"><i class="fas fa-cash-register"></i></span>POS
                            </a>
                            <a href="<?= url('/products' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-[9px]"><i class="fas fa-box"></i></span>ສິນຄ້າ
                            </a>
                            <a href="<?= url('/categories' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-purple-100 flex items-center justify-center text-purple-600 text-[9px]"><i class="fas fa-tags"></i></span>ໝວດ
                            </a>
                            <a href="<?= url('/sales' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-amber-100 flex items-center justify-center text-amber-600 text-[9px]"><i class="fas fa-receipt"></i></span>ປະຫວັດ
                            </a>
                            <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-violet-100 flex items-center justify-center text-violet-600 text-[9px]"><i class="fas fa-users"></i></span>ລູກຄ້າ
                            </a>
                            <a href="<?= url('/suppliers' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 text-[9px]"><i class="fas fa-truck"></i></span>ຜູ້ສະໜອງ
                            </a>
                            <a href="<?= url('/users' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                <span class="w-5 h-5 rounded-md bg-rose-100 flex items-center justify-center text-rose-600 text-[9px]"><i class="fas fa-users-cog"></i></span>ພະນັກງານ
                            </a>
                            <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= is_menu_active('/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
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

                        <div x-data="{ open: false }">
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
                                <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-lg text-xs font-black transition-all">
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
                    <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-sm"><i class="fas fa-chart-pie"></i></span> ໜ້າຫຼັກ
                    </a>
                    <a href="<?= url('/pos?layout=navbar') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fas fa-cash-register"></i></span> POS ຂາຍສິນຄ້າ
                    </a>
                    <a href="<?= url('/products' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/products') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-sm"><i class="fas fa-box"></i></span> ສິນຄ້າ
                    </a>
                    <a href="<?= url('/categories' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/categories') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-sm"><i class="fas fa-tags"></i></span> ໝວດສິນຄ້າ
                    </a>
                    <a href="<?= url('/sales' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/sales') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fas fa-receipt"></i></span> ປະຫວັດການຂາຍ
                    </a>
                    <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-sm"><i class="fas fa-users"></i></span> ລູກຄ້າ
                    </a>
                    <a href="<?= url('/suppliers' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/suppliers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-sm"><i class="fas fa-truck"></i></span> ຜູ້ສະໜອງ
                    </a>
                    <div class="border-t my-2"></div>
                    <a href="<?= url('/users' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/users') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fas fa-users-cog"></i></span> ພະນັກງານ
                    </a>
                    <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-bold transition-all <?= is_menu_active('/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                        <span class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 text-sm"><i class="fas fa-cog"></i></span> ຕັ້ງຄ່າ
                    </a>
                </div>
            </header>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
            <?php if ($layout_pref === 'sidebar' && (!isset($no_nav) || !$no_nav)): ?>
            <header class="h-16 bg-white border-b flex items-center justify-between px-4 flex-shrink-0">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg mr-2">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="font-semibold text-gray-800"><?= $title ?? 'ໜ້າຫຼັກ' ?></h2>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleFullscreen()" class="flex items-center gap-2 px-3 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors text-sm font-medium">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </header>
            <?php endif; ?>

            <main class="flex-grow overflow-y-auto">
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
