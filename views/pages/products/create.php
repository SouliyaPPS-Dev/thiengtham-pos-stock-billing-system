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

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <form action="<?= url('/products/store') ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ສິນຄ້າ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ປ້ອນຊື່ສິນຄ້າ">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">SKU</label>
                <input type="text" name="sku" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ລະຫັດສິນຄ້າ">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ໝວດສິນຄ້າ</label>
                <select name="category_id" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="">-- ເລືອກໝວດ --</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລາຄາຕົ້ນທຶນ</label>
                <input type="number" name="cost_price" step="0.01" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="0">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລາຄາຂາຍ <span class="text-red-500">*</span></label>
                <input type="number" name="selling_price" required step="0.01" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="0">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ສະຕ໋ອກ</label>
                <input type="number" name="stock" value="0" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ສະຕ໋ອກຕ່ຳສຸດ</label>
                <input type="number" name="min_stock" value="0" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຫົວໜ່ວຍ</label>
                <input type="text" name="unit" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ຊິ້ນ, ກິໂລ, ລິດ...">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ສະຖານະ</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="active">ເປີດໃຊ້</option>
                    <option value="inactive">ປິດໃຊ້</option>
                </select>
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-sm font-bold text-gray-700">ລາຍລະອຽດ</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ລາຍລະອຽດສິນຄ້າ"></textarea>
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-sm font-bold text-gray-700">ຮູບສິນຄ້າ</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກ</span>
                </button>
                <a href="<?= url('/products') ?>" class="bg-gray-100 text-gray-600 rounded-xl px-6 py-2.5 font-bold hover:bg-gray-200 inline-flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
