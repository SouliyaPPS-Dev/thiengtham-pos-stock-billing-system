<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
        <a href="<?= url('/') ?>" class="hover:text-sky-600 transition-colors"><i class="fas fa-home"></i></a>
        <span>/</span>
        <a href="<?= url('/cart') ?>" class="hover:text-sky-600 transition-colors">ກະຕ່າສິນຄ້າ</a>
        <span>/</span>
        <span class="text-foreground/85 font-bold">ຊຳລະເງິນ</span>
    </div>

    <h1 class="text-2xl md:text-3xl font-black text-foreground mb-8">ຊຳລະເງິນ</h1>

    <?php if (empty($cart)): ?>
    <div class="text-center py-20">
        <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
        </div>
        <h3 class="text-lg font-bold text-foreground mb-2">ກະຕ່າສິນຄ້າວ່າງເປົ່າ</h3>
        <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-sky-700 transition-all">ເລີ່ມຊື້ເລີຍ</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form id="checkout-form" action="<?= url('/checkout/process') ?>" method="POST" class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-card rounded-2xl border border-border p-6">
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600 text-sm"><i class="fas fa-user"></i></span>
                        ຂໍ້ມູນຜູ້ຮັບສິນຄ້າ
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຊື່ ແລະ ນາມສະກຸນ <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" value="<?= htmlspecialchars($customer['fullname'] ?? '') ?>" required class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເບີໂທລະສັບ <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>" required class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ອີເມວ</label>
                            <input type="email" name="customer_email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-card rounded-2xl border border-border p-6">
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fas fa-map-marker-alt"></i></span>
                        ທີ່ຢູ່ຈັດສົ່ງ
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ແຂວງ</label>
                            <input type="text" name="shipping_province" value="<?= htmlspecialchars($customer['province'] ?? 'ນະຄອນຫຼວງວຽງຈັນ') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເມືອງ</label>
                            <input type="text" name="shipping_district" value="<?= htmlspecialchars($customer['district'] ?? 'ໄຊເສດຖາ') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ບ້ານ</label>
                            <input type="text" name="shipping_village" value="<?= htmlspecialchars($customer['village'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ທີ່ຢູ່ແບບລະອຽດ <span class="text-red-500">*</span></label>
                        <textarea name="shipping_address" rows="2" required class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"><?= htmlspecialchars($customer['address'] ?? 'ຖະໜົນ ດາວທຽມ ປະສົມ, ເມືອງໄຊເສດຖາ, ນະຄອນຫຼວງວຽງຈັນ') ?></textarea>
                    </div>
            </div>

            <!-- GPS Location (admin-style card) -->
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
                <div id="map-checkout" class="w-full h-72 rounded-xl border border-border z-10"></div>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex gap-3">
                        <span class="text-xs text-muted-foreground">ເສັ້ນຂວາງ: <span id="lat-display" class="font-bold text-foreground"><?= htmlspecialchars($customer['latitude'] ?? '17.977') ?></span></span>
                        <span class="text-xs text-muted-foreground">ເສັ້ນແວງ: <span id="lng-display" class="font-bold text-foreground"><?= htmlspecialchars($customer['longitude'] ?? '102.639') ?></span></span>
                    </div>
                    <a id="gmaps-link" href="https://www.google.com/maps?q=<?= htmlspecialchars($customer['latitude'] ?? '17.977') ?>,<?= htmlspecialchars($customer['longitude'] ?? '102.639') ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-[11px] font-bold transition-all">
                        <i class="fab fa-google"></i> Google Maps
                    </a>
                </div>
                <p class="text-xs text-muted-foreground mt-1">ກົດເທິງແຜນທີ່ເພື່ອເລືອກຕຳແໜ່ງ</p>
                <input type="hidden" name="shipping_latitude" id="shipping_latitude" value="<?= htmlspecialchars($customer['latitude'] ?? '17.977') ?>">
                <input type="hidden" name="shipping_longitude" id="shipping_longitude" value="<?= htmlspecialchars($customer['longitude'] ?? '102.639') ?>">
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var lat = parseFloat(document.getElementById('shipping_latitude').value) || 17.977;
                    var lng = parseFloat(document.getElementById('shipping_longitude').value) || 102.639;
                    var el = document.getElementById('map-checkout');
                    if (!el || typeof L === 'undefined') return;
                    
                    var isDark = document.documentElement.classList.contains('dark');
                    var map = L.map(el, { zoomControl: true }).setView([lat, lng], 15);
                    
                    L.tileLayer(
                        isDark 
                            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' 
                            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
                        { 
                            maxZoom: 19, 
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' 
                        }
                    ).addTo(map);
                    
                    var marker = L.marker([lat, lng]).addTo(map);
                    
                    map.on('click', function(e) {
                        marker.setLatLng(e.latlng);
                        document.getElementById('shipping_latitude').value = e.latlng.lat.toFixed(6);
                        document.getElementById('shipping_longitude').value = e.latlng.lng.toFixed(6);
                        document.getElementById('lat-display').textContent = e.latlng.lat.toFixed(6);
                        document.getElementById('lng-display').textContent = e.latlng.lng.toFixed(6);
                        document.getElementById('gmaps-link').href = 'https://www.google.com/maps?q=' + e.latlng.lat.toFixed(6) + ',' + e.latlng.lng.toFixed(6);
                    });
                });
                </script>
            </div>

                <!-- Payment Method -->
                <div class="bg-card rounded-2xl border border-border p-6">
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-sm"><i class="fas fa-credit-card"></i></span>
                        ວິທີການຊຳລະເງິນ
                    </h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border border-border rounded-xl cursor-pointer hover:border-sky-200 transition-all has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                            <input type="radio" name="payment_method" value="cod" checked class="accent-sky-600">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-foreground">ເງິນສົດປາຍທາງ (COD)</p>
                                <p class="text-xs text-muted-foreground">ຊຳລະເມື່ອໄດ້ຮັບສິນຄ້າ</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-border rounded-xl cursor-pointer hover:border-sky-200 transition-all has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                            <input type="radio" name="payment_method" value="qr" class="accent-sky-600">
                            <div class="w-10 h-10 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-foreground">QR Code</p>
                                <p class="text-xs text-muted-foreground">ສະແກນ QR ຈ່າຍຜ່ານແອັບທະນາຄານ</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-card rounded-2xl border border-border p-6">
                    <h3 class="text-lg font-black text-foreground mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-foreground/70 text-sm"><i class="fas fa-sticky-note"></i></span>
                        ຫມາຍເຫດ (ບໍ່ບັງຄັບ)
                    </h3>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm" placeholder="ຂໍ້ຄວາມເພີ່ມເຕີມ..."></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all active:scale-[0.98] text-lg shadow-lg shadow-sky-200 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> ສັ່ງຊື້
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-2xl border border-border p-6 sticky top-28">
                <h3 class="text-lg font-black text-foreground mb-4">ສະຫຼຸບຄຳສັ່ງຊື້</h3>
                <div class="space-y-4 max-h-80 overflow-y-auto mb-4">
                    <?php foreach ($cart as $item): ?>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-gray-50 overflow-hidden flex-shrink-0">
                            <?php if (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-foreground truncate"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-xs text-muted-foreground">x<?= (int)($item['quantity'] ?? 0) ?></p>
                        </div>
                        <span class="text-sm font-black text-foreground"><?= number_format((float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1), 0) ?> ກີບ</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="border-t border-border pt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">ລາຄາລວມ</span>
                        <span class="font-bold"><?= number_format((float)$subtotal, 0) ?> ກີບ</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">ຄ່າຈັດສົ່ງ</span>
                        <span class="font-bold text-emerald-600">ຟຣີ</span>
                    </div>
                    <div class="border-t border-border pt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-black text-foreground">ລວມທັງໝົດ</span>
                            <span class="text-xl font-black text-sky-600"><?= number_format((float)$grandTotal, 0) ?> ກີບ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
