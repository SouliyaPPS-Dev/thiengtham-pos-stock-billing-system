<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
        <a href="<?= url('/') ?>" class="hover:text-sky-600 transition-colors"><i class="fas fa-home"></i></a>
        <span>/</span>
        <span class="text-foreground/85 font-bold">ບັນຊີຂອງຂ້ອຍ</span>
    </div>

    <h1 class="text-2xl md:text-3xl font-black text-foreground mb-2">ບັນຊີຂອງຂ້ອຍ</h1>
    <p class="text-sm text-muted-foreground mb-8">ຈັດການຂໍ້ມູນສ່ວນຕົວ ແລະ ຕິດຕາມຄຳສັ່ງຊື້ຂອງທ່ານ</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Nav -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-2xl border border-border p-4 sticky top-28 space-y-1">
                <a href="#profile" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-sky-600 bg-sky-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600 text-xs"><i class="fas fa-user"></i></span>
                    ຂໍ້ມູນສ່ວນຕົວ
                </a>
                <a href="#address" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-foreground/70 hover:text-foreground hover:bg-gray-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fas fa-map-marker-alt"></i></span>
                    ທີ່ຢູ່
                </a>
                <a href="#orders" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-foreground/70 hover:text-foreground hover:bg-gray-50 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 text-xs"><i class="fas fa-box"></i></span>
                    ຄຳສັ່ງຊື້
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Profile Section -->
            <div id="profile" class="bg-card rounded-2xl border border-border p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-foreground">ຂໍ້ມູນສ່ວນຕົວ</h2>
                        <p class="text-xs text-muted-foreground">ແກ້ໄຂຂໍ້ມູນສ່ວນຕົວຂອງທ່ານ</p>
                    </div>
                </div>
                <form method="POST" action="<?= url('/account/update') ?>" class="space-y-4">
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຊື່ ແລະ ນາມສະກຸນ <span class="text-red-500">*</span></label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($customer['fullname'] ?? '') ?>" required class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເບີໂທລະສັບ <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars(preg_replace('/^\+856\s*/', '', $customer['phone'] ?? '')) ?>" required class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <input type="hidden" name="phone_prefix" value="+856">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ອີເມວ</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all active:scale-[0.98] text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> ບັນທຶກ
                    </button>
                </form>
            </div>

            <!-- Address Section -->
            <div id="address" class="bg-card rounded-2xl border border-border p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-foreground">ທີ່ຢູ່</h2>
                        <p class="text-xs text-muted-foreground">ຈັດການທີ່ຢູ່ຈັດສົ່ງຂອງທ່ານ</p>
                    </div>
                </div>
                <form method="POST" action="<?= url('/account/update') ?>" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ແຂວງ</label>
                            <input type="text" name="province" value="<?= htmlspecialchars($customer['province'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເມືອງ</label>
                            <input type="text" name="district" value="<?= htmlspecialchars($customer['district'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ບ້ານ</label>
                            <input type="text" name="village" value="<?= htmlspecialchars($customer['village'] ?? '') ?>" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ທີ່ຢູ່ແບບລະອຽດ</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຕຳແໜ່ງທີ່ຕັ້ງ (GPS)</label>
                        <div class="flex gap-2 mb-2">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs"></i>
                                <input type="text" id="map-search-account" placeholder="ຄົ້ນຫາສະຖານທີ່..." class="w-full pl-8 pr-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" oninput="searchLocation(this.value, 'account')">
                            </div>
                            <button type="button" onclick="getCurrentLocation('account')" class="px-3 py-2 bg-sky-50 text-sky-600 rounded-xl text-xs font-bold hover:bg-sky-100 transition-all whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-location-dot"></i> ຕຳແໜ່ງປັດຈຸບັນ
                            </button>
                        </div>
                        <div id="map-account" class="w-full h-56 rounded-xl border border-border z-0" x-init="initMapPicker('account', 'latitude', 'longitude')"></div>
                        <div class="flex gap-3 mt-2">
                            <input type="text" name="latitude" id="latitude" value="<?= htmlspecialchars($customer['latitude'] ?? '') ?>" readonly placeholder="ເສັ້ນຂວາງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                            <input type="text" name="longitude" id="longitude" value="<?= htmlspecialchars($customer['longitude'] ?? '') ?>" readonly placeholder="ເສັ້ນແວງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">ກົດເທິງແຜນທີ່ເພື່ອເລືອກຕຳແໜ່ງ</p>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all active:scale-[0.98] text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> ບັນທຶກທີ່ຢູ່
                    </button>
                </form>
            </div>

            <!-- Orders Section -->
            <div id="orders" class="bg-card rounded-2xl border border-border p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fas fa-box text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-foreground">ຄຳສັ່ງຊື້ຂອງຂ້ອຍ</h2>
                        <p class="text-xs text-muted-foreground">ຕິດຕາມສະຖານະຄຳສັ່ງຊື້ຂອງທ່ານ</p>
                    </div>
                </div>

                <?php if (empty($orders)): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-shopping-bag text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີຄຳສັ່ງຊື້</p>
                    <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-sky-600 text-white font-bold rounded-xl hover:bg-sky-700 transition-all text-sm">ເລີ່ມຊື້ເລີຍ</a>
                </div>
                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($orders as $o): ?>
                    <a href="<?= url('/order/' . $o['id']) ?>" class="block bg-gray-50/50 hover:bg-gray-100/70 rounded-xl p-4 border border-border transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono font-bold text-foreground text-sm">#<?= htmlspecialchars($o['order_number'] ?? str_pad($o['id'], 6, '0', STR_PAD_LEFT)) ?></span>
                            <?php
                            $os = strtolower($o['order_status'] ?? 'Pending');
                            $statusBadge = 'bg-gray-100 text-gray-600';
                            $statusLabel = 'ລໍຖ້າ';
                            if ($os === 'delivered') { $statusBadge = 'bg-emerald-100 text-emerald-600'; $statusLabel = 'ສົ່ງແລ້ວ'; }
                            elseif ($os === 'confirmed') { $statusBadge = 'bg-blue-100 text-blue-600'; $statusLabel = 'ຢືນຢັນ'; }
                            elseif ($os === 'processing') { $statusBadge = 'bg-indigo-100 text-indigo-600'; $statusLabel = 'ກຳລັງດຳເນີນ'; }
                            elseif ($os === 'shipped') { $statusBadge = 'bg-sky-100 text-sky-600'; $statusLabel = 'ຈັດສົ່ງ'; }
                            elseif ($os === 'cancelled') { $statusBadge = 'bg-red-100 text-red-600'; $statusLabel = 'ຍົກເລີກ'; }
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold <?= $statusBadge ?>">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <?= $statusLabel ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-muted-foreground"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?> • <?= (int)($o['items_count'] ?? 0) ?> ລາຍການ</span>
                            <span class="font-black text-foreground"><?= number_format((float)$o['grand_total'], 0) ?> ກີບ</span>
                        </div>
                        <?php if (!empty($o['shipping_latitude']) && !empty($o['shipping_longitude'])): ?>
                        <div class="mt-2 flex items-center gap-1.5 text-xs text-muted-foreground">
                            <i class="fas fa-map-marker-alt text-sky-500"></i>
                            <span>GPS: <?= $o['shipping_latitude'] ?>, <?= $o['shipping_longitude'] ?></span>
                        </div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
