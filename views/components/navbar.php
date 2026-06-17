<nav class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container flex h-16 items-center mx-auto px-4">
        <div class="flex flex-1 items-center justify-between">
            <a class="flex items-center space-x-2" href="<?= url('/') ?>">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-sm shadow-lg shadow-sky-200">
                    <i class="fas fa-cash-register"></i>
                </div>
                <span class="font-bold"><?= get_store_name() ?></span>
            </a>

            <button x-data="{ mobileMenuOpen: false }" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-muted-foreground hover:bg-accent">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg>
                <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
            <a class="transition-colors hover:text-foreground/80 text-foreground inline-flex items-center gap-1.5" href="<?= url('/') ?>"><i class="fas fa-chart-pie text-sky-500 text-[10px]"></i>ໜ້າຫຼັກ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/pos') ?>"><i class="fas fa-cash-register text-emerald-500 text-[10px]"></i>POS</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/products') ?>"><i class="fas fa-box text-fuchsia-500 text-[10px]"></i>ສິນຄ້າ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/sales') ?>"><i class="fas fa-receipt text-amber-500 text-[10px]"></i>ປະຫວັດ</a>
            <a class="transition-colors hover:text-foreground/80 text-muted-foreground inline-flex items-center gap-1.5" href="<?= url('/customers') ?>"><i class="fas fa-users text-violet-500 text-[10px]"></i>ລູກຄ້າ</a>
        </nav>

        <div class="hidden md:flex flex-1 items-center justify-end space-x-8">
            <div class="text-sm text-muted-foreground">
                ສະບາຍດີ, <?= $_SESSION['user']['username'] ?? 'Admin' ?>
            </div>
            <a href="<?= url('/logout') ?>" onclick="confirmLogout(event)" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-secondary text-secondary-foreground hover:bg-secondary/80 h-9 px-4">
                ອອກຈາກລະບົບ
            </a>
        </div>
    </div>
</nav>
