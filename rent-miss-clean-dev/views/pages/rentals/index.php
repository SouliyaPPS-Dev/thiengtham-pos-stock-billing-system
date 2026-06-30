<?php
$statuses = ['Active', 'Returned', 'Overdue', 'Cancelled'];
$statusLabels = [
    'Active' => 'ກຳລັງເຊົ່າ',
    'Returned' => 'ຄືນແລ້ວ',
    'Overdue' => 'ເກີນກຳນົດ',
    'Cancelled' => 'ຍົກເລີກ'
];
$statusDotColors = [
    'Active' => 'bg-blue-50 text-blue-700 border-blue-100',
    'Returned' => 'bg-green-50 text-green-700 border-green-100',
    'Overdue' => 'bg-red-50 text-red-700 border-red-100',
    'Cancelled' => 'bg-gray-50 text-gray-700 border-gray-100'
];
$statusDot = [
    'Active' => 'bg-blue-500',
    'Returned' => 'bg-green-500',
    'Overdue' => 'bg-red-500',
    'Cancelled' => 'bg-gray-400'
];
$statusIcons = [
    'Active' => 'fa-play',
    'Returned' => 'fa-check-circle',
    'Overdue' => 'fa-exclamation-triangle',
    'Cancelled' => 'fa-ban'
];
$rentalIds = array_map('strval', array_column($rentals, 'id'));
 ?> 

<script>
function rentalFilter() {
    return {
        search: '<?= htmlspecialchars($search) ?>',
        status: '<?= $status ?>',
        timer: null,
        debounceFilter() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.applyFilter(), 500);
        },
        applyFilter() {
            const params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.status) params.set('status', this.status);
            window.location.href = '<?= url('/rentals') ?>' + (params.toString() ? '?' + params.toString() : '');
        },
        resetFilter() {
            window.location.href = '<?= url('/rentals') ?>';
        }
    };
}
</script>

<div x-data="rentalViewModal()" class="p-4 md:p-8">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"> 
            <div>
                <h1 class="text-2xl font-black text-gray-800">ປະຫວັດບິນເຊົ່າເຄື່ອງ</h1>
                <p class="text-sm text-gray-400">ຈັດການບິນເຊົ່າທັງໝົດໃນລະບົບ</p>
            </div>
            <a href="<?= url('/pos') ?>" class="inline-flex items-center justify-center rounded-xl h-11 px-6 text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20">
                <i class="fas fa-plus-circle mr-2"></i>
                ສ້າງບິນໃໝ່
            </a>
        </div>

        <!-- Search & Filter -->
        <div x-data="rentalFilter()" class="bg-white p-4 rounded-2xl border shadow-sm flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" x-model="search" @input="debounceFilter" @keydown.enter="applyFilter" placeholder="ຄົ້ນຫາຕາມຊື່ລູກຄ້າ, ເລກບິນ ຫຼື ເບີໂທ..."
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
            </div>
            <div class="flex gap-2">
                <select x-model="status" @change="applyFilter" class="px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                    <option value="">ສະຖານະທັງໝົດ</option>
                    <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>"><?= $statusLabels[$s] ?></option>
                    <?php endforeach; ?>
                </select>
                <button @click="resetFilter" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl text-sm font-black hover:bg-gray-200 transition-all border">
                    <i class="fas fa-undo mr-2"></i> ລ້າງຟິວເຕີ
                </button>
            </div>
        </div>

<div x-data="rentalBulkDelete()">
    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left"> 
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs font-bold"> 
                    <tr>
                        <th class="px-2 py-4 w-10 text-center">
                            <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                        </th>
                        <th class="px-4 py-4">ເລກບິນ</th>
                        <th class="px-4 py-4">ລູກຄ້າ</th>
                        <th class="px-4 py-4">ວັນທີເຊົ່າ</th>
                        <th class="px-4 py-4">ກຳນົດສົ່ງຄືນ</th>
                        <th class="px-4 py-4 text-right">ຍອດລວມ</th>
                        <th class="px-4 py-4">ຊຳລະ</th>
                        <th class="px-4 py-4 text-center">ສະຖານະ</th>
                        <th class="px-4 py-4">ພະນັກງານ</th>
                        <th class="px-4 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    <?php if (empty($rentals)): ?>
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-receipt text-4xl text-gray-200"></i>
                                <p class="font-bold">ຍັງບໍ່ມີບິນເຊົ່າ</p>
                            </div>
                        </td>
                    </tr> 
                    <?php else: ?>
                    <?php foreach ($rentals as $r): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" value="<?= $r['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                        </td>
                        <td class="px-4 py-4">
                            <button @click="openViewModal(<?= $r['id'] ?>)" class="font-bold text-primary hover:underline cursor-pointer"><?= htmlspecialchars($r['invoice_number'] ?? '') ?></button>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-medium text-gray-800"><?= htmlspecialchars($r['customer_name'] ?? '') ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($r['customer_phone'] ?? '') ?></div>
                        </td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($r['pickup_date'])) ?></td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($r['return_date'])) ?></td>
                        <td class="px-4 py-4 text-right font-bold text-gray-800 whitespace-nowrap"><?= number_format($r['grand_total']) ?></td>
                        <td class="px-4 py-4">
                            <div class="text-xs font-bold <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'text-green-600' : 'text-yellow-600' ?>">
                                <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້' ?>
                                <span class="text-gray-400 font-normal block"><?= htmlspecialchars($r['payment_method_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php $sc = $statusDotColors[$r['status']] ?? 'bg-gray-50 text-gray-700 border-gray-100'; ?>
                            <?php $si = $statusIcons[$r['status']] ?? 'fa-circle'; ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $sc ?>">
                                <i class="fas <?= $si ?> text-[10px]"></i>
                                <?= $statusLabels[$r['status']] ?? $r['status'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 font-medium text-gray-600"><?= htmlspecialchars($r['created_by_name'] ?? 'Admin') ?></td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openViewModal(<?= $r['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white rounded-xl transition-all shadow-sm border border-sky-100 text-xs font-bold" title="ລາຍລະອຽດ">
                                    <i class="fas fa-info-circle"></i> ລາຍລະອຽດ
                                </button>
                                <a href="<?= url('/print-invoice/' . $r['id']) ?>" target="_blank" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all shadow-sm border border-emerald-100 text-xs font-bold" title="ພິມ">
                                    <i class="fas fa-print"></i> ພິມ
                                </a>
                                <button onclick="changeStatus(<?= $r['id'] ?>, '<?= $r['status'] ?>')" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100 text-xs font-bold" title="ປ່ຽນສະຖານະ">
                                    <i class="fas fa-sync-alt"></i> ສະຖານະ
                                </button>
                                <button onclick="shareRental(<?= $r['id'] ?>, '<?= htmlspecialchars($r['customer_name'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($r['invoice_number'] ?? '', ENT_QUOTES) ?>')" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-sm border border-blue-100 text-xs font-bold" title="ແຊຣ໌">
                                    <i class="fas fa-share-alt"></i> ແຊຣ໌
                                </button>
                                <button onclick="deleteRental(<?= $r['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100 text-xs font-bold" title="ລົບ">
                                    <i class="fas fa-trash-alt"></i> ລົບ
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Bulk Action Bar INSIDE table container (Desktop) -->
        <div x-show="selected.length > 0" class="border-t border-red-200 bg-red-50 px-5 py-3 flex items-center justify-between transition-all">
            <span class="text-sm font-bold text-red-700">
                <i class="fas fa-check-circle mr-1.5"></i>
                ເລືອກ <span x-text="selected.length" class="text-red-600 text-base"></span> ລາຍການ
            </span>
            <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black transition-all shadow-sm" style="background:#dc2626;color:#fff">
                <i class="fas fa-trash-alt"></i>
                ລຶບທັງໝົດ
            </button>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
        <?php if (empty($rentals)): ?>
        <div class="bg-white rounded-2xl border p-8 text-center text-gray-400">
            <div class="flex flex-col items-center gap-2">
                <i class="fas fa-receipt text-4xl text-gray-200"></i>
                <p class="font-bold">ຍັງບໍ່ມີບິນເຊົ່າ</p>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($rentals as $r): ?>
        <div class="bg-white rounded-2xl border p-4 space-y-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" value="<?= $r['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                    <div>
                        <div class="font-bold text-gray-800"><?= htmlspecialchars($r['customer_name'] ?? '') ?></div>
                        <button @click="openViewModal(<?= $r['id'] ?>)" class="text-xs text-primary hover:underline cursor-pointer"><?= htmlspecialchars($r['invoice_number'] ?? '') ?></button>
                    </div>
                </div> 
                <?php $sc = $statusDotColors[$r['status']] ?? 'bg-gray-50 text-gray-700 border-gray-100'; ?>
                <?php $si = $statusIcons[$r['status']] ?? 'fa-circle'; ?>
                <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $sc ?>">
                    <i class="fas <?= $si ?> text-[8px]"></i>
                    <?= $statusLabels[$r['status']] ?? $r['status'] ?>
                </span>
            </div>
            <div class="flex flex-col border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-bold">ວັນທີເຊົ່າ</span>
                        <div class="font-bold text-sm text-gray-700"><?= date('d/m/Y', strtotime($r['pickup_date'])) ?></div>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-bold">ກຳນົດສົ່ງຄືນ</span>
                        <div class="font-bold text-sm text-gray-700"><?= date('d/m/Y', strtotime($r['return_date'])) ?></div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 uppercase font-bold">ຍອດລວມ</span>
                        <div class="font-black text-sm text-gray-800"><?= number_format($r['grand_total']) ?></div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs mb-2">
                    <div>
                        <span class="text-[10px] text-gray-400">ຊຳລະ</span>
                        <p class="font-bold <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'text-green-600' : 'text-yellow-600' ?>">
                            <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້' ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400">ພະນັກງານ</span>
                        <p class="font-bold text-gray-700"><?= htmlspecialchars($r['created_by_name'] ?? '') ?></p>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t">
                    <button @click="openViewModal(<?= $r['id'] ?>)" class="flex-1 py-2 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center gap-1 text-xs font-bold hover:bg-sky-100 transition-colors" title="ລາຍລະອຽດ">
                        <i class="fas fa-info-circle"></i> ລາຍລະອຽດ
                    </button>
                    <a href="<?= url('/print-invoice/' . $r['id']) ?>" target="_blank" class="flex-1 py-2 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center gap-1 text-xs font-bold hover:bg-emerald-100 transition-colors" title="ພິມ">
                        <i class="fas fa-print"></i> ພິມ
                    </a>
                    <button onclick="changeStatus(<?= $r['id'] ?>, '<?= $r['status'] ?>')" class="flex-1 py-2 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center gap-1 text-xs font-bold hover:bg-amber-100 transition-colors" title="ປ່ຽນສະຖານະ">
                        <i class="fas fa-sync-alt"></i> ສະຖານະ
                    </button>
                    <button onclick="shareRental(<?= $r['id'] ?>, '<?= htmlspecialchars($r['customer_name'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($r['invoice_number'] ?? '', ENT_QUOTES) ?>')" class="flex-1 py-2 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center gap-1 text-xs font-bold hover:bg-blue-100 transition-colors" title="ແຊຣ໌">
                        <i class="fas fa-share-alt"></i> ແຊຣ໌
                    </button>
                    <button onclick="deleteRental(<?= $r['id'] ?>)" class="flex-1 py-2 rounded-lg bg-red-50 text-red-500 flex items-center justify-center gap-1 text-xs font-bold hover:bg-red-100 transition-colors" title="ລົບ">
                        <i class="fas fa-trash-alt"></i> ລົບ
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Bulk Action Bar (Mobile - fixed bottom) -->
    <div x-show="selected.length > 0" class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-2xl px-4 py-3 z-50 md:hidden">
        <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-gray-600">
                ເລືອກ <span x-text="selected.length" class="text-primary font-black"></span> ລາຍການ
            </span>
            <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black transition-all shadow-lg" style="background:#dc2626;color:#fff">
                <i class="fas fa-trash-alt"></i>
                ລຶບທັງໝົດ
            </button>
        </div>
    </div>
</div>

    <!-- View Modal -->
    <div x-show="showViewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
        <div class="fixed inset-0 bg-black/40" @click="showViewModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto z-10">
            <div class="sticky top-0 bg-white border-b flex items-center justify-between px-5 py-4 rounded-t-2xl">
                <h3 class="text-lg font-black text-gray-800">
                    <i class="fas fa-receipt text-primary mr-2"></i>
                    <span x-text="viewData.invoice_number || ''"></span>
                </h3>
                <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-xl transition-all text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5 space-y-5">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ລູກຄ້າ</p>
                    <p class="font-bold text-gray-800 text-base" x-text="viewData.customer_name"></p>
                    <p class="text-sm text-gray-500" x-text="viewData.customer_phone"></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ວັນທີເຊົ່າ</p>
                        <p class="font-bold text-gray-700 text-base" x-text="viewData.pickup_date_formatted"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ກຳນົດສົ່ງຄືນ</p>
                        <p class="font-bold text-gray-700 text-base" x-text="viewData.return_date_formatted"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ສະຖານະ</p>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold" x-bind:class="viewData.status_text_class">
                            <i class="fas text-[10px]" x-bind:class="viewData.status_icon_class"></i>
                            <span x-text="viewData.status_label"></span>
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ການຊຳລະ</p>
                        <p class="font-bold text-base" x-bind:class="viewData.payment_status_class" x-text="viewData.payment_status_label"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ຊ່ອງທາງຊຳລະ</p>
                        <p class="font-bold text-gray-700 text-base" x-text="viewData.payment_method_name || 'ເງິນສົດ'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1.5">ພະນັກງານ</p>
                        <p class="font-bold text-gray-700 text-base" x-text="viewData.created_by_name || '-'"></p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-3">ລາຍການທີ່ເຊົ່າ</p>
                    <div class="space-y-2">
                        <template x-for="(item, i) in viewItems" :key="i">
                            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm" x-text="item.product_name"></p>
                                        <p class="text-xs text-gray-400">
                                            <span x-text="item.product_code"></span>
                                            <span x-show="item.size"> | <span x-text="item.size"></span></span>
                                            <span> | x<span x-text="item.qty"></span></span>
                                        </p>
                                    </div>
                                    <button @click="openProductHistory(item.product_id)" class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-sky-50 text-sky-500 hover:bg-sky-500 hover:text-white rounded-lg transition-all text-[10px]" title="ເບິ່ງປະຫວັດການເຊົ່າຂອງສິນຄ້ານີ້">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                                <p class="font-black text-gray-800 text-base" x-text="number_format(item.product_base_price || 0) + ' ກີບ'"></p>
                            </div>
                        </template>
                    </div>
                </div>
                <div x-show="viewData.guarantee_id_card || viewData.guarantee_passport || viewData.guarantee_family_book || viewData.guarantee_cash" class="border-t pt-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-2.5">ເອກະສານຄ້ຳປະກັນ</p>
                    <div class="flex flex-wrap gap-2">
                        <span x-show="viewData.guarantee_id_card" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">✓ ບັດປະຈຳຕົວ</span>
                        <span x-show="!viewData.guarantee_id_card" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 border border-gray-200">○ ບັດປະຈຳຕົວ</span>
                        <span x-show="viewData.guarantee_passport" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">✓ ພາດສະປອດ</span>
                        <span x-show="!viewData.guarantee_passport" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 border border-gray-200">○ ພາດສະປອດ</span>
                        <span x-show="viewData.guarantee_family_book" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">✓ ສຳມະໂນຄົວ</span>
                        <span x-show="!viewData.guarantee_family_book" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 border border-gray-200">○ ສຳມະໂນຄົວ</span>
                        <span x-show="viewData.guarantee_cash" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">✓ ມັດຈຳເງິນ</span>
                        <span x-show="!viewData.guarantee_cash" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 border border-gray-200">○ ມັດຈຳເງິນ</span>
                    </div>
                </div>
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm" x-show="viewData.total_rental_fee">
                        <span class="text-gray-500">ລວມລາຄາ</span>
                        <span class="font-bold" x-text="number_format(viewData.total_rental_fee) + ' ກີບ'"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="viewData.discount && viewData.discount > 0">
                        <span class="text-gray-500">ສ່ວນຫຼຸດ</span>
                        <span class="font-bold text-red-500" x-text="'-' + number_format(viewData.discount) + ' ກີບ'"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="viewData.total_deposit && viewData.total_deposit > 0">
                        <span class="text-gray-500">ຄ່າມັດຈຳ</span>
                        <span class="font-bold" x-text="number_format(viewData.total_deposit) + ' ກີບ'"></span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="font-black text-gray-800">ຍອດທີ່ຕ້ອງຊຳລະ</span>
                        <span class="font-black text-green-600" x-text="number_format(viewData.grand_total) + ' ກີບ'"></span>
                    </div>
                </div>
                <div x-show="viewData.notes" class="border-t pt-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">ໝາຍເຫດ</p>
                    <p class="text-sm text-gray-700" x-text="viewData.notes"></p>
                </div>
                <div x-show="viewData.returned_at" class="border-t pt-4 text-center text-xs text-gray-400">
                    <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                    ຄືນແລ້ວເມື່ອ: <span x-text="viewData.returned_at_formatted"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function rentalViewModal() {
    const statusDotColors = <?= json_encode($statusDotColors) ?>;
    const statusDot = <?= json_encode($statusDot) ?>;
    const statusIcons = <?= json_encode($statusIcons) ?>;
    return {
        showViewModal: false,
        viewData: {},
        viewItems: [],
        statusDotColors: statusDotColors,
        statusDot: statusDot,
        openProductHistory(productId) {
            if (!productId) return;
            const self = this;
            fetch('<?= url('/inventory') ?>/' + productId + '/history')
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        Swal.fire('ບໍ່ມີປະຫວັດ', 'ສິນຄ້ານີ້ຍັງບໍ່ເຄີຍຖືກເຊົ່າ', 'info');
                        return;
                    }
                    const statusStyles = {
                        Active: { bg: '#eff6ff', text: '#2563eb', border: '#bfdbfe', icon: 'fa-play', label: 'ກຳລັງເຊົ່າ' },
                        Returned: { bg: '#f0fdf4', text: '#16a34a', border: '#bbf7d0', icon: 'fa-check-circle', label: 'ຄືນແລ້ວ' },
                        Overdue: { bg: '#fef2f2', text: '#dc2626', border: '#fecaca', icon: 'fa-exclamation-triangle', label: 'ເກີນກຳນົດ' },
                        Cancelled: { bg: '#f9fafb', text: '#6b7280', border: '#e5e7eb', icon: 'fa-ban', label: 'ຍົກເລີກ' }
                    };
                    const allData = data;
                    Swal.fire({
                        html: `
                            <div style="text-align:left;">
                                <div style="background:#fff;border-bottom:1px solid #e5e7eb;margin:-16px -16px 0 -16px;padding:16px 20px;font-weight:700;font-size:15px;color:#111827;display:flex;align-items:center;justify-content:space-between;">
                                    <span><i class="fas fa-history" style="margin-right:8px;color:#0284c7;"></i>ປະຫວັດການເຊົ່າ</span>
                                    <span style="background:#f3f4f6;color:#374151;padding:1px 12px;border-radius:999px;font-size:12px;">${data.length} ຄັ້ງ</span>
                                </div>
                                <div style="padding:12px 16px;">
                                    <div style="position:relative;margin-bottom:8px;">
                                        <i class="fas fa-search" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#38bdf8;font-size:13px;"></i>
                                        <input type="text" id="swal-history-search" placeholder="ຄົ້ນຫາຕາມເລກບິນ, ຊື່ລູກຄ້າ, ເບີໂທ..." style="width:100%;padding:11px 13px 11px 38px;border:1px solid #bae6fd;border-radius:12px;font-size:13px;font-family:inherit;outline:none;background:#f0f9ff;box-sizing:border-box;transition:all 0.2s;" onfocus="this.style.borderColor='#38bdf8';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(56,189,248,0.15)'" onblur="this.style.borderColor='#bae6fd';this.style.background='#f0f9ff';this.style.boxShadow='none'">
                                        <button id="swal-history-clear" onclick="var inp=document.getElementById('swal-history-search');inp.value='';window.filterSwalHistory('');inp.focus()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:22px;height:22px;border-radius:50%;border:none;background:#e5e7eb;color:#6b7280;cursor:pointer;font-size:10px;padding:0;display:none;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                        <div style="position:relative;flex:1;">
                                            <i class="fas fa-calendar-alt" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:11px;"></i>
                                            <input type="date" id="swal-date-from" onchange="window.filterSwalHistory()" style="width:100%;padding:8px 8px 8px 32px;border:1px solid #e5e7eb;border-radius:10px;font-size:12px;font-family:inherit;outline:none;background:#f9fafb;box-sizing:border-box;color-scheme:light;">
                                        </div>
                                        <span style="color:#d1d5db;font-size:12px;">–</span>
                                        <div style="position:relative;flex:1;">
                                            <i class="fas fa-calendar-alt" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:11px;"></i>
                                            <input type="date" id="swal-date-to" onchange="window.filterSwalHistory()" style="width:100%;padding:8px 8px 8px 32px;border:1px solid #e5e7eb;border-radius:10px;font-size:12px;font-family:inherit;outline:none;background:#f9fafb;box-sizing:border-box;color-scheme:light;">
                                        </div>
                                        <button id="swal-date-clear" onclick="document.getElementById('swal-date-from').value='';document.getElementById('swal-date-to').value='';window.filterSwalHistory()" style="display:none;width:34px;height:34px;border-radius:10px;border:none;background:#f3f4f6;color:#9ca3af;cursor:pointer;font-size:12px;padding:0;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div id="swal-history-count" style="font-size:11px;color:#9ca3af;margin-bottom:8px;"><i class="fas fa-list-ul" style="color:#d1d5db;margin-right:4px;"></i>ພົບ ${data.length} ລາຍການ</div>
                                    <div id="swal-history-list" style="max-height:320px;overflow-y:auto;padding:2px 0;">
                                        ${data.map((h, idx) => {
                                            const s = statusStyles[h.status] || statusStyles.Cancelled;
                                            const pickup = (h.pickup_date || '').substring(0, 10);
                                            const searchData = (h.invoice_number||'').toLowerCase() + ' ' + (h.customer_name||'').toLowerCase() + ' ' + (h.customer_phone||'');
                                            return '<div class="swal-h-item" data-search="' + searchData + '" data-pickup="' + pickup + '" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;margin-bottom:8px;border:1px solid #e5e7eb;border-radius:12px;">' +
                                                '<div style="min-width:0;">' +
                                                '<div style="font-weight:700;font-size:13px;color:#0ea5e9;cursor:pointer;" class="history-invoice-link" data-rental-id="' + (h.id||'') + '">' + (h.invoice_number||'') + '</div>' +
                                                '<div style="font-size:12px;color:#374151;font-weight:600;margin-top:2px;">' + (h.customer_name||'') + '</div>' +
                                                '<div style="display:flex;gap:12px;margin-top:4px;font-size:11px;color:#9ca3af;">' +
                                                '<span>#' + (data.length - idx) + '</span>' +
                                                '<span>x' + h.qty + '</span>' +
                                                '<span>' + new Date(h.pickup_date).toLocaleDateString('en-GB') + ' → ' + new Date(h.return_date).toLocaleDateString('en-GB') + '</span>' +
                                                '</div></div>' +
                                                '<span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;border:1px solid ' + s.border + ';background:' + s.bg + ';color:' + s.text + ';white-space:nowrap;">' +
                                                '<i class="fas ' + s.icon + '" style="font-size:10px;"></i>' +
                                                s.label +
                                                '</span></div>';
                                        }).join('')}
                                    </div>
                                </div>
                            </div>
                        `,
                        width: 512,
                        showConfirmButton: false,
                        showCloseButton: true,
                        closeButtonHtml: '<i class="fas fa-times" style="font-size:16px;color:#94a3b8;"></i>',
                        customClass: { closeButton: 'swal-close-modern' },
                        didOpen: () => {
                            const clearBtn = document.getElementById('swal-history-clear');
                            const dateClearBtn = document.getElementById('swal-date-clear');
                            const inp = document.getElementById('swal-history-search');
                            const dateFrom = document.getElementById('swal-date-from');
                            const dateTo = document.getElementById('swal-date-to');
                            window.filterSwalHistory = function() {
                                const items = document.querySelectorAll('.swal-h-item');
                                const countEl = document.getElementById('swal-history-count');
                                const q = (document.getElementById('swal-history-search')?.value || '').toLowerCase().trim();
                                const df = dateFrom?.value || '';
                                const dt = dateTo?.value || '';
                                let count = 0;
                                items.forEach(el => {
                                    let match = true;
                                    if (q) match = match && (el.dataset.search || '').includes(q);
                                    if (df) match = match && (el.dataset.pickup || '') >= df;
                                    if (dt) match = match && (el.dataset.pickup || '') <= dt;
                                    el.style.display = match ? 'flex' : 'none';
                                    if (match) count++;
                                });
                                countEl.innerHTML = '<i class="fas fa-list-ul" style="color:#d1d5db;margin-right:4px;"></i>ພົບ ' + count + ' ລາຍການ';
                                if (clearBtn) clearBtn.style.display = q ? 'flex' : 'none';
                                if (dateClearBtn) dateClearBtn.style.display = (df || dt) ? 'flex' : 'none';
                            };
                            if (inp) inp.addEventListener('input', window.filterSwalHistory);
                            document.querySelectorAll('.history-invoice-link').forEach(el => {
                                el.addEventListener('click', function() {
                                    const rentalId = this.dataset.rentalId;
                                    if (rentalId) {
                                        Swal.close();
                                        window.open('<?= url('/print-invoice') ?>/' + rentalId, '_blank');
                                    }
                                });
                            });
                        }
                    });
                });
        },
        openViewModal(id) {
            const statusLabels = <?= json_encode($statusLabels) ?>;
            const statusDotColors = <?= json_encode($statusDotColors) ?>;
            fetch('<?= url('/rentals') ?>/' + id + '/items')
                .then(res => res.json())
                .then(items => {
                    fetch('<?= url('/rentals') ?>/' + id + '/data')
                        .then(res => res.json())
                        .then(data => {
                            const sc = statusDotColors[data.status] || 'bg-gray-50 text-gray-700 border-gray-100';
                            const sd = statusDot[data.status] || 'bg-gray-400';
                            const si = statusIcons[data.status] || 'fa-circle';
                            this.viewData = {
                                ...data,
                                pickup_date_formatted: data.pickup_date ? new Date(data.pickup_date).toLocaleDateString('en-GB') : '',
                                return_date_formatted: data.return_date ? new Date(data.return_date).toLocaleDateString('en-GB') : '',
                                returned_at_formatted: data.returned_at ? new Date(data.returned_at).toLocaleDateString('en-GB') + ' ' + new Date(data.returned_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '',
                                status_label: statusLabels[data.status] || data.status,
                                status_text_class: sc,
                                status_dot_class: sd,
                                status_icon_class: si,
                                payment_status_label: data.payment_status === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້',
                                payment_status_class: data.payment_status === 'Paid' ? 'text-green-600' : 'text-yellow-600'
                            };
                            this.viewItems = items.map(item => ({
                                ...item,
                                total: parseFloat(item.total) || 0
                            }));
                            this.showViewModal = true;
                        });
                });
        }
    };
}

function number_format(x) {
    return Number(x).toLocaleString('en-US');
}

function changeStatus(id, currentStatus) {
    Swal.fire({
        title: 'ປ່ຽນສະຖານະບິນ',
        input: 'select',
        inputOptions: {
            'Active': 'ກຳລັງເຊົ່າ',
            'Returned': 'ຄືນແລ້ວ (ປິດບິນ)',
            'Overdue': 'ເກີນກຳນົດ',
            'Cancelled': 'ຍົກເລີກ'
        },
        inputValue: currentStatus,
        showCancelButton: true,
        confirmButtonText: 'ປ່ຽນສະຖານະ',
        cancelButtonText: 'ຍົກເລີກ',
        confirmButtonColor: '#0ea5e9',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= url("/rentals/update-status") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${result.value}`
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ສຳເລັດ',
                        text: 'ປ່ຽນສະຖານະຮຽບຮ້ອຍແລ້ວ',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ເກີດຂໍ້ຜິດພາດ', data.message, 'error');
                }
            });
        }
    });
}
function shareRental(id, customerName, invoiceNumber) {
    const baseUrl = '<?= url('/print-invoice') ?>';
    const url = baseUrl + '/' + id;
    const message = `ສະບາຍດີ ${customerName}\nນີ້ຄືໃບບິນເຊົ່າ ${invoiceNumber} ຂອງທ່ານ:\n${url}`;
    const waUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(waUrl, '_blank');
}

function deleteRental(id) {
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: "ທ່ານຕ້ອງການລົບບິນນີ້ແທ້ບໍ່?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ລົບ',
        cancelButtonText: 'ຍົກເລີກ',
        reverseButtons: true,
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/rentals/delete') ?>';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function rentalBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($rentalIds) ?>,
        get allSelected() {
            return this.allIds.length > 0 && this.selected.length === this.allIds.length;
        },
        toggleAll() {
            if (this.allSelected) {
                this.selected = [];
            } else {
                this.selected = [...this.allIds];
            }
        },
        confirmBulkDelete() {
            if (this.selected.length === 0) return;
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລົບ ' + this.selected.length + ' ບິນນີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ລົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url('/rentals/bulk-delete') ?>';
                    this.selected.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    };
}
</script>
<style>
.swal-close-modern:focus { outline: none; box-shadow: none; }
.history-invoice-link:hover { text-decoration: underline; }
</style>
  