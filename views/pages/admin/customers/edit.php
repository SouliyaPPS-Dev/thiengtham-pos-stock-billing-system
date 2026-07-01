<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-2xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/customers') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ແກ້ໄຂລູກຄ້າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ແກ້ໄຂຂໍ້ມູນລູກຄ້າ</p>
            </div>
        </div>

        <form action="<?= url('/admin/customers/' . $customer['id'] . '/update') ?>" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ຊື່ ແລະ ນາມສະກຸນ <span class="text-red-400">*</span></label>
                    <input type="text" name="fullname" required value="<?= htmlspecialchars($customer['fullname'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ຊື່ ແລະ ນາມສະກຸນ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ເບີໂທ <span class="text-red-400">*</span></label>
                    <input type="text" name="phone" required value="<?= htmlspecialchars($customer['phone'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ເບີໂທລະສັບ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ອີເມວ</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ອີເມວ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ປະເພດລູກຄ້າ</label>
                    <select name="customer_type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="regular" <?= (($customer['customer_type'] ?? 'regular') === 'regular') ? 'selected' : '' ?>>ທົ່ວໄປ</option>
                        <option value="wholesale" <?= (($customer['customer_type'] ?? '') === 'wholesale') ? 'selected' : '' ?>>ສົ່ງ</option>
                        <option value="vip" <?= (($customer['customer_type'] ?? '') === 'vip') ? 'selected' : '' ?>>VIP</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ທີ່ຢູ່</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ທີ່ຢູ່"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຫມາຍເຫດ</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ຫມາຍເຫດ"><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-save"></i>
                    ບັນທຶກການແກ້ໄຂ
                </button>
                <a href="<?= url('/admin/customers') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    ຍົກເລີກ
                </a>
            </div>
        </form>
    </div>
</div>
