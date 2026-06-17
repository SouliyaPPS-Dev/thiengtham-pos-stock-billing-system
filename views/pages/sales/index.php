<div class="p-4 md:p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ປະຫວັດການຂາຍ</h1>
        <p class="text-sm text-gray-500">ບັນທຶກການຂາຍທັງໝົດ</p>
    </div>

    <div class="table-wrap">
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
                        <th>ໃບບິນ</th>
                        <th>ວັນທີ</th>
                        <th>ລູກຄ້າ</th>
                        <th>ລາຍການ</th>
                        <th>ຍອດລວມ</th>
                        <th>ສະຖານະ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <p class="empty-state-title">ຍັງບໍ່ມີປະຫວັດການຂາຍ</p>
                                <p class="empty-state-desc">ປະຫວັດການຂາຍຈະສະແດງຢູ່ນີ້</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($sales as $s): ?>
                    <tr class="cursor-pointer" onclick="window.location.href='<?= url('/sales/' . $s['id']) ?>'">
                        <td class="font-mono font-bold text-gray-800">#<?= htmlspecialchars($s['invoice_number'] ?? str_pad($s['id'], 6, '0', STR_PAD_LEFT)) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['created_at']))) ?></td>
                        <td><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></td>
                        <td><?= (int)($s['items_count'] ?? 0) ?></td>
                        <td><?= number_format($s['total'], 0) ?> ກີບ</td>
                        <td>
                            <?php if (($s['status'] ?? 'completed') === 'completed'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span> ສຳເລັດ</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-red"><span class="dot"></span> ຄືນເງິນ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('/sales/' . $s['id']) ?>" class="icon-btn icon-btn-view">
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
