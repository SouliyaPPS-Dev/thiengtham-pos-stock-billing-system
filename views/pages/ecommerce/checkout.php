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
                    <div class="mt-4">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຕຳແໜ່ງທີ່ຕັ້ງ (GPS)</label>
                        <div class="flex gap-2 mb-2">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs"></i>
                                <input type="text" id="map-search-checkout" placeholder="ຄົ້ນຫາສະຖານທີ່..." class="w-full pl-8 pr-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" oninput="searchLocation(this.value, 'checkout')">
                            </div>
                            <button type="button" onclick="getCurrentLocation('checkout')" class="px-3 py-2 bg-sky-50 text-sky-600 rounded-xl text-xs font-bold hover:bg-sky-100 transition-all whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-location-dot"></i> ຕຳແໜ່ງປັດຈຸບັນ
                            </button>
                        </div>
                        <div id="map-checkout" class="w-full h-72 rounded-xl border border-border z-0" x-init="initMapPicker('checkout', 'shipping_latitude', 'shipping_longitude')"></div>
                        <div class="flex gap-3 mt-2">
                            <input type="text" name="shipping_latitude" id="shipping_latitude" value="17.977000" readonly placeholder="ເສັ້ນຂວາງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                            <input type="text" name="shipping_longitude" id="shipping_longitude" value="102.639000" readonly placeholder="ເສັ້ນແວງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">ກົດເທິງແຜນທີ່ເພື່ອເລືອກຕຳແໜ່ງ</p>
                    </div>
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
