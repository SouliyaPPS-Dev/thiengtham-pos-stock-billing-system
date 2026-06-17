<?php
$statuses = ['Active', 'Returned', 'Overdue', 'Cancelled'];
$statusLabels = [
    'Active' => 'ກຳລັງເຊົ່າ',
    'Returned' => 'ຄືນແລ້ວ',
    'Overdue' => 'ເກີນກຳນົດ',
    'Cancelled' => 'ຍົກເລີກ'
];
$statusDotColors = [
    'Active' => 'text-sky-600',
    'Returned' => 'text-emerald-600',
    'Overdue' => 'text-red-500',
    'Cancelled' => 'text-gray-400'
];
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

<div x-data="rentalViewModal()" class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4"> 
        <div>
            <h1 class="text-2xl font-black text-gray-800">ປະຫວັດບິນເຊົ່າເຄື່ອງ</h1>
            <p class="text-sm text-gray-400">ຈັດການບິນເຊົ່າທັງໝົດໃນລະບົບ</p>
        </div>
        <a href="<?= url('/pos') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl text-sm font-black hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            <i class="fas fa-plus-circle"></i>
            ສ້າງບິນໃໝ່
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-2xl border shadow-sm" x-data="rentalFilter()">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-grow">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" x-model="search" @input="debounceFilter" @keydown.enter="applyFilter" placeholder="ຄົ້ນຫາຕາມຊື່ລູກຄ້າ, ເລກບິນ ຫຼື ເບີໂທ..." 
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
            </div>
            <select x-model="status" @change="applyFilter" class="px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                    <option value="">ສະຖານະທັງໝົດ</option>
                    <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>"><?= $statusLabels[$s] ?></option>
                    <?php endforeach; ?>
                </select>
            <button @click="applyFilter" class="px-6 py-3 bg-primary text-white rounded-xl text-sm font-black hover:bg-primary/90 transition-all">
                        <i class="fas fa-search mr-2"></i>ຄົ້ນຫາ
                    </button>
            <button @click="resetFilter" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl text-sm font-black hover:bg-gray-200 transition-all border">
                <i class="fas fa-undo mr-2"></i>ລ້າງຟິວເຕີ
                    </button>
        </div>
    </div>

<!-- Desktop Table -->
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-sm"> 
                <thead> 
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ເລກບິນ</th>
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ລູກຄ້າ</th>
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ວັນທີເຊົ່າ</th>
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ກຳນົດສົ່ງຄືນ</th>
                        <th class="text-right px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ຍອດລວມ</th>
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ຊຳລະ</th>
                        <th class="text-center px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ສະຖານະ</th>
                        <th class="text-left px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ພະນັກງານ</th>
                        <th class="text-center px-4 py-4 font-black text-gray-700 text-sm uppercase tracking-tight">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($rentals)): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-receipt text-4xl text-gray-200"></i>
                                <p class="font-bold">ຍັງບໍ່ມີບິນເຊົ່າ</p>
                            </div>
                        </td>
                    </tr> 
                    <?php else: ?>
                    <?php foreach ($rentals as $r): ?>
                    <tr class="hover:bg-sky-50/30 transition-colors">
                        <td class="px-4 py-4">
                            <span class="font-black text-primary text-sm"><?= htmlspecialchars($r['invoice_number'] ?? '') ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($r['customer_name'] ?? '') ?></p>
                                <p class="text-[10px] text-gray-400"><?= htmlspecialchars($r['customer_phone'] ?? '') ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($r['pickup_date'])) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($r['return_date'])) ?></td>
                        <td class="px-4 py-4 text-right text-sm font-black text-gray-900 whitespace-nowrap"><?= number_format($r['grand_total']) ?></td>
                        <td class="px-4 py-4">
                            <div class="text-xs">
                                <span class="font-bold <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'text-green-600' : 'text-yellow-600' ?>">
                                    <?= ($r['payment_status'] ?? 'Paid') === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້' ?>
                                </span>
                                <span class="text-gray-400 block"><?= htmlspecialchars($r['payment_method_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php $dotColor = $statusDotColors[$r['status']] ?? 'text-gray-400'; ?>
                            <span class="inline-flex items-center gap-1.5 text-xs font-black <?= $dotColor ?>">
                                <span class="w-2 h-2 rounded-full <?= str_replace('text-', 'bg-', $dotColor) ?>"></span>
                                <?= $statusLabels[$r['status']] ?? $r['status'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600 font-bold"><?= htmlspecialchars($r['created_by_name'] ?? 'Admin') ?></td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                               
                                <a href="<?= url('/print-invoice/' . $r['id']) ?>" target="_blank" class="w-10 h-10 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all shadow-sm border border-emerald-100" title="ພິມ">
                                    <i class="fas fa-print text-base"></i>
                                </a>
                                <button onclick="changeStatus(<?= $r['id'] ?>, '<?= $r['status'] ?>')" class="w-10 h-10 flex items-center justify-center bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white rounded-xl transition-all shadow-sm border border-purple-100" title="ປ່ຽນສະຖານະ">
                                    <i class="fas fa-sync-alt text-base"></i>
                                </button>
                                <button onclick="shareRental(<?= $r['id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white rounded-xl transition-all shadow-sm border border-sky-100" title="ແຊຣ໌">
                                    <i class="fas fa-share-alt text-base"></i>
                                </button>
                                <button onclick="deleteRental(<?= $r['id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100" title="ລົບ">
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="block md:hidden space-y-3">
        <?php if (empty($rentals)): ?>
        <div class="bg-white rounded-2xl border p-8 text-center text-gray-400">
            <div class="flex flex-col items-center gap-2">
                <i class="fas fa-receipt text-4xl text-gray-200"></i>
                <p class="font-bold">ຍັງບໍ່ມີບິນເຊົ່າ</p>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($rentals as $r): ?>
        <div class="bg-white rounded-2xl border p-4 shadow-sm">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-black text-primary text-xs"><?= htmlspecialchars($r['invoice_number'] ?? '') ?></p>
                    <p class="font-bold text-gray-800"><?= htmlspecialchars($r['customer_name'] ?? '') ?></p>
                    <p class="text-[10px] text-gray-400"><?= htmlspecialchars($r['customer_phone'] ?? '') ?></p>
                </div> 
                <?php $dotColor = $statusDotColors[$r['status']] ?? 'text-gray-400'; ?>
                <span class="inline-flex items-center gap-1 text-[10px] font-black <?= $dotColor ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= str_replace('text-', 'bg-', $dotColor) ?>"></span>
                    <?= $statusLabels[$r['status']] ?? $r['status'] ?>
                </span>
            </div>
            <div class="grid grid-cols-2 gap-1 text-xs text-gray-500 mb-2">
                <div>
                    <span class="text-[10px] text-gray-400">ວັນທີເຊົ່າ</span>
                    <p class="font-bold text-gray-700"><?= date('d/m/Y', strtotime($r['pickup_date'])) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400">ກຳນົດສົ່ງຄືນ</span>
                    <p class="font-bold text-gray-700"><?= date('d/m/Y', strtotime($r['return_date'])) ?></p>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs mb-2">
                <div>
                    <span class="text-[10px] text-gray-400">ຍອດລວມ</span>
                    <p class="font-black text-gray-800"><?= number_format($r['grand_total']) ?> ₭</p>
                </div>
                <div class="text-right">
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
               
                <a href="<?= url('/print-invoice/' . $r['id']) ?>" target="_blank" class="flex-1 px-3 py-2 bg-sky-50 text-primary rounded-xl text-xs font-bold hover:bg-primary/10 transition-all text-center">
                    <i class="fas fa-print mr-1"></i>ພິມ
                </a>
                <button onclick="changeStatus(<?= $r['id'] ?>, '<?= $r['status'] ?>')" class="flex-1 px-3 py-2 bg-purple-50 text-purple-600 rounded-xl text-xs font-bold hover:bg-purple-100 transition-all text-center">
                    <i class="fas fa-sync-alt mr-1"></i>ສະຖານະ
                </button>
                <button onclick="shareRental(<?= $r['id'] ?>)" class="flex-1 px-3 py-2 bg-sky-50 text-primary rounded-xl text-xs font-bold hover:bg-primary/10 transition-all text-center">
                    <i class="fas fa-share-alt mr-1"></i>ແຊຣ໌
                </button>
                <button onclick="deleteRental(<?= $r['id'] ?>)" class="flex-1 px-3 py-2 bg-red-50 text-red-500 rounded-xl text-xs font-bold hover:bg-red-100 transition-all text-center">
                    <i class="fas fa-trash-alt mr-1"></i>ລົບ
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
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
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ລູກຄ້າ</p>
                    <p class="font-bold text-gray-800" x-text="viewData.customer_name"></p>
                    <p class="text-sm text-gray-500" x-text="viewData.customer_phone"></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ວັນທີເຊົ່າ</p>
                        <p class="font-bold text-gray-700" x-text="viewData.pickup_date_formatted"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ກຳນົດສົ່ງຄືນ</p>
                        <p class="font-bold text-gray-700" x-text="viewData.return_date_formatted"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ສະຖານະ</p>
                        <span class="inline-flex items-center gap-1.5 text-xs font-black" x-bind:class="viewData.status_text_class">
                            <span class="w-2 h-2 rounded-full" x-bind:class="viewData.status_dot_class"></span>
                            <span x-text="viewData.status_label"></span>
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ການຊຳລະ</p>
                        <p class="font-bold" x-bind:class="viewData.payment_status_class" x-text="viewData.payment_status_label"></p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-2">ລາຍການທີ່ເຊົ່າ</p>
                    <div class="space-y-2">
                        <template x-for="(item, i) in viewItems" :key="i">
                            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm" x-text="item.product_name"></p>
                                    <p class="text-xs text-gray-400">
                                        <span x-text="item.product_code"></span>
                                        <span x-show="item.size"> | <span x-text="item.size"></span></span>
                                        <span> | x<span x-text="item.qty"></span></span>
                                    </p>
                                </div>
                                <p class="font-black text-gray-800 text-sm" x-text="number_format(item.total) + ' ₭'"></p>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm" x-show="viewData.subtotal">
                        <span class="text-gray-500">ຍອດກ່ອນສ່ວນຫຼຸດ</span>
                        <span class="font-bold" x-text="number_format(viewData.subtotal) + ' ₭'"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="viewData.discount">
                        <span class="text-gray-500">ສ່ວນຫຼຸດ</span>
                        <span class="font-bold text-red-500" x-text="'-' + number_format(viewData.discount) + ' ₭'"></span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="font-black text-gray-800">ຍອດລວມ</span>
                        <span class="font-black text-primary" x-text="number_format(viewData.grand_total) + ' ₭'"></span>
                    </div>
                </div>
                <div x-show="viewData.returned_at" class="border-t pt-4 text-center text-xs text-gray-400">
                    <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                    ຄືນແລ້ວເມື່ອ: <span x-text="viewData.returned_at_formatted"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function rentalViewModal() {
    return {
        showViewModal: false,
        viewData: {},
        viewItems: [],
        openViewModal(id) {
            const statusLabels = <?= json_encode($statusLabels) ?>;
            const statusDotColors = <?= json_encode($statusDotColors) ?>;
            fetch('<?= url('/rentals') ?>/' + id + '/items')
                .then(res => res.json())
                .then(items => {
                    fetch('<?= url('/rentals') ?>/' + id + '/data')
                        .then(res => res.json())
                        .then(data => {
                            const dotColor = statusDotColors[data.status] || 'text-gray-400';
                            this.viewData = {
                                ...data,
                                pickup_date_formatted: data.pickup_date ? new Date(data.pickup_date).toLocaleDateString('en-GB') : '',
                                return_date_formatted: data.return_date ? new Date(data.return_date).toLocaleDateString('en-GB') : '',
                                returned_at_formatted: data.returned_at ? new Date(data.returned_at).toLocaleDateString('en-GB') + ' ' + new Date(data.returned_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '',
                                status_label: statusLabels[data.status] || data.status,
                                status_text_class: dotColor,
                                status_dot_class: dotColor.replace('text-', 'bg-'),
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
function shareRental(id) {
    const url = '<?= url('/print-invoice') ?>' + '/' + id;
    if (navigator.share) {
        navigator.share({ url: url });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'ສຳເລັດ!',
                text: 'ຄັດລອກລິ້ງແລ້ວ',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
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
</script>
 