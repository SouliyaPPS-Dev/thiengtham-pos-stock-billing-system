<div class="p-4 md:p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ປະຫວັດການຂາຍ</h1>
        <p class="text-sm text-gray-500">ບັນທຶກການຂາຍທັງໝົດ</p>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <form method="GET" action="<?= url('/sales') ?>" class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາໃບບິນ ຫຼື ລູກຄ້າ..." class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
            </div>
            <button type="submit" class="bg-primary text-white rounded-xl px-4 py-2.5 font-bold hover:opacity-90 text-sm">
                <i class="fas fa-search"></i> ຄົ້ນຫາ
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ໃບບິນ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວັນທີ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລູກຄ້າ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍການ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຍອດລວມ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຖານະ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fas fa-receipt text-3xl mb-2 block"></i>
                            <span>ຍັງບໍ່ມີປະຫວັດການຂາຍ</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($sales as $s): ?>
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='<?= url('/sales/' . $s['id']) ?>'">
                        <td class="py-3 px-2 font-mono font-bold text-gray-800">#<?= htmlspecialchars($s['invoice_number'] ?? str_pad($s['id'], 6, '0', STR_PAD_LEFT)) ?></td>
                        <td class="py-3 px-2 text-gray-500 text-xs"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['created_at']))) ?></td>
                        <td class="py-3 px-2 text-gray-700"><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></td>
                        <td class="py-3 px-2 text-gray-500"><?= (int)($s['items_count'] ?? 0) ?></td>
                        <td class="py-3 px-2 font-bold"><?= number_format($s['total'], 0) ?> ກີບ</td>
                        <td class="py-3 px-2">
                            <?php if (($s['status'] ?? 'completed') === 'completed'): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-600 text-xs font-bold"><i class="fas fa-check-circle"></i> ສຳເລັດ</span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold"><i class="fas fa-undo"></i> ຄືນເງິນ</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-2">
                            <a href="<?= url('/sales/' . $s['id']) ?>" class="h-8 w-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all flex items-center justify-center">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
