<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ປະຫວັດການຂາຍ</h1>
            <p class="text-sm text-muted-foreground mt-0.5">ບັນທຶກການຂາຍທັງໝົດ</p>
        </div>

        <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
            <form method="GET" action="<?= url('/sales') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາໃບບິນ ຫຼື ລູກຄ້າ..." class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ໃບບິນ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລູກຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຍອດລວມ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
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
                                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີປະຫວັດການຂາຍ</p>
                                    <p class="text-sm text-muted-foreground mt-1">ປະຫວັດການຂາຍຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($sales as $s): $i++; ?>
                        <tr class="cursor-pointer border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors" onclick="window.location.href='<?= url('/sales/' . $s['id']) ?>'">
                            <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2 font-mono font-bold text-foreground">#<?= htmlspecialchars($s['invoice_number'] ?? str_pad($s['id'], 6, '0', STR_PAD_LEFT)) ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['created_at']))) ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= (int)($s['items_count'] ?? 0) ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= number_format($s['total'], 0) ?> ກີບ</td>
                            <td class="py-3 px-2">
                                <?php if (($s['status'] ?? 'completed') === 'completed'): ?>
                                <span class="status-badge status-badge-green"><span class="dot"></span> ສຳເລັດ</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-red"><span class="dot"></span> ຄືນເງິນ</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
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
</div>
