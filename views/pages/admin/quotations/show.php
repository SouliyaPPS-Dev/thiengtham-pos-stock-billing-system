<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-5xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= url('/admin/quotations') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">#<?= htmlspecialchars($quotation['quotation_number']) ?></h1>
                    <p class="text-sm text-muted-foreground mt-0.5">ໃບສະເໜີລາຄາ - <?= htmlspecialchars($template['label']) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= url('/admin/quotations/' . $quotation['id'] . '/print') ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm hover:bg-emerald-200 transition-all shadow-lg shadow-emerald-200">
                    <i class="fas fa-print"></i> ພິມ
                </a>
                <a href="<?= url('/admin/quotations/' . $quotation['id'] . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-100 text-amber-700 rounded-xl font-bold text-sm hover:bg-amber-200 transition-all shadow-lg shadow-amber-200">
                    <i class="fas fa-pen"></i> ແກ້ໄຂ
                </a>
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
                            <p class="text-xs text-muted-foreground">ສິນຄ້າທີ່ສະເໜີລາຄາ</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລ/ດ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center">ຈຳນວນ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center">ຫົວໜ່ວຍ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-right">ລາຄາ/ໜ່ວຍ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-right">ຈຳນວນເງິນ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotation['items'] ?? [] as $i => $item): ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 text-foreground/70"><?= $i + 1 ?></td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td class="py-3 px-2 text-foreground/70 text-center"><?= (float)$item['quantity'] ?></td>
                                    <td class="py-3 px-2 text-foreground/70 text-center"><?= htmlspecialchars($item['unit'] ?: '-') ?></td>
                                    <td class="py-3 px-2 text-foreground/70 text-right"><?= number_format($item['unit_price'], 0) ?></td>
                                    <td class="py-3 px-2 font-medium text-foreground text-right"><?= number_format($item['amount'], 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-4 border-t border-border flex flex-col items-end">
                        <div class="w-full max-w-xs space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">ລວມຍ່ອຍ</span>
                                <span class="font-medium"><?= number_format($quotation['subtotal'], 0) ?></span>
                            </div>
                            <?php if (!empty($quotation['discount']) && (float)$quotation['discount'] > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">ສ່ວນຫຼຸດ</span>
                                <span class="font-medium text-red-500">-<?= number_format($quotation['discount'], 0) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($quotation['tax_amount']) && (float)$quotation['tax_amount'] > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">ອາກອນມູນຄ່າເພີ້ມ (<?= (float)$quotation['tax_percent'] ?>%)</span>
                                <span class="font-medium"><?= number_format($quotation['tax_amount'], 0) ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-between text-base font-black text-primary border-t border-border pt-1.5">
                                <span>ລວມທັງໝົດ</span>
                                <span><?= number_format($quotation['grand_total'], 0) ?> ກີບ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($quotation['notes'])): ?>
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <h3 class="text-sm font-bold text-foreground mb-2">ໝາຍເຫດ</h3>
                    <p class="text-sm text-foreground/70 whitespace-pre-line"><?= nl2br(htmlspecialchars($quotation['notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-file-invoice text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນໃບສະເໜີລາຄາ</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">ແມ່ແບບ</p>
                            <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($template['label']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">ວັນທີ</p>
                            <p class="text-sm text-foreground"><?= $quotation['date'] ? date('d/m/Y', strtotime($quotation['date'])) : '-' ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">ເລກອ້າງອີງ</p>
                            <p class="text-sm text-foreground"><?= htmlspecialchars($quotation['ref_no'] ?: '-') ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">ສະຖານະ</p>
                            <?php $st = $quotation['status'] ?? 'Draft'; ?>
                            <?php if ($st === 'Sent'): ?>
                            <span class="status-badge status-badge-green">ສົ່ງແລ້ວ</span>
                            <?php elseif ($st === 'Approved'): ?>
                            <span class="status-badge" style="background:#ecfdf5;color:#059669;border-color:#a7f3d0">ອະນຸມັດ</span>
                            <?php elseif ($st === 'Rejected'): ?>
                            <span class="status-badge status-badge-red">ປະຕິເສດ</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-gray">ຮ່າງ</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-truck text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນຜູ້ສະໜອງ</h2>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($quotation['supplier_name'] ?: '-') ?></p>
                    <?php if (!empty($quotation['supplier_contact'])): ?>
                    <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars($quotation['supplier_contact']) ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($quotation['terms'])): ?>
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white shadow-lg shadow-gray-200">
                            <i class="fas fa-file-contract text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ເງື່ອນໄຂ</h2>
                        </div>
                    </div>
                    <ul class="space-y-1.5">
                        <?php foreach (explode("\n", $quotation['terms']) as $term): ?>
                        <?php $term = trim($term); if (empty($term)) continue; ?>
                        <li class="text-sm text-foreground/70 flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-400 text-xs mt-0.5"></i>
                            <?= htmlspecialchars($term) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <p class="text-xs text-muted-foreground text-center">ສ້າງເມື່ອ: <?= date('d/m/Y H:i', strtotime($quotation['created_at'])) ?></p>
            </div>
        </div>
    </div>
</div>
