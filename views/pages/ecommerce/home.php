<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Content Column (2/3) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Category Chips -->
            <div class="-mt-8 relative z-10">
                <div class="bg-card rounded-2xl shadow-xl shadow-sky-100/50 border border-border p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-foreground">ໝວດສິນຄ້າ</h2>
                        <a href="<?= url('/products') ?>" class="text-sm font-bold text-sky-600 hover:text-sky-700 transition-colors">ເບິ່ງທັງໝົດ <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <?php if (empty($categories)): ?>
                        <div class="col-span-full text-center py-8 text-muted-foreground text-sm">ຍັງບໍ່ມີໝວດສິນຄ້າ</div>
                        <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-border hover:border-sky-200 hover:bg-sky-50 transition-all">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-100 to-sky-50 flex items-center justify-center text-sky-600 group-hover:from-sky-200 group-hover:to-sky-100 transition-all">
                                <i class="fas fa-tag text-lg"></i>
                            </div>
                            <span class="text-xs font-bold text-foreground/85 group-hover:text-sky-600 text-center"><?= htmlspecialchars($cat['name']) ?></span>
                        </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Featured Products -->
            <?php if (!empty($featured)): ?>
            <section>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-foreground">ສິນຄ້າແນະນຳ</h2>
                        <p class="text-sm text-muted-foreground mt-1">ສິນຄ້າຍອດນິຍົມທີ່ທ່ານບໍ່ຄວນພາດ</p>
                    </div>
                    <a href="<?= url('/products') ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-bold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-4 py-2.5 rounded-xl transition-all">
                        ເບິ່ງທັງໝົດ <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($featured as $product): ?>
                    <div class="group bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:shadow-sky-100/30 transition-all duration-300 hover:-translate-y-1">
                        <a href="<?= url('/products/' . htmlspecialchars($product['slug'])) ?>" class="block aspect-[4/3] bg-gray-50 overflow-hidden relative">
                            <?php if (!empty($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-box fa-4x"></i>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($product['compare_price']) && (float)$product['compare_price'] > (float)$product['selling_price']): ?>
                            <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-lg">
                                -<?= round((1 - (float)$product['selling_price'] / (float)$product['compare_price']) * 100) ?>%
                            </span>
                            <?php endif; ?>
                            <button onclick="event.preventDefault(); addToCart(<?= (int)$product['id'] ?>, 1)" class="absolute bottom-3 right-3 w-10 h-10 rounded-xl bg-card shadow-lg flex items-center justify-center text-sky-600 hover:bg-sky-600 hover:text-white transition-all opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </a>
                        <div class="p-4">
                            <?php if (!empty($product['category_name'])): ?>
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md"><?= htmlspecialchars($product['category_name']) ?></span>
                            <?php endif; ?>
                            <a href="<?= url('/products/' . htmlspecialchars($product['slug'])) ?>" class="block mt-2">
                                <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($product['name']) ?></h3>
                            </a>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-lg font-black text-sky-600"><?= number_format((float)$product['selling_price'], 0) ?> ກີບ</span>
                                <?php if (!empty($product['compare_price']) && (float)$product['compare_price'] > (float)$product['selling_price']): ?>
                                <span class="text-sm text-muted-foreground line-through"><?= number_format((float)$product['compare_price'], 0) ?> ກີບ</span>
                                <?php endif; ?>
                            </div>
                            <button onclick="addToCart(<?= (int)$product['id'] ?>, 1)" class="mt-3 w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                <i class="fas fa-cart-plus"></i> ເພີ່ມໃສ່ກະຕ່າ
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- New Arrivals -->
            <?php if (!empty($newArrivals)): ?>
            <section>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-foreground">ສິນຄ້າມາໃໝ່</h2>
                        <p class="text-sm text-muted-foreground mt-1">ສິນຄ້າທີ່ຫາກໍ່ເພີ່ມເຂົ້າມາໃໝ່</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($newArrivals as $product): ?>
                    <div class="group bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:shadow-sky-100/30 transition-all duration-300 hover:-translate-y-1">
                        <a href="<?= url('/products/' . htmlspecialchars($product['slug'])) ?>" class="block aspect-[4/3] bg-gray-50 overflow-hidden">
                            <?php if (!empty($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-box fa-4x"></i>
                            </div>
                            <?php endif; ?>
                        </a>
                        <div class="p-4">
                            <?php if (!empty($product['category_name'])): ?>
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md"><?= htmlspecialchars($product['category_name']) ?></span>
                            <?php endif; ?>
                            <a href="<?= url('/products/' . htmlspecialchars($product['slug'])) ?>" class="block mt-2">
                                <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($product['name']) ?></h3>
                            </a>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-lg font-black text-sky-600"><?= number_format((float)$product['selling_price'], 0) ?> ກີບ</span>
                            </div>
                            <button onclick="addToCart(<?= (int)$product['id'] ?>, 1)" class="mt-3 w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                <i class="fas fa-cart-plus"></i> ເພີ່ມໃສ່ກະຕ່າ
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- CTA Section -->
            <section class="bg-gradient-to-r from-sky-600 to-sky-800 rounded-2xl">
                <div class="px-4 sm:px-6 lg:px-8 py-16 text-center">
                    <h2 class="text-2xl md:text-4xl font-black text-white mb-4">ພ້ອມທີ່ຈະສັ່ງຊື້ແລ້ວ ຫຼື ຍັງ?</h2>
                    <p class="text-lg text-sky-100 mb-8 max-w-xl mx-auto">ເລືອກຊື້ສິນຄ້າທີ່ທ່ານມັກ ພວກເຮົາພ້ອມຈັດສົ່ງໃຫ້ທ່ານໄວທີ່ສຸດ</p>
                    <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-card text-sky-700 font-bold px-10 py-4 rounded-xl hover:bg-sky-50 transition-all shadow-xl text-lg">
                        <i class="fas fa-shopping-bag"></i> ເລີ່ມຊື້ເລີຍ
                    </a>
                </div>
            </section>

            <!-- Features Strip -->
            <section>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-card rounded-2xl border border-border">
                        <div class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-truck text-2xl text-sky-600"></i>
                        </div>
                        <h4 class="font-bold text-foreground mb-1">ຈັດສົ່ງໄວ</h4>
                        <p class="text-xs text-muted-foreground">ຈັດສົ່ງທົ່ວປະເທດ</p>
                    </div>
                    <div class="text-center p-6 bg-card rounded-2xl border border-border">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shield-alt text-2xl text-emerald-600"></i>
                        </div>
                        <h4 class="font-bold text-foreground mb-1">ຄຸນນະພາບ</h4>
                        <p class="text-xs text-muted-foreground">ສິນຄ້າມີຄຸນນະພາບ</p>
                    </div>
                    <div class="text-center p-6 bg-card rounded-2xl border border-border">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-undo-alt text-2xl text-amber-600"></i>
                        </div>
                        <h4 class="font-bold text-foreground mb-1">ຄືນເງິນ</h4>
                        <p class="text-xs text-muted-foreground">ຮັບປະກັນຄືນເງິນ</p>
                    </div>
                    <div class="text-center p-6 bg-card rounded-2xl border border-border">
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-headset text-2xl text-purple-600"></i>
                        </div>
                        <h4 class="font-bold text-foreground mb-1">ບໍລິການ 24/7</h4>
                        <p class="text-xs text-muted-foreground">ໃຫ້ບໍລິການຕະຫຼອດ 24 ຊົ່ວໂມງ</p>
                    </div>
                </div>
            </section>

        </div>

        <!-- Sidebar Column (1/3) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Promotions -->
            <?php if (!empty($promotions)): ?>
            <div class="bg-card rounded-2xl border border-border p-6">
                <h3 class="text-base font-black text-foreground mb-4 flex items-center gap-2">
                    <i class="fas fa-bullhorn text-sky-600"></i>
                    ໂປຣໂມຊັ້ນ
                </h3>
                <div class="space-y-4">
                    <?php foreach ($promotions as $promo): ?>
                    <a href="<?= htmlspecialchars($promo['link'] ?? '#') ?>" class="block group">
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-50 mb-2">
                            <img src="<?= htmlspecialchars($promo['image']) ?>" alt="<?= htmlspecialchars($promo['title'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <?php if (!empty($promo['title'])): ?>
                        <p class="text-xs font-bold text-foreground/85 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($promo['title']) ?></p>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>
</div>
