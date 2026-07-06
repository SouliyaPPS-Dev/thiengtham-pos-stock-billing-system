<div class="bg-background p-4 md:p-8 min-h-full">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="<?= url('/admin/sales') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໃບເກັບເງິນ #<?= htmlspecialchars($sale['invoice_number'] ?? str_pad($sale['id'], 6, '0', STR_PAD_LEFT)) ?></h1>
                        <p class="text-sm text-muted-foreground mt-0.5">ລາຍລະອຽດການຂາຍ</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= url('/admin/invoices/' . $sale['id'] . '/print') ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm hover:bg-emerald-200 transition-all shadow-lg shadow-emerald-200 active:scale-[0.97]">
                        <i class="fas fa-print"></i>
                        <span class="hidden sm:inline">ພິມ</span>
                    </a>
                    <?php if (strtolower($sale['status'] ?? '') === 'completed'): ?>
                    <button type="button" onclick="confirmRefund(<?= $sale['id'] ?>)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-100 text-amber-700 rounded-xl font-bold text-sm hover:bg-amber-200 transition-all shadow-lg shadow-amber-200 active:scale-[0.97]">
                        <i class="fas fa-undo"></i>
                        <span class="hidden sm:inline">ຄືນເງິນ</span>
                    </button>
                    <?php endif; ?>
                    <button type="button" onclick="confirmDelete(<?= $sale['id'] ?>)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-100 text-red-700 rounded-xl font-bold text-sm hover:bg-red-200 transition-all shadow-lg shadow-red-200 active:scale-[0.97]">
                        <i class="fas fa-trash"></i>
                        <span class="hidden sm:inline">ລົບ</span>
                    </button>
                </div>
            </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <i class="fas fa-receipt text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ລາຍການສິນຄ້າ</h2>
                            <p class="text-xs text-muted-foreground">ສິນຄ້າທີ່ຂາຍໃນບິນນີ້</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລ/ດ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຈຳນວນ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຄາ/ຫົວໜ່ວຍ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຈຳນວນເງີນ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sale['items'] ?? [] as $i => $item): ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 text-foreground/70"><?= $i + 1 ?></td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= (int)$item['quantity'] ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= number_format($item['price'], 0) ?> ກີບ</td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= number_format($item['price'] * $item['quantity'], 0) ?> ກີບ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground"><?= !empty($sale['supplier_name']) ? 'ຂໍ້ມູນຜູ້ສະໜອງ' : 'ຂໍ້ມູນລູກຄ້າ' ?></h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <?php if (!empty($sale['supplier_name'])): ?>
                        <p class="text-sm font-bold text-foreground"><i class="fas fa-truck mr-2 text-amber-500"></i><?= htmlspecialchars($sale['supplier_name']) ?></p>
                        <?php else: ?>
                        <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($sale['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></p>
                        <?php if (!empty($sale['customer_phone'])): ?>
                        <p class="text-sm text-muted-foreground"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($sale['customer_phone']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($sale['customer_address'])): ?>
                        <p class="text-sm text-muted-foreground"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($sale['customer_address']) ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <p class="text-xs text-muted-foreground"><i class="far fa-calendar mr-1"></i><?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?></p>
                    </div>
                </div>

                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-calculator text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ລວມຍອດ</h2>
                        </div>
                    </div>
                    <?php
                        $items = $sale['items'] ?? [];
                        $calcSubtotal = 0;
                        foreach ($items as $item) {
                            $calcSubtotal += (float)$item['subtotal'];
                        }
                        $showDiscount = !empty($sale['discount']) && (float)$sale['discount'] > 0;
                        $showTax = !empty($sale['tax_amount']) && (float)$sale['tax_amount'] > 0;
                    ?>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ລວມຍ່ອຍ</span>
                            <span class="font-medium"><?= number_format($sale['subtotal'] ?: $calcSubtotal, 0) ?> ກີບ</span>
                        </div>
                        <?php if ($showDiscount): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ສ່ວນຫຼຸດ</span>
                            <span class="font-medium text-red-500">-<?= number_format($sale['discount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($showTax): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ອາກອນ</span>
                            <span class="font-medium"><?= number_format($sale['tax_amount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-base font-bold pt-3 border-t">
                            <span>ລວມທັງໝົດ</span>
                            <span class="text-primary"><?= number_format($sale['grand_total'] ?: $calcSubtotal, 0) ?> ກີບ</span>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-credit-card text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນຊຳລະ</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ວິທີຊຳລະ</span>
                            <span class="font-bold capitalize"><?= $sale['payment_method'] ?? 'ເງິນສົດ' ?></span>
                        </div>
                        <?php if (!empty($sale['amount_paid'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ຊຳລະແລ້ວ</span>
                            <span class="font-medium"><?= number_format($sale['amount_paid'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($sale['change_amount'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ເງິນທອນ</span>
                            <span class="font-medium text-green-600"><?= number_format($sale['change_amount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-sm pt-3 border-t">
                            <span class="text-muted-foreground">ສະຖານະ</span>
                            <?php $st = strtolower($sale['status'] ?? 'Completed'); ?>
                            <?php if ($st === 'completed'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span> ສຳເລັດ</span>
                            <?php elseif ($st === 'refunded'): ?>
                            <span class="status-badge status-badge-red"><span class="dot"></span> ຄືນເງິນ</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-gray"><span class="dot"></span> ຍົກເລີກ</span>
                            <?php endif; ?>
                        </div>
                    </div>
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
</script>
