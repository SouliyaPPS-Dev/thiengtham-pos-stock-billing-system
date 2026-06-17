<div class="min-h-screen flex items-center justify-center bg-[#f8f9fa] px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Logo & Branding -->
        <div class="flex flex-col items-center mb-10">
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary to-sky-400 rounded-full blur opacity-25"></div>
                <img src="<?= url('/public/logo.jpg') ?>" alt="Logo" class="relative h-24 w-24 object-cover rounded-full border-4 border-white shadow-xl">
            </div>
            <h1 class="mt-6 text-3xl font-black tracking-tight text-gray-800">Miss Clean</h1>
            <p class="text-gray-500 font-medium">ລະບົບຈັດການຊຸດໄໝໃຫ້ເຊົ່າ</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl border shadow-xl shadow-gray-200/50 overflow-hidden">
            <div class="p-8 sm:p-10">
                <h2 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-2">
                    <i class="fas fa-lock text-primary text-sm"></i>
                    ເຂົ້າສູ່ລະບົບ
                </h2>

                <form action="<?= url('/login') ?>" method="POST" class="space-y-6">
                    <?php if (isset($error)): ?>
                        <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl border border-red-100 flex items-center gap-3 animate-pulse">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1" for="username">ຊື່ຜູ້ໃຊ້</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                            <input 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-gray-400" 
                                id="username" name="username" placeholder="ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້" required
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1" for="password">ລະຫັດຜ່ານ</label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-key text-sm"></i>
                            </span>
                            <input 
                                :type="show ? 'text' : 'password'"
                                class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-gray-400" 
                                id="password" name="password" placeholder="••••••••" required
                            >
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button class="w-full bg-primary text-white py-4 rounded-2xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 active:scale-[0.98] transition-all mt-4">
                        ເຂົ້າສູ່ລະບົບ
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-gray-400">
            &copy; 2026 Miss Clean Team. All rights reserved.
        </p>
    </div>
</div>

