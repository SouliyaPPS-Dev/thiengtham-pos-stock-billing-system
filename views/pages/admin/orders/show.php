<div class="bg-background p-4 md:p-8 min-h-full">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="<?= url('/admin/orders') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ຄຳສັ່ງຊື້ #<?= htmlspecialchars($order['order_number'] ?? str_pad($order['id'], 6, '0', STR_PAD_LEFT)) ?></h1>
                        <p class="text-sm text-muted-foreground mt-0.5">ລາຍລະອຽດຄຳສັ່ງຊື້ຈາກເວັບໄຊຕ໌</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="confirmDelete(<?= $order['id'] ?>)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-100 text-red-700 rounded-xl font-bold text-sm hover:bg-red-200 transition-all shadow-lg shadow-red-200 active:scale-[0.97]">
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
                            <p class="text-xs text-muted-foreground">ສິນຄ້າທີ່ສັ່ງຊື້</p>
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
                                <?php foreach ($order['items'] ?? [] as $i => $item): ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 text-foreground/70"><?= $i + 1 ?></td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= (int)$item['quantity'] ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= number_format((float)$item['unit_price'], 0) ?> ກີບ</td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= number_format((float)$item['subtotal'], 0) ?> ກີບ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($order['shipping_latitude']) && !empty($order['shipping_longitude'])): ?>
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-map-marked-alt text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຕຳແໜ່ງທີ່ຕັ້ງ</h2>
                            <p class="text-xs text-muted-foreground">GPS ຂອງທີ່ຢູ່ຈັດສົ່ງ</p>
                        </div>
                    </div>
                    <div id="map-order-show" class="w-full h-48 rounded-xl border border-border z-0"></div>
                    <div class="flex gap-3 mt-2">
                        <span class="text-xs text-muted-foreground">ເສັ້ນຂວາງ: <?= $order['shipping_latitude'] ?></span>
                        <span class="text-xs text-muted-foreground">ເສັ້ນແວງ: <?= $order['shipping_longitude'] ?></span>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var isDark = document.documentElement.classList.contains('dark');
                        var map = L.map('map-order-show', { zoomControl: true, scrollWheelZoom: false }).setView([<?= $order['shipping_latitude'] ?>, <?= $order['shipping_longitude'] ?>], 15);
                        L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OSM' }).addTo(map);
                        L.marker([<?= $order['shipping_latitude'] ?>, <?= $order['shipping_longitude'] ?>]).addTo(map);
                    });
                    </script>
                </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນລູກຄ້າ</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
                        <?php if (!empty($order['customer_phone'])): ?>
                        <p class="text-sm text-muted-foreground"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($order['customer_phone']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($order['customer_email'])): ?>
                        <p class="text-sm text-muted-foreground"><i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($order['customer_email']) ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-muted-foreground"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($order['shipping_address'] ?? '') ?></p>
                        <?php if (!empty($order['shipping_province'])): ?>
                        <p class="text-xs text-muted-foreground ml-6"><?= htmlspecialchars($order['shipping_province']) ?><?= !empty($order['shipping_district']) ? ', ' . htmlspecialchars($order['shipping_district']) : '' ?><?= !empty($order['shipping_village']) ? ', ' . htmlspecialchars($order['shipping_village']) : '' ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-muted-foreground mt-2"><i class="far fa-calendar mr-1"></i><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
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
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ລວມຍ່ອຍ</span>
                            <span class="font-medium"><?= number_format((float)$order['subtotal'], 0) ?> ກີບ</span>
                        </div>
                        <?php if ((float)$order['shipping_fee'] > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ຄ່າຈັດສົ່ງ</span>
                            <span class="font-medium"><?= number_format((float)$order['shipping_fee'], 0) ?> ກີບ</span>
                        </div>
                        <?php else: ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ຄ່າຈັດສົ່ງ</span>
                            <span class="font-medium text-emerald-600">ຟຣີ</span>
                        </div>
                        <?php endif; ?>
                        <?php if ((float)$order['discount'] > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ສ່ວນຫຼຸດ</span>
                            <span class="font-medium text-red-500">-<?= number_format((float)$order['discount'], 0) ?> ກີບ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-base font-bold pt-3 border-t">
                            <span>ລວມທັງໝົດ</span>
                            <span class="text-primary"><?= number_format((float)$order['grand_total'], 0) ?> ກີບ</span>
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
                            <span class="font-bold capitalize"><?= $order['payment_method'] === 'cod' ? 'ເງິນສົດປາຍທາງ' : ($order['payment_method'] === 'qr' ? 'QR Code' : $order['payment_method']) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted-foreground">ສະຖານະການຊຳລະ</span>
                            <?php $ps = strtolower($order['payment_status'] ?? 'Pending'); ?>
                            <?php if ($ps === 'paid'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span> ຊຳລະແລ້ວ</span>
                            <?php elseif ($ps === 'failed'): ?>
                            <span class="status-badge status-badge-red"><span class="dot"></span> ລົ້ມເຫຼວ</span>
                            <?php elseif ($ps === 'refunded'): ?>
                            <span class="status-badge status-badge-gray"><span class="dot"></span> ຄືນເງິນ</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-yellow"><span class="dot"></span> ລໍຖ້າ</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-between text-sm pt-3 border-t">
                            <span class="text-muted-foreground">ສະຖານະຄຳສັ່ງ</span>
                            <?php $os = strtolower($order['order_status'] ?? 'Pending'); ?>
                            <?php if ($os === 'delivered'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span> ສົ່ງແລ້ວ</span>
                            <?php elseif ($os === 'confirmed'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span> ຢືນຢັນ</span>
                            <?php elseif ($os === 'processing'): ?>
                            <span class="status-badge" style="background:#dbeafe;color:#1d4ed8"><span class="dot bg-blue-500"></span> ກຳລັງດຳເນີນ</span>
                            <?php elseif ($os === 'shipped'): ?>
                            <span class="status-badge status-badge-blue"><span class="dot"></span> ຈັດສົ່ງ</span>
                            <?php elseif ($os === 'cancelled'): ?>
                            <span class="status-badge status-badge-gray"><span class="dot"></span> ຍົກເລີກ</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-yellow"><span class="dot"></span> ລໍຖ້າ</span>
                            <?php endif; ?>
                        </div>

                        <!-- Update Order Status -->
                        <div class="pt-3 border-t">
                            <form method="POST" action="<?= url('/admin/orders/' . $order['id'] . '/update-status') ?>" class="space-y-2">
                                <label class="text-xs font-bold text-muted-foreground block">ປ່ຽນສະຖານະ</label>
                                <div class="flex gap-2">
                                    <select name="order_status" class="flex-1 px-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                        <option value="Pending" <?= $os === 'pending' ? 'selected' : '' ?>>ລໍຖ້າ</option>
                                        <option value="Confirmed" <?= $os === 'confirmed' ? 'selected' : '' ?>>ຢືນຢັນ</option>
                                        <option value="Processing" <?= $os === 'processing' ? 'selected' : '' ?>>ກຳລັງດຳເນີນ</option>
                                        <option value="Shipped" <?= $os === 'shipped' ? 'selected' : '' ?>>ຈັດສົ່ງ</option>
                                        <option value="Delivered" <?= $os === 'delivered' ? 'selected' : '' ?>>ສົ່ງແລ້ວ</option>
                                        <option value="Cancelled" <?= $os === 'cancelled' ? 'selected' : '' ?>>ຍົກເລີກ</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-xl font-bold text-sm hover:bg-sky-700 transition-all">ບັນທຶກ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (!empty($order['notes'])): ?>
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white shadow-lg shadow-gray-200">
                            <i class="fas fa-sticky-note text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຫມາຍເຫດ</h2>
                        </div>
                    </div>
                    <p class="text-sm text-foreground/70"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<form id="form-delete" method="POST" action="" class="hidden"></form>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'ລົບຄຳສັ່ງຊື້?',
        text: 'ທ່ານຕ້ອງການລົບຄຳສັ່ງຊື້ນີ້ ບໍ? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ແມ່ນ, ລົບ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('form-delete');
            form.action = '<?= url('/admin/orders') ?>/' + id + '/delete';
            form.submit();
        }
    });
}
</script>

