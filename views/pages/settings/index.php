<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ຕັ້ງຄ່າ</h1>
        <p class="text-sm text-gray-500">ຈັດການການຕັ້ງຄ່າລະບົບ</p>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <h2 class="font-bold text-gray-800 text-lg mb-4">ຂໍ້ມູນຮ້ານຄ້າ</h2>
        <form action="<?= url('/settings/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ຮ້ານ <span class="text-red-500">*</span></label>
                <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ຊື່ຮ້ານ">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ສະກຸນເງິນ</label>
                <select name="currency" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="LAK" <?= (($settings['currency'] ?? 'LAK') === 'LAK') ? 'selected' : '' ?>>LAK - ກີບລາວ</option>
                    <option value="THB" <?= (($settings['currency'] ?? 'LAK') === 'THB') ? 'selected' : '' ?>>THB - ບາດໄທ</option>
                    <option value="USD" <?= (($settings['currency'] ?? 'LAK') === 'USD') ? 'selected' : '' ?>>USD - ໂດລາສະຫະລັດ</option>
                </select>
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-sm font-bold text-gray-700">ທີ່ຢູ່ຮ້ານ</label>
                <input type="text" name="store_address" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ທີ່ຢູ່ຮ້ານ">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ເບີໂທຮ້ານ</label>
                <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ເບີໂທ">
            </div>
            <div class="md:col-span-2 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກຂໍ້ມູນ</span>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <h2 class="font-bold text-gray-800 text-lg mb-4">ປ່ຽນລະຫັດຜ່ານ</h2>
        <form action="<?= url('/settings/change-password') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ລະຫັດປັດຈຸບັນ">
            </div>
            <div></div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລະຫັດຜ່ານໃໝ່ <span class="text-red-500">*</span></label>
                <input type="password" name="new_password" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ລະຫັດໃໝ່">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-red-500">*</span></label>
                <input type="password" name="new_password_confirm" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ຢືນຢັນລະຫັດໃໝ່">
            </div>
            <div class="md:col-span-2 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-key"></i>
                    <span>ປ່ຽນລະຫັດຜ່ານ</span>
                </button>
            </div>
        </form>
    </div>
</div>
