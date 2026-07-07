<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ status: '<?= $order['order_status'] ?? 'Pending' ?>', polling: true }"
     x-init="if (polling) { setInterval(pollOrderStatus, 15000) }">

    <!-- Success Header -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-4xl text-emerald-500"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-foreground mb-2">ສັ່ງຊື້ສຳເລັດ!</h1>
        <p class="text-muted-foreground">ຂອບໃຈທີ່ສັ່ງຊື້ສິນຄ້າກັບພວກເຮົາ</p>
    </div>

    <!-- Order Number & Status -->
    <div class="bg-card rounded-2xl border border-border p-6 mb-6 text-center">
        <p class="text-sm text-muted-foreground mb-1">ເລກທີຄຳສັ່ງຊື້</p>
        <p class="text-2xl font-black text-sky-600">#<?= htmlspecialchars($order['order_number']) ?></p>
        <div class="mt-3 flex items-center justify-center gap-2">
            <span id="order-status-badge" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                <?php
                $os = $order['order_status'] ?? 'Pending';
                if ($os === 'Delivered') echo 'bg-emerald-100 text-emerald-700';
                elseif ($os === 'Confirmed') echo 'bg-blue-100 text-blue-700';
                elseif ($os === 'Processing') echo 'bg-indigo-100 text-indigo-700';
                elseif ($os === 'Shipped') echo 'bg-sky-100 text-sky-700';
                elseif ($os === 'Cancelled') echo 'bg-red-100 text-red-700';
                else echo 'bg-amber-100 text-amber-700';
                ?>">
                <span class="w-2 h-2 rounded-full
                    <?php
                    if ($os === 'Delivered') echo 'bg-emerald-500';
                    elseif ($os === 'Confirmed') echo 'bg-blue-500';
                    elseif ($os === 'Processing') echo 'bg-indigo-500';
                    elseif ($os === 'Shipped') echo 'bg-sky-500';
                    elseif ($os === 'Cancelled') echo 'bg-red-500';
                    else echo 'bg-amber-500';
                    ?>"></span>
                <?php
                $labels = ['Pending' => 'ລໍຖ້າ', 'Confirmed' => 'ຢືນຢັນ', 'Processing' => 'ກຳລັງດຳເນີນ', 'Shipped' => 'ຈັດສົ່ງ', 'Delivered' => 'ສົ່ງແລ້ວ', 'Cancelled' => 'ຍົກເລີກ'];
                echo $labels[$os] ?? $os;
                ?>
            </span>
        </div>
        <p class="text-xs text-muted-foreground mt-2"><i class="fas fa-sync-alt mr-1"></i>ສະຖານະອັບເດດອັດຕະໂນມັດທຸກ 15 ວິນາທີ</p>
    </div>

    <!-- Tracking Timeline -->
    <?php
    $steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
    $stepLabels = ['Pending' => 'ລໍຖ້າ', 'Confirmed' => 'ຢືນຢັນ', 'Processing' => 'ກຳລັງດຳເນີນ', 'Shipped' => 'ຈັດສົ່ງ', 'Delivered' => 'ສົ່ງແລ້ວ'];
    $currentIdx = array_search($order['order_status'] ?? 'Pending', $steps);
    $isCancelled = $order['order_status'] === 'Cancelled';
    ?>
    <div class="bg-card rounded-2xl border border-border p-6 mb-6">
        <h3 class="text-sm font-black text-foreground mb-5 flex items-center gap-2">
            <i class="fas fa-map-signs text-sky-500"></i> ຕິດຕາມສະຖານະ
        </h3>
        <ul id="tracking-timeline" class="flex items-center gap-0">
            <?php foreach ($steps as $idx => $step):
                $done = $idx <= $currentIdx && !$isCancelled;
                $active = $idx === $currentIdx && !$isCancelled;
            ?>
            <li class="flex-1 flex flex-col items-center relative">
                <div class="w-full flex items-center">
                    <?php if ($idx > 0): ?>
                    <div class="connector-left flex-1 h-0.5 <?= $idx <= $currentIdx && !$isCancelled ? 'bg-sky-400' : 'bg-gray-200' ?>"></div>
                    <?php endif; ?>
                    <div class="timeline-circle w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-black flex-shrink-0 ring-4
                        <?= $active ? 'bg-sky-500 text-white ring-sky-200' : ($done ? 'bg-sky-500 text-white ring-sky-200' : ($isCancelled && $idx === 0 ? 'bg-red-400 text-white ring-red-100' : 'bg-gray-300 text-white ring-gray-100')) ?>">
                        <?php if ($isCancelled && $idx === 0): ?>
                        <i class="fas fa-times text-[10px]"></i>
                        <?php elseif ($done): ?>
                        <i class="fas fa-check text-[10px]"></i>
                        <?php else: ?>
                        <?= $idx + 1 ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($idx < count($steps) - 1): ?>
                    <div class="connector-right flex-1 h-0.5 <?= $idx < $currentIdx && !$isCancelled ? 'bg-sky-400' : 'bg-gray-200' ?>"></div>
                    <?php endif; ?>
                </div>
                <span class="timeline-label text-[11px] mt-1.5 whitespace-nowrap
                    <?= $active ? 'text-sky-600 font-black' : ($done ? 'text-sky-600 font-black' : ($isCancelled && $idx === 0 ? 'text-red-500 font-black' : 'text-gray-400 font-medium')) ?>">
                    <?= $stepLabels[$step] ?>
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
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
                        <span class="text-muted-foreground">ສະຖານະຊຳລະ</span>
                        <span class="font-bold <?= ($order['payment_status'] ?? 'Pending') === 'Paid' ? 'text-emerald-600' : 'text-amber-600' ?>">
                            <?= ($order['payment_status'] ?? 'Pending') === 'Paid' ? 'ຊຳລະແລ້ວ' : 'ລໍຖ້າຊຳລະ' ?>
                        </span>
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
                    L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>' }).addTo(map);
                    L.marker([<?= $order['shipping_latitude'] ?>, <?= $order['shipping_longitude'] ?>]).addTo(map);
                });
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- WhatsApp Order Notification -->
    <?php
    $wa_number = get_store_whatsapp();
    $wa_number_clean = preg_replace('/[^0-9]/', '', $wa_number);
    if ($wa_number_clean):
        $items_list = '';
        foreach ($items as $idx => $item) {
            $items_list .= ($idx + 1) . '. ' . htmlspecialchars($item['product_name']) . ' x' . (int)$item['quantity'] . ' = ' . number_format((float)$item['subtotal'], 0) . " ກີບ\n";
        }
        $wa_message = "ສະບາຍດີ, ຂ້ອຍຕ້ອງການສັ່ງຊື້ສິນຄ້າ\n\n";
        $wa_message .= "ລິ້ງຄຳສັ່ງຊື້ (Admin):\n" . url('/admin/orders/' . $order['id']) . "\n\n";
        $wa_message .= "ເລກທີຄຳສັ່ງ: #{$order['order_number']}\n";
        $wa_message .= "ຊື່: {$order['customer_name']}\n";
        $wa_message .= "ເບີໂທ: {$order['customer_phone']}\n";
        if (!empty($order['customer_email'])) $wa_message .= "ອີເມວ: {$order['customer_email']}\n";
        $wa_message .= "\n--- ລາຍການສິນຄ້າ ---\n{$items_list}\n";
        $wa_message .= "ລວມທັງໝົດ: " . number_format((float)$order['grand_total'], 0) . " ກີບ\n";
        $wa_message .= "ຄ່າຈັດສົ່ງ: " . number_format((float)$order['shipping_fee'], 0) . " ກີບ\n";
        if ((float)$order['discount'] > 0) $wa_message .= "ສ່ວນຫຼຸດ: -" . number_format((float)$order['discount'], 0) . " ກີບ\n";
        $wa_message .= "ວິທີຊຳລະ: " . ($order['payment_method'] === 'cod' ? 'ເງິນສົດປາຍທາງ' : 'QR Code') . "\n";
        $wa_message .= "\n--- ທີ່ຢູ່ຈັດສົ່ງ ---\n{$order['shipping_address']}";
        if (!empty($order['shipping_province'])) $wa_message .= ", {$order['shipping_province']}";
        if (!empty($order['shipping_district'])) $wa_message .= ", {$order['shipping_district']}";
        if (!empty($order['shipping_village'])) $wa_message .= ", {$order['shipping_village']}";
        $wa_url = 'https://wa.me/' . $wa_number_clean . '?text=' . urlencode($wa_message);
    ?>
    <div class="text-center mt-6">
        <a href="<?= url('/order/' . $order['id']) ?>" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-200">
            <i class="fa-brands fa-whatsapp text-lg"></i> ສົ່ງຂໍ້ມູນຄຳສັ່ງຊື້ຜ່ານ WhatsApp
        </a>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6">
        <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-sky-700 transition-all shadow-lg shadow-sky-200">
            <i class="fas fa-shopping-bag"></i> ຊື້ສິນຄ້າເພີ່ມ
        </a>
        <a href="<?= url('/account#orders') ?>" class="inline-flex items-center gap-2 border border-border text-foreground/70 font-bold px-8 py-3.5 rounded-xl hover:bg-gray-50 transition-all">
            <i class="fas fa-box"></i> ຄຳສັ່ງຊື້ຂອງຂ້ອຍ
        </a>
        <a href="<?= url('/') ?>" class="inline-flex items-center gap-2 border border-border text-foreground/70 font-bold px-8 py-3.5 rounded-xl hover:bg-gray-50 transition-all">
            <i class="fas fa-home"></i> ກັບໄປໜ້າຫຼັກ
        </a>
    </div>

    <p class="text-center text-sm text-muted-foreground mt-6">ພວກເຮົາຈະຕິດຕໍ່ຫາທ່ານເພື່ອຢືນຢັນຄຳສັ່ງຊື້</p>
</div>

<script>
function pollOrderStatus() {
    fetch('<?= url('/order/' . $order['id'] . '/status') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                status = data.order_status;
                let badge = document.getElementById('order-status-badge');
                let labels = { 'Pending': 'ລໍຖ້າ', 'Confirmed': 'ຢືນຢັນ', 'Processing': 'ກຳລັງດຳເນີນ', 'Shipped': 'ຈັດສົ່ງ', 'Delivered': 'ສົ່ງແລ້ວ', 'Cancelled': 'ຍົກເລີກ' };
                let colors = { 'Pending': 'bg-amber-100 text-amber-700', 'Confirmed': 'bg-blue-100 text-blue-700', 'Processing': 'bg-indigo-100 text-indigo-700', 'Shipped': 'bg-sky-100 text-sky-700', 'Delivered': 'bg-emerald-100 text-emerald-700', 'Cancelled': 'bg-red-100 text-red-700' };
                let dotColors = { 'Pending': 'bg-amber-500', 'Confirmed': 'bg-blue-500', 'Processing': 'bg-indigo-500', 'Shipped': 'bg-sky-500', 'Delivered': 'bg-emerald-500', 'Cancelled': 'bg-red-500' };
                if (badge) {
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full ' + (dotColors[data.order_status] || 'bg-gray-400') + '"></span> ' + (labels[data.order_status] || data.order_status);
                    badge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold ' + (colors[data.order_status] || 'bg-gray-100 text-gray-600');
                }
                let steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
                let current = steps.indexOf(data.order_status);
                document.querySelectorAll('#tracking-timeline li').forEach((li, idx) => {
                    let circle = li.querySelector('.timeline-circle');
                    let conn1 = li.querySelector('.connector-left');
                    let conn2 = li.querySelector('.connector-right');
                    let label = li.querySelector('.timeline-label');
                    if (idx < current) {
                        circle?.classList.add('bg-sky-500', 'text-white', 'ring-sky-200');
                        circle?.classList.remove('bg-gray-300', 'text-gray-400', 'ring-gray-100', 'bg-red-400', 'ring-red-100');
                        circle.innerHTML = '<i class="fas fa-check text-[8px]"></i>';
                        label?.classList.add('text-sky-600', 'font-black');
                        label?.classList.remove('text-gray-400', 'font-medium');
                        if (conn1) conn1.className = conn1.className.replace('bg-gray-200', 'bg-sky-400');
                        if (conn2) conn2.className = conn2.className.replace('bg-gray-200', 'bg-sky-400');
                    } else if (idx === current) {
                        circle?.classList.add('bg-sky-500', 'text-white', 'ring-sky-200');
                        circle?.classList.remove('bg-gray-300', 'text-gray-400', 'ring-gray-100', 'bg-red-400', 'ring-red-100');
                        circle.innerHTML = '<i class="fas fa-check text-[8px]"></i>';
                        label?.classList.add('text-sky-600', 'font-black');
                        label?.classList.remove('text-gray-400', 'font-medium');
                    } else {
                        circle?.classList.add('bg-gray-300', 'text-white', 'ring-gray-100');
                        circle?.classList.remove('bg-sky-500', 'text-white', 'ring-sky-200');
                        circle.innerHTML = (idx + 1);
                        label?.classList.add('text-gray-400', 'font-medium');
                        label?.classList.remove('text-sky-600', 'font-black');
                    }
                    if (data.order_status === 'Cancelled') {
                        if (idx === 0) {
                            circle?.classList.add('bg-red-400', 'text-white', 'ring-red-100');
                            circle?.classList.remove('bg-sky-500', 'bg-gray-300', 'ring-sky-200', 'ring-gray-100');
                            circle.innerHTML = '<i class="fas fa-times text-[8px]"></i>';
                            label?.classList.add('text-red-500', 'font-black');
                            label?.classList.remove('text-gray-400', 'font-medium', 'text-sky-600');
                        } else {
                            circle?.classList.add('bg-gray-300', 'text-white', 'ring-gray-100');
                            circle?.classList.remove('bg-sky-500', 'text-white', 'ring-sky-200');
                            circle.innerHTML = (idx + 1);
                            label?.classList.add('text-gray-400', 'font-medium');
                            label?.classList.remove('text-sky-600', 'font-black', 'text-red-500');
                        }
                    }
                });
            }
        }).catch(() => {});
}
</script>
