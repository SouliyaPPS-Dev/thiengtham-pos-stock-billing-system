<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="<?= url('/users') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ແກ້ໄຂພະນັກງານ</h1>
            <p class="text-sm text-gray-500">ແກ້ໄຂຂໍ້ມູນ: <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <form action="<?= url('/users/' . $user['id'] . '/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ຜູ້ໃຊ້ <span class="text-red-500">*</span></label>
                <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ເຕັມ</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລະຫັດຜ່ານໃໝ່</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ປະໄວ້ວ່າງ ຖ້າບໍ່ຕ້ອງການປ່ຽນ">
                <p class="text-xs text-gray-400 mt-1">ປະໄວ້ວ່າງ ຖ້າບໍ່ຕ້ອງການປ່ຽນລະຫັດ</p>
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ບົດບາດ</label>
                <select name="role" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="staff" <?= ($user['role'] === 'staff') ? 'selected' : '' ?>>Staff</option>
                    <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ສະຖານະ</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="active" <?= (($user['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>ເປີດໃຊ້</option>
                    <option value="inactive" <?= (($user['status'] ?? 'active') === 'inactive') ? 'selected' : '' ?>>ປິດໃຊ້</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກຂໍ້ມູນ</span>
                </button>
                <a href="<?= url('/users') ?>" class="bg-gray-100 text-gray-600 rounded-xl px-6 py-2.5 font-bold hover:bg-gray-200 inline-flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
