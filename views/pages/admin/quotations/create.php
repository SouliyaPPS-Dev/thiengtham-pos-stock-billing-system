<?php $isEdit = !is_null($quotation); ?>
<div class="min-h-screen bg-background p-4 md:p-8" x-data="quotationForm()" @scroll.window="closeOpenDropdowns()">
    <div class="max-w-6xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/quotations') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight"><?= $isEdit ? 'ແກ້ໄຂໃບສະເໜີລາຄາ' : 'ສ້າງໃບສະເໜີລາຄາໃໝ່' ?></h1>
                <p class="text-sm text-muted-foreground mt-0.5"><?= $isEdit ? 'ແກ້ໄຂຂໍ້ມູນໃບສະເໜີລາຄາ' : 'ສ້າງໃບສະເໜີລາຄາຕາມແບບຟອມ Excel' ?></p>
            </div>
        </div>

        <form method="POST" action="<?= $isEdit ? url('/admin/quotations/' . $quotation['id'] . '/update') : url('/admin/quotations/store') ?>" @submit.prevent="submitForm()">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Settings -->
                <div class="space-y-6">
                    <div class="bg-card rounded-2xl border border-border shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-foreground mb-4">ຂໍ້ມູນລູກຄ້າທີ່ສະເໜີລາຄາ</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ເລືອກລູກຄ້າທີ່ສະເໜີລາຄາ</label>
                                <select x-model="supplierId" @change="selectSupplier()" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">-- ເລືອກ --</option>
                                    <?php foreach ($bidCustomers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="bid_customer_id" x-model="supplierId">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ</label>
                                <input type="text" name="bid_customer_name" x-model="supplierName" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ຜູ້ຕິດຕໍ່ / ເບີໂທ</label>
                                <input type="text" name="bid_customer_contact" x-model="supplierContact" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ອາກອນມູນຄ່າເພີ້ມ (VAT %)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="tax_percent" x-model="taxPercent" @input="calcTotals()" min="0" max="100" step="0.01" list="vat-options" class="w-24 px-3 py-2.5 border border-border rounded-xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <datalist id="vat-options">
                                        <option value="0">
                                        <option value="5">
                                        <option value="7">
                                        <option value="10">
                                    </datalist>
                                    <button type="button" @click="saveSupplierTax()" :disabled="!supplierId" class="px-3 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap" :class="supplierId ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-gray-50 text-gray-300 cursor-not-allowed'">
                                        <i class="fas fa-save mr-1"></i> ບັນທຶກ
                                    </button>
                                    <span class="text-xs text-muted-foreground ml-1">(ຕັ້ງອັດຕາອາກອນຕາມລູກຄ້າທີ່ສະເໜີລາຄາ)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-foreground mb-4">ຂໍ້ມູນລູກຄ້າ</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ເລືອກລູກຄ້າ</label>
                                <select x-model="customerId" @change="selectCustomer()" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">-- ເລືອກ --</option>
                                    <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['fullname']) ?> (<?= htmlspecialchars($c['phone'] ?? '') ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="customer_id" x-model="customerId">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ຊື່ລູກຄ້າ</label>
                                <input type="text" name="customer_name" x-model="customerName" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ຜູ້ຕິດຕໍ່ລູກຄ້າ</label>
                                <input type="text" name="customer_contact" x-model="customerContact" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-foreground mb-4">ຂໍ້ມູນເພີ່ມເຕີມ</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ແບບຟອມຄົບຮອບ (Company Template)</label>
                                <select name="company_template" x-model="companyTemplate" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <?php foreach ($templates as $key => $t): ?>
                                    <option value="<?= $key ?>"><?= htmlspecialchars($t['label']) ?> - <?= htmlspecialchars($t['company']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ເລກອ້າງອີງ (Ref No.)</label>
                                <input type="text" name="ref_no" x-model="refNo" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="z.B. PR-2026-...">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-muted-foreground mb-1 block">ວັນທີ</label>
                                    <input type="date" name="date" x-model="date" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-muted-foreground mb-1 block">ວັນໝົດອາຍຸ (Expiry)</label>
                                    <input type="date" name="expiry_date" x-model="expiryDate" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ເງື່ອນໄຂ / Terms</label>
                                <textarea name="terms" x-model="terms" rows="4" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ສະຖານະ</label>
                                <select name="status" x-model="status" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="Draft">ຮ່າງ</option>
                                    <option value="Sent">ສົ່ງແລ້ວ</option>
                                    <option value="Approved">ອະນຸມັດ</option>
                                    <option value="Rejected">ປະຕິເສດ</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-muted-foreground mb-1 block">ໝາຍເຫດ</label>
                                <textarea name="notes" x-model="notes" rows="2" class="w-full px-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bill Settings Section -->
                    <div class="bg-card rounded-2xl border border-border shadow-sm p-6" id="bill-settings-section">
                        <h2 class="text-base font-extrabold text-foreground mb-4">ຕັ້ງຄ່າຮູບພາບໃບບິນ</h2>
                        <div class="space-y-4">

                            <!-- Logo Section -->
                            <div class="space-y-3">
                                <h4 class="text-sm font-extrabold text-foreground">ໂລໂກ້ໃບບິນ</h4>
                                <div x-data="{ preview: null, currentLogo: '<?= htmlspecialchars($settings['bill_logo'] ?? '') ?>' }" class="flex items-center gap-3">
                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-muted to-muted flex items-center justify-center overflow-hidden border shrink-0">
                                        <template x-if="currentLogo && !preview">
                                            <img :src="currentLogo" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!currentLogo && !preview">
                                            <i class="fas fa-image text-xl text-gray-300"></i>
                                        </template>
                                        <template x-if="preview">
                                            <img :src="preview" class="h-full w-full object-cover">
                                        </template>
                                    </div>
                                    <input type="file" name="bill_logo" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0]); currentLogo = null"
                                           class="flex-1 text-xs file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100">
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ກ້ວາງ</label>
                                        <input type="number" name="bill_logo_width" min="20" max="500" value="<?= htmlspecialchars($settings['bill_logo_width'] ?? '150') ?>"
                                               class="w-full px-3 py-2 border border-border rounded-xl text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ສູງ</label>
                                        <input type="number" name="bill_logo_height" min="20" max="500" value="<?= htmlspecialchars($settings['bill_logo_height'] ?? '150') ?>"
                                               class="w-full px-3 py-2 border border-border rounded-xl text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ຕຳແໜ່ງ</label>
                                        <select name="bill_logo_position"
                                                class="w-full px-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            <option value="top-left" <?= ($settings['bill_logo_position'] ?? 'top-left') === 'top-left' ? 'selected' : '' ?>>ຊ້າຍ-ເທິງ</option>
                                            <option value="top-center" <?= ($settings['bill_logo_position'] ?? '') === 'top-center' ? 'selected' : '' ?>>ກາງ-ເທິງ</option>
                                            <option value="top-right" <?= ($settings['bill_logo_position'] ?? '') === 'top-right' ? 'selected' : '' ?>>ຂວາ-ເທິງ</option>
                                            <option value="center" <?= ($settings['bill_logo_position'] ?? '') === 'center' ? 'selected' : '' ?>>ກາງ</option>
                                            <option value="bottom-left" <?= ($settings['bill_logo_position'] ?? '') === 'bottom-left' ? 'selected' : '' ?>>ຊ້າຍ-ລຸ່ມ</option>
                                            <option value="bottom-center" <?= ($settings['bill_logo_position'] ?? '') === 'bottom-center' ? 'selected' : '' ?>>ກາງ-ລຸ່ມ</option>
                                            <option value="bottom-right" <?= ($settings['bill_logo_position'] ?? '') === 'bottom-right' ? 'selected' : '' ?>>ຂວາ-ລຸ່ມ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Section -->
                            <div class="space-y-3">
                                <h4 class="text-sm font-extrabold text-foreground">ລາຍເຊັນໃບບິນ</h4>
                                <div x-data="{ preview: null, currentSig: '<?= htmlspecialchars($settings['bill_signature'] ?? '') ?>' }" class="flex items-center gap-3">
                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-muted to-muted flex items-center justify-center overflow-hidden border shrink-0">
                                        <template x-if="currentSig && !preview">
                                            <img :src="currentSig" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!currentSig && !preview">
                                            <i class="fas fa-pen text-xl text-gray-300"></i>
                                        </template>
                                        <template x-if="preview">
                                            <img :src="preview" class="h-full w-full object-cover">
                                        </template>
                                    </div>
                                    <input type="file" name="bill_signature" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0]); currentSig = null"
                                           class="flex-1 text-xs file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100">
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ກ້ວາງ</label>
                                        <input type="number" name="bill_signature_width" min="20" max="500" value="<?= htmlspecialchars($settings['bill_signature_width'] ?? '150') ?>"
                                               class="w-full px-3 py-2 border border-border rounded-xl text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ສູງ</label>
                                        <input type="number" name="bill_signature_height" min="20" max="500" value="<?= htmlspecialchars($settings['bill_signature_height'] ?? '50') ?>"
                                               class="w-full px-3 py-2 border border-border rounded-xl text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs font-bold text-muted-foreground mb-1 block">ຕຳແໜ່ງ</label>
                                        <select name="bill_signature_position"
                                                class="w-full px-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            <option value="center" <?= ($settings['bill_signature_position'] ?? 'center') === 'center' ? 'selected' : '' ?>>ກາງ</option>
                                            <option value="top-left" <?= ($settings['bill_signature_position'] ?? '') === 'top-left' ? 'selected' : '' ?>>ຊ້າຍ-ເທິງ</option>
                                            <option value="top-center" <?= ($settings['bill_signature_position'] ?? '') === 'top-center' ? 'selected' : '' ?>>ກາງ-ເທິງ</option>
                                            <option value="top-right" <?= ($settings['bill_signature_position'] ?? '') === 'top-right' ? 'selected' : '' ?>>ຂວາ-ເທິງ</option>
                                            <option value="center-left" <?= ($settings['bill_signature_position'] ?? '') === 'center-left' ? 'selected' : '' ?>>ຊ້າຍ-ກາງ</option>
                                            <option value="center-right" <?= ($settings['bill_signature_position'] ?? '') === 'center-right' ? 'selected' : '' ?>>ຂວາ-ກາງ</option>
                                            <option value="bottom-left" <?= ($settings['bill_signature_position'] ?? '') === 'bottom-left' ? 'selected' : '' ?>>ຊ້າຍ-ລຸ່ມ</option>
                                            <option value="bottom-center" <?= ($settings['bill_signature_position'] ?? '') === 'bottom-center' ? 'selected' : '' ?>>ກາງ-ລຸ່ມ</option>
                                            <option value="bottom-right" <?= ($settings['bill_signature_position'] ?? '') === 'bottom-right' ? 'selected' : '' ?>>ຂວາ-ລຸ່ມ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bill Terms -->
                            <div class="space-y-3">
                                <h4 class="text-sm font-extrabold text-foreground">ເງື່ອນໄຂໃບບິນ</h4>
                                <textarea name="bill_terms" rows="4" class="w-full px-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder='ຕົວຢ່າງ: ເສັ້ນສະເໜີລາຄາ 60 ວັນ / ຈັດສົ່ງ 14 ວັນ ຫຼັງ PO / ເຄຣດິດ 30 ວັນ'><?= htmlspecialchars($settings['bill_terms'] ?? '') ?></textarea>
                            </div>

                            <!-- Save Button -->
                            <button type="button" onclick="saveBillSettings()" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-300">
                                <i class="fas fa-save"></i> ບັນທຶກຕັ້ງຄ່າ
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right: Items -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-card rounded-2xl border border-border shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-extrabold text-foreground">ລາຍການສິນຄ້າ</h2>
                            <div class="flex items-center gap-2">
                                <button @click="openProductBrowser()" type="button" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-all">
                                    <i class="fas fa-box"></i> ລາຍການສິນຄ້າ
                                </button>
                                <button @click="addItem()" type="button" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl text-xs font-bold hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                                    <i class="fas fa-plus"></i> ເພີ່ມລາຍການ
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto" @scroll="closeOpenDropdowns()">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-border">
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:36px">#</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:40px">ຮູບ</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:70px">ຈຳນວນ</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:60px">ຫົວໜ່ວຍ</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-right" style="width:120px">ລາຄາ/ໜ່ວຍ</th>
                                        <th class="py-2 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-right" style="width:120px">ຈຳນວນເງິນ</th>
                                        <th class="py-2 px-2 text-center" style="width:32px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="border-b border-gray-50">
                                            <td class="py-2 px-2 text-muted-foreground text-xs text-center" x-text="index + 1"></td>
                                            <td class="py-2 px-2 text-center">
                                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden mx-auto">
                                                    <template x-if="item.image">
                                                        <img :src="item.image" class="h-full w-full object-cover">
                                                    </template>
                                                    <template x-if="!item.image">
                                                        <i class="fas fa-box text-[10px] text-gray-300"></i>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="py-2 px-2">
                                                <div class="relative" @click.away="item.showDropdown = false">
                                                    <input type="text" x-model="item.product_name" @focus="searchProduct(item, $el)" @input.debounce="searchProduct(item, $el)" placeholder="ຊື່ສິນຄ້າ..." class="w-full px-2 py-1.5 bg-gray-50 border border-border rounded-lg text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                                    <template x-if="item.showDropdown && item.searchResults?.length > 0">
                                                        <div class="fixed z-[9999] max-h-40 overflow-y-auto bg-card border rounded-xl shadow-xl"
                                                             :style="item.searchDropdownStyle">
                                                            <template x-for="p in item.searchResults" :key="p.id">
                                                                <div @click="selectProduct(item, p)" class="px-3 py-2 hover:bg-gray-50 cursor-pointer border-b last:border-0 flex items-center gap-2">
                                                                    <div x-show="p.image" class="w-6 h-6 rounded bg-gray-100 overflow-hidden flex-shrink-0">
                                                                        <img :src="p.image" class="w-full h-full object-cover">
                                                                    </div>
                                                                    <div>
                                                                        <div class="text-xs font-bold text-foreground" x-text="p.name"></div>
                                                                        <div class="text-[9px] text-muted-foreground" x-text="p.sku || ''"></div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                                <input type="hidden" :name="'items[' + index + '][product_id]'" x-model="item.product_id">
                                                <input type="hidden" :name="'items[' + index + '][product_name]'" x-model="item.product_name">
                                            </td>
                                            <td class="py-2 px-2">
                                                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" @input="calcItemAmount(item)" min="0" step="1" class="w-full px-2 py-1.5 bg-gray-50 border border-border rounded-lg text-xs text-center font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2">
                                                <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit" class="w-full px-2 py-1.5 bg-gray-50 border border-border rounded-lg text-xs text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2">
                                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" @input="calcItemAmount(item)" min="0" step="1" class="w-full px-2 py-1.5 bg-gray-50 border border-border rounded-lg text-xs text-right font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2 text-right font-bold text-foreground text-xs" x-text="formatPrice(item.amount)"></td>
                                            <td class="py-2 px-2 text-center">
                                                <button @click="removeItem(index)" type="button" class="text-gray-300 hover:text-red-500 transition-colors"><i class="fas fa-times text-xs"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr>
                                            <td colspan="8" class="py-8 text-center text-muted-foreground">
                                                <i class="fas fa-box-open text-2xl mb-2 block"></i>
                                                <p class="text-xs font-bold">ຍັງບໍ່ມີລາຍການ</p>
                                                <p class="text-[10px] mt-1">ກົດ "ເພີ່ມລາຍການ" ເພື່ອເລີ່ມຕື່ມ</p>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="mt-4 pt-4 border-t border-border flex flex-col items-end">
                            <div class="w-full max-w-xs space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-muted-foreground">ລວມຍ່ອຍ</span>
                                    <span class="font-bold text-foreground" x-text="formatPrice(subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-muted-foreground">ສ່ວນຫຼຸດ</span>
                                    <input type="number" name="discount" x-model="discount" @input="calcTotals()" min="0" class="w-20 px-2 py-0.5 bg-gray-50 border border-border rounded text-xs text-right font-bold focus:ring-1 focus:ring-primary outline-none">
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-muted-foreground">ອາກອນມູນຄ່າເພີ້ມ</span>
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="tax_percent" x-model="taxPercent" @input="calcTotals()" min="0" max="100" step="0.01" class="w-14 px-2 py-0.5 bg-gray-50 border border-border rounded text-xs text-right font-bold focus:ring-1 focus:ring-primary outline-none">
                                        <span class="text-muted-foreground">%</span>
                                    </div>
                                </div>
                                <div class="flex justify-between text-base font-black text-primary border-t border-border pt-1.5">
                                    <span>ລວມທັງໝົດ</span>
                                    <span x-text="formatPrice(grandTotal)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="items_json" x-model="itemsJson">
                    <input type="hidden" name="subtotal" x-model="subtotal">
                    <input type="hidden" name="tax_amount" x-model="taxAmount">
                    <input type="hidden" name="grand_total" x-model="grandTotal">

                    <div class="flex items-center justify-end gap-3">
                        <a href="<?= url('/admin/quotations') ?>" class="px-5 py-2.5 bg-gray-100 text-foreground/70 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">ຍົກເລີກ</a>
                        <?php if ($isEdit): ?>
                        <a href="<?= url('/admin/quotations/' . $quotation['id'] . '/print?pdf=1') ?>" target="_blank" class="px-5 py-2.5 bg-red-500 text-white rounded-xl font-bold text-sm hover:bg-red-600 transition-all shadow-lg shadow-red-300 inline-flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            Export PDF
                        </a>
                        <?php endif; ?>
                        <button type="submit" name="action" value="save" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-300">ບັນທຶກ</button>
                        <button type="submit" name="action" value="save_print" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-300">
                            <i class="fas fa-print"></i> ບັນທຶກ ແລະ ພິມ
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Product Browser Modal (center) -->
        <div x-show="showProductBrowser" class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             x-cloak>
            <div class="fixed inset-0 bg-black/40 z-0"
                 @click="closeProductBrowser()"></div>
            <div class="relative z-50 w-full max-w-lg bg-card rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-border flex-shrink-0">
                    <h3 class="text-base font-extrabold text-foreground">
                        <i class="fas fa-box text-primary mr-2"></i>ລາຍການສິນຄ້າ
                    </h3>
                    <button @click="closeProductBrowser()" type="button" class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <!-- Search -->
                <div class="p-4 border-b border-gray-50 flex-shrink-0">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs"></i>
                        <input type="text" x-model="productSearch" @input.debounce.500ms="onProductSearchInput()" placeholder="ຄົ້ນຫາຊື່ສິນຄ້າ..." class="w-full pl-8 pr-3 py-2.5 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                </div>
                <!-- Product List (scrollable, infinite scroll) -->
                <div class="flex-1 overflow-y-auto min-h-0 px-1" @scroll="checkProductScroll($el)" style="max-height: calc(85vh - 180px);">
                    <template x-if="!loading && filteredProducts.length === 0">
                        <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                            <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-3">
                                <i class="fas fa-box-open text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-foreground/70">ບໍ່ພົບສິນຄ້າ</p>
                        </div>
                    </template>
                    <div class="divide-y divide-gray-50">
                        <template x-for="p in filteredProducts" :key="p.id">
                            <div @click="addProductFromBrowser(p)"
                                 class="flex items-center gap-3 px-4 py-3 cursor-pointer transition-all hover:bg-gray-50 rounded-xl mx-1"
                                 :class="isProductInItems(p.id) ? 'bg-primary/5' : ''">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-muted to-muted flex items-center justify-center text-gray-300 overflow-hidden flex-shrink-0">
                                    <template x-if="p.image">
                                        <img :src="p.image" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!p.image">
                                        <i class="fas fa-box text-lg"></i>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-foreground truncate" x-text="p.name"></div>
                                    <div class="text-[11px] text-muted-foreground truncate" x-text="p.sku || ''"></div>
                                    <div class="text-xs font-bold text-primary mt-0.5" x-text="formatPrice(p.selling_price)"></div>
                                </div>
                                <template x-if="isProductInItems(p.id)">
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg flex-shrink-0">ເພີ່ມແລ້ວ</span>
                                </template>
                            </div>
                        </template>
                    </div>
                    <!-- Loading more indicator -->
                    <template x-if="loading">
                        <div class="flex items-center justify-center py-6">
                            <svg class="h-5 w-5 text-primary animate-spin-custom" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="ml-2 text-xs text-muted-foreground">ກຳລັງໂຫຼດ...</span>
                        </div>
                    </template>
                    <template x-if="!loading && !hasMore && filteredProducts.length > 0">
                        <div class="py-4 text-center text-[10px] text-muted-foreground">— ທັງໝົດແລ້ວ —</div>
                    </template>
                </div>
                <!-- Footer -->
                <div class="p-4 border-t border-border flex items-center justify-between rounded-b-2xl bg-gray-50/50 flex-shrink-0">
                    <span class="text-xs text-muted-foreground" x-text="filteredProducts.length + ' ລາຍການ'"></span>
                    <button @click="closeProductBrowser()" type="button" class="px-4 py-2 bg-gray-100 text-foreground/70 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all">ປິດ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quotationForm() {
    const allSearchProducts = <?= json_encode($products ?? []) ?>;
    let initialItems = [];
    <?php if ($isEdit && !empty($quotation['items'])): ?>
    initialItems = <?= json_encode(array_map(function($item) {
        return [
            'product_id' => $item['product_id'],
            'product_name' => $item['product_name'],
            'quantity' => (float)$item['quantity'],
            'unit' => $item['unit'] ?: 'SET',
            'unit_price' => (float)$item['unit_price'],
            'amount' => (float)$item['amount'],
            'image' => $item['product_image'] ?? '',
            'searchResults' => [],
            'showDropdown' => false,
            'searchDropdownStyle' => [],
        ];
    }, $quotation['items'])) ?>;
    <?php endif; ?>

    return {

        supplierId: '<?= $isEdit ? ($quotation['bid_customer_id'] ?? '') : '' ?>',
        supplierName: '<?= $isEdit ? htmlspecialchars($quotation['bid_customer_name'] ?? '', ENT_QUOTES) : '' ?>',
        supplierContact: '<?= $isEdit ? htmlspecialchars($quotation['bid_customer_contact'] ?? '', ENT_QUOTES) : '' ?>',
        customerId: '<?= $isEdit ? ($quotation['customer_id'] ?? '') : '' ?>',
        customerName: '<?= $isEdit ? htmlspecialchars($quotation['customer_name'] ?? '', ENT_QUOTES) : '' ?>',
        customerContact: '<?= $isEdit ? htmlspecialchars($quotation['customer_contact'] ?? '', ENT_QUOTES) : '' ?>',
        companyTemplate: '<?= $isEdit ? ($quotation['company_template'] ?? 'luang-prabarg') : 'luang-prabarg' ?>',
        refNo: '<?= $isEdit ? htmlspecialchars($quotation['ref_no'] ?? '', ENT_QUOTES) : '' ?>',
        date: '<?= $isEdit ? ($quotation['date'] ?? date('Y-m-d')) : date('Y-m-d') ?>',
        expiryDate: '<?= $isEdit ? ($quotation['expiry_date'] ?? '') : '' ?>',
        terms: '<?= $isEdit ? htmlspecialchars($quotation['terms'] ?? '', ENT_QUOTES) : '' ?>',
        status: '<?= $isEdit ? ($quotation['status'] ?? 'Draft') : 'Draft' ?>',
        notes: '<?= $isEdit ? htmlspecialchars($quotation['notes'] ?? '', ENT_QUOTES) : '' ?>',
        discount: <?= $isEdit ? ($quotation['discount'] ?? 0) : 0 ?>,
        taxPercent: <?= $isEdit ? ($quotation['tax_percent'] ?? 10) : 10 ?>,
        items: initialItems.length ? initialItems : [],

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
        },

        get taxAmount() {
            const sub = this.subtotal - (parseFloat(this.discount) || 0);
            return sub * (parseFloat(this.taxPercent) || 0) / 100;
        },

        get grandTotal() {
            const sub = this.subtotal - (parseFloat(this.discount) || 0);
            return sub + this.taxAmount;
        },

        get itemsJson() {
            return JSON.stringify(this.items.map(item => ({
                product_id: item.product_id,
                product_name: item.product_name,
                quantity: item.quantity,
                unit: item.unit,
                unit_price: item.unit_price,
                amount: item.amount,
            })));
        },

        selectSupplier() {
            const supplier = <?= json_encode($bidCustomers ?? []) ?>.find(s => s.id == this.supplierId);
            if (supplier) {
                this.supplierName = supplier.name;
                this.supplierContact = (supplier.contact_person || '') + (supplier.phone ? ' | ' + supplier.phone : '');
                if (supplier.tax_percent || supplier.tax_percent === 0) {
                    this.taxPercent = parseFloat(supplier.tax_percent);
                }
            }
        },

        selectCustomer() {
            const customer = <?= json_encode($customers ?? []) ?>.find(c => c.id == this.customerId);
            if (customer) {
                this.customerName = customer.fullname;
                this.customerContact = (customer.phone || '') + (customer.email ? ' | ' + customer.email : '');
            }
        },

        saveSupplierTax() {
            if (!this.supplierId) return;
            const formData = new FormData();
            formData.append('tax_percent', this.taxPercent);
            fetch('<?= url('/admin/bid-customers') ?>/' + this.supplierId + '/update-tax', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'ບັນທຶກສຳເລັດ', text: 'ບັນທຶກອັດຕາອາກອນໃຫ້ລູກຄ້າທີ່ສະເໜີລາຄາສຳເລັດ', timer: 2500, showConfirmButton: false });
                }
            });
        },

        addItem() {
            this.items.push({
                product_id: '',
                product_name: '',
                image: '',
                quantity: 1,
                unit: 'SET',
                unit_price: 0,
                amount: 0,
                searchResults: [],
                showDropdown: false,
                searchDropdownStyle: {},
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        searchProduct(item, inputEl) {
            const q = (item.product_name || '').toLowerCase().trim();
            if (!q) {
                item.searchResults = [];
                item.showDropdown = false;
                return;
            }
            item.searchResults = allSearchProducts.filter(p =>
                p.name.toLowerCase().includes(q) ||
                (p.sku || '').toLowerCase().includes(q)
            ).slice(0, 10);
            item.showDropdown = item.searchResults.length > 0;
            if (item.showDropdown && inputEl) {
                const rect = inputEl.getBoundingClientRect();
                item.searchDropdownStyle = {
                    top: (rect.bottom + 4) + 'px',
                    left: rect.left + 'px',
                    width: Math.max(rect.width, 280) + 'px',
                };
            }
        },

        selectProduct(item, product) {
            item.product_id = product.id;
            item.product_name = product.name;
            item.image = product.image || '';
            item.unit = product.unit || 'SET';
            item.unit_price = parseFloat(product.selling_price) || 0;
            item.amount = (parseFloat(item.quantity) || 1) * item.unit_price;
            item.showDropdown = false;
            item.searchResults = [];
            this.calcTotals();
        },

        calcItemAmount(item) {
            item.amount = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
        },

        calcTotals() {
            // Reactive - computed getters handle it
        },

        closeOpenDropdowns() {
            this.items.forEach(item => { item.showDropdown = false; });
        },

        // ── Product browser (infinite scroll modal) ──
        showProductBrowser: false,

        productSearch: '',
        products: [],
        productPage: 1,
        hasMore: true,
        loading: false,

        get filteredProducts() {
            return this.products;
        },

        async loadProducts(reset = true) {
            if (this.loading) return;
            if (reset) {
                this.productPage = 1;
                this.products = [];
            }
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: this.productPage, per_page: 30 });
                if (this.productSearch) params.set('search', this.productSearch);
                const res = await fetch('<?= url('/admin/products/json') ?>?' + params.toString());
                const data = await res.json();
                if (reset) {
                    this.products = data.products;
                } else {
                    this.products = [...this.products, ...data.products];
                }
                this.hasMore = data.hasMore;
            } catch (e) {
                console.error('Failed to load products', e);
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            if (!this.hasMore || this.loading) return;
            this.productPage++;
            await this.loadProducts(false);
        },

        openProductBrowser() {
            this.productSearch = '';
            this.showProductBrowser = true;
            this.loadProducts(true);
        },

        closeProductBrowser() {
            this.showProductBrowser = false;
        },

        onProductSearchInput() {
            this.loadProducts(true);
        },

        checkProductScroll(el) {
            if (!el || this.loading || !this.hasMore) return;
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 120) {
                this.loadMore();
            }
        },

        isProductInItems(productId) {
            return this.items.some(item => String(item.product_id) === String(productId));
        },

        addProductFromBrowser(product) {
            const existing = this.items.find(item => String(item.product_id) === String(product.id));
            if (existing) {
                existing.quantity = (parseFloat(existing.quantity) || 1) + 1;
                existing.amount = (parseFloat(existing.quantity) || 1) * (parseFloat(existing.unit_price) || 0);
            } else {
                this.items.push({
                    product_id: product.id,
                    product_name: product.name,
                    image: product.image || '',
                    quantity: 1,
                    unit: product.unit || 'SET',
                    unit_price: parseFloat(product.selling_price) || 0,
                    amount: parseFloat(product.selling_price) || 0,
                    searchResults: [],
                    showDropdown: false,
                    searchDropdownStyle: {},
                });
            }
            this.calcTotals();
        },

        formatPrice(amount) {
            return new Intl.NumberFormat('lo-LA').format(Math.round(amount || 0)) + ' ກີບ';
        },

        submitForm() {
            this.$el.submit();
        }
    };
}
</script>
<script>
function saveBillSettings() {
    const section = document.getElementById('bill-settings-section');
    const formData = new FormData();
    const fields = section.querySelectorAll('input, select, textarea');
    fields.forEach(f => {
        if (f.name && f.type !== 'file') formData.append(f.name, f.value);
    });
    const fileInputs = section.querySelectorAll('input[type="file"]');
    fileInputs.forEach(f => {
        if (f.files[0]) formData.append(f.name, f.files[0]);
    });
    fetch('<?= url('/admin/settings/update') ?>', { method: 'POST', body: formData, redirect: 'manual' })
    .then(r => {
        if (r.type === 'opaqueredirect' || r.status === 302) {
            Swal.fire({ icon: 'success', title: 'ສຳເລັດ', text: 'ບັນທຶກຕັ້ງຄ່າຮູບພາບໃບບິນສຳເລັດ', timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
        }
    });
}
</script>
<style>
@keyframes spin-custom { to { transform: rotate(360deg); } }
.animate-spin-custom { animation: spin-custom 0.8s linear infinite; }
</style>
