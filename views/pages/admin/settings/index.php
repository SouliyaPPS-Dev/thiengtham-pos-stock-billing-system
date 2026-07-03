<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ຕັ້ງຄ່າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການການຕັ້ງຄ່າລະບົບ</p>
            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <i class="fas fa-store text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນຮ້ານຄ້າ</h2>
                        <p class="text-xs text-gray-400">ຂໍ້ມູນທົ່ວໄປຂອງຮ້ານຄ້າ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700">ໂລໂກ້ຮ້ານ</label>
                        <div x-data="{ preview: null, currentLogo: '<?= htmlspecialchars($settings['store_logo'] ?? '') ?>' }" class="flex items-center gap-4">
                            <div class="h-20 w-20 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden border">
                                <template x-if="currentLogo && !preview">
                                    <img :src="currentLogo" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!currentLogo && !preview">
                                    <i class="fas fa-store text-2xl text-gray-300"></i>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="h-full w-full object-cover">
                                </template>
                            </div>
                            <input type="file" name="store_logo" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0]); currentLogo = null"
                                   class="flex-1 text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ຊື່ຮ້ານ <span class="text-red-400">*</span></label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-store text-xs"></i>
                            </span>
                            <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ຊື່ຮ້ານ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ສະກຸນເງິນ</label>
                        <select name="currency"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="LAK" <?= (($settings['currency'] ?? 'LAK') === 'LAK') ? 'selected' : '' ?>>LAK - ກີບລາວ</option>
                            <option value="THB" <?= (($settings['currency'] ?? 'LAK') === 'THB') ? 'selected' : '' ?>>THB - ບາດໄທ</option>
                            <option value="USD" <?= (($settings['currency'] ?? 'LAK') === 'USD') ? 'selected' : '' ?>>USD - ໂດລາສະຫະລັດ</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ອາກອນ (%)</label>
                        <input type="number" name="tax_percent" step="0.01" min="0" value="<?= htmlspecialchars($settings['tax_percent'] ?? '0') ?>"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ຂະໜາດໃບບິນ</label>
                        <select name="paper_size"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="58mm" <?= (($settings['paper_size'] ?? '58mm') === '58mm') ? 'selected' : '' ?>>58mm (Thermal)</option>
                            <option value="80mm" <?= (($settings['paper_size'] ?? '') === '80mm') ? 'selected' : '' ?>>80mm (Thermal)</option>
                            <option value="A4" <?= (($settings['paper_size'] ?? '') === 'A4') ? 'selected' : '' ?>>A4</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກຂໍ້ມູນ</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fas fa-info-circle text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນເພີ່ມເຕີມ</h2>
                        <p class="text-xs text-gray-400">ຂໍ້ມູນຕິດຕໍ່ ແລະ ຂໍ້ຄວາມໃບບິນ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700">ທີ່ຢູ່ຮ້ານ</label>
                        <input type="text" name="store_address" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ທີ່ຢູ່ຮ້ານ">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ເບີໂທຮ້ານ</label>
                        <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ເບີໂທຮ້ານ">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ອີເມວຮ້ານ</label>
                        <input type="email" name="store_email" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ອີເມວຮ້ານ">
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700">ຂໍ້ຄວາມທ້າຍໃບບິນ (Receipt Footer)</label>
                        <textarea name="receipt_footer" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ຂໍ້ຄວາມທ້າຍໃບບິນ"><?= htmlspecialchars($settings['receipt_footer'] ?? '') ?></textarea>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700">ເງື່ອນໄຂໃບແຈ້ງໜີ້ (Invoice Terms)</label>
                        <textarea name="invoice_terms" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ເງື່ອນໄຂ"><?= htmlspecialchars($settings['invoice_terms'] ?? '') ?></textarea>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກຂໍ້ມູນ</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">ປ່ຽນລະຫັດຜ່ານ</h2>
                        <p class="text-xs text-gray-400">ປ່ຽນລະຫັດຜ່ານສຳລັບເຂົ້າສູ່ລະບົບ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/change-password') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-gray-700">ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="current_password" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ລະຫັດປັດຈຸບັນ">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                <i class="fas fa-key text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="new_password" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ລະຫັດໃໝ່">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-gray-700">ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                <i class="fas fa-check-circle text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="new_password_confirm" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ຢືນຢັນລະຫັດໃໝ່">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
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
