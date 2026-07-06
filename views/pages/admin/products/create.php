<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/products') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ເພີ່ມສິນຄ້າໃໝ່</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ສ້າງຂໍ້ມູນສິນຄ້າໃໝ່ໃນລະບົບ</p>
            </div>
        </div>

        <form action="<?= url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data" class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຊື່ສິນຄ້າ <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ຊື່ສິນຄ້າ">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">SKU</label>
                        <input type="text" name="sku" value="<?= htmlspecialchars($_POST['sku'] ?? $autoSku ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ລະຫັດສິນຄ້າ (ອັດຕະໂນມັດ)">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ໝວດສິນຄ້າ <span class="text-red-400">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="">-- ເລືອກໝວດ --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຫົວໜ່ວຍ</label>
                        <input type="text" name="unit" value="<?= htmlspecialchars($_POST['unit'] ?? 'ຊິ້ນ') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ຊິ້ນ, ກິໂລ, ກ່ອງ">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">Barcode</label>
                        <input type="text" name="barcode" value="<?= htmlspecialchars($_POST['barcode'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ບາໂຄດສິນຄ້າ">
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ລາຄາຕົ້ນທຶນ</label>
                        <div class="relative">
                            <input type="number" name="cost_price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['cost_price'] ?? '0') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <span class="absolute inset-y-0 right-3 flex items-center text-xs text-muted-foreground">ກີບ</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ລາຄາຂາຍ <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="number" name="selling_price" step="0.01" min="0" required value="<?= htmlspecialchars($_POST['selling_price'] ?? '0') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <span class="absolute inset-y-0 right-3 flex items-center text-xs text-muted-foreground">ກີບ</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85">ສະຕ໋ອກເລີ່ມຕົ້ນ</label>
                            <input type="number" name="stock" min="0" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85">ສະຕ໋ອກຕ່ຳສຸດ</label>
                            <input type="number" name="min_stock" min="0" value="<?= htmlspecialchars($_POST['min_stock'] ?? '10') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຮູບພາບ</label>
                        <div x-data="{ preview: null }">
                            <input type="file" name="image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])"
                                   class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
                            <template x-if="preview">
                                <div class="mt-3 relative inline-block">
                                    <img :src="preview" class="h-32 w-32 object-cover rounded-xl border">
                                    <button type="button" @click="preview = null; $el.closest('div').querySelector('input').value = ''" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center shadow">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="text-sm font-bold text-foreground/85">ສະຖານະ</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="active" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-card after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 text-sm text-foreground/70 peer-checked:text-green-600" id="statusLabel">ເປີດໃຊ້ງານ</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-border">
                <div class="space-y-1.5 mb-6">
                    <label class="text-sm font-bold text-foreground/85">ລາຍລະອຽດ</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ລາຍລະອຽດສິນຄ້າ"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                        <i class="fas fa-save"></i>
                        ບັນທຶກ
                    </button>
                    <a href="<?= url('/admin/products') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-foreground/85 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                        <i class="fas fa-times"></i>
                        ຍົກເລີກ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('input[name="status"]')?.addEventListener('change', function() {
    document.getElementById('statusLabel').textContent = this.checked ? 'ເປີດໃຊ້ງານ' : 'ປິດໃຊ້ງານ';
});
</script>
