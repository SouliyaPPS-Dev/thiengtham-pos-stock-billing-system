<div class="p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-800">ຕັ້ງຄ່າລະບົບ</h1>
                <p class="text-gray-500 text-sm">ຈັດການຂໍ້ມູນຮ້ານ ແລະ ການຕັ້ງຄ່າທົ່ວໄປ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Forms -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Card Form 1: ຂໍ້ມູນຮ້ານຄ້າ -->
                <form action="<?= url('/settings/update') ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                    <div class="p-6 border-b flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 leading-none">ຂໍ້ມູນຮ້ານຄ້າ</h3>
                            <p class="text-sm text-gray-500 mt-1">ຂໍ້ມູນເຫຼົ່ານີ້จะแสดงຢູ່ໃນໃບບິນ ແລະ ໜ້າເວັບ</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ຊື່ຮ້ານຄ້າ (Store Name) *</label>
                                <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ເບີໂທຕິດຕໍ່ຮ້ານ *</label>
                                <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ສະກຸນເງິນຫຼັກ</label>
                                <div class="relative" x-data="{ open: false, selected: '<?= htmlspecialchars($settings['currency'] ?? '₭') ?>' }">
                                    <div class="flex gap-2">
                                        <select @change="selected = $event.target.value; if($event.target.value === 'other') { selected = ''; $nextTick(() => $refs.customCurrency.focus()) }" class="w-1/3 px-2 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                            <option value="₭" <?= ($settings['currency'] ?? '₭') === '₭' ? 'selected' : '' ?>>₭ (LAK)</option>
                                            <option value="฿" <?= ($settings['currency'] ?? '') === '฿' ? 'selected' : '' ?>>฿ (THB)</option>
                                            <option value="$" <?= ($settings['currency'] ?? '') === '$' ? 'selected' : '' ?>>$ (USD)</option>
                                            <option value="other" <?= !in_array($settings['currency'] ?? '', ['₭', '฿', '$']) ? 'selected' : '' ?>>ອື່ນໆ...</option>
                                        </select>
                                        <input type="text" name="currency" x-model="selected" x-ref="customCurrency" placeholder="ລະບຸສະກຸນເງິນ" class="flex-1 px-2 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ອັດຕາອາກອນ (Tax %)</label>
                                <input type="number" step="0.01" name="tax_percent" value="<?= htmlspecialchars($settings['tax_percent'] ?? 0) ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div> 
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ຂະໜາດໃບບິນພິມ (Paper Size)</label>
                                <select name="paper_size" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    <option value="58mm" <?= ($settings['paper_size'] ?? '') === '58mm' ? 'selected' : '' ?>>58mm</option>
                                    <option value="80mm" <?= ($settings['paper_size'] ?? '') === '80mm' ? 'selected' : '' ?>>80mm</option>
                                    <option value="A4" <?= ($settings['paper_size'] ?? '') === 'A4' ? 'selected' : '' ?>>A4</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">ອີເມລ</label>
                                <input type="email" name="store_email" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">ທີ່ຢູ່ຮ້ານ</label>
                            <textarea name="store_address" rows="2" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"><?= htmlspecialchars($settings['store_address'] ?? '') ?></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">ເງື່ອນໄຂການເຊົ່າ</label>
                            <textarea name="rental_terms" rows="8" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="ລະບຸເງື່ອນໄຂການເຊົ່າ..."><?= htmlspecialchars($settings['rental_terms'] ?? '') ?></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">ຂໍ້ຄວາມທ້າຍໃບບິນ</label>
                            <textarea name="receipt_footer" rows="2" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"><?= htmlspecialchars($settings['receipt_footer'] ?? '') ?></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">ໂລໂກ້ຮ້ານ</label>
                            <div class="flex items-center gap-4 mt-2">
                                <?php if (!empty($settings['store_logo'])): ?>
                                    <img id="logoPreview" src="<?= get_logo_url($settings['store_logo']) ?>" class="h-20 w-20 rounded-xl object-cover border shadow-sm">
                                <?php else: ?>
                                    <div id="logoPlaceholder" class="h-20 w-20 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-dashed">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                    <img id="logoPreview" src="" class="h-20 w-20 rounded-xl object-cover border hidden">
                                <?php endif; ?>
                                <div class="flex-1">
                                    <input type="file" name="store_logo" id="logoInput" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-1">ແນະນຳຮູບຊົງສີ່ຫຼ່ຽມ 500x500px</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all">
                                <i class="fas fa-save mr-2"></i> ບັນທຶກຂໍ້ມູນ
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Card Form 2: ຊ່ອງທາງການຊຳລະເງິນ -->
                <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                    <div class="p-6 border-b flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 leading-none">ຊ່ອງທາງການຊຳລະເງິນ</h3>
                            <p class="text-sm text-gray-500 mt-1">ຈັດການບັນຊີທະນາຄານ ຫຼື ຊ່ອງທາງຮັບເງິນ</p>
                        </div>
                        <button onclick="toggleModal('paymentModal')" class="bg-gray-100 text-gray-700 text-sm font-bold py-2 px-4 rounded-xl hover:bg-gray-200 transition-all">
                            <i class="fas fa-plus mr-1"></i> ເພີ່ມ
                        </button>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 border-b">
                                <tr>
                                    <th class="px-6 py-3">ຊື່ຊ່ອງທາງ</th>
                                    <th class="px-6 py-3">ລາຍລະອຽດ</th>
                                    <th class="px-6 py-3 text-right">ຈັດການ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <?php foreach ($payment_methods as $method): ?>
                                    <tr class="hover:bg-gray-50 transition-all text-sm">
                                        <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($method['name']) ?></td>
                                        <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($method['details']) ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button onclick="openEditPaymentModal(<?= htmlspecialchars(json_encode($method)) ?>)" class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100" title="ແກ້ໄຂ">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="<?= url('/settings/payment-method/delete') ?>" method="POST" class="inline" onsubmit="return confirm('ຕ້ອງການລຶບແທ້ບໍ່?')">
                                                    <input type="hidden" name="id" value="<?= $method['id'] ?>">
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100" title="ລຶບ">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($payment_methods)): ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">ບໍ່ມີຂໍ້ມູນຊ່ອງທາງການຊຳລະ</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Cards -->
            <div class="space-y-8">
                <!-- Card Form 3: ແພັກເກັດ & ຄວາມປອດໄພ -->
                <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                    <div class="p-6 border-b">
                        <h3 class="font-bold text-gray-800 leading-none">ແພັກເກັດ & ຄວາມປອດໄພ</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="p-4 bg-primary/5 rounded-xl border border-primary/10">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-primary/20 rounded-full flex items-center justify-center text-primary">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">ແພັກເກັດປັດຈຸບັນ</p>
                                    <p class="font-bold text-gray-800">Premium Plan</p>
                                </div>
                            </div>
                        </div>
                         
                        <button onclick="toggleModal('passwordModal')" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all border border-gray-100">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-lock text-gray-400"></i>
                                <span class="font-semibold text-gray-700">ປ່ຽນລະຫັດຜ່ານແອັດມິນ</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 text-white shadow-xl">
                    <h4 class="font-bold mb-4">ຈັດການຂໍ້ມູນສຳຮອງ</h4>
                    <div class="space-y-3">
                        <a href="<?= url('/settings/database/export') ?>" class="flex items-center justify-center gap-2 w-full py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition-all border border-white/10">
                            <i class="fas fa-download"></i> ສົ່ງອອກຂໍ້ມູນ (Backup)
                        </a>
                        <button onclick="toggleModal('importModal')" class="flex items-center justify-center gap-2 w-full py-3 bg-sky-500 hover:bg-sky-600 rounded-xl font-bold transition-all">
                            <i class="fas fa-upload"></i> ກູ້ຄືນຂໍ້ມູນ (Restore)
                        </button>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 text-white shadow-xl">
                    <h4 class="font-bold mb-2">ຕ້ອງການຄວາມຊ່ວຍເຫຼືອ?</h4>
                    <p class="text-gray-400 text-sm mb-4">ຕິດຕໍ່ທີມງານຊ່ວຍເຫຼືອໄດ້ຕະຫຼອດ 24 ຊົ່ວໂມງ</p>
                    <a href="https://wa.me/8562078287509" target="_blank" class="block w-full text-center py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition-all">
                        <i class="fab fa-whatsapp mr-1"></i> WhatsApp Support
                    </a>
                </div>
            </div>
        </div>
    </div> 
</div> 

<!-- Payment Method Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto p-4" onclick="if(event.target===this) toggleModal('paymentModal')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-gray-800">ເພີ່ມຊ່ອງທາງການຊຳລະ</h3>
            <button onclick="toggleModal('paymentModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= url('/settings/payment-method/add') ?>" method="POST" class="p-6 space-y-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">ຊື່ຊ່ອງທາງ *</label>
                <input type="text" name="name" placeholder="ຕົວຢ່າງ: QR CODE, ເງິນສົດ" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">ລາຍລະອຽດ</label>
                <input type="text" name="details" placeholder="ເລກບັນຊີ ຫຼື ຂໍ້ມູນເພີ່ມເຕີມ" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all">
                ບັນທຶກຂໍ້ມູນ
            </button>
        </form>
    </div>
</div>

<!-- Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto p-4" onclick="if(event.target===this) toggleModal('passwordModal')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-gray-800">ປ່ຽນລະຫັດຜ່ານແອັດມິນ</h3>
            <button onclick="toggleModal('passwordModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= url('/settings/change-password') ?>" method="POST" class="p-6 space-y-4">
            <div class="space-y-2" x-data="{ show: false }">
                <label class="text-sm font-semibold text-gray-700">ລະຫັດຜ່ານປັດຈຸບັນ *</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="current_password" class="w-full px-4 pr-12 py-3 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2" x-data="{ show: false }">
                <label class="text-sm font-semibold text-gray-700">ລະຫັດຜ່ານໃໝ່ *</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="new_password" class="w-full px-4 pr-12 py-3 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2" x-data="{ show: false }">
                <label class="text-sm font-semibold text-gray-700">ຢືນຢັນລະຫັດຜ່ານໃໝ່ *</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="confirm_password" class="w-full px-4 pr-12 py-3 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all">
                ຢືນຢັນການປ່ຽນແປງ
            </button>
        </form>
    </div>
</div>

<!-- Edit Payment Method Modal -->
<div id="editPaymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto p-4" onclick="if(event.target===this) toggleModal('editPaymentModal')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-gray-800">ແກ້ໄຂຊ່ອງທາງການຊຳລະ</h3>
            <button onclick="toggleModal('editPaymentModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= url('/settings/payment-method/edit') ?>" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="edit_payment_id">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">ຊື່ຊ່ອງທາງ *</label>
                <input type="text" name="name" id="edit_payment_name" placeholder="ຕົວຢ່າງ: QR CODE, ເງິນສົດ" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">ລາຍລະອຽດ</label>
                <input type="text" name="details" id="edit_payment_details" placeholder="ເລກບັນຊີ ຫຼື ຂໍ້ມູນເພີ່ມເຕີມ" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all">
                ບັນທຶກການປ່ຽນແປງ
            </button>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto p-4" onclick="if(event.target===this) toggleModal('importModal')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-gray-800">ກູ້ຄືນຂໍ້ມູນ (Restore Database)</h3>
            <button onclick="toggleModal('importModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= url('/settings/database/import') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4" onsubmit="return confirm('ຄຳເຕືອນ: ການກູ້ຄືນຂໍ້ມູນຈະຂຽນທັບຂໍ້ມູນທັງໝົດໃນປັດຈຸບັນ. ທ່ານແນ່ໃຈບໍ່ວ່າຈະດຳເນີນການ?')">
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 mb-4">
                <p class="text-xs text-amber-700 leading-relaxed">
                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                    ເລືອກໄຟລ໌ສຳຮອງ (.sql) ທີ່ທ່ານໄດ້ດາວໂຫລດມາເພື່ອປ່ຽນແທນຂໍ້ມູນໃນຖານຂໍ້ມູນປັດຈຸບັນ.
                </p>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">ເລືອກໄຟລ໌ SQL *</label>
                <input type="file" name="backup_file" accept=".sql" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" required>
            </div>
            <button type="submit" class="w-full bg-sky-500 text-white font-bold py-3 rounded-xl hover:bg-sky-600 shadow-lg shadow-sky-200 transition-all">
                <i class="fas fa-upload mr-2"></i> ເລີ່ມການກູ້ຄືນ
            </button>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } else {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}

function openEditPaymentModal(data) {
    document.getElementById('edit_payment_id').value = data.id;
    document.getElementById('edit_payment_name').value = data.name;
    document.getElementById('edit_payment_details').value = data.details || '';
    toggleModal('editPaymentModal');
}

document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');
            
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
});

// Add Enter key support for modals
window.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        const modals = ['paymentModal', 'passwordModal', 'editPaymentModal', 'importModal'];
        for (const id of modals) {
            const modal = document.getElementById(id);
            if (modal && !modal.classList.contains('hidden')) {
                const form = modal.querySelector('form');
                if (form) {
                    // Check if it's a textarea to allow new lines
                    if (e.target.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                        form.submit();
                    }
                }
                break;
            }
        }
    }
});
</script>
