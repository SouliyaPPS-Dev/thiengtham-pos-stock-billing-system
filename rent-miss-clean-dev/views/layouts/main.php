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
    <title><?= $title ?? 'Miss Clean - ຊຸດໄໝໃຫ້ເຊົ່າ' ?></title> 
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="<?= url('/public/logo.jpg') ?>">
     
    <!-- PWA Manifest & Meta Tags -->
    <link rel="manifest" href="<?= url('/public/manifest.json') ?>">
    <meta name="theme-color" content="#0ea5e9">
    
    <!-- iOS / Safari PWA Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Miss Clean">
    <link rel="apple-touch-icon" href="<?= url('/public/logo.jpg') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS (Compiled) -->
    <link rel="stylesheet" href="<?= url('/public/css/app.css') ?>?v=<?= filemtime(__DIR__ . '/../../public/css/app.css') ?>">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
    
    <script>
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Error attempting to enable fullscreen:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
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
                text: "ທ່ານຕ້ອງການອອກຈາກລະບົບແທ້ບໍ່?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'ອອກຈາກລະບົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        }

        // Global success/error messages from URL params
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') || urlParams.has('updated') || urlParams.has('deleted') || urlParams.has('error')) {
                let icon = 'success';
                let title = 'ສຳເລັດ';
                let text = '';
                let timer = 2500;

                if (urlParams.has('success')) {
                    text = 'ບັນທຶກຂໍ້ມູນສຳເລັດແລ້ວ';
                } else if (urlParams.has('updated')) {
                    text = 'ແກ້ໄຂຂໍ້ມູນຮຽບຮ້ອຍແລ້ວ';
                } else if (urlParams.has('deleted')) {
                    text = 'ລຶບຂໍ້ມູນຮຽບຮ້ອຍແລ້ວ';
                } else if (urlParams.has('error')) {
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
                
                // Clear URL parameters without refreshing the page
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
                <!-- SIDEBAR MODE -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r transition-transform duration-300 transform md:translate-x-0 md:static md:inset-0 flex-shrink-0">
                    <div class="flex flex-col h-full">
                        <div class="flex items-center justify-center h-20 border-b flex-shrink-0"> 
                            <a href="<?= url('/' . $lp) ?>">
                                <img src="<?= url('/public/logo.jpg') ?>" alt="Logo" class="h-12 w-12 rounded-full border-2 border-primary/20 shadow-sm">
                            </a>
                        </div>

                        <nav class="flex-grow px-3 py-2 space-y-3 overflow-y-auto">
                            <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/') ?>">
                                <span class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-sm"><i class="fas fa-home"></i></span>
                                <span>ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ</span>
                            </a>
                            <a href="<?= url('/pos' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/pos') ?>">
                                <span class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fas fa-cash-register"></i></span>
                                <span>POS ລະບົບຂາຍ</span> 
                            </a>
                            <a href="<?= url('/rentals' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/rentals') ?>">
                                <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fas fa-receipt"></i></span>
                                <span>ປະຫວັດບິນເຊົ່າ</span>
                                <span class="ml-auto <?= get_active_rentals_count() > 0 ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500' ?> text-xs font-black px-2 py-0.5 rounded-full"><?= get_active_rentals_count() ?></span>
                            </a>
                            <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/customers') ?>">
                                <span class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-sm"><i class="fas fa-users"></i></span>
                                <span>ລູກຄ້າ</span>
                            </a>
                            <a href="<?= url('/inventory' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/inventory') ?>">
                                <span class="w-10 h-10 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-sm"><i class="fas fa-tshirt"></i></span>
                                <span>ສາງຊຸດໄໝ</span>
                            </a>
                            <a href="<?= url('/expenses' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/expenses') ?>">
                                <span class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fas fa-file-invoice-dollar"></i></span>
                                <span>ບັນທຶກລາຍຈ່າຍ</span>
                            </a>
                            <a href="<?= url('/staff' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/staff') ?>">
                                <span class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-sm"><i class="fas fa-users-cog"></i></span>
                                <span>ຈັດການພະນັກງານ</span>
                            </a>
                            <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-colors text-lg font-bold <?= get_menu_active_class('/settings') ?>">
                                <span class="w-10 h-10 rounded-xl flex items-center justify-center <?= is_menu_active('/settings') ? 'text-white' : 'text-gray-600' ?> text-sm"><i class="fas fa-cog"></i></span>
                                <span>ຕັ້ງຄ່າລະບົບ</span>
                            </a>
                        </nav>

                        <div class="p-4 border-t space-y-4 flex-shrink-0">
                            <!-- Layout Switcher (Sidebar) -->
                            <div class="px-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Layout Mode</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="?layout=sidebar" class="flex flex-col items-center justify-center p-2 rounded-lg border text-xs font-bold <?= $layout_pref === 'sidebar' ? 'border-primary text-primary bg-sky-50' : 'text-gray-400 bg-gray-50' ?>">
                                        <i class="fas fa-columns mb-1"></i>
                                        Sidebar
                                    </a>
                                    <a href="?layout=navbar" class="flex flex-col items-center justify-center p-2 rounded-lg border text-xs font-bold <?= $layout_pref === 'navbar' ? 'border-primary text-primary bg-sky-50' : 'text-gray-400 bg-gray-50' ?>">
                                        <i class="fas fa-window-maximize mb-1"></i>
                                        Navbar
                                    </a> 
                                </div>
                            </div> 

                            <div class="flex items-center gap-2 px-4">
                                <button onclick="installPWA()" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-primary text-white rounded-lg text-xs font-black hover:bg-primary/90 transition-all shadow-sm">
                                    <i class="fas fa-download"></i>
                                    ຕິດຕັ້ງແອັບ
                                </button>
                                <button onclick="toggleFullscreen()" class="flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-500 hover:bg-sky-500 hover:text-white rounded-lg text-xs font-black transition-all">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
   
                            <div class="flex items-center space-x-3 px-4">
                                <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div class="flex-grow overflow-hidden">
                                    <p class="font-bold text-base truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                                    <p class="text-sm text-gray-500">ຜູ້ຈັດການ</p>
                                </div>
                            </div>
                            <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm font-bold">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>ອອກຈາກລະບົບ</span>
                            </a>
                        </div>
                    </div>
                </aside>
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <?php else: ?> 
                <!-- NAVBAR MODE -->
                <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 shadow-sm" x-data="notifications()">
                    <div class="w-full px-3 lg:px-6 flex items-center h-14 lg:h-16 justify-between">
                        <!-- Left: Logo + Desktop Nav -->
                        <div class="flex items-center gap-2 lg:gap-6 min-w-0 flex-shrink">
                            <a href="<?= url('/' . $lp) ?>" class="flex items-center flex-shrink-0">
                                <img src="<?= url('/public/logo.jpg') ?>" alt="Logo" class="h-9 w-9 lg:h-11 lg:w-11 rounded-full border-2 border-primary/20 shadow-sm">
                            </a>
                            
                            <nav class="hidden md:flex items-center gap-0.5 overflow-x-auto scrollbar-hide">
                                <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-sky-100 flex items-center justify-center text-sky-600 text-[10px]"><i class="fas fa-home"></i></span>ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ
                                </a>
                                <a href="<?= url('/pos' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-emerald-100 flex items-center justify-center text-emerald-600 text-[10px]"><i class="fas fa-cash-register"></i></span>POS
                                </a>
                                <a href="<?= url('/rentals' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/rentals') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-amber-100 flex items-center justify-center text-amber-600 text-[10px]"><i class="fas fa-receipt"></i></span>ບິນເຊົ່າ
                                    <span class="ml-0.5 <?= get_active_rentals_count() > 0 ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500' ?> text-[10px] font-black px-1.5 py-0.5 rounded-full"><?= get_active_rentals_count() ?></span>
                                </a> 
                                <a href="<?= url('/inventory' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/inventory') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-[10px]"><i class="fas fa-tshirt"></i></span>ສາງຊຸດໄໝ
                                </a>
                                <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-violet-100 flex items-center justify-center text-violet-600 text-[10px]"><i class="fas fa-users"></i></span>ລູກຄ້າ
                                </a>
                                <a href="<?= url('/expenses' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-rose-100 flex items-center justify-center text-rose-600 text-[10px]"><i class="fas fa-file-invoice-dollar"></i></span>ບັນທຶກລາຍຈ່າຍ
                                </a>
                                <a href="<?= url('/staff' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/staff') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 text-[10px]"><i class="fas fa-users-cog"></i></span>ພະນັກງານ
                                </a>
                                <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= is_menu_active('/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                                    <span class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-gray-600 text-[10px]"><i class="fas fa-cog"></i></span>ຕັ້ງຄ່າ
                                </a>
                            </nav>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-1.5 lg:gap-3 flex-shrink-0">
                            <!-- Layout Switcher (Now visible on mobile) -->
                            <div class="flex bg-gray-100/80 p-0.5 rounded-lg border border-gray-200/50">
                                <a href="?layout=sidebar" class="px-2 lg:px-3 py-1 rounded-md text-xs font-black transition-all <?= $layout_pref === 'sidebar' ? 'bg-white shadow-sm text-primary' : 'text-gray-400 hover:text-gray-600' ?>">
                                    <i class="fas fa-columns mr-0.5"></i> SB
                                </a>
                                <a href="?layout=navbar" class="px-2 lg:px-3 py-1 rounded-md text-xs font-black transition-all <?= $layout_pref === 'navbar' ? 'bg-white shadow-sm text-primary' : 'text-gray-400 hover:text-gray-600' ?>">
                                    <i class="fas fa-window-maximize mr-0.5"></i> NB
                                </a>
                            </div>

                            <div class="relative">
                                <button @click="toggleNotifications()" class="relative flex items-center justify-center h-10 w-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-primary/10 hover:text-primary transition-all duration-300">
                                    <i class="fas fa-bell text-[22px]"></i>
                                    <span x-show="unreadCount > 0" class="absolute top-[7px] right-[7px] flex h-[16px] w-[16px]">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-[16px] w-[16px] bg-red-500 border-2 border-white items-center justify-center text-[8px] font-black text-white leading-none" x-text="unreadCount"></span>
                                    </span>
                                </button>

                                <!-- Notifications Dropdown (Same as Sidebar mode) -->
                                <div x-show="showDropdown" @click.away="showDropdown = false" x-cloak
                                    class="absolute right-0 mt-3 w-80 sm:w-[450px] bg-white rounded-2xl border border-gray-100 shadow-2xl z-50 overflow-hidden flex flex-col max-h-[calc(100vh-100px)]"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                                    <div class="p-4 border-b bg-gray-50/50 flex-shrink-0 flex items-center justify-between">
                                        <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-bell text-primary text-sm"></i>
                                            <span class="font-bold text-gray-800 text-sm">ການແຈ້ງເຕືອນ</span>
                                        </div>
                                        <a href="<?= url('/rentals') ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-100 text-gray-400 hover:text-primary hover:border-primary/30 transition-all" title="ເບິ່ງທັງໝົດ">
                                            <i class="fas fa-history text-xs"></i>
                                        </a>
                                    </div>
                                   
                                      
                                    </div>
                                    <div class="overflow-y-auto overflow-x-hidden custom-scrollbar overscroll-contain flex-grow max-h-[450px]" style="-webkit-overflow-scrolling: touch;">
                                        <template x-if="list.length === 0">
                                            <div class="p-12 text-center text-gray-400">
                                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-bell-slash text-3xl text-gray-200"></i>
                                                </div>
                                                <p class="text-base font-semibold text-gray-500">ບໍ່ມີການແຈ້ງເຕືອນ</p>
                                                <p class="text-sm mt-1">ລາຍການໃໝ່ຈະສະແດງຢູ່ນີ້</p>
                                            </div>
                                        </template>
                                        <template x-for="item in list" :key="item.id">
                                            <a :href="'<?= url('/print-invoice') ?>/' + item.id" target="_blank" class="block p-4 hover:bg-primary/[0.02] transition-all border-b border-gray-50 last:border-0 group relative">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <p class="text-[15px] font-black text-gray-900 leading-none truncate pr-2" x-text="item.customer_name"></p>
                                                            <span :class="{
                                                                'text-sky-600': item.status === 'Active',
                                                                'text-emerald-600': item.status === 'Returned',
                                                                'text-red-500': item.status === 'Overdue',
                                                                'text-gray-400': item.status === 'Cancelled'
                                                            }" class="inline-flex items-center gap-1 text-[9px] font-black whitespace-nowrap uppercase tracking-tighter">
                                                                <span :class="{
                                                                    'bg-sky-600': item.status === 'Active',
                                                                    'bg-emerald-600': item.status === 'Returned',
                                                                    'bg-red-500': item.status === 'Overdue',
                                                                    'bg-gray-400': item.status === 'Cancelled'
                                                                }" class="w-1.5 h-1.5 rounded-full"></span>
                                                                <span x-text="{
                                                                    'Active': 'ກຳລັງເຊົ່າ',
                                                                    'Returned': 'ຄືນແລ້ວ',
                                                                    'Overdue': 'ເກີນກຳນົດ',
                                                                    'Cancelled': 'ຍົກເລີກ'
                                                                }[item.status] || item.status"></span>
                                                            </span>
                                                        </div>                                                        
                                                    <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400">
                                                        <span class="flex items-center gap-1">
                                                            <i class="far fa-clock text-[8px]"></i> <span x-text="formatTime(item.created_at)"></span>
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-gray-100">
                                                        <span class="text-[10px] text-gray-400 uppercase font-black tracking-widest">ຍອດລວມ</span>
                                                        <span class="text-sm font-black text-primary" x-text="formatCurrency(item.grand_total) + ' ກີບ'"></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                    <a href="<?= url('/rentals') ?>" class="block p-4 text-center text-xs font-black text-primary hover:bg-primary/5 transition-colors border-t border-gray-100 bg-gray-50/30 flex-shrink-0">
                                        ເບິ່ງປະຫວັດບິນທັງໝົດ
                                    </a>
                                </div>
                            </div>

                            <button onclick="toggleFullscreen()" class="hidden md:flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-xl bg-gray-100/80 text-gray-400 hover:bg-sky-500 hover:text-white transition-all" title="ເຕັມຈໍ">
                                <i class="fas fa-expand text-xs"></i>
                            </button>

                            <!-- User Dropdown -->
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 h-8 lg:h-9 px-2 lg:px-3 rounded-xl bg-gray-100/80 hover:bg-primary/10 transition-all group">
                                    <div class="h-6 w-6 lg:h-7 lg:w-7 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                        <i class="fas fa-user text-[10px] lg:text-xs"></i>
                                    </div>
                                    <span class="hidden sm:block text-sm font-bold text-gray-700 max-w-[120px] truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                                    <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden sm:block"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1.5 w-80 bg-white rounded-xl border border-gray-100 shadow-xl p-2 z-50" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                    <div class="p-3 border-b mb-1">
                                        <p class="text-sm font-black text-gray-800 truncate"><?= $_SESSION['user']['username'] ?? 'Admin' ?></p>
                                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider mt-0.5">Manager (ຜູ້ຈັດການ)</p>
                                    </div>
                                    <button onclick="installPWA()" class="w-full flex items-center gap-3 px-3 py-2.5 text-white bg-primary hover:bg-primary/90 rounded-lg text-xs font-black transition-all mb-1 shadow-sm">
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                                            <i class="fas fa-download"></i>
                                        </div>
                                        <span class="flex-1 text-left">ຕິດຕັ້ງແອັບ</span>
                                    </button>
                                    <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="flex items-center gap-3 px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-lg text-xs font-black transition-all">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <span class="flex-1 text-left">ອອກຈາກລະບົບ</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Mobile Menu Toggle -->
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden h-8 w-8 rounded-xl bg-gray-100/80 flex items-center justify-center text-gray-500 hover:bg-primary/10 hover:text-primary transition-all">
                                <i class="fas fa-bars text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Nav Menu -->
                    <div x-show="sidebarOpen" class="md:hidden border-t border-gray-50 bg-white/95 backdrop-blur-md p-3 space-y-1 shadow-lg" @click.away="sidebarOpen = false">
                        <a href="<?= url('/' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 text-base"><i class="fas fa-home"></i></span> ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ
                        </a>
                        <a href="<?= url('/pos' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/pos') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-base"><i class="fas fa-cash-register"></i></span> POS ລະບົບຂາຍ
                        </a>
                        <a href="<?= url('/rentals' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/rentals') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-base"><i class="fas fa-receipt"></i></span> ປະຫວັດບິນເຊົ່າ
                            <span class="ml-auto <?= get_active_rentals_count() > 0 ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500' ?> text-xs font-black px-2 py-0.5 rounded-full"><?= get_active_rentals_count() ?></span>
                        </a>
                        <a href="<?= url('/inventory' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/inventory') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600 text-base"><i class="fas fa-tshirt"></i></span> ສາງຊຸດໄໝ
                        </a>
                        <a href="<?= url('/customers' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/customers') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 text-base"><i class="fas fa-users"></i></span> ລູກຄ້າ
                        </a>
                        <a href="<?= url('/expenses' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/expenses') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 text-base"><i class="fas fa-file-invoice-dollar"></i></span> ບັນທຶກລາຍຈ່າຍ
                        </a>
                        <a href="<?= url('/staff' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/staff') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-base"><i class="fas fa-users-cog"></i></span> ຈັດການພະນັກງານ
                        </a>
                        <a href="<?= url('/settings' . $lp) ?>" class="flex items-center gap-3 px-4 py-4 rounded-xl text-lg font-bold transition-all <?= is_menu_active('/settings') ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <span class="w-10 h-10 rounded-xl <?= is_menu_active('/settings') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' ?> flex items-center justify-center text-base"><i class="fas fa-cog"></i></span> ຕັ້ງຄ່າລະບົບ
                        </a>
                    </div>
                </header>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
            <?php if ($layout_pref === 'sidebar' && (!isset($no_nav) || !$no_nav)): ?>
                <!-- Topbar only in Sidebar mode -->
                <header class="h-16 bg-white border-b flex items-center justify-between px-4 flex-shrink-0">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg mr-2">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="font-bold text-gray-800 text-lg"><?= $title ?? 'ລະບົບຈັດການຊຸດໄໝ' ?></h2>
                    </div>

                    <div class="flex items-center space-x-4" x-data="notifications()">
                        <button onclick="toggleFullscreen()" class="flex items-center gap-2 px-4 py-2.5 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors text-sm font-bold">
                            <i class="fas fa-expand"></i>
                        </button>
                        <div class="relative">
                            <button @click="toggleNotifications()" class="relative flex items-center justify-center h-10 w-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-primary/10 hover:text-primary transition-all duration-300">
                                <i class="fas fa-bell text-[22px]"></i>
                                <span x-show="unreadCount > 0" class="absolute top-[7px] right-[7px] flex h-[16px] w-[16px]">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-[16px] w-[16px] bg-red-500 border-2 border-white items-center justify-center text-[8px] font-black text-white leading-none" x-text="unreadCount"></span>
                                </span>
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="showDropdown" @click.away="showDropdown = false" x-cloak
                                 class="absolute right-0 mt-3 w-80 sm:w-[450px] bg-white rounded-2xl border border-gray-100 shadow-2xl z-50 overflow-hidden flex flex-col max-h-[calc(100vh-100px)]"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                                <div class="p-4 border-b bg-gray-50/50 flex-shrink-0 flex items-center justify-between">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-bell text-primary text-sm"></i>
                                            <span class="font-bold text-gray-800 text-sm">ການແຈ້ງເຕືອນ</span>
                                        </div>
                                        <a href="<?= url('/rentals') ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-100 text-gray-400 hover:text-primary hover:border-primary/30 transition-all" title="ເບິ່ງທັງໝົດ">
                                            <i class="fas fa-history text-xs"></i>
                                        </a>
                                    </div>
                                   
                                </div>
                                <div class="overflow-y-auto overflow-x-hidden custom-scrollbar overscroll-contain flex-grow max-h-[450px]" style="-webkit-overflow-scrolling: touch;">
                                    <template x-if="list.length === 0">
                                        <div class="p-12 text-center text-gray-400">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-bell-slash text-3xl text-gray-200"></i>
                                            </div>
                                            <p class="text-base font-semibold text-gray-500">ບໍ່ມີການແຈ້ງເຕືອນ</p>
                                            <p class="text-sm mt-1">ລາຍການໃໝ່ຈະສະແດງຢູ່ນີ້</p>
                                        </div>
                                    </template>
                                    <template x-for="item in list" :key="item.id">
                                        <a :href="'<?= url('/print-invoice') ?>/' + item.id" target="_blank" class="block p-4 hover:bg-primary/[0.02] transition-all border-b border-gray-50 last:border-0 group relative">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-center mb-1">
                                                    <p class="text-[15px] font-black text-gray-900 leading-none truncate pr-2" x-text="item.customer_name"></p>
                                                    <span :class="{
                                                        'bg-sky-100 text-sky-700': item.status === 'Active',
                                                        'bg-emerald-100 text-emerald-700': item.status === 'Returned',
                                                        'bg-rose-100 text-rose-700': item.status === 'Overdue',
                                                        'bg-slate-100 text-slate-500': item.status === 'Cancelled'
                                                    }" class="text-[9px] font-black px-2 py-0.5 rounded-lg whitespace-nowrap uppercase tracking-tighter" 
                                                    x-text="{
                                                        'Active': 'ກຳລັງເຊົ່າ',
                                                        'Returned': 'ຄືນແລ້ວ',
                                                        'Overdue': 'ເກີນກຳນົດ',
                                                        'Cancelled': 'ຍົກເລີກ'
                                                    }[item.status] || item.status"></span>
                                                </div>                                                    
                                                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400">
                                                    <span class="flex items-center gap-1">
                                                        <i class="far fa-clock text-[8px]"></i> <span x-text="formatTime(item.created_at)"></span>
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-gray-100">
                                                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-widest">ຍອດລວມ</span>
                                                    <span class="text-sm font-black text-primary" x-text="formatCurrency(item.grand_total) + ' ກີບ'"></span>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                <a href="<?= url('/rentals') ?>" class="block p-4 text-center text-xs font-black text-primary hover:bg-primary/5 transition-colors border-t border-gray-100 bg-gray-50/30 flex-shrink-0">
                                    ເບິ່ງປະຫວັດບິນທັງໝົດ
                                </a>
                            </div>
                        </div>
                    </div>
                </header>
            <?php endif; ?>

<main class="flex-grow overflow-y-auto">
                <?= $content ?>
            </main>
        </div>
    </div>
    <script>
        function notifications() {
            return {
                showDropdown: false,
                unreadCount: 0,
                list: [],
                lastId: localStorage.getItem('last_noti_id') || 0,

                init() {
                    this.fetchNotifications();
                    // Poll for new notifications every 30 seconds
                    setInterval(() => this.fetchNotifications(), 30000);
                    
                    // Request permission for push notifications
                    if (Notification.permission === 'default') {
                        Notification.requestPermission();
                    }
                },

                toggleNotifications() {
                    this.showDropdown = !this.showDropdown;
                    if (this.showDropdown) {
                        this.unreadCount = 0;
                    }
                },

                fetchNotifications() {
                    fetch('<?= url("/rentals/notifications") ?>')
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const newList = data.notifications;
                                
                                // Check if there are new items for push notification
                                if (newList.length > 0) {
                                    const latestItem = newList[0];
                                    if (latestItem.id > this.lastId) {
                                        if (this.lastId != 0) {
                                            this.sendPushNotification(latestItem);
                                            this.unreadCount = newList.filter(item => item.id > this.lastId).length;
                                        }
                                        this.lastId = latestItem.id;
                                        localStorage.setItem('last_noti_id', this.lastId);
                                    }
                                }
                                
                                this.list = newList;
                            }
                        });
                },

                sendPushNotification(item) {
                    if (Notification.permission === 'granted') {
                        const noti = new Notification('ລາຍການເຊົ່າໃໝ່!', {
                            body: `ລູກຄ້າ: ${item.customer_name}\nຍອດລວມ: ${this.formatCurrency(item.grand_total)} ກີບ`,
                            icon: '<?= url("/public/logo.jpg") ?>'
                        });
                        
                        noti.onclick = () => {
                            window.focus();
                            window.location.href = '<?= url("/rentals") ?>';
                        };
                    }
                },

                formatCurrency(num) {
                    return new Intl.NumberFormat().format(num);
                },

                formatTime(dateStr) {
                    const date = new Date(dateStr);
                    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>

    <!-- Minimalist Image Preview with Zoom Controls -->
    <div x-data="{ 
            open: false, 
            imageUrl: '', 
            scale: 1,
            zoomIn() { if(this.scale < 3) this.scale += 0.25 },
            zoomOut() { if(this.scale > 0.5) this.scale -= 0.25 },
            reset() { this.scale = 1 }
         }" 
         @open-image-preview.window="open = true; imageUrl = $event.detail.url; reset()"
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white/10 backdrop-blur-3xl p-4 overflow-auto"         @click="open = false"
         @keydown.escape.window="open = false">
        
        <!-- Close Button (Top Right Viewport) -->
        <button @click="open = false" 
                class="fixed top-6 right-6 w-12 h-12 flex items-center justify-center text-gray-800 hover:text-red-500 transition-colors z-[10001]" 
                title="Close">
            <i class="fas fa-times text-3xl"></i>
        </button>

        <!-- Zoom Controls (Floating Top Center) -->
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[10000]" @click.stop>
            <div class="flex items-center bg-white/40 backdrop-blur-md rounded-2xl border border-gray-200 p-1 shadow-lg">
                <button @click="zoomOut()" class="p-3 text-gray-700 hover:bg-gray-100 rounded-xl transition-all" title="Zoom Out">
                    <i class="fas fa-search-minus text-xl"></i>
                </button>
                <div class="px-4 text-gray-800 font-bold text-sm w-20 text-center" x-text="Math.round(scale * 100) + '%'"></div>
                <button @click="zoomIn()" class="p-3 text-gray-700 hover:bg-gray-100 rounded-xl transition-all" title="Zoom In">
                    <i class="fas fa-search-plus text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Image Container -->
        <div class="w-full h-full flex items-center justify-center overflow-auto scrollbar-hide">
            <div class="relative transition-transform duration-200 ease-out shadow-2xl rounded-2xl overflow-hidden" 
                 :style="`transform: scale(${scale})`"
                 @click.stop>
                <!-- Image -->
                <img :src="imageUrl" 
                     class="max-w-full max-h-[85vh] object-contain cursor-default"
                     alt="Preview">
            </div>
        </div>
    </div>

    <script>
    (function() {
        var dPrompt = null;
        var banner = null;
        var autoTimer = null;
        var pendingInstall = false;

        function tryInstall() {
            if (dPrompt) {
                dPrompt.prompt();
                dPrompt.userChoice.then(function(choice) {
                    if (choice.outcome === 'accepted') {
                        localStorage.setItem('mc_install_dismissed', 'true');
                    }
                    dPrompt = null;
                    pendingInstall = false;
                });
            }
        }

        window.installPWA = function() {
            clearTimeout(autoTimer);
            if (banner && banner.parentNode) banner.parentNode.removeChild(banner);
            banner = null;
            if (dPrompt) {
                tryInstall();
            } else {
                pendingInstall = true;
            }
        };

        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) return;
        if (localStorage.getItem('mc_install_dismissed') === 'true') return;

        function createBanner() {
            if (banner) return;
            banner = document.createElement('div');
            banner.id = 'pwa-install-banner';
            banner.style.cssText = 'position:fixed;bottom:85px;left:50%;transform:translateX(-50%);z-index:9999;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.15);padding:14px 18px;display:flex;align-items:center;gap:12px;width:calc(100% - 32px);max-width:400px;';
            banner.innerHTML =
                '<img src="<?= url('/public/logo.jpg') ?>" alt="" width="44" height="44" style="border-radius:8px;flex-shrink:0;">' +
                '<div style="flex:1;min-width:0;">' +
                    '<p style="margin:0;font-size:15px;font-weight:bold;color:#1e293b;">ຕິດຕັ້ງ App Miss Clean</p>' +
                    '<p style="margin:0;font-size:13px;color:#64748b;">ຕິດຕັ້ງເພື່ອໃຊ້ງານໄດ້ງ່າຍຂຶ້ນ</p>' +
                '</div>' +
                '<button id="pwa-install-btn" style="background:#0ea5e9;color:#fff;border:none;padding:8px 18px;border-radius:10px;font-size:14px;font-weight:bold;cursor:pointer;white-space:nowrap;flex-shrink:0;">ຕິດຕັ້ງ</button>' +
                '<button id="pwa-close-btn" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;flex-shrink:0;" aria-label="ປິດ">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';

            document.body.appendChild(banner);

            autoTimer = setTimeout(function() {
                if (banner && banner.parentNode) banner.parentNode.removeChild(banner);
                banner = null;
            }, 15000);

            document.getElementById('pwa-install-btn').addEventListener('click', function() {
                clearTimeout(autoTimer);
                if (banner && banner.parentNode) banner.parentNode.removeChild(banner);
                banner = null;
                tryInstall();
            });

            document.getElementById('pwa-close-btn').addEventListener('click', function() {
                clearTimeout(autoTimer);
                if (banner && banner.parentNode) banner.parentNode.removeChild(banner);
                banner = null;
                localStorage.setItem('mc_install_dismissed', 'true');
            });
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            dPrompt = e;
            if (pendingInstall) {
                tryInstall();
                pendingInstall = false;
            } else if (!banner) {
                createBanner();
            }
        });

        setTimeout(function() {
            if (banner) return;
            if (!('serviceWorker' in navigator)) return;
            createBanner();
        }, 3000);

        window.addEventListener('appinstalled', function() {
            dPrompt = null;
            if (banner && banner.parentNode) banner.parentNode.removeChild(banner);
            banner = null;
            localStorage.setItem('mc_install_dismissed', 'true');
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>').then(function(reg) {
                    reg.addEventListener('updatefound', function() {
                        var newSW = reg.installing;
                        newSW.addEventListener('statechange', function() {
                            if (newSW.state === 'installed' && navigator.serviceWorker.controller) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'ມີອັບເດດໃໝ່',
                                    text: 'ກົດ OK ເພື່ອໂຫຼດເວີຊັນລ່າສຸດ',
                                    confirmButtonColor: '#0ea5e9',
                                    confirmButtonText: 'OK',
                                    customClass: { popup: 'rounded-3xl' }
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        });
                    });
                }).catch(function(err) {
                    console.log('SW reg failed', err);
                });
            });

            navigator.serviceWorker.addEventListener('controllerchange', function() {
                window.location.reload();
            });
        }

    })();

    // Apply saved font size from localStorage
    (function() {
        var savedSize = localStorage.getItem('mc_font_size');
        if (savedSize) {
            var size = parseInt(savedSize, 10);
            if (size >= 10 && size <= 30) {
                document.documentElement.style.fontSize = size + 'px';
            }
        }
    })();
    </script>
</body>
</html>
     