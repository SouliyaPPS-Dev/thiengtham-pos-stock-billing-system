<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-2xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/promotions') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ແກ້ໄຂໂປຣໂມຊັ້ນ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ແກ້ໄຂຂໍ້ມູນໂປຣໂມຊັ້ນ</p>
            </div>
        </div>

        <form action="<?= url('/admin/promotions/' . $promotion['id'] . '/update') ?>" method="POST" class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8 space-y-5">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ຊື່ໂປຣໂມຊັ້ນ</label>
                <input type="text" name="title" value="<?= htmlspecialchars($promotion['title'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                       placeholder="ຊື່ໂປຣໂມຊັ້ນ">
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ລິ້ງຮູບພາບ <span class="text-red-400">*</span></label>
                <input type="url" name="image" required value="<?= htmlspecialchars($promotion['image']) ?>"
                       class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                       placeholder="https://example.com/image.jpg">
            </div>

            <?php if (!empty($promotion['image'])): ?>
            <div class="rounded-xl overflow-hidden border border-border">
                <img src="<?= htmlspecialchars($promotion['image']) ?>" alt="<?= htmlspecialchars($promotion['title'] ?? '') ?>" class="w-full h-40 object-cover">
            </div>
            <?php endif; ?>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ລິ້ງເມື່ອຄລິກ</label>
                <input type="text" name="link" value="<?= htmlspecialchars($promotion['link'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                       placeholder="/products ຫຼື https://...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ລຳດັບ</label>
                    <input type="number" name="sort_order" min="0" value="<?= htmlspecialchars($promotion['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="0">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ສະຖານະ</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="Active" <?= ($promotion['status'] ?? 'Active') === 'Active' ? 'selected' : '' ?>>ເປີດໃຊ້</option>
                        <option value="Inactive" <?= ($promotion['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>ປິດໃຊ້</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-save"></i>
                    ບັນທຶກການແກ້ໄຂ
                </button>
                <a href="<?= url('/admin/promotions') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-foreground/85 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    ຍົກເລີກ
                </a>
            </div>
        </form>
    </div>
</div>
