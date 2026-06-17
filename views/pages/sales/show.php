<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('/sales') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">ໃບບິນ #<?= htmlspecialchars($sale['invoice_number'] ?? str_pad($sale['id'], 6, '0', STR_PAD_LEFT)) ?></h1>
                <p class="text-sm text-gray-500">ລາຍລະອຽດໃບບິນ</p>
            </div>
        </div>
        <a href="<?= url('/invoices/' . $sale['id'] . '/print') ?>" target="_blank" class="bg-primary text-white rounded-xl px-4 py-2 font-bold hover:opacity-90 inline-flex items-center gap-2">
            <i class="fas fa-print"></i>
            <span>ພິມໃບບິນ</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-4 border-b mb-4">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ໃບບິນເລກທີ</p>
                <p class="font-bold text-gray-800">#<?= htmlspecialchars($sale['invoice_number'] ?? str_pad($sale['id'], 6, '0', STR_PAD_LEFT)) ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ວັນທີ</p>
                <p class="font-bold text-gray-800"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($sale['created_at']))) ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ລູກຄ້າ</p>
                <p class="font-bold text-gray-800"><?= htmlspecialchars($sale['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">ສະຖານະ</p>
                <?php if (($sale['status'] ?? 'completed') === 'completed'): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-green-50 text-green-600 text-xs font-bold"><i class="fas fa-check-circle"></i> ສຳເລັດ</span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold"><i class="fas fa-undo"></i> ຄືນເງິນ</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">#</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ສິນຄ້າ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຈຳນວນ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຄາ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລວມ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sale['items'] ?? [] as $i => $item): ?>
                    <tr class="border-b last:border-0">
                        <td class="py-3 px-2 text-gray-400"><?= $i + 1 ?></td>
                        <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($item['product_name'] ?? $item['name']) ?></td>
                        <td class="py-3 px-2 text-gray-600"><?= (int)($item['quantity'] ?? $item['qty']) ?></td>
                        <td class="py-3 px-2 text-gray-600"><?= number_format($item['price'], 0) ?> ກີບ</td>
                        <td class="py-3 px-2 font-bold"><?= number_format(($item['price'] * ($item['quantity'] ?? $item['qty'])), 0) ?> ກີບ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t mt-4 pt-4 flex flex-col items-end">
            <div class="w-full max-w-xs space-y-1.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">ລວມຍ່ອຍ</span>
                    <span><?= number_format($sale['total'], 0) ?> ກີບ</span>
                </div>
                <div class="flex justify-between text-lg font-black text-primary border-t pt-1.5">
                    <span>ລວມທັງໝົດ</span>
                    <span><?= number_format($sale['total'], 0) ?> ກີບ</span>
                </div>
            </div>
        </div>
    </div>
</div>
