<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-lg">
        <div class="bg-card rounded-2xl shadow-xl shadow-sky-100/30 border border-border p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-sky-200">
                    <i class="fas fa-user-plus text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-black text-foreground">ສະໝັກສະມາຊິກ</h1>
                <p class="text-sm text-muted-foreground mt-1">ສ້າງບັນຊີເພື່ອສັ່ງຊື້ສິນຄ້າ</p>
            </div>

            <?php if (isset($errors) && !empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?php foreach ($errors as $err): ?>
                <p class="text-sm font-bold flex items-center gap-2 <?= $err !== end($errors) ? 'mb-1' : '' ?>">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($err) ?>
                </p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຊື່ ແລະ ນາມສະກຸນ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-user"></i></span>
                            <input type="text" name="fullname" required value="<?= htmlspecialchars($old['fullname'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເບີໂທລະສັບ <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <select name="phone_prefix" class="w-[110px] px-3 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm bg-card">
                                <option value="+856" <?= (($old['phone_prefix'] ?? '+856') === '+856') ? 'selected' : '' ?>>🇱🇦 +856</option>
                                <option value="+66" <?= (($old['phone_prefix'] ?? '') === '+66') ? 'selected' : '' ?>>🇹🇭 +66</option>
                                <option value="+84" <?= (($old['phone_prefix'] ?? '') === '+84') ? 'selected' : '' ?>>🇻🇳 +84</option>
                                <option value="+855" <?= (($old['phone_prefix'] ?? '') === '+855') ? 'selected' : '' ?>>🇰🇭 +855</option>
                                <option value="+95" <?= (($old['phone_prefix'] ?? '') === '+95') ? 'selected' : '' ?>>🇲🇲 +95</option>
                                <option value="+86" <?= (($old['phone_prefix'] ?? '') === '+86') ? 'selected' : '' ?>>🇨🇳 +86</option>
                                <option value="+1" <?= (($old['phone_prefix'] ?? '') === '+1') ? 'selected' : '' ?>>🇺🇸 +1</option>
                                <option value="+44" <?= (($old['phone_prefix'] ?? '') === '+44') ? 'selected' : '' ?>>🇬🇧 +44</option>
                                <option value="+81" <?= (($old['phone_prefix'] ?? '') === '+81') ? 'selected' : '' ?>>🇯🇵 +81</option>
                                <option value="+82" <?= (($old['phone_prefix'] ?? '') === '+82') ? 'selected' : '' ?>>🇰🇷 +82</option>
                                <option value="+65" <?= (($old['phone_prefix'] ?? '') === '+65') ? 'selected' : '' ?>>🇸🇬 +65</option>
                                <option value="+60" <?= (($old['phone_prefix'] ?? '') === '+60') ? 'selected' : '' ?>>🇲🇾 +60</option>
                                <option value="+62" <?= (($old['phone_prefix'] ?? '') === '+62') ? 'selected' : '' ?>>🇮🇩 +62</option>
                                <option value="+63" <?= (($old['phone_prefix'] ?? '') === '+63') ? 'selected' : '' ?>>🇵🇭 +63</option>
                                <option value="+91" <?= (($old['phone_prefix'] ?? '') === '+91') ? 'selected' : '' ?>>🇮🇳 +91</option>
                                <option value="+61" <?= (($old['phone_prefix'] ?? '') === '+61') ? 'selected' : '' ?>>🇦🇺 +61</option>
                            </select>
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" required value="<?= htmlspecialchars($old['phone'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ອີເມວ</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ລະຫັດຜ່ານ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" required class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຢືນຢັນລະຫັດຜ່ານ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirm_password" required class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ແຂວງ</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="province" value="<?= htmlspecialchars($old['province'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ເມືອງ</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-map-pin"></i></span>
                            <input type="text" name="district" value="<?= htmlspecialchars($old['district'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ບ້ານ</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground"><i class="fas fa-home"></i></span>
                            <input type="text" name="village" value="<?= htmlspecialchars($old['village'] ?? '') ?>" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ທີ່ຢູ່</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຕຳແໜ່ງທີ່ຕັ້ງ (GPS)</label>
                        <div class="flex gap-2 mb-2">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-xs"></i>
                                <input type="text" id="map-search-map-register" placeholder="ຄົ້ນຫາສະຖານທີ່..." class="w-full pl-8 pr-3 py-2 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" oninput="searchLocation(this.value, 'map-register')">
                            </div>
                            <button type="button" onclick="getCurrentLocation('map-register')" class="px-3 py-2 bg-sky-50 text-sky-600 rounded-xl text-xs font-bold hover:bg-sky-100 transition-all whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-location-dot"></i> ຕຳແໜ່ງປັດຈຸບັນ
                            </button>
                        </div>
                        <div id="map-map-register" class="w-full h-56 rounded-xl border border-border relative z-0"></div>
                        <div class="flex gap-3 mt-2">
                            <input type="text" name="latitude" id="latitude" value="<?= htmlspecialchars($old['latitude'] ?? '') ?>" readonly placeholder="ເສັ້ນຂວາງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                            <input type="text" name="longitude" id="longitude" value="<?= htmlspecialchars($old['longitude'] ?? '') ?>" readonly placeholder="ເສັ້ນແວງ" class="flex-1 px-3 py-2 border border-border rounded-lg text-xs bg-muted text-muted-foreground">
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">ກົດເທິງແຜນທີ່ເພື່ອເລືອກຕຳແໜ່ງ</p>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.98]">
                    <i class="fas fa-user-plus mr-2"></i>ສະໝັກສະມາຊິກ
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-muted-foreground">
                    ມີບັນຊີຢູ່ແລ້ວ?
                    <a href="<?= url('/login-customer') ?>" class="text-sky-600 font-bold hover:text-sky-700">ເຂົ້າສູ່ລະບົບ</a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= url('/') ?>" class="text-sm text-muted-foreground hover:text-foreground/70">
                    <i class="fas fa-arrow-left mr-1"></i> ກັບໄປໜ້າຫຼັກ
                </a>
            </div>
        </div>
    </div>
</div>
