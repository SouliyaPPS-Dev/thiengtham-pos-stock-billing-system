<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Header -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-4xl text-emerald-500"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-foreground mb-2">ສັ່ງຊື້ສຳເລັດ!</h1>
        <p class="text-muted-foreground">ຂອບໃຈທີ່ສັ່ງຊື້ສິນຄ້າກັບພວກເຮົາ</p>
    </div>

    <!-- Order Number -->
    <div class="bg-card rounded-2xl border border-border p-6 mb-6 text-center">
        <p class="text-sm text-muted-foreground mb-1">ເລກທີຄຳສັ່ງຊື້</p>
        <p class="text-2xl font-black text-sky-600">#<?= htmlspecialchars($order['order_number']) ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Order Items -->
        <div class="md:col-span-2 bg-card rounded-2xl border border-border p-6">
            <h3 class="text-lg font-black text-foreground mb-4">ລາຍການສິນຄ້າ</h3>
            <div class="space-y-3">
                <?php foreach ($items as $item): ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="text-xs text-muted-foreground">x<?= (int)$item['quantity'] ?> @ <?= number_format((float)$item['unit_price'], 0) ?> ກີບ</p>
                    </div>
                    <span class="text-sm font-bold text-foreground"><?= number_format((float)$item['subtotal'], 0) ?> ກີບ</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Totals -->
        <div class="bg-card rounded-2xl border border-border p-6">
            <h3 class="text-lg font-black text-foreground mb-4">ລາຍລະອຽດການຊຳລະ</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">ລາຄາລວມ</span>
                    <span class="font-bold"><?= number_format((float)$order['subtotal'], 0) ?> ກີບ</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">ຄ່າຈັດສົ່ງ</span>
                    <span class="font-bold"><?= number_format((float)$order['shipping_fee'], 0) ?> ກີບ</span>
                </div>
                <?php if ((float)$order['discount'] > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">ສ່ວນຫຼຸດ</span>
                    <span class="font-bold text-red-500">-<?= number_format((float)$order['discount'], 0) ?> ກີບ</span>
                </div>
                <?php endif; ?>
                <div class="border-t pt-2 mt-2">
                    <div class="flex justify-between">
                        <span class="font-black text-foreground">ລວມທັງໝົດ</span>
                        <span class="text-xl font-black text-sky-600"><?= number_format((float)$order['grand_total'], 0) ?> ກີບ</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">ວິທີຊຳລະ</span>
                        <span class="font-bold"><?= $order['payment_method'] === 'cod' ? 'ເງິນສົດປາຍທາງ' : 'QR Code' ?></span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-muted-foreground">ສະຖານະ</span>
                        <span class="font-bold text-amber-600"><?= htmlspecialchars($order['order_status'] ?? 'Pending') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-card rounded-2xl border border-border p-6">
            <h3 class="text-lg font-black text-foreground mb-4">ຂໍ້ມູນຈັດສົ່ງ</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-muted-foreground">ຊື່:</span> <span class="font-bold"><?= htmlspecialchars($order['customer_name']) ?></span></p>
                <p><span class="text-muted-foreground">ເບີໂທ:</span> <span class="font-bold"><?= htmlspecialchars($order['customer_phone']) ?></span></p>
                <?php if (!empty($order['customer_email'])): ?>
                <p><span class="text-muted-foreground">ອີເມວ:</span> <span class="font-bold"><?= htmlspecialchars($order['customer_email']) ?></span></p>
                <?php endif; ?>
                <p><span class="text-muted-foreground">ທີ່ຢູ່:</span> <span class="font-bold"><?= htmlspecialchars($order['shipping_address']) ?></span></p>
                <?php if (!empty($order['shipping_latitude']) && !empty($order['shipping_longitude'])): ?>
                <div id="map-order" class="w-full h-40 rounded-xl border border-border mt-3 z-0"></div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var isDark = document.documentElement.classList.contains('dark');
                    var map = L.map('map-order', { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([<?= $order['shipping_latitude'] ?>, <?= $order['shipping_longitude'] ?>], 15);
                    L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OSM' }).addTo(map);
                    L.marker([<?= $order['shipping_latitude'] ?>, <?= $order['shipping_longitude'] ?>]).addTo(map);
                });
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
        <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-sky-700 transition-all shadow-lg shadow-sky-200">
            <i class="fas fa-shopping-bag"></i> ຊື້ສິນຄ້າເພີ່ມ
        </a>
        <a href="<?= url('/') ?>" class="inline-flex items-center gap-2 border border-border text-foreground/70 font-bold px-8 py-3.5 rounded-xl hover:bg-gray-50 transition-all">
            <i class="fas fa-home"></i> ກັບໄປໜ້າຫຼັກ
        </a>
    </div>

    <p class="text-center text-sm text-muted-foreground mt-6">ພວກເຮົາຈະຕິດຕໍ່ຫາທ່ານເພື່ອຢືນຢັນຄຳສັ່ງຊື້</p>
</div>
