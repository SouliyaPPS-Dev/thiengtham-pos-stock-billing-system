<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ຕັ້ງຄ່າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການການຕັ້ງຄ່າລະບົບ</p>
            </div>
        </div>

        <div class="space-y-6">

            <!-- Section: Store Info -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <i class="fas fa-store text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນຮ້ານຄ້າ</h2>
                        <p class="text-xs text-gray-400">ຂໍ້ມູນທົ່ວໄປຂອງຮ້ານຄ້າ</p>
                    </div>
                </div>
                <form action="<?= url('/settings/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-store text-[10px] text-primary"></i>
                            ຊື່ຮ້ານ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-store text-xs"></i>
                            </span>
                            <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ຊື່ຮ້ານ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-money-bill text-[10px] text-primary"></i>
                            ສະກຸນເງິນ
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                            <select name="currency"
                                    class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm appearance-none">
                                <option value="LAK" <?= (($settings['currency'] ?? 'LAK') === 'LAK') ? 'selected' : '' ?>>LAK - ກີບລາວ</option>
                                <option value="THB" <?= (($settings['currency'] ?? 'LAK') === 'THB') ? 'selected' : '' ?>>THB - ບາດໄທ</option>
                                <option value="USD" <?= (($settings['currency'] ?? 'LAK') === 'USD') ? 'selected' : '' ?>>USD - ໂດລາສະຫະລັດ</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-[10px] text-primary"></i>
                            ທີ່ຢູ່ຮ້ານ
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </span>
                            <input type="text" name="store_address" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ທີ່ຢູ່ຮ້ານ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-phone text-[10px] text-primary"></i>
                            ເບີໂທຮ້ານ
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-phone text-xs"></i>
                            </span>
                            <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ເບີໂທ">
                        </div>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກຂໍ້ມູນ</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section: Change Password -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">ປ່ຽນລະຫັດຜ່ານ</h2>
                        <p class="text-xs text-gray-400">ປ່ຽນລະຫັດຜ່ານສຳລັບເຂົ້າສູ່ລະບົບ</p>
                    </div>
                </div>
                <form action="<?= url('/settings/change-password') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-lock text-[10px] text-violet-500"></i>
                            ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <input type="password" name="current_password" required
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ລະຫັດປັດຈຸບັນ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-key text-[10px] text-violet-500"></i>
                            ລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-key text-xs"></i>
                            </span>
                            <input type="password" name="new_password" required
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ລະຫັດໃໝ່">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-[10px] text-violet-500"></i>
                            ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-check-circle text-xs"></i>
                            </span>
                            <input type="password" name="new_password_confirm" required
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                   placeholder="ຢືນຢັນລະຫັດໃໝ່">
                        </div>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-key"></i>
                            <span>ປ່ຽນລະຫັດຜ່ານ</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>