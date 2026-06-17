<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="<?= url('/suppliers') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ແກ້ໄຂຜູ້ສະໜອງ</h1>
            <p class="text-sm text-gray-500">ແກ້ໄຂຂໍ້ມູນ: <?= htmlspecialchars($supplier['name']) ?></p>
        </div>
    </div>

    <div class="table-wrap">
        <form action="<?= url('/suppliers/' . $supplier['id'] . '/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="form-label">ຊື່ຜູ້ສະໜອງ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="<?= htmlspecialchars($supplier['name']) ?>" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ຜູ້ຕິດຕໍ່</label>
                <input type="text" name="contact_person" value="<?= htmlspecialchars($supplier['contact_person'] ?? '') ?>" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ເບີໂທ</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone'] ?? '') ?>" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ອີເມວ</label>
                <input type="email" name="email" value="<?= htmlspecialchars($supplier['email'] ?? '') ?>" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ທີ່ຢູ່</label>
                <input type="text" name="address" value="<?= htmlspecialchars($supplier['address'] ?? '') ?>" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ໝາຍເຫດ</label>
                <textarea name="notes" rows="3" class="form-input"><?= htmlspecialchars($supplier['notes'] ?? '') ?></textarea>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກຂໍ້ມູນ</span>
                </button>
                <a href="<?= url('/suppliers') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
