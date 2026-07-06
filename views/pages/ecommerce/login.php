<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-card rounded-2xl shadow-xl shadow-sky-100/30 border border-border p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-sky-200">
                    <i class="fas fa-user text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-black text-foreground">ເຂົ້າສູ່ລະບົບ</h1>
                <p class="text-sm text-muted-foreground mt-1">ເຂົ້າສູ່ລະບົບເພື່ອສັ່ງຊື້ສິນຄ້າ</p>
            </div>

            <?php if (isset($error) && $error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ອີເມວ ຫຼື ເບີໂທ</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="text" name="email" placeholder="ອີເມວ" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ຫຼື ເບີໂທລະສັບ</label>
                    <div class="flex gap-2">
                        <select name="phone_prefix" class="w-[110px] px-3 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm bg-card">
                            <option value="+856" selected>🇱🇦 +856</option>
                            <option value="+66">🇹🇭 +66</option>
                            <option value="+84">🇻🇳 +84</option>
                            <option value="+855">🇰🇭 +855</option>
                            <option value="+95">🇲🇲 +95</option>
                            <option value="+86">🇨🇳 +86</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                            <option value="+81">🇯🇵 +81</option>
                            <option value="+82">🇰🇷 +82</option>
                            <option value="+65">🇸🇬 +65</option>
                            <option value="+60">🇲🇾 +60</option>
                            <option value="+62">🇮🇩 +62</option>
                            <option value="+63">🇵🇭 +63</option>
                            <option value="+91">🇮🇳 +91</option>
                            <option value="+61">🇦🇺 +61</option>
                        </select>
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" name="phone" placeholder="ເບີໂທລະສັບ" class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold text-foreground/85 mb-1.5 block">ລະຫັດຜ່ານ</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-muted-foreground">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" required class="w-full pl-11 pr-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                </div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.98]">
                    <i class="fas fa-sign-in-alt mr-2"></i>ເຂົ້າສູ່ລະບົບ
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-muted-foreground">
                    ຍັງບໍ່ມີບັນຊີ?
                    <a href="<?= url('/register') ?>" class="text-sky-600 font-bold hover:text-sky-700">ສະໝັກສະມາຊິກ</a>
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
