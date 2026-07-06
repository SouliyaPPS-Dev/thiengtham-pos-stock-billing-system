<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ຕັ້ງຄ່າ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການການຕັ້ງຄ່າລະບົບ</p>
            </div>
        </div>

        <div class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <i class="fas fa-store text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນຮ້ານຄ້າ</h2>
                        <p class="text-xs text-muted-foreground">ຂໍ້ມູນທົ່ວໄປຂອງຮ້ານຄ້າ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85">ໂລໂກ້ຮ້ານ</label>
                        <div x-data="{ preview: null, currentLogo: '<?= htmlspecialchars($settings['store_logo'] ?? '') ?>' }" class="flex items-center gap-4">
                            <div class="h-20 w-20 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden border">
                                <template x-if="currentLogo && !preview">
                                    <img :src="currentLogo" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!currentLogo && !preview">
                                    <i class="fas fa-store text-2xl text-gray-300"></i>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="h-full w-full object-cover">
                                </template>
                            </div>
                            <input type="file" name="store_logo" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0]); currentLogo = null"
                                   class="flex-1 text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຊື່ຮ້ານ <span class="text-red-400">*</span></label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                <i class="fas fa-store text-xs"></i>
                            </span>
                            <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ຊື່ຮ້ານ">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ສະກຸນເງິນ</label>
                        <select name="currency"
                                class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="LAK" <?= (($settings['currency'] ?? 'LAK') === 'LAK') ? 'selected' : '' ?>>LAK - ກີບລາວ</option>
                            <option value="THB" <?= (($settings['currency'] ?? 'LAK') === 'THB') ? 'selected' : '' ?>>THB - ບາດໄທ</option>
                            <option value="USD" <?= (($settings['currency'] ?? 'LAK') === 'USD') ? 'selected' : '' ?>>USD - ໂດລາສະຫະລັດ</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ອາກອນ (%)</label>
                        <input type="number" name="tax_percent" step="0.01" min="0" value="<?= htmlspecialchars($settings['tax_percent'] ?? '0') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຂະໜາດໃບບິນ</label>
                        <select name="paper_size"
                                class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="58mm" <?= (($settings['paper_size'] ?? '58mm') === '58mm') ? 'selected' : '' ?>>58mm (Thermal)</option>
                            <option value="80mm" <?= (($settings['paper_size'] ?? '') === '80mm') ? 'selected' : '' ?>>80mm (Thermal)</option>
                            <option value="A4" <?= (($settings['paper_size'] ?? '') === 'A4') ? 'selected' : '' ?>>A4</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກຂໍ້ມູນ</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <i class="fas fa-database text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ສຳຮອງຂໍ້ມູນ (Backup / Restore)</h2>
                        <p class="text-xs text-muted-foreground">ສົ່ງອອກ ຫຼື ກູ້ຄືນຂໍ້ມູນຖານຂໍ້ມູນ</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="<?= url('/admin/settings/database/export') ?>"
                       class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold text-sm hover:from-green-600 hover:to-green-700 transition-all shadow-lg shadow-green-300 active:scale-[0.97]">
                        <i class="fas fa-download"></i> ສົ່ງອອກຂໍ້ມູນ (Backup)
                    </a>
                    <button onclick="toggleImportModal()"
                            class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                        <i class="fas fa-upload"></i> ກູ້ຄືນຂໍ້ມູນ (Restore)
                    </button>
                </div>
            </div>

            </div>

            <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i class="fas fa-file-invoice text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ຕັ້ງຄ່າໂລໂກ້ໃບບິນ</h2>
                        <p class="text-xs text-muted-foreground">ຮູບພາບໂລໂກ້ທີ່ສະແດງໃນໃບພິມ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/update') ?>" method="POST" enctype="multipart/form-data">
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ໂລໂກ້ໃບບິນ (Bill Logo)</label>
                        <div x-data="{ preview: null, currentLogo: '<?= htmlspecialchars($settings['bill_logo'] ?? '') ?>' }" class="flex items-center gap-4">
                            <div class="h-20 w-20 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden border">
                                <template x-if="currentLogo && !preview">
                                    <img :src="currentLogo" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!currentLogo && !preview">
                                    <i class="fas fa-file-invoice text-2xl text-gray-300"></i>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="h-full w-full object-cover">
                                </template>
                            </div>
                            <input type="file" name="bill_logo" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0]); currentLogo = null"
                                   class="flex-1 text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85">ກ້ວາງ (Width)</label>
                            <input type="number" name="bill_logo_width" min="20" max="500" value="<?= htmlspecialchars($settings['bill_logo_width'] ?? '150') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85">ສູງ (Height)</label>
                            <input type="number" name="bill_logo_height" min="20" max="500" value="<?= htmlspecialchars($settings['bill_logo_height'] ?? '150') ?>"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກ</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fas fa-info-circle text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນເພີ່ມເຕີມ</h2>
                        <p class="text-xs text-muted-foreground">ຂໍ້ມູນຕິດຕໍ່ ແລະ ຂໍ້ຄວາມໃບບິນ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/update') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85">ທີ່ຢູ່ຮ້ານ</label>
                        <input type="text" name="store_address" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ທີ່ຢູ່ຮ້ານ">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ເບີໂທຮ້ານ</label>
                        <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ເບີໂທຮ້ານ">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ອີເມວຮ້ານ</label>
                        <input type="email" name="store_email" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                               placeholder="ອີເມວຮ້ານ">
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85">ຂໍ້ຄວາມທ້າຍໃບບິນ (Receipt Footer)</label>
                        <textarea name="receipt_footer" rows="2" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ຂໍ້ຄວາມທ້າຍໃບບິນ"><?= htmlspecialchars($settings['receipt_footer'] ?? '') ?></textarea>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85">ເງື່ອນໄຂໃບແຈ້ງໜີ້ (Invoice Terms)</label>
                        <textarea name="invoice_terms" rows="2" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm resize-none" placeholder="ເງື່ອນໄຂ"><?= htmlspecialchars($settings['invoice_terms'] ?? '') ?></textarea>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            <span>ບັນທຶກຂໍ້ມູນ</span>
                        </button>
                    </div>
                </form>
            </div>

            <div id="payment-methods" class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ວິທີຊຳລະ</h2>
                        <p class="text-xs text-muted-foreground">ຈັດການວິທີຊຳລະເງິນໃນລະບົບ POS</p>
                    </div>
                </div>

                <div x-data="paymentMethodsManager()" class="space-y-4">
                    <!-- Add Form -->
                    <form method="POST" action="<?= url('/admin/payment-methods/store') ?>" class="flex flex-col sm:flex-row gap-3 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-muted-foreground mb-1">ຊື່ວິທີຊຳລະ</label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ເຊັ່ນ: ເງິນສົດ, QR Code, ໂອນ">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-muted-foreground mb-1">ລາຍລະອຽດ (ທາງເລືອກ)</label>
                            <input type="text" name="details"
                                   class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ຄຳອະທິບາຍ">
                        </div>
                        <label class="flex items-center gap-2 pb-2 cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-foreground/70">ເປີດໃຊ້</span>
                        </label>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97] whitespace-nowrap">
                            <i class="fas fa-plus"></i>
                            <span>ເພີ່ມ</span>
                        </button>
                    </form>

                    <!-- List -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຊື່</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍລະອຽດ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($paymentMethods)): ?>
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-muted-foreground text-sm">ຍັງບໍ່ມີວິທີຊຳລະ</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($paymentMethods as $pm): ?>
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($pm['name']) ?></td>
                                    <td class="py-3 px-2 text-muted-foreground"><?= htmlspecialchars($pm['details'] ?? '') ?></td>
                                    <td class="py-3 px-2">
                                        <?php if ($pm['is_active']): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            ເປີດໃຊ້
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-50 text-muted-foreground">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                            ປິດໃຊ້
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-1 justify-end">
                                            <button type="button" @click="openEdit(<?= $pm['id'] ?>, '<?= htmlspecialchars($pm['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($pm['details'] ?? '', ENT_QUOTES) ?>', <?= $pm['is_active'] ?>)" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                            <form method="POST" action="<?= url('/admin/payment-methods/' . $pm['id'] . '/delete') ?>" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່?')">
                                                <button type="submit" class="icon-btn icon-btn-danger" title="ລົບ">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Edit Modal -->
                    <div x-show="editModal" @keydown.escape.window="editModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30" x-cloak>
                        <div class="bg-card rounded-2xl shadow-2xl max-w-md w-full p-6" @click.away="editModal = false">
                            <h3 class="text-lg font-extrabold text-foreground mb-4">ແກ້ໄຂວິທີຊຳລະ</h3>
                            <form :action="editAction" method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-foreground/70 mb-1">ຊື່</label>
                                    <input type="text" name="name" x-model="editName" required
                                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-foreground/70 mb-1">ລາຍລະອຽດ</label>
                                    <input type="text" name="details" x-model="editDetails"
                                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" x-model="editActive" class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-foreground/70">ເປີດໃຊ້</span>
                                </label>
                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="editModal = false" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-foreground/70 hover:bg-gray-200 transition-all">
                                        ຍົກເລີກ
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200">
                                        <i class="fas fa-save"></i> ບັນທຶກ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                function paymentMethodsManager() {
                    return {
                        editModal: false,
                        editAction: '',
                        editName: '',
                        editDetails: '',
                        editActive: true,
                        openEdit(id, name, details, active) {
                            this.editAction = '<?= url('/admin/payment-methods') ?>/' + id + '/update';
                            this.editName = name;
                            this.editDetails = details;
                            this.editActive = !!active;
                            this.editModal = true;
                        }
                    };
                }
                </script>
            </div>

            <!-- Import Modal -->
            <div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto p-4" onclick="if(event.target===this) toggleImportModal()">
                <div class="bg-card rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h3 class="font-bold text-foreground">ກູ້ຄືນຂໍ້ມູນ (Restore Database)</h3>
                        <button onclick="toggleImportModal()" class="text-muted-foreground hover:text-foreground/70">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="<?= url('/admin/settings/database/import') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່? ຂໍ້ມູນປັດຈຸບັນຈະຖືກແທນທີ່ທັງໝົດ!')">
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <p class="text-xs text-amber-700 leading-relaxed">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                ເລືອກໄຟລ໌ສຳຮອງ (.sql) ທີ່ຕ້ອງການກູ້ຄືນ. ຂໍ້ມູນທີ່ມີຢູ່ຈະຖືກແທນທີ່ທັງໝົດ.
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-foreground/85">ເລືອກໄຟລ໌ SQL *</label>
                            <input type="file" name="backup_file" accept=".sql" required
                                   class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100 border border-border rounded-xl p-2">
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-upload"></i> ເລີ່ມການກູ້ຄືນ
                        </button>
                    </form>
                </div>
            </div>

            <script>
            function toggleImportModal() {
                document.getElementById('importModal').classList.toggle('hidden');
                document.getElementById('importModal').classList.toggle('flex');
            }
            </script>

            <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-foreground">ປ່ຽນລະຫັດຜ່ານ</h2>
                        <p class="text-xs text-muted-foreground">ປ່ຽນລະຫັດຜ່ານສຳລັບເຂົ້າສູ່ລະບົບ</p>
                    </div>
                </div>
                <form action="<?= url('/admin/settings/change-password') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85">ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground pointer-events-none">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="current_password" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ລະຫັດປັດຈຸບັນ">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground pointer-events-none">
                                <i class="fas fa-key text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="new_password" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ລະຫັດໃໝ່">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-foreground/85">ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-red-400">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground pointer-events-none">
                                <i class="fas fa-check-circle text-xs"></i>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="new_password_confirm" required
                                   class="w-full pl-9 pr-10 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"
                                   placeholder="ຢືນຢັນລະຫັດໃໝ່">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-primary">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                            <i class="fas fa-key"></i>
                            <span>ປ່ຽນລະຫັດຜ່ານ</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
