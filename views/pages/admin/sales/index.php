<div class="bg-background p-4 md:p-8 min-h-full">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ປະຫວັດການຂາຍ</h1>
            <p class="text-sm text-muted-foreground mt-0.5">ບັນທຶກການຂາຍທັງໝົດ</p>
        </div>

        <?php $saleIds = $saleIds ?? []; ?>

        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/sales') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-muted-foreground">ຈາກ</label>
                    <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>"
                           class="px-3 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-muted-foreground">ຫາ</label>
                    <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>"
                           class="px-3 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາໃບບິນ ຫຼື ລູກຄ້າ..." class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <div x-data="salesBulkDelete()" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 text-center" style="width:40px">
                                <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ໃບບິນ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລູກຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ວິທີຊຳລະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຍອດລວມ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="10" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີປະຫວັດການຂາຍ</p>
                                    <p class="text-sm text-muted-foreground mt-1">ປະຫວັດການຂາຍຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($sales as $s): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-2 text-center">
                                <input type="checkbox" :value="<?= $s['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </td>
                            <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <a href="<?= url('/admin/sales/' . $s['id']) ?>" class="font-mono font-bold text-foreground hover:text-primary">#<?= htmlspecialchars($s['invoice_number'] ?? str_pad($s['id'], 6, '0', STR_PAD_LEFT)) ?></a>
                            </td>
                            <td class="py-3 px-2 text-foreground/70"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= (int)($s['items_count'] ?? 0) ?></td>
                            <td class="py-3 px-2">
                                <?php
                                $pm = $s['payment_method'] ?? 'ເງິນສົດ';
                                $pmColors = [
                                    'bg-green-50 text-green-600' => ['ເງິນສົດ', 'cash', 'ເງິນສົດສົດ'],
                                    'bg-blue-50 text-blue-600' => ['ໂອນ', 'transfer', 'bank', 'banking'],
                                    'bg-purple-50 text-purple-600' => ['qr', 'qr code', 'qrcode', 'promptpay'],
                                    'bg-amber-50 text-amber-600' => ['credit', 'card', 'visa', 'mastercard'],
                                    'bg-rose-50 text-rose-600' => ['mobile', 'wallet', 'truewallet'],
                                ];
                                $pmClass = 'bg-gray-50 text-foreground/70';
                                foreach ($pmColors as $class => $keywords) {
                                    foreach ($keywords as $kw) {
                                        if (str_contains(mb_strtolower($pm), $kw)) {
                                            $pmClass = $class;
                                            break 2;
                                        }
                                    }
                                }
                                ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold <?= $pmClass ?>">
                                    <i class="fas fa-credit-card"></i>
                                    <?= htmlspecialchars($pm) ?>
                                </span>
                            </td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= number_format($s['grand_total'] ?: 0, 0) ?> ກີບ</td>
                            <td class="py-3 px-2">
                                <?php $st = strtolower($s['status'] ?? 'Completed'); ?>
                                <?php if ($st === 'completed'): ?>
                                <span class="status-badge status-badge-green"><span class="dot"></span> ສຳເລັດ</span>
                                <?php elseif ($st === 'refunded'): ?>
                                <span class="status-badge status-badge-red"><span class="dot"></span> ຄືນເງິນ</span>
                                <?php elseif ($st === 'cancelled'): ?>
                                <span class="status-badge status-badge-gray"><span class="dot"></span> ຍົກເລີກ</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-gray"><?= htmlspecialchars($s['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    <a href="<?= url('/admin/sales/' . $s['id']) ?>" class="icon-btn icon-btn-view" title="ເບິ່ງລາຍລະອຽດ">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="<?= url('/admin/invoices/' . $s['id'] . '/print') ?>" target="_blank" class="icon-btn icon-btn-print" title="ພິມໃບບິນ">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <?php if (strtolower($s['status'] ?? '') === 'completed'): ?>
                                    <button type="button" class="icon-btn icon-btn-warning" title="ຄືນເງິນ"
                                        onclick="confirmRefund(<?= $s['id'] ?>)">
                                        <i class="fas fa-undo text-xs"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" class="icon-btn icon-btn-danger" title="ລົບ"
                                        onclick="confirmDelete(<?= $s['id'] ?>)">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($sales)): ?>
                    <tfoot x-show="selected.length > 0">
                        <tr>
                            <td colspan="10" class="px-2 py-0">
                                <div class="border border-red-200 bg-red-50/80 rounded-xl px-5 py-3 flex items-center justify-between transition-all">
                                    <span class="text-sm font-bold text-red-700">
                                        <i class="fas fa-check-circle mr-1.5"></i>
                                        ເລືອກ <span x-text="selected.length" class="text-red-600 text-base"></span> ລາຍການ
                                    </span>
                                    <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-sm" style="background:#dc2626">
                                        <i class="fas fa-trash-alt"></i>
                                        ລຶບທັງໝົດ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Mobile Bulk Action Bar -->
            <div x-show="selected.length > 0" class="fixed bottom-0 left-0 right-0 bg-card border-t shadow-2xl px-4 py-3 z-50 md:hidden">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-foreground/70">
                        ເລືອກ <span x-text="selected.length" class="text-primary font-black"></span> ລາຍການ
                    </span>
                    <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-lg" style="background:#dc2626">
                        <i class="fas fa-trash-alt"></i>
                        ລຶບທັງໝົດ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-refund" method="POST" action="" class="hidden">
    <input type="hidden" name="status" value="Refunded">
</form>
<form id="form-delete" method="POST" action="" class="hidden"></form>

<script>
function confirmRefund(id) {
    Swal.fire({
        title: 'ຄືນເງິນໃບບິນ?',
        text: 'ທ່ານຕ້ອງການປ່ຽນສະຖານະເປັນ ຄືນເງິນ ບໍ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ແມ່ນ, ຄືນເງິນ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('form-refund');
            form.action = '<?= url('/admin/sales') ?>/' + id + '/update-status';
            form.submit();
        }
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'ລົບໃບບິນ?',
        text: 'ທ່ານຕ້ອງການລົບໃບບິນນີ້ ບໍ? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ແມ່ນ, ລົບ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('form-delete');
            form.action = '<?= url('/admin/sales') ?>/' + id + '/delete';
            form.submit();
        }
    });
}

function salesBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($saleIds) ?>,
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
                confirmButtonText: 'ແມ່ນ, ລົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url('/admin/sales/bulk-delete') ?>';
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
