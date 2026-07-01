<?php $isEdit = !is_null($quotation); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-6xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin/quotations') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight"><?= $isEdit ? 'ແກ້ໄຂໃບສະເໜີລາຄາ' : 'ສ້າງໃບສະເໜີລາຄາໃໝ່' ?></h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $isEdit ? 'ແກ້ໄຂຂໍ້ມູນໃບສະເໜີລາຄາ' : 'ສ້າງໃບສະເໜີລາຄາຕາມແບບຟອມ Excel' ?></p>
            </div>
        </div>

        <form method="POST" action="<?= $isEdit ? url('/admin/quotations/' . $quotation['id'] . '/update') : url('/admin/quotations/store') ?>" x-data="quotationForm()" @submit.prevent="submitForm()">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Settings -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-gray-800 mb-4">ແມ່ແບບ</h2>
                        <div class="space-y-2">
                            <?php foreach ($templates as $key => $tpl): ?>
                            <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all" :class="template === '<?= $key ?>' ? 'border-primary bg-primary/5' : 'border-gray-100 hover:border-gray-200'">
                                <input type="radio" name="company_template" value="<?= $key ?>" x-model="template" class="text-primary focus:ring-primary">
                                <div>
                                    <div class="text-xs font-bold text-gray-800"><?= htmlspecialchars($tpl['label']) ?></div>
                                    <div class="text-[10px] text-gray-400 mt-0.5"><?= htmlspecialchars($tpl['company']) ?></div>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-gray-800 mb-4">ຂໍ້ມູນຜູ້ສະໜອງ</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ເລືອກຜູ້ສະໜອງ</label>
                                <select x-model="supplierId" @change="selectSupplier()" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">-- ເລືອກ --</option>
                                    <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="supplier_id" x-model="supplierId">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ຊື່ຜູ້ສະໜອງ</label>
                                <input type="text" name="supplier_name" x-model="supplierName" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ຜູ້ຕິດຕໍ່ / ເບີໂທ</label>
                                <input type="text" name="supplier_contact" x-model="supplierContact" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="text-base font-extrabold text-gray-800 mb-4">ຂໍ້ມູນເພີ່ມເຕີມ</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ເລກອ້າງອີງ (Ref No.)</label>
                                <input type="text" name="ref_no" x-model="refNo" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="z.B. PR-2026-...">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ວັນທີ</label>
                                <input type="date" name="date" x-model="date" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ເງື່ອນໄຂ</label>
                                <textarea name="terms" x-model="terms" rows="4" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ສະຖານະ</label>
                                <select name="status" x-model="status" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="Draft">ຮ່າງ</option>
                                    <option value="Sent">ສົ່ງແລ້ວ</option>
                                    <option value="Approved">ອະນຸມັດ</option>
                                    <option value="Rejected">ປະຕິເສດ</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1 block">ໝາຍເຫດ</label>
                                <textarea name="notes" x-model="notes" rows="2" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Items -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-extrabold text-gray-800">ລາຍການສິນຄ້າ</h2>
                            <button @click="addItem()" type="button" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary/10 text-primary rounded-xl text-xs font-bold hover:bg-primary/20 transition-all">
                                <i class="fas fa-plus"></i> ເພີ່ມລາຍການ
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-center" style="width:36px">#</th>
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍການ</th>
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-center" style="width:70px">ຈຳນວນ</th>
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-center" style="width:60px">ຫົວໜ່ວຍ</th>
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-right" style="width:120px">ລາຄາ/ໜ່ວຍ</th>
                                        <th class="py-2 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-right" style="width:120px">ຈຳນວນເງິນ</th>
                                        <th class="py-2 px-2 text-center" style="width:32px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="border-b border-gray-50">
                                            <td class="py-2 px-2 text-gray-400 text-xs text-center" x-text="index + 1"></td>
                                            <td class="py-2 px-2">
                                                <div class="relative" @click.away="item.showDropdown = false">
                                                    <input type="text" x-model="item.product_name" @focus="searchProduct(item, index)" @input.debounce="searchProduct(item, index)" placeholder="ຊື່ສິນຄ້າ..." class="w-full px-2 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                                    <template x-if="item.showDropdown && item.searchResults?.length > 0">
                                                        <div class="absolute left-0 right-0 mt-1 bg-white border rounded-xl shadow-xl z-50 max-h-40 overflow-y-auto">
                                                            <template x-for="p in item.searchResults" :key="p.id">
                                                                <div @click="selectProduct(item, p)" class="px-3 py-2 hover:bg-gray-50 cursor-pointer border-b last:border-0 flex items-center gap-2">
                                                                    <div x-show="p.image" class="w-6 h-6 rounded bg-gray-100 overflow-hidden flex-shrink-0">
                                                                        <img :src="p.image" class="w-full h-full object-cover">
                                                                    </div>
                                                                    <div>
                                                                        <div class="text-xs font-bold text-gray-800" x-text="p.name"></div>
                                                                        <div class="text-[9px] text-gray-400" x-text="p.sku || ''"></div>
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
                                                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" @input="calcItemAmount(item)" min="0" step="1" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-center font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2">
                                                <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-center focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2">
                                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" @input="calcItemAmount(item)" min="0" step="1" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-right font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                            </td>
                                            <td class="py-2 px-2 text-right font-bold text-gray-800 text-xs" x-text="formatPrice(item.amount)"></td>
                                            <td class="py-2 px-2 text-center">
                                                <button @click="removeItem(index)" type="button" class="text-gray-300 hover:text-red-500 transition-colors"><i class="fas fa-times text-xs"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-gray-400">
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
                        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col items-end">
                            <div class="w-full max-w-xs space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">ລວມຍ່ອຍ</span>
                                    <span class="font-bold text-gray-800" x-text="formatPrice(subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">ສ່ວນຫຼຸດ</span>
                                    <input type="number" name="discount" x-model="discount" @input="calcTotals()" min="0" class="w-20 px-2 py-0.5 bg-gray-50 border border-gray-100 rounded text-xs text-right font-bold focus:ring-1 focus:ring-primary outline-none">
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">ອາກອນມູນຄ່າເພີ້ມ</span>
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="tax_percent" x-model="taxPercent" @input="calcTotals()" min="0" max="100" step="0.01" class="w-14 px-2 py-0.5 bg-gray-50 border border-gray-100 rounded text-xs text-right font-bold focus:ring-1 focus:ring-primary outlineline-none">
                                        <span class="text-gray-400">%</span>
                                    </div>
                                </div>
                                <div class="flex justify-between text-base font-black text-primary border-t border-gray-100 pt-1.5">
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
                        <a href="<?= url('/admin/quotations') ?>" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">ຍົກເລີກ</a>
                        <button type="submit" name="action" value="save" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200">ບັນທຶກ</button>
                        <button type="submit" name="action" value="save_print" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold text-sm hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-200">
                            <i class="fas fa-print"></i> ບັນທຶກ ແລະ ພິມ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function quotationForm() {
    const allProducts = <?= json_encode($products ?? []) ?>;
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
            'searchResults' => [],
            'showDropdown' => false,
        ];
    }, $quotation['items'])) ?>;
    <?php endif; ?>

    return {
        template: '<?= $isEdit ? $quotation['company_template'] : 'luang-prabarg' ?>',
        supplierId: '<?= $isEdit ? ($quotation['supplier_id'] ?? '') : '' ?>',
        supplierName: '<?= $isEdit ? htmlspecialchars($quotation['supplier_name'] ?? '', ENT_QUOTES) : '' ?>',
        supplierContact: '<?= $isEdit ? htmlspecialchars($quotation['supplier_contact'] ?? '', ENT_QUOTES) : '' ?>',
        refNo: '<?= $isEdit ? htmlspecialchars($quotation['ref_no'] ?? '', ENT_QUOTES) : '' ?>',
        date: '<?= $isEdit ? ($quotation['date'] ?? date('Y-m-d')) : date('Y-m-d') ?>',
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
            const supplier = <?= json_encode($suppliers ?? []) ?>.find(s => s.id == this.supplierId);
            if (supplier) {
                this.supplierName = supplier.name;
                this.supplierContact = (supplier.contact_person || '') + (supplier.phone ? ' | ' + supplier.phone : '');
            }
        },

        addItem() {
            this.items.push({
                product_id: '',
                product_name: '',
                quantity: 1,
                unit: 'SET',
                unit_price: 0,
                amount: 0,
                searchResults: [],
                showDropdown: false,
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        searchProduct(item) {
            const q = (item.product_name || '').toLowerCase().trim();
            if (!q) {
                item.searchResults = [];
                item.showDropdown = false;
                return;
            }
            item.searchResults = allProducts.filter(p =>
                p.name.toLowerCase().includes(q) ||
                (p.sku || '').toLowerCase().includes(q)
            ).slice(0, 10);
            item.showDropdown = item.searchResults.length > 0;
        },

        selectProduct(item, product) {
            item.product_id = product.id;
            item.product_name = product.name;
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

        formatPrice(amount) {
            return new Intl.NumberFormat('lo-LA').format(Math.round(amount || 0)) + ' ກີບ';
        },

        submitForm() {
            this.$el.submit();
        }
    };
}
</script>
