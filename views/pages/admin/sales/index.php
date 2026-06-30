<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ປະຫວັດການຂາຍ</h1>
            <p class="text-sm text-gray-500 mt-0.5">ບັນທຶກການຂາຍທັງໝົດ</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/sales') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-400">ຈາກ</label>
                    <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>"
                           class="px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-400">ຫາ</label>
                    <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>"
                           class="px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາໃບບິນ ຫຼື ລູກຄ້າ..." class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ໃບບິນ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລູກຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍການ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວິທີຊຳລະ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຍອດລວມ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="8" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີປະຫວັດການຂາຍ</p>
                                    <p class="text-sm text-gray-400 mt-1">ປະຫວັດການຂາຍຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($sales as $s): ?>
                        <tr class="cursor-pointer border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors" onclick="window.location.href='<?= url('/admin/sales/' . $s['id']) ?>'">
                            <td class="py-3 px-2 font-mono font-bold text-gray-800">#<?= htmlspecialchars($s['invoice_number'] ?? str_pad($s['id'], 6, '0', STR_PAD_LEFT)) ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= (int)($s['items_count'] ?? 0) ?></td>
                            <td class="py-3 px-2">
                                <?php $pm = $s['payment_method'] ?? 'cash'; ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold <?= $pm === 'cash' ? 'bg-green-50 text-green-600' : ($pm === 'transfer' ? 'bg-blue-50 text-blue-600' : ($pm === 'qr' ? 'bg-purple-50 text-purple-600' : 'bg-gray-50 text-gray-600')) ?>">
                                    <i class="fas fa-<?= $pm === 'cash' ? 'money-bill' : ($pm === 'transfer' ? 'exchange-alt' : ($pm === 'qr' ? 'qrcode' : 'credit-card')) ?>"></i>
                                    <?= $pm === 'cash' ? 'ເງິນສົດ' : ($pm === 'transfer' ? 'ໂອນ' : ($pm === 'qr' ? 'QR' : 'ກູ້')) ?>
                                </span>
                            </td>
                            <td class="py-3 px-2 font-medium text-gray-800"><?= number_format($s['grand_total'] ?? $s['total'], 0) ?> ກີບ</td>
                            <td class="py-3 px-2">
                                <?php $st = $s['status'] ?? 'completed'; ?>
                                <?php if ($st === 'completed'): ?>
                                <span class="status-badge status-badge-green"><span class="dot"></span> ສຳເລັດ</span>
                                <?php elseif ($st === 'refunded'): ?>
                                <span class="status-badge status-badge-red"><span class="dot"></span> ຄືນເງິນ</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-gray"><span class="dot"></span> ຍົກເລີກ</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <a href="<?= url('/admin/sales/' . $s['id']) ?>" class="icon-btn icon-btn-view">
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
</div>
