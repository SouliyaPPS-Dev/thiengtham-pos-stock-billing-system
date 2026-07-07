<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: window.location.hash.replace('#', '') || 'profile' }">

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
                <a href="#profile" @click.prevent="activeTab = 'profile'; window.location.hash = 'profile'"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                   :class="activeTab === 'profile' ? 'text-sky-600 bg-sky-50' : 'text-foreground/70 hover:text-foreground hover:bg-gray-50'">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs"
                          :class="activeTab === 'profile' ? 'bg-sky-100 text-sky-600' : 'bg-gray-100 text-muted-foreground'">
                        <i class="fas fa-user"></i>
                    </span>
                    ຂໍ້ມູນສ່ວນຕົວ
                </a>
                <a href="#address" @click.prevent="activeTab = 'address'; window.location.hash = 'address'; $nextTick(() => { if (!window['__map_account']) initMapPicker('account', 'latitude', 'longitude'); })"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                   :class="activeTab === 'address' ? 'text-sky-600 bg-sky-50' : 'text-foreground/70 hover:text-foreground hover:bg-gray-50'">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs"
                          :class="activeTab === 'address' ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-muted-foreground'">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    ທີ່ຢູ່
                </a>
                <a href="#orders" @click.prevent="activeTab = 'orders'; window.location.hash = 'orders'"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                   :class="activeTab === 'orders' ? 'text-sky-600 bg-sky-50' : 'text-foreground/70 hover:text-foreground hover:bg-gray-50'">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs"
                          :class="activeTab === 'orders' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-muted-foreground'">
                        <i class="fas fa-box"></i>
                    </span>
                    ຄຳສັ່ງຊື້
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">

            <!-- ===== PROFILE TAB ===== -->
            <div x-show="activeTab === 'profile'" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-card rounded-2xl border border-border p-6 md:p-8">
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
            </div>

            <!-- ===== ADDRESS TAB ===== -->
            <div x-show="activeTab === 'address'" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-card rounded-2xl border border-border p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-foreground">ທີ່ຢູ່</h2>
                            <p class="text-xs text-muted-foreground">ຈັດການທີ່ຢູ່ຈັດສົ່ງຂອງທ່ານ</p>
                        </div>
                    </div>
                    <form method="POST" action="<?= url('/account/update') ?>" class="space-y-4"
                          x-data="addressPicker({
                             province: '<?= htmlspecialchars($customer['province'] ?? '') ?>',
                             district: '<?= htmlspecialchars($customer['district'] ?? '') ?>',
                             village: '<?= htmlspecialchars($customer['village'] ?? '') ?>'
                          })">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Province -->
                            <div class="relative" @click.outside="provinceOpen = false">
                                <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ແຂວງ</label>
                                <div class="relative">
                                    <input type="text" x-model="provinceSearch" @focus="provinceOpen = true; provinceSearch = ''" @input="provinceOpen = true"
                                           placeholder="ຄົ້ນຫາແຂວງ..." autocomplete="off"
                                           class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm pr-10">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs" x-show="!provinceOpen">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                                <template x-if="provinceOpen && filteredProvinces.length">
                                    <div class="absolute z-50 mt-1 w-full bg-card border border-border rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                        <template x-for="p in filteredProvinces" :key="p">
                                            <div @click="selectProvince(p)" class="px-4 py-2.5 cursor-pointer text-sm hover:bg-primary/10 hover:text-primary transition-colors"
                                                 :class="p === province ? 'bg-primary/10 text-primary font-bold' : 'text-foreground'">
                                                <span x-text="p"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <input type="hidden" name="province" x-model="province">
                            </div>
                            <!-- District -->
                            <div class="relative" @click.outside="districtOpen = false">
                                <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເມືອງ</label>
                                <div class="relative">
                                    <input type="text" x-model="districtSearch" @focus="districtOpen = true; districtSearch = ''" @input="districtOpen = true"
                                           placeholder="ຄົ້ນຫາເມືອງ..." autocomplete="off"
                                           class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm pr-10">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs" x-show="!districtOpen">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                                <template x-if="districtOpen && filteredDistricts.length">
                                    <div class="absolute z-50 mt-1 w-full bg-card border border-border rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                        <template x-for="d in filteredDistricts" :key="d">
                                            <div @click="selectDistrict(d)" class="px-4 py-2.5 cursor-pointer text-sm hover:bg-primary/10 hover:text-primary transition-colors"
                                                 :class="d === district ? 'bg-primary/10 text-primary font-bold' : 'text-foreground'">
                                                <span x-text="d"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <input type="hidden" name="district" x-model="district">
                            </div>
                            <!-- Village -->
                            <div class="relative" @click.outside="villageOpen = false">
                                <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ບ້ານ</label>
                                <div class="relative">
                                    <input type="text" x-model="villageSearch" @focus="villageOpen = true" @input="searchVillage($el.value)"
                                           placeholder="ຄົ້ນຫາບ້ານ..." autocomplete="off"
                                           class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm pr-10">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs" x-show="villageLoading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                <template x-if="villageOpen && villageResults.length">
                                    <div class="absolute z-50 mt-1 w-full bg-card border border-border rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                        <template x-for="v in villageResults" :key="v">
                                            <div @click="selectVillage(v)" class="px-4 py-2.5 cursor-pointer text-sm hover:bg-primary/10 hover:text-primary transition-colors"
                                                 :class="v === village ? 'bg-primary/10 text-primary font-bold' : 'text-foreground'">
                                                <span x-text="v"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <input type="hidden" name="village" x-model="village">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ທີ່ຢູ່ແບບລະອຽດ</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຕຳແໜ່ງທີ່ຕັ້ງ GPS ຂອງທີ່ຢູ່ຈັດສົ່ງ</label>
                            <div class="flex gap-2 mb-2">
                                <div class="relative flex-1">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs"></i>
                                    <input type="text" id="map-search-account" placeholder="ຄົ້ນຫາສະຖານທີ່..." class="w-full pl-8 pr-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" oninput="searchLocation(this.value, 'account')">
                                </div>
                                <button type="button" onclick="getCurrentLocation('account')" class="px-3 py-2 bg-sky-50 text-sky-600 rounded-xl text-xs font-bold hover:bg-sky-100 transition-all whitespace-nowrap flex items-center gap-1.5">
                                    <i class="fas fa-location-dot"></i> ຕຳແໜ່ງປັດຈຸບັນ
                                </button>
                            </div>
                            <div id="map-account" class="w-full h-56 rounded-xl border border-border relative z-0" x-init="if (activeTab === 'address') $nextTick(() => initMapPicker('account', 'latitude', 'longitude'))"></div>
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
            </div>

            <!-- ===== ORDERS TAB ===== -->
            <div x-show="activeTab === 'orders'" x-cloak
                 x-data="{ polling: true }"
                 x-init="if (polling) { setInterval(pollOrderStatus, 15000) }"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-card rounded-2xl border border-border p-6 md:p-8">
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
                        <?php foreach ($orders as $o):
                            $os = strtolower($o['order_status'] ?? 'Pending');
                            $statusBadge = 'bg-gray-100 text-gray-600';
                            $statusDot = 'bg-gray-400';
                            $statusLabel = 'ລໍຖ້າ';
                            if ($os === 'delivered') { $statusBadge = 'bg-emerald-100 text-emerald-600'; $statusDot = 'bg-emerald-500'; $statusLabel = 'ສົ່ງແລ້ວ'; }
                            elseif ($os === 'confirmed') { $statusBadge = 'bg-blue-100 text-blue-600'; $statusDot = 'bg-blue-500'; $statusLabel = 'ຢືນຢັນ'; }
                            elseif ($os === 'processing') { $statusBadge = 'bg-indigo-100 text-indigo-600'; $statusDot = 'bg-indigo-500'; $statusLabel = 'ກຳລັງດຳເນີນ'; }
                            elseif ($os === 'shipped') { $statusBadge = 'bg-sky-100 text-sky-600'; $statusDot = 'bg-sky-500'; $statusLabel = 'ຈັດສົ່ງ'; }
                            elseif ($os === 'cancelled') { $statusBadge = 'bg-red-100 text-red-600'; $statusDot = 'bg-red-500'; $statusLabel = 'ຍົກເລີກ'; }
                        ?>
                        <div class="bg-gray-50/50 rounded-xl border border-border overflow-hidden">
                            <!-- Order Header -->
                            <a href="<?= url('/order/' . $o['id']) ?>" class="block p-4 hover:bg-gray-100/70 transition-all">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono font-bold text-foreground text-sm">#<?= htmlspecialchars($o['order_number'] ?? str_pad($o['id'], 6, '0', STR_PAD_LEFT)) ?></span>
                                    <span id="status-badge-<?= (int)$o['id'] ?>" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold <?= $statusBadge ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $statusDot ?>"></span>
                                        <?= $statusLabel ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?> • <?= (int)($o['items_count'] ?? 0) ?> ລາຍການ</span>
                                    <span class="font-black text-foreground"><?= number_format((float)$o['grand_total'], 0) ?> ກີບ</span>
                                </div>
                            </a>
                            <!-- Tracking Timeline -->
                            <div class="px-4 pb-4" id="timeline-<?= (int)$o['id'] ?>">
                                <?php
                                $steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
                                $stepLabels = ['Pending' => 'ລໍຖ້າ', 'Confirmed' => 'ຢືນຢັນ', 'Processing' => 'ກຳລັງດຳເນີນ', 'Shipped' => 'ຈັດສົ່ງ', 'Delivered' => 'ສົ່ງແລ້ວ'];
                                $currentIdx = array_search($o['order_status'] ?? 'Pending', $steps);
                                $isCancelled = strtolower($o['order_status'] ?? '') === 'cancelled';
                                ?>
                                <ul class="flex items-center gap-0">
                                    <?php foreach ($steps as $idx => $step):
                                        $done = $idx <= $currentIdx && !$isCancelled;
                                        $active = $idx === $currentIdx && !$isCancelled;
                                    ?>
                                    <li class="flex-1 flex flex-col items-center relative">
                                        <div class="w-full flex items-center">
                                            <?php if ($idx > 0): ?>
                                            <div class="flex-1 h-0.5 <?= $idx <= $currentIdx && !$isCancelled ? 'bg-sky-400' : 'bg-gray-200' ?>"></div>
                                            <?php endif; ?>
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black flex-shrink-0 ring-4 <?= $active ? 'bg-sky-500 text-white ring-sky-200' : ($done ? 'bg-sky-500 text-white ring-sky-200' : ($isCancelled && $idx === 0 ? 'bg-red-400 text-white ring-red-100' : 'bg-gray-300 text-white ring-gray-100')) ?>">
                                                <?php if ($isCancelled && $idx === 0): ?>
                                                <i class="fas fa-times"></i>
                                                <?php elseif ($done): ?>
                                                <i class="fas fa-check"></i>
                                                <?php else: ?>
                                                <?= $idx + 1 ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($idx < count($steps) - 1): ?>
                                            <div class="flex-1 h-0.5 <?= $idx < $currentIdx && !$isCancelled ? 'bg-sky-400' : 'bg-gray-200' ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-[10px] mt-1.5 whitespace-nowrap <?= $active ? 'text-sky-600 font-black' : ($done ? 'text-sky-600 font-black' : 'text-gray-400 font-medium') ?>"><?= $stepLabels[$step] ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
            var LAOS_ADDRESSES = {
            "ນະຄອນຫຼວງວຽງຈັນ": ["ຈັນທະບູລີ", "ສີໂຄດຕະບອງ", "ໄຊເສດຖາ", "ສີສັດຕະນາກ", "ນາກຊຽງທອງ", "ຫາດຊາຍຟອງ", "ສັງທອງ", "ປາກງື່ມ", "ໄຊທານີ"],
            "ຜົ້ງສາລີ": ["ຜົ້ງສາລີ", "ໃໝ່", "ຂວາ", "ສຳເໜືອ", "ບຸນເໜືອ", "ຍອດອູ", "ບຸນໃຕ້"],
            "ຫຼວງນ້ຳທາ": ["ຫຼວງນ້ຳທາ", "ສິງ", "ລອງ", "ວຽງພູຄາ", "ນາແລ"],
            "ອຸດົມໄຊ": ["ໄຊ", "ຫຼາ", "ນາໝໍ້", "ງາ", "ແບ່ງ", "ຮຸນ", "ປົກ"],
            "ບໍ່ແກ້ວ": ["ຫ້ວຍຊາຍ", "ຕົ້ນເຜິ້ງ", "ປາກທາ", "ຜາອຸດົມ", "ເມືອງ"],
            "ຫຼວງພະບາງ": ["ຫຼວງພະບາງ", "ຊຽງເງິນ", "ນານ", "ປາກອູ", "ນ້ຳບາກ", "ງອຍ", "ປົ່ນ", "ພູທອນ", "ໜອງ", "ວຽງຄຳ", "ຊົມເພັດ"],
            "ໄຊຍະບູລີ": ["ໄຊຍະບູລີ", "ຄອບ", "ຫົງສາ", "ເງິນ", "ຊຽງຮ່ອນ", "ພຽງ", "ປາກລາຍ", "ແກ່ນທ້າວ", "ບໍ່ແກ່ນ", "ທົ່ງມີໄຊ"],
            "ຊຽງຂວາງ": ["ແປກ", "ຄຳ", "ໜອງແຮດ", "ຄູນ", "ໝອກ", "ພູກູດ", "ພວານ"],
            "ວຽງຈັນ": ["ໂພນໂຮງ", "ທຸລະຄົມ", "ແກ້ວອຸດົມ", "ກາສີ", "ວັງວຽງ", "ເຟືອງ", "ຊະນະຄາມ", "ແມດ", "ວຽງຄຳ", "ຫີນເໝືອງ", "ໝື່ນ"],
            "ບໍລິຄຳໄຊ": ["ປາກຊັນ", "ທ່າພະບາດ", "ປາກກະດິງ", "ບໍລິຄັນ", "ວຽງທອງ", "ໄຊຈຳພອນ"],
            "ຄຳມ່ວນ": ["ທ່າແຂກ", "ມະຫາໄຊ", "ໜອງບົກ", "ຫີນບູນ", "ຍົມມະລາດ", "ບົວລະພາ", "ນາກາຍ", "ຊຽງບົວທອງ", "ຄູນຄຳ"],
            "ສະຫວັນນະເຂດ": ["ໄກສອນ ພົມວິຫານ", "ອຸດູມພອນ", "ອາດສະພັງທອງ", "ພີນ", "ເຊໂປນ", "ນອງ", "ທ່າປາງທອງ", "ສອງຄອນ", "ຈຳພອນ", "ຊົນບູລີ", "ໄຊບູລີ", "ວຽງໄຊ"],
            "ສາລະວັນ": ["ສາລະວັນ", "ຕະໂອ້ຍ", "ຕຸ້ມລານ", "ລະຄອນເພັງ", "ວາປີ", "ຄົງເຊໂດນ", "ເລົ່າງາມ", "ສະມ້ວຍ"],
            "ເຊກອງ": ["ເຊກອງ", "ລະມາມ", "ກະເຕື", "ທ່າແຕງ"],
            "ຈຳປາສັກ": ["ປາກເຊ", "ຊະນະສົມບູນ", "ປາກຊ່ອງ", "ຈຳປາສັກ", "ມຸນລະປະໂມກ", "ໂຂງ", "ສຸຂຸມາ", "ບາຈຽງຈະເລີນສຸກ"],
            "ອັດຕະປື": ["ສະໝັກຊີ", "ຊຽງໃໝ່", "ບົງ", "ສານໄຊ", "ກົກມ່ວງ"],
            "ໄຊສົມບູນ": ["ອະນຸວັງ", "ລ້ອງແຈ້ງ", "ຮົ່ມ", "ທ່າໂທມ"]
            };

            function addressPicker(initial) {
                return {
                    province: initial.province || '',
                    district: initial.district || '',
                    village: initial.village || '',
                    provinceSearch: initial.province || '',
                    districtSearch: initial.district || '',
                    villageSearch: initial.village || '',
                    provinceOpen: false,
                    districtOpen: false,
                    villageOpen: false,
                    villageLoading: false,
                    villageResults: [],
                    _villageTimer: null,

                    get provinces() {
                        return Object.keys(LAOS_ADDRESSES);
                    },

                    get filteredProvinces() {
                        var s = (this.provinceSearch || '').trim().toLowerCase();
                        if (!s) return this.provinces;
                        return this.provinces.filter(function(p) { return p.toLowerCase().indexOf(s) !== -1; });
                    },

                    get districts() {
                        return LAOS_ADDRESSES[this.province] || [];
                    },

                    get filteredDistricts() {
                        var s = (this.districtSearch || '').trim().toLowerCase();
                        var d = this.districts;
                        if (!s) return d;
                        return d.filter(function(d) { return d.toLowerCase().indexOf(s) !== -1; });
                    },

                    selectProvince: function(p) {
                        this.province = p;
                        this.provinceSearch = p;
                        this.provinceOpen = false;
                        this.district = '';
                        this.districtSearch = '';
                        this.village = '';
                        this.villageSearch = '';
                        this.villageResults = [];
                    },

                    selectDistrict: function(d) {
                        this.district = d;
                        this.districtSearch = d;
                        this.districtOpen = false;
                        this.village = '';
                        this.villageSearch = '';
                        this.villageResults = [];
                    },

                    searchVillage: function(q) {
                        var self = this;
                        if (this._villageTimer) clearTimeout(this._villageTimer);
                        q = (q || '').trim();
                        if (q.length < 2) {
                            this.villageResults = [];
                            this.villageOpen = false;
                            return;
                        }
                        this._villageTimer = setTimeout(function() {
                            self.villageLoading = true;
                            var bounds = '';
                            var pName = self.province;
                            if (pName === 'ນະຄອນຫຼວງວຽງຈັນ') bounds = '18.0,102.4,18.2,102.8';
                            var url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(q + ', ' + pName + ', ລາວ') + '&limit=10&countrycodes=la' + (bounds ? '&bounded=1&viewbox=' + bounds : '');
                            fetch(url)
                                .then(function(r) { return r.json(); })
                                .then(function(data) {
                                    var names = [];
                                    for (var i = 0; i < data.length; i++) {
                                        var name = data[i].display_name.split(',')[0].trim();
                                        if (names.indexOf(name) === -1 && name.length > 0) names.push(name);
                                    }
                                    self.villageResults = names;
                                    self.villageOpen = names.length > 0;
                                    self.villageLoading = false;
                                })
                                .catch(function() {
                                    self.villageLoading = false;
                                });
                        }, 400);
                    },

                    selectVillage: function(v) {
                        this.village = v;
                        this.villageSearch = v;
                        this.villageOpen = false;
                    }
                };
            }

            function pollOrderStatus() {
                fetch('<?= url('/account/orders-status') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.orders) {
                            data.orders.forEach(o => {
                                let badge = document.getElementById('status-badge-' + o.id);
                                let timeline = document.getElementById('timeline-' + o.id);
                                if (badge) {
                                    let labels = { 'Pending': 'ລໍຖ້າ', 'Confirmed': 'ຢືນຢັນ', 'Processing': 'ກຳລັງດຳເນີນ', 'Shipped': 'ຈັດສົ່ງ', 'Delivered': 'ສົ່ງແລ້ວ', 'Cancelled': 'ຍົກເລີກ' };
                                    let colors = { 'Pending': 'bg-gray-100 text-gray-600', 'Confirmed': 'bg-blue-100 text-blue-600', 'Processing': 'bg-indigo-100 text-indigo-600', 'Shipped': 'bg-sky-100 text-sky-600', 'Delivered': 'bg-emerald-100 text-emerald-600', 'Cancelled': 'bg-red-100 text-red-600' };
                                    let dotColors = { 'Pending': 'bg-gray-400', 'Confirmed': 'bg-blue-500', 'Processing': 'bg-indigo-500', 'Shipped': 'bg-sky-500', 'Delivered': 'bg-emerald-500', 'Cancelled': 'bg-red-500' };
                                    badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full ' + (dotColors[o.order_status] || 'bg-gray-400') + '"></span> ' + (labels[o.order_status] || o.order_status);
                                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold ' + (colors[o.order_status] || 'bg-gray-100 text-gray-600');
                                }
                                if (timeline) {
                                    let steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
                                    let current = steps.indexOf(o.order_status);
                                    let lis = timeline.querySelectorAll('li');
                                    lis.forEach((li, idx) => {
                                        li.querySelector('div:first-child')?.classList.remove('bg-sky-500', 'bg-gray-300', 'ring-sky-200', 'ring-gray-100');
                                        li.querySelector('span:last-child')?.classList.remove('text-sky-600', 'text-gray-400', 'font-black', 'font-medium');
                                        if (idx < current) {
                                            li.querySelector('div:first-child').classList.add('bg-sky-500', 'ring-sky-200');
                                            li.querySelector('span:last-child').classList.add('text-sky-600', 'font-black');
                                        } else if (idx === current) {
                                            li.querySelector('div:first-child').classList.add('bg-sky-500', 'ring-sky-200');
                                            li.querySelector('span:last-child').classList.add('text-sky-600', 'font-black');
                                        } else {
                                            li.querySelector('div:first-child').classList.add('bg-gray-300', 'ring-gray-100');
                                            li.querySelector('span:last-child').classList.add('text-gray-400', 'font-medium');
                                        }
                                    });
                                }
                            });
                        }
                    }).catch(() => {});
            }
            </script>

        </div>
    </div>
</div>
