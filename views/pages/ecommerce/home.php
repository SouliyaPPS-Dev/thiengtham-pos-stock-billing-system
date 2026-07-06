<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.3); }
    50% { box-shadow: 0 0 0 12px rgba(2, 132, 199, 0); }
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes scale-in {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
.anim-float { animation: float 3s ease-in-out infinite; }
.anim-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
.anim-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    background-size: 200% 100%;
    animation: shimmer 2.5s infinite;
}
.anim-fade-in-up { animation: fade-in-up 0.7s ease-out both; }
.anim-delay-1 { animation-delay: 0.15s; }
.anim-delay-2 { animation-delay: 0.3s; }
.anim-delay-3 { animation-delay: 0.45s; }
.anim-delay-4 { animation-delay: 0.6s; }
.anim-delay-5 { animation-delay: 0.75s; }
.anim-delay-6 { animation-delay: 0.9s; }
.card-hover {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.card-hover:hover {
    transform: translateY(-6px) scale(1.02);
}
.cat-card {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.cat-card:hover {
    transform: translateY(-3px) scale(1.05);
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

        <!-- ============ MAIN CONTENT (2/3) ============ -->
        <div class="lg:col-span-2 space-y-20 lg:space-y-28">

            <!-- ===== CATEGORIES ===== -->
            <div class="relative z-10 anim-fade-in-up">
                <div class="bg-card/80 backdrop-blur-xl rounded-2xl shadow-lg shadow-sky-100/30 border border-border/80 p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-foreground flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs shadow-lg shadow-sky-200"><i class="fas fa-th-large"></i></span>
                            ໝວດສິນຄ້າ
                        </h2>
                        <a href="<?= url('/products') ?>" class="text-sm font-bold text-sky-600 hover:text-sky-700 transition-all hover:underline underline-offset-4 decoration-2 decoration-sky-300">ເບິ່ງທັງໝົດ <i class="fas fa-arrow-right text-xs ml-0.5"></i></a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-6 gap-2.5">
                        <?php if (empty($categories)): ?>
                        <div class="col-span-full text-center py-6 text-muted-foreground text-sm">ຍັງບໍ່ມີໝວດສິນຄ້າ</div>
                        <?php else: ?>
                        <?php $catIcons = ['fa-mobile-alt', 'fa-laptop', 'fa-tshirt', 'fa-utensils', 'fa-home', 'fa-book', 'fa-gamepad', 'fa-gem', 'fa-apple-alt', 'fa-car', 'fa-basketball-ball', 'fa-paw']; ?>
                        <?php foreach ($categories as $idx => $cat): ?>
                        <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="cat-card group flex flex-col items-center gap-2 p-3 md:p-4 rounded-xl border border-border/60 hover:border-sky-200 hover:bg-gradient-to-b hover:from-sky-50 hover:to-white transition-all">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center text-gray-400 group-hover:from-sky-100 group-hover:to-sky-50 group-hover:text-sky-500 transition-all duration-300 text-base md:text-lg">
                                <i class="fas <?= $catIcons[$idx % count($catIcons)] ?>"></i>
                            </div>
                            <span class="text-[11px] md:text-xs font-bold text-foreground/70 group-hover:text-sky-600 text-center leading-tight line-clamp-2"><?= htmlspecialchars($cat['name']) ?></span>
                        </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== FEATURED PRODUCTS ===== -->
            <?php if (!empty($featured)): ?>
            <section class="anim-fade-in-up anim-delay-1 mt-12 lg:mt-16">
                <div class="flex items-end justify-between mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black text-sky-600 bg-sky-100 px-2.5 py-0.5 rounded-full tracking-wider uppercase">ແນະນຳ</span>
                            <?php if (count($featured) >= 4): ?>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-100 px-2.5 py-0.5 rounded-full"><i class="fas fa-fire mr-0.5"></i>ຍອດນິຍົມ</span>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-foreground">ສິນຄ້າຂາຍດີ</h2>
                        <p class="text-xs text-muted-foreground mt-0.5">ສິນຄ້າທີ່ລູກຄ້າມັກຊື້ຫຼາຍທີ່ສຸດ</p>
                    </div>
                    <a href="<?= url('/products') ?>" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-3.5 py-2 rounded-lg transition-all">
                        ທັງໝົດ <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
                    <?php foreach ($featured as $p): ?>
                    <div class="group bg-card rounded-2xl border border-border/80 overflow-hidden hover:shadow-lg hover:shadow-sky-100/40 transition-all duration-500 card-hover">
                        <!-- Image -->
                        <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block aspect-[4/3] bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden relative">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box fa-4x"></i></div>
                            <?php endif; ?>
                            <!-- Discount badge -->
                            <?php if (!empty($p['compare_price']) && (float)$p['compare_price'] > (float)$p['selling_price']): ?>
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-lg shadow-rose-200/50 z-10">
                                -<?= round((1 - (float)$p['selling_price'] / (float)$p['compare_price']) * 100) ?>%
                            </span>
                            <?php endif; ?>
                            <!-- Quick add -->
                            <button onclick="event.preventDefault(); addToCart(<?= (int)$p['id'] ?>, 1)" class="absolute bottom-3 right-3 w-10 h-10 rounded-xl bg-white/90 backdrop-blur-sm shadow-lg flex items-center justify-center text-sky-600 hover:bg-sky-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 translate-y-3 group-hover:translate-y-0 z-10">
                                <i class="fas fa-cart-plus text-sm"></i>
                            </button>
                            <!-- Rating placeholder -->
                            <div class="absolute top-3 right-3 flex items-center gap-0.5 bg-white/80 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold text-amber-500 z-10">
                                <i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star-half-alt text-[9px]"></i>
                                <span class="text-foreground/60 ml-0.5">(<?= rand(12, 89) ?>)</span>
                            </div>
                        </a>
                        <!-- Info -->
                        <div class="p-4">
                            <?php if (!empty($p['category_name'])): ?>
                            <span class="text-[9px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md"><?= htmlspecialchars($p['category_name']) ?></span>
                            <?php endif; ?>
                            <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block mt-1.5">
                                <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                            </a>
                            <div class="mt-2 flex items-center justify-between">
                                <div>
                                    <span class="text-base md:text-lg font-black text-sky-600"><?= number_format((float)$p['selling_price'], 0) ?> ກີບ</span>
                                    <?php if (!empty($p['compare_price']) && (float)$p['compare_price'] > (float)$p['selling_price']): ?>
                                    <span class="text-[10px] text-muted-foreground line-through ml-1.5"><?= number_format((float)$p['compare_price'], 0) ?> ກີບ</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ((int)$p['stock'] <= 5 && (int)$p['stock'] > 0): ?>
                                <span class="text-[9px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded-md">ເຫຼືອ <?= (int)$p['stock'] ?></span>
                                <?php endif; ?>
                            </div>
                            <button onclick="addToCart(<?= (int)$p['id'] ?>, 1)" class="mt-3 w-full py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-lg shadow-sky-200/50">
                                <i class="fas fa-cart-plus text-xs"></i> ເພີ່ມໃສ່ກະຕ່າ
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <!-- ============ SIDEBAR (1/3) ============ -->
        <div class="lg:col-span-1 space-y-6">

            <!-- ===== PROMOTIONS ===== -->
            <?php if (!empty($promotions)): ?>
            <div class="bg-card rounded-2xl border border-border/80 p-5 anim-fade-in-up anim-delay-2">
                <h3 class="text-sm font-black text-foreground mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white text-[10px]"><i class="fas fa-bullhorn"></i></span>
                    ໂປຣໂມຊັ້ນ
                </h3>
                <div class="space-y-4">
                    <?php foreach ($promotions as $promo): ?>
                    <a href="<?= htmlspecialchars($promo['link'] ?? '#') ?>" class="block group rounded-xl overflow-hidden border border-border/50 hover:border-sky-200 transition-all card-hover">
                        <div class="aspect-[16/9] bg-gray-100 overflow-hidden relative">
                            <img src="<?= htmlspecialchars($promo['image']) ?>" alt="<?= htmlspecialchars($promo['title'] ?? '') ?>" class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
                            <?php if (!empty($promo['title'])): ?>
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-3">
                                <p class="text-xs font-bold text-white drop-shadow"><?= htmlspecialchars($promo['title']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ===== TRENDING NOW ===== -->
            <div class="bg-card rounded-2xl border border-border/80 p-5 anim-fade-in-up anim-delay-3">
                <h3 class="text-sm font-black text-foreground mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center text-white text-[10px]"><i class="fas fa-chart-line"></i></span>
                    ກຳລັງມານິຍົມ
                </h3>
                <div class="space-y-3">
                    <?php if (!empty($featured)): ?>
                    <?php foreach (array_slice($featured, 0, 3) as $i => $p): ?>
                    <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden flex-shrink-0 border border-border/50">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs"><i class="fas fa-box"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-foreground truncate group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($p['name']) ?></p>
                            <p class="text-[11px] font-black text-sky-600"><?= number_format((float)$p['selling_price'], 0) ?> ກີບ</p>
                        </div>
                        <div class="text-[9px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded-md whitespace-nowrap">#<?= $i + 1 ?></div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== NEWSLETTER ===== -->
            <div class="bg-gradient-to-br from-sky-500 to-sky-700 rounded-2xl p-5 relative overflow-hidden anim-fade-in-up anim-delay-4">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/5 blur-xl"></div>
                <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-white/5 blur-xl"></div>
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-lg mb-3">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-base font-black text-white mb-1">ຕິດຕາມຂ່າວສານ</h3>
                    <p class="text-xs text-sky-100 mb-4">ຮັບຂໍ້ມູນຂ່າວສານ ແລະ ໂປຣໂມຊັ້ນພິເສດກ່ອນໃຜ</p>
                    <form onsubmit="event.preventDefault(); Swal.fire({icon:'success',title:'ສະໝັກສຳເລັດ',text:'ຂອບໃຈທີ່ຕິດຕາມ',confirmButtonColor:'#0284c7'})" class="space-y-2">
                        <input type="email" required placeholder="ອີເມວຂອງທ່ານ..." class="w-full px-4 py-2.5 rounded-xl border-0 text-sm bg-white/90 backdrop-blur-sm placeholder:text-gray-400 focus:ring-2 focus:ring-white/50 outline-none">
                        <button type="submit" class="w-full py-2.5 bg-white text-sky-700 font-bold rounded-xl hover:bg-sky-50 transition-all active:scale-[0.97] text-sm shadow-xl">
                            ສະໝັກຮັບຂ່າວ
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <br>

    <!-- ===== TRUST STRIP (FULL WIDTH) ===== -->
    <section class="anim-fade-in-up anim-delay-2 mt-16 lg:mt-24">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 md:gap-6">
            <div class="relative p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/50 overflow-hidden group hover:shadow-lg hover:shadow-emerald-200/40 transition-all duration-300 card-hover text-center">
                <div class="w-10 h-10 rounded-xl bg-white shadow-md shadow-emerald-200/50 flex items-center justify-center text-emerald-500 text-lg mb-3 anim-float mx-auto">
                    <i class="fas fa-truck"></i>
                </div>
                <h4 class="text-sm font-black text-emerald-800">ສົ່ງຟຣີ</h4>
                <p class="text-[11px] text-emerald-600/70 mt-0.5">ສຳລັບຄຳສັ່ງ 500,000 ກີບຂຶ້ນໄປ</p>
            </div>
            <div class="relative p-4 md:p-5 bg-gradient-to-br from-sky-50 to-sky-100/50 rounded-2xl border border-sky-200/50 overflow-hidden group hover:shadow-lg hover:shadow-sky-200/40 transition-all duration-300 card-hover text-center">
                <div class="w-10 h-10 rounded-xl bg-white shadow-md shadow-sky-200/50 flex items-center justify-center text-sky-500 text-lg mb-3 anim-float mx-auto" style="animation-delay: 0.5s;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 class="text-sm font-black text-sky-800">ຄຸນນະພາບ</h4>
                <p class="text-[11px] text-sky-600/70 mt-0.5">ຮັບປະກັນສິນຄ້າແທ້ 100%</p>
            </div>
            <div class="relative p-4 md:p-5 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl border border-amber-200/50 overflow-hidden group hover:shadow-lg hover:shadow-amber-200/40 transition-all duration-300 card-hover text-center">
                <div class="w-10 h-10 rounded-xl bg-white shadow-md shadow-amber-200/50 flex items-center justify-center text-amber-500 text-lg mb-3 anim-float mx-auto" style="animation-delay: 1s;">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h4 class="text-sm font-black text-amber-800">ຄືນເງິນ</h4>
                <p class="text-[11px] text-amber-600/70 mt-0.5">ຮັບປະກັນຄືນເງິນພາຍໃນ 7 ວັນ</p>
            </div>
            <div class="relative p-4 md:p-5 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl border border-purple-200/50 overflow-hidden group hover:shadow-lg hover:shadow-purple-200/40 transition-all duration-300 card-hover text-center">
                <div class="w-10 h-10 rounded-xl bg-white shadow-md shadow-purple-200/50 flex items-center justify-center text-purple-500 text-lg mb-3 anim-float mx-auto" style="animation-delay: 1.5s;">
                    <i class="fas fa-headset"></i>
                </div>
                <h4 class="text-sm font-black text-purple-800">ບໍລິການ</h4>
                <p class="text-[11px] text-purple-600/70 mt-0.5">ພະນັກງານພ້ອມໃຫ້ບໍລິການ 24/7</p>
            </div>
        </div>
    </section>

    <br>

    <!-- ===== ສິນຄ້າມາໃໝ່ ===== -->
    <?php if (!empty($newArrivals)): ?>
    <section class="anim-fade-in-up anim-delay-3 mt-16 lg:mt-24">
        <div class="flex items-end justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2.5 py-0.5 rounded-full tracking-wider uppercase"><i class="fas fa-sparkles mr-0.5"></i>ມາໃໝ່</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-foreground">ສິນຄ້າມາໃໝ່ຮ້ອນໆ</h2>
                <p class="text-xs text-muted-foreground mt-0.5">ສິນຄ້າທີ່ຫາກໍ່ເພີ່ມເຂົ້າມາໃໝ່ຫວ້ານນີ້</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
            <?php foreach ($newArrivals as $p): ?>
            <div class="group bg-card rounded-2xl border border-border/80 overflow-hidden hover:shadow-lg hover:shadow-sky-100/40 transition-all duration-500 card-hover">
                <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block aspect-[4/3] bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden relative">
                    <?php if (!empty($p['image'])): ?>
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box fa-4x"></i></div>
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 bg-gradient-to-r from-emerald-400 to-emerald-500 text-white text-[9px] font-black px-2.5 py-1 rounded-lg shadow-lg shadow-emerald-200/50 z-10">
                        <i class="fas fa-sparkles mr-0.5"></i>ໃໝ່
                    </span>
                    <div class="absolute top-3 right-3 flex items-center gap-0.5 bg-white/80 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold text-amber-500 z-10">
                        <i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i>
                        <span class="text-foreground/60 ml-0.5">(ໃໝ່)</span>
                    </div>
                </a>
                <div class="p-4">
                    <?php if (!empty($p['category_name'])): ?>
                    <span class="text-[9px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md"><?= htmlspecialchars($p['category_name']) ?></span>
                    <?php endif; ?>
                    <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block mt-1.5">
                        <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                    </a>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-base md:text-lg font-black text-sky-600"><?= number_format((float)$p['selling_price'], 0) ?> ກີບ</span>
                    </div>
                    <button onclick="addToCart(<?= (int)$p['id'] ?>, 1)" class="mt-3 w-full py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-lg shadow-sky-200/50">
                        <i class="fas fa-cart-plus text-xs"></i> ສັ່ງຊື້
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <br>

    <!-- ===== ສະຖິຕິ ===== -->
    <section class="anim-fade-in-up anim-delay-4 mt-16 lg:mt-24" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 200)">
        <div class="bg-gradient-to-r from-sky-600 via-sky-500 to-sky-600 rounded-2xl p-6 md:p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(255,255,255,0.1),transparent_50%)]"></div>
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
            <div class="relative grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-center">
                <div>
                    <div class="text-2xl md:text-4xl font-black text-white mb-1">
                        <span x-text="shown ? '<?= rand(500, 2000) ?>' : '0'"></span><span class="text-sky-200">+</span>
                    </div>
                    <p class="text-[11px] md:text-xs text-sky-100 font-bold">ລາຍການສິນຄ້າ</p>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-black text-white mb-1">
                        <span x-text="shown ? '<?= rand(200, 1000) ?>' : '0'"></span><span class="text-sky-200">+</span>
                    </div>
                    <p class="text-[11px] md:text-xs text-sky-100 font-bold">ລູກຄ້າປະຈຳ</p>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-black text-white mb-1">
                        <span x-text="shown ? '<?= rand(50, 500) ?>' : '0'"></span><span class="text-sky-200">+</span>
                    </div>
                    <p class="text-[11px] md:text-xs text-sky-100 font-bold">ຄຳສັ່ງຊື້</p>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-black text-white mb-1">
                        <span class="text-2xl md:text-3xl">⭐</span> <span x-text="shown ? '4.9' : '0'"></span>
                    </div>
                    <p class="text-[11px] md:text-xs text-sky-100 font-bold">ຄະແນນຮ້ານ</p>
                </div>
            </div>
        </div>
    </section>

    <br>

    <!-- ===== ຄຳຕິຊົມ ===== -->
    <section class="anim-fade-in-up anim-delay-5 mt-16 lg:mt-24">
        <div class="flex items-center gap-2 mb-6">
            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white text-xs shadow-lg shadow-amber-200"><i class="fas fa-star"></i></span>
            <h2 class="text-xl md:text-2xl font-black text-foreground">ລູກຄ້າເວົ້າແນວໃດ</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-card rounded-2xl border border-border/80 p-5 hover:shadow-md hover:shadow-amber-100/30 transition-all duration-300 card-hover">
                <div class="flex items-center gap-1 text-amber-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-sm text-foreground/80 leading-relaxed mb-3">"ສິນຄ້າຄຸນນະພາບດີ ການຫຸ້ມຫໍ່ລະມັດລະວັງ ສົ່ງໄວ ປະທັບໃຈຫຼາຍ ຈະສັ່ງຊື້ອີກແນ່ນອນ"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs font-black">ສ</div>
                    <div>
                        <p class="text-sm font-bold text-foreground">ສຸລິຍາ ວົງສະຫວັດ</p>
                        <p class="text-[10px] text-muted-foreground">ສັ່ງຊື້ 12 ເທື່ອ</p>
                    </div>
                </div>
            </div>
            <div class="bg-card rounded-2xl border border-border/80 p-5 hover:shadow-md hover:shadow-amber-100/30 transition-all duration-300 card-hover">
                <div class="flex items-center gap-1 text-amber-400 text-xs mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-sm text-foreground/80 leading-relaxed mb-3">"ຮ້ານນີ້ດີ ລາຄາຖືກ ສິນຄ້າແທ້ ພະນັກງານຕອບແຊດໄວ ແນະນຳໃຫ້ຊື້"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xs font-black">ອ</div>
                    <div>
                        <p class="text-sm font-bold text-foreground">ອະນຸສາ ແກ້ວມະນີ</p>
                        <p class="text-[10px] text-muted-foreground">ສັ່ງຊື້ 8 ເທື່ອ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>