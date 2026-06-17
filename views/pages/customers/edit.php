<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="<?= url('/customers') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ແກ້ໄຂລູກຄ້າ</h1>
            <p class="text-sm text-gray-500">ແກ້ໄຂຂໍ້ມູນ: <?= htmlspecialchars($customer['name']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <form action="<?= url('/customers/' . $customer['id'] . '/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ລູກຄ້າ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="<?= htmlspecialchars($customer['name']) ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ເບີໂທ</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ອີເມວ</label>
                <input type="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ທີ່ຢູ່</label>
                <input type="text" name="address" value="<?= htmlspecialchars($customer['address'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-sm font-bold text-gray-700">ໝາຍເຫດ</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກຂໍ້ມູນ</span>
                </button>
                <a href="<?= url('/customers') ?>" class="bg-gray-100 text-gray-600 rounded-xl px-6 py-2.5 font-bold hover:bg-gray-200 inline-flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
