<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= url('/admin/sales') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໃບເກັບເງິນ #<?= htmlspecialchars($sale['invoice_number'] ?? str_pad($sale['id'], 6, '0', STR_PAD_LEFT)) ?></h1>
                    <p class="text-sm text-gray-500 mt-0.5">ລາຍລະອຽດການຂາຍ</p>
                </div>
            </div>
            <a href="<?= url('/admin/invoices/' . $sale['id'] . '/print') ?>" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">ພິມໃບບິນ</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <i class="fas fa-receipt text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ລາຍການສິນຄ້າ</h2>
                            <p class="text-xs text-gray-400">ສິນຄ້າທີ່ຂາຍໃນບິນນີ້</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລ/ດ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍການ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຈຳນວນ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຄາ/ຫົວໜ່ວຍ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຈຳນວນເງີນ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sale['items'] ?? [] as $i => $item): ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 text-gray-600"><?= $i + 1 ?></td>
                                    <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= (int)$item['quantity'] ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= number_format($item['price'], 0) ?> ກີບ</td>
                                    <td class="py-3 px-2 font-medium text-gray-800"><?= number_format($item['price'] * $item['quantity'], 0) ?> ກີບ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນລູກຄ້າ</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars($sale['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></p>
                        <?php if (!empty($sale['customer_phone'])): ?>
                        <p class="text-sm text-gray-500"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($sale['customer_phone']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($sale['customer_address'])): ?>
                        <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($sale['customer_address']) ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-gray-400"><i class="far fa-calendar mr-1"></i><?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-calculator text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ລວມຍອດ</h2>
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
                            <span class="text-gray-500">ລວມຍ່ອຍ</span>
                            <span class="font-medium"><?= number_format($sale['subtotal'] ?: $calcSubtotal, 0) ?> ກີບ</span>
                        </div>
                        <?php if ($showDiscount): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ສ່ວນຫຼຸດ</span>
                            <span class="font-medium text-red-500">-<?= number_format($sale['discount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($showTax): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ອາກອນ</span>
                            <span class="font-medium"><?= number_format($sale['tax_amount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-base font-bold pt-3 border-t">
                            <span>ລວມທັງໝົດ</span>
                            <span class="text-primary"><?= number_format($sale['grand_total'] ?: $calcSubtotal, 0) ?> ກີບ</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-credit-card text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນຊຳລະ</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ວິທີຊຳລະ</span>
                            <span class="font-bold capitalize"><?= $sale['payment_method'] ?? 'ເງິນສົດ' ?></span>
                        </div>
                        <?php if (!empty($sale['amount_paid'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ຊຳລະແລ້ວ</span>
                            <span class="font-medium"><?= number_format($sale['amount_paid'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($sale['change_amount'])): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ເງິນທອນ</span>
                            <span class="font-medium text-green-600"><?= number_format($sale['change_amount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-sm pt-3 border-t">
                            <span class="text-gray-500">ສະຖານະ</span>
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
