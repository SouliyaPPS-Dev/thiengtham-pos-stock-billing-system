<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="<?= url('/products') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ເພີ່ມສິນຄ້າໃໝ່</h1>
            <p class="text-sm text-gray-500">ບັນທຶກຂໍ້ມູນສິນຄ້າໃໝ່ເຂົ້າສູ່ລະບົບ</p>
        </div>
    </div>

    <div class="table-wrap">
        <form action="<?= url('/products/store') ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="form-label">ຊື່ສິນຄ້າ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="form-input" placeholder="ປ້ອນຊື່ສິນຄ້າ">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">SKU</label>
                <input type="text" name="sku" class="form-input" placeholder="ລະຫັດສິນຄ້າ">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ໝວດສິນຄ້າ</label>
                <select name="category_id" class="form-input">
                    <option value="">-- ເລືອກໝວດ --</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ລາຄາຕົ້ນທຶນ</label>
                <input type="number" name="cost_price" step="0.01" class="form-input" placeholder="0">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ລາຄາຂາຍ <span class="text-red-500">*</span></label>
                <input type="number" name="selling_price" required step="0.01" class="form-input" placeholder="0">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ສະຕ໋ອກ</label>
                <input type="number" name="stock" value="0" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ສະຕ໋ອກຕ່ຳສຸດ</label>
                <input type="number" name="min_stock" value="0" class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ຫົວໜ່ວຍ</label>
                <input type="text" name="unit" class="form-input" placeholder="ຊິ້ນ, ກິໂລ, ລິດ...">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ສະຖານະ</label>
                <select name="status" class="form-input">
                    <option value="active">ເປີດໃຊ້</option>
                    <option value="inactive">ປິດໃຊ້</option>
                </select>
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="form-label">ລາຍລະອຽດ</label>
                <textarea name="description" rows="3" class="form-input" placeholder="ລາຍລະອຽດສິນຄ້າ"></textarea>
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="form-label">ຮູບສິນຄ້າ</label>
                <input type="file" name="image" accept="image/*" class="form-input">
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກ</span>
                </button>
                <a href="<?= url('/products') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
