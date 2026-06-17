<nav class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container flex h-16 items-center mx-auto px-4">
        <div class="flex flex-1 items-center justify-between">
            <a class="flex items-center space-x-2" href="<?= url('/') ?>"> 
                <img src="<?= url('/public/logo.jpg') ?>" alt="Logo" class="h-10 w-10 object-cover rounded-full border">
                <span class="font-bold">
                    <?= get_store_name() ?>
                </span>
            </a>
            
            <!-- Mobile menu button -->
            <button x-data="{ mobileMenuOpen: false }" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-muted-foreground hover:bg-accent">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg>
                <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <!-- Desktop navigation --> 
        <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
            <a class="transition-colors hover:text-foreground/80 text-foreground inline-flex items-center gap-1.5" href="<?= url('/') ?>"><i class="fas fa-home text-sky-500 text-[10px]"></i>ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/pos') ?>"><i class="fas fa-cash-register text-emerald-500 text-[10px]"></i>POS ລະບົບຂາຍ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/rentals?layout=navbar') ?>"><i class="fas fa-receipt text-amber-500 text-[10px]"></i>ປະຫວັດບິນເຊົ່າ
                <span class="<?= get_active_rentals_count() > 0 ? 'bg-red-500 text-white' : 'bg-gray-300 text-gray-500' ?> text-[10px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= get_active_rentals_count() ?></span>
            </a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/inventory') ?>"><i class="fas fa-tshirt text-fuchsia-500 text-[10px]"></i>ສາງຊຸດໄໝ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/customers') ?>"><i class="fas fa-users text-violet-500 text-[10px]"></i>ລູກຄ້າ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/expenses') ?>"><i class="fas fa-file-invoice-dollar text-rose-500 text-[10px]"></i>ບັນທຶກລາຍຈ່າຍ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/staff') ?>"><i class="fas fa-users-cog text-orange-500 text-[10px]"></i>ຈັດການພະນັກງານ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/settings') ?>"><i class="fas fa-cog text-gray-500 text-[10px]"></i>ຕັ້ງຄ່າ</a>
        </nav>
        
        <!-- Desktop user section -->
        <div class="hidden md:flex flex-1 items-center justify-end space-x-8" x-data="notifications()">
            <!-- Notifications Icon -->
            <div class="relative">
                <button @click="toggleNotifications()" class="relative flex items-center justify-center h-10 w-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-primary/10 hover:text-primary transition-all duration-300">
                    <i class="fas fa-bell text-[22px]"></i>
                    <span x-show="unreadCount > 0" class="absolute top-[5px] right-[5px] flex h-[18px] w-[18px]">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-[18px] w-[18px] bg-red-500 border-2 border-white items-center justify-center text-[9px] font-black text-white leading-none" x-text="unreadCount"></span>
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

            <div class="text-sm text-muted-foreground border-l pl-6 w-full">
                ສະບາຍດີ, <?= $_SESSION['user']['username'] ?? 'Admin' ?>
            </div>
            <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-secondary text-secondary-foreground hover:bg-secondary/80 h-9 px-4 w-full">
                ອອກຈາກລະບົບ
            </a>
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
    </div>
    
    <!-- Mobile menu -->
    <div x-data="{ mobileMenuOpen: false }" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden border-t bg-background">
        <div class="container mx-auto px-4 py-4 space-y-4">
            <nav class="flex flex-col space-y-3 text-sm font-medium">
                <a class="transition-colors hover:text-foreground/80 text-foreground inline-flex items-center gap-2" href="<?= url('/') ?>"><i class="fas fa-home text-sky-500 w-5 text-center"></i>ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/pos') ?>"><i class="fas fa-cash-register text-emerald-500 w-5 text-center"></i>POS ລະບົບຂາຍ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/rentals?layout=navbar') ?>"><i class="fas fa-receipt text-amber-500 w-5 text-center"></i>ປະຫວັດບິນເຊົ່າ
                    <span class="<?= get_active_rentals_count() > 0 ? 'bg-red-500 text-white' : 'bg-gray-300 text-gray-500' ?> text-[10px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= get_active_rentals_count() ?></span>
                </a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/inventory') ?>"><i class="fas fa-tshirt text-fuchsia-500 w-5 text-center"></i>ສາງຊຸດໄໝ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/customers') ?>"><i class="fas fa-users text-violet-500 w-5 text-center"></i>ລູກຄ້າ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/expenses') ?>"><i class="fas fa-file-invoice-dollar text-rose-500 w-5 text-center"></i>ບັນທຶກລາຍຈ່າຍ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/staff') ?>"><i class="fas fa-users-cog text-orange-500 w-5 text-center"></i>ຈັດການພະນັກງານ</a>
                <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-2" href="<?= url('/settings') ?>"><i class="fas fa-cog text-gray-500 w-5 text-center"></i>ຕັ້ງຄ່າ</a>
            </nav>
            <div class="flex flex-col space-y-3 pt-4 border-t">
                <div class="text-sm text-muted-foreground">
                    ສະບາຍດີ, <?= $_SESSION['user']['username'] ?? 'Admin' ?>
                </div>
                <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-secondary text-secondary-foreground hover:bg-secondary/80 h-9 px-3">
                    ອອກຈາກລະບົບ
                </a>
            </div>
        </div>
    </div>
</nav>
 