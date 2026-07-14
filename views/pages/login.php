<?php $no_nav = true; ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-sky-50 via-background to-sky-50 p-4 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-emerald-100/30 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="absolute top-1/3 -left-16 w-48 h-48 bg-violet-100/30 rounded-full blur-3xl animate-float-slow"></div>
        <div class="absolute top-1/4 right-1/4 w-3 h-3 bg-primary/20 rounded-full animate-pulse-soft" style="animation-delay: 0.5s;"></div>
        <div class="absolute top-3/4 left-1/3 w-2 h-2 bg-emerald-300/40 rounded-full animate-pulse-soft" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/3 w-4 h-4 bg-violet-300/30 rounded-full animate-pulse-soft" style="animation-delay: 1.5s;"></div>
    </div>

    <div class="w-full max-w-md relative animate-slide-up">
        <div class="bg-card/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-sky-100/50 border border-sky-100/60 p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-sky-200/50 animate-scale-in">
                    <i class="fas fa-cash-register text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-black text-foreground"><?= get_store_name() ?></h1>
                <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars(get_store_setting('store_description', '')) ?></p>
            </div>

            <?php if (isset($error) && $error): ?>
            <div class="bg-red-50/80 backdrop-blur border border-red-200/60 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium animate-slide-down flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/admin/login') ?>" class="space-y-5">
                <div>
                    <label class="text-sm font-bold text-foreground/85 mb-2 block">ຊື່ຜູ້ໃຊ້</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground group-focus-within:text-primary transition-colors">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card/50 focus:bg-card text-sm"
                               placeholder="ປ້ອນຊື່ຜູ້ໃຊ້">
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label class="text-sm font-bold text-foreground/85 mb-2 block">ລະຫັດຜ່ານ</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground group-focus-within:text-primary transition-colors">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="w-full pl-11 pr-11 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card/50 focus:bg-card text-sm"
                               placeholder="ປ້ອນລະຫັດຜ່ານ">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-muted-foreground hover:text-primary transition-colors">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.98]">
                    <i class="fas fa-sign-in-alt mr-2"></i>ເຂົ້າສູ່ລະບົບ
                </button>
            </form>

            <p class="text-center text-xs text-muted-foreground mt-6">
                Demo: admin / 123456
            </p>
        </div>
    </div>
</div>
