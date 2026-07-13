<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="<?= url('/bid-customers') ?>" class="h-10 w-10 rounded-2xl bg-card border border-border shadow-sm flex items-center justify-center text-muted-foreground hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ແກ້ໄຂລູກຄ້າທີ່ສະເໜີລາຄາ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ແກ້ໄຂຂໍ້ມູນ: <?= htmlspecialchars($bidCustomer['name']) ?></p>
            </div>
        </div>

        <!-- Form -->
        <form action="<?= url('/bid-customers/' . $bidCustomer['id'] . '/update') ?>" method="POST">
            <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fas fa-truck text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນລູກຄ້າທີ່ສະເໜີລາຄາ</h2>
                        <p class="text-xs text-muted-foreground">ຂໍ້ມູນພື້ນຖານຂອງລູກຄ້າທີ່ສະເໜີລາຄາ</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-building text-[10px] text-primary"></i>
                            ຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-building text-xs"></i>
                            </span>
                            <input type="text" name="name" required value="<?= htmlspecialchars($bidCustomer['name']) ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                   placeholder="ປ້ອນຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-user-tie text-[10px] text-primary"></i>
                            ຜູ້ຕິດຕໍ່
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-user-tie text-xs"></i>
                            </span>
                            <input type="text" name="contact_person" value="<?= htmlspecialchars($bidCustomer['contact_person'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                   placeholder="ຊື່ຜູ້ຕິດຕໍ່">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-phone text-[10px] text-primary"></i>
                            ເບີໂທ
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-phone text-xs"></i>
                            </span>
                            <input type="text" name="phone" value="<?= htmlspecialchars($bidCustomer['phone'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                   placeholder="ເບີໂທລະສັບ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-envelope text-[10px] text-primary"></i>
                            ອີເມວ
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-envelope text-xs"></i>
                            </span>
                            <input type="email" name="email" value="<?= htmlspecialchars($bidCustomer['email'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                   placeholder="ອີເມວ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-[10px] text-primary"></i>
                            ທີ່ຢູ່
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </span>
                            <input type="text" name="address" value="<?= htmlspecialchars($bidCustomer['address'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                   placeholder="ທີ່ຢູ່">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                            <i class="fas fa-sticky-note text-[10px] text-primary"></i>
                            ໝາຍເຫດ
                        </label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300 resize-none"
                                  placeholder="ໝາຍເຫດ"><?= htmlspecialchars($bidCustomer['notes'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-50">
                    <a href="<?= url('/bid-customers') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-foreground/70 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all border border-border">
                        <i class="fas fa-times"></i>
                        <span>ຍົກເລີກ</span>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                        <i class="fas fa-save"></i>
                        <span>ບັນທຶກຂໍ້ມູນ</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
