<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-2xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/suppliers') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ເພີ່ມຜູ້ສະໜອງໃໝ່</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ສ້າງຂໍ້ມູນຜູ້ສະໜອງໃໝ່</p>
            </div>
        </div>

        <form action="<?= url('/admin/suppliers/store') ?>" method="POST" class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ຊື່ຜູ້ສະໜອງ <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ຊື່ຜູ້ສະໜອງ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ຜູ້ຕິດຕໍ່</label>
                    <input type="text" name="contact_person" value="<?= htmlspecialchars($_POST['contact_person'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ຊື່ຜູ້ຕິດຕໍ່">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ເບີໂທ <span class="text-red-400">*</span></label>
                    <input type="text" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ເບີໂທລະສັບ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ອີເມວ</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                           placeholder="ອີເມວ">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ທີ່ຢູ່</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ທີ່ຢູ່"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ອາກອນມູນຄ່າເພີ່ມ (VAT %)</label>
                <input type="number" name="tax_percent" step="0.01" min="0" max="100" value="<?= htmlspecialchars($_POST['tax_percent'] ?? '10') ?>"
                       class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                       placeholder="10">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85">ຫມາຍເຫດ</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ຫມາຍເຫດ"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-save"></i>
                    ບັນທຶກ
                </button>
                <a href="<?= url('/admin/suppliers') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-foreground/85 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    ຍົກເລີກ
                </a>
            </div>
        </form>
    </div>
</div>
