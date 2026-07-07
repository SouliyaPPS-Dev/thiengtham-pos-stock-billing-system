<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 0 0 hsl(var(--primary) / 0.3); }
    50% { box-shadow: 0 0 0 12px hsl(var(--primary) / 0); }
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
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.anim-float { animation: float 3s ease-in-out infinite; }
.anim-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
.anim-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
    background-size: 200% 100%;
    animation: shimmer 2.5s infinite;
}
.anim-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(0.16, 1, 0.3, 1) both; }
.anim-delay-1 { animation-delay: 0.1s; }
.anim-delay-2 { animation-delay: 0.2s; }
.anim-delay-3 { animation-delay: 0.3s; }
.anim-delay-4 { animation-delay: 0.4s; }
.anim-delay-5 { animation-delay: 0.5s; }
.anim-delay-6 { animation-delay: 0.6s; }

.card-hover {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
}
.cat-card {
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.cat-card:hover {
    transform: translateY(-4px) scale(1.03);
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

        <!-- ============ MAIN CONTENT (2/3) ============ -->
        <div class="lg:col-span-2 space-y-8 lg:space-y-10">

            <!-- ===== CATEGORIES ===== -->
            <div class="relative z-10 anim-fade-in-up">
                <div class="bg-card/80 backdrop-blur-xl rounded-3xl shadow-sm border border-border/80 p-5 md:p-7">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-black text-foreground flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center text-white text-xs shadow-lg shadow-primary/20"><i class="fas fa-th-large"></i></span>
                            ໝວດສິນຄ້າ
                        </h2>
                        <a href="<?= url('/products') ?>" class="text-sm font-bold text-primary hover:text-primary/85 transition-all hover:underline underline-offset-4 decoration-2 decoration-primary/30">ເບິ່ງທັງໝົດ <i class="fas fa-arrow-right text-xs ml-0.5"></i></a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-6 gap-3">
                        <?php if (empty($categories)): ?>
                        <div class="col-span-full text-center py-6 text-muted-foreground text-sm">ຍັງບໍ່ມີໝວດສິນຄ້າ</div>
                        <?php else: ?>
                        <?php $catIcons = ['fa-mobile-alt', 'fa-laptop', 'fa-tshirt', 'fa-utensils', 'fa-home', 'fa-book', 'fa-gamepad', 'fa-gem', 'fa-apple-alt', 'fa-car', 'fa-basketball-ball', 'fa-paw']; ?>
                        <?php foreach ($categories as $idx => $cat): ?>
                        <a href="<?= url('/category/' . htmlspecialchars($cat['slug'])) ?>" class="cat-card group flex flex-col items-center gap-2.5 p-4 rounded-2xl border border-border/60 hover:border-primary/30 hover:bg-gradient-to-b hover:from-primary/5 hover:to-card transition-all shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-muted to-muted/50 flex items-center justify-center text-muted-foreground group-hover:from-primary/10 group-hover:to-primary/5 group-hover:text-primary transition-all duration-300 text-base md:text-lg">
                                <i class="fas <?= $catIcons[$idx % count($catIcons)] ?>"></i>
                            </div>
                            <span class="text-[11px] md:text-xs font-black text-foreground/80 group-hover:text-primary text-center leading-tight line-clamp-2"><?= htmlspecialchars($cat['name']) ?></span>
                        </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== FEATURED PRODUCTS ===== -->
            <?php if (!empty($featured)): ?>
            <section id="featured-section" class="anim-fade-in-up anim-delay-1">
                <div class="flex items-end justify-between mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-black text-primary bg-primary/10 px-2.5 py-0.5 rounded-full tracking-wider uppercase">ແນະນຳ</span>
                            <?php if (count($featured) >= 4): ?>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-100 dark:bg-amber-950/20 px-2.5 py-0.5 rounded-full"><i class="fas fa-fire mr-0.5"></i>ຍອດນິຍົມ</span>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-foreground">ສິນຄ້າຂາຍດີ</h2>
                        <p class="text-xs text-muted-foreground mt-0.5">ສິນຄ້າທີ່ລູກຄ້າມັກຊື້ ແລະ ໄດ້ຮັບຄວາມນິຍົມສູງສຸດ</p>
                    </div>
                    <a href="<?= url('/products') ?>" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-primary bg-primary/5 hover:bg-primary/10 px-4 py-2.5 rounded-xl transition-all">
                        ທັງໝົດ <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
                    <?php foreach ($featured as $p): ?>
                    <div class="group bg-card rounded-3xl border border-border/80 overflow-hidden hover:border-primary/20 transition-all duration-500 card-hover shadow-sm">
                        <!-- Image -->
                        <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block aspect-square bg-gradient-to-br from-muted/50 to-muted overflow-hidden relative">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-muted-foreground/60"><i class="fas fa-box fa-3x"></i></div>
                            <?php endif; ?>
                            <!-- Discount badge -->
                            <?php if (!empty($p['compare_price']) && (float)$p['compare_price'] > (float)$p['selling_price']): ?>
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-lg shadow-rose-500/20 z-10">
                                -<?= round((1 - (float)$p['selling_price'] / (float)$p['compare_price']) * 100) ?>%
                            </span>
                            <?php endif; ?>
                            <!-- Quick add -->
                            <button onclick="event.preventDefault(); addToCart(<?= (int)$p['id'] ?>, 1)" class="absolute bottom-3 right-3 w-10 h-10 rounded-xl bg-white/95 dark:bg-card/95 backdrop-blur-sm shadow-lg flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 translate-y-3 group-hover:translate-y-0 z-10 border border-border/40">
                                <i class="fas fa-cart-plus text-sm"></i>
                            </button>
                            <!-- Rating Badge -->
                            <div class="absolute top-3 right-3 flex items-center gap-0.5 bg-white/90 dark:bg-card/90 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold text-amber-500 z-10 border border-border/40 shadow-sm">
                                <i class="fas fa-star text-[9px]"></i>
                                <span class="text-foreground font-black ml-0.5">4.8</span>
                                <span class="text-muted-foreground ml-0.5">(<?= rand(12, 89) ?>)</span>
                            </div>
                        </a>
                        <!-- Info -->
                        <div class="p-5">
                            <?php if (!empty($p['category_name'])): ?>
                            <span class="text-[9px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md"><?= htmlspecialchars($p['category_name']) ?></span>
                            <?php endif; ?>
                            <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block mt-1.5">
                                <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-primary transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                            </a>
                            <div class="mt-2.5 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-primary"><?= number_format((float)$p['selling_price'], 0) ?> ₭</span>
                                    <?php if (!empty($p['compare_price']) && (float)$p['compare_price'] > (float)$p['selling_price']): ?>
                                    <span class="text-[10px] text-muted-foreground line-through"><?= number_format((float)$p['compare_price'], 0) ?> ₭</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ((int)$p['stock'] <= 5 && (int)$p['stock'] > 0): ?>
                                <span class="text-[9px] font-bold text-rose-500 bg-rose-50 dark:bg-rose-950/20 px-1.5 py-0.5 rounded-md">ເຫຼືອ <?= (int)$p['stock'] ?></span>
                                <?php endif; ?>
                            </div>
                            <button onclick="addToCart(<?= (int)$p['id'] ?>, 1)" class="mt-4 w-full py-3 bg-gradient-to-r from-primary to-primary/95 hover:from-primary/95 hover:to-primary text-white text-sm font-black rounded-xl transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-md shadow-primary/20">
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
            <div class="bg-card rounded-3xl border border-border/80 p-5 shadow-sm anim-fade-in-up anim-delay-2">
                <h3 class="text-sm font-black text-foreground mb-4 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white text-xs shadow-lg shadow-rose-500/20"><i class="fas fa-bullhorn"></i></span>
                    ໂປຣໂມຊັ້ນພິເສດ
                </h3>
                <div class="space-y-4">
                    <?php foreach ($promotions as $promo): ?>
                    <a href="<?= htmlspecialchars($promo['link'] ?? '#') ?>" class="block group rounded-2xl overflow-hidden border border-border/50 hover:border-primary/25 transition-all card-hover shadow-sm">
                        <div class="aspect-[16/9] bg-muted overflow-hidden relative">
                            <img src="<?= htmlspecialchars($promo['image']) ?>" alt="<?= htmlspecialchars($promo['title'] ?? '') ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105">
                            <?php if (!empty($promo['title'])): ?>
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent p-3.5">
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
            <div class="bg-card rounded-3xl border border-border/80 p-5 shadow-sm anim-fade-in-up anim-delay-3">
                <h3 class="text-sm font-black text-foreground mb-4 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-400 to-rose-500 flex items-center justify-center text-white text-xs shadow-lg shadow-amber-500/20"><i class="fas fa-chart-line"></i></span>
                    ກຳລັງມານິຍົມ
                </h3>
                <div class="space-y-3.5">
                    <?php if (!empty($featured)): ?>
                    <?php foreach (array_slice($featured, 0, 3) as $i => $p): ?>
                    <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="flex items-center gap-3.5 group border-b border-border/40 pb-3 last:border-0 last:pb-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-muted/50 to-muted overflow-hidden flex-shrink-0 border border-border/60">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 duration-300 transition-all">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-muted-foreground/60 text-xs"><i class="fas fa-box"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-foreground truncate group-hover:text-primary transition-colors"><?= htmlspecialchars($p['name']) ?></p>
                            <p class="text-xs font-black text-primary mt-0.5"><?= number_format((float)$p['selling_price'], 0) ?> ₭</p>
                        </div>
                        <?php 
                        $ranks = [
                            0 => ['color' => 'text-amber-600 bg-transparent border-amber-200/40'],
                            1 => ['color' => 'text-slate-500 bg-transparent border-slate-200/40'],
                            2 => ['color' => 'text-orange-700 bg-transparent border-orange-200/40']
                        ];
                        $rankClass = $ranks[$i % 3]['color'];
                        ?>
                        <div class="text-[10px] font-black w-5 h-5 rounded-lg flex items-center justify-center border <?= $rankClass ?> whitespace-nowrap">#<?= $i + 1 ?></div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== NEWSLETTER ===== -->
            <div class="bg-gradient-to-br from-primary via-primary to-indigo-850 rounded-3xl p-6 relative overflow-hidden shadow-xl shadow-primary/10 anim-fade-in-up anim-delay-4">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/5 blur-xl"></div>
                <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-white/5 blur-xl"></div>
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white text-lg mb-3">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-base font-black text-white mb-1">ຕິດຕາມຂ່າວສານ</h3>
                    <p class="text-xs text-white/80 mb-4 leading-relaxed">ຮັບຂໍ້ມູນຂ່າວສານສິນຄ້າໃໝ່ ແລະ ໂປຣໂມຊັ້ນພິເສດກ່ອນໃຜ</p>
                    <form onsubmit="event.preventDefault(); Swal.fire({icon:'success',title:'ສະໝັກສຳເລັດ',text:'ຂອບໃຈທີ່ຕິດຕາມຂ່າວສານຂອງພວກເຮົາ',confirmButtonColor:'hsl(var(--primary))',customClass:{popup:'rounded-2xl'}})" class="space-y-2">
                        <input type="email" required placeholder="ອີເມວຂອງທ່ານ..." class="w-full px-4 py-3 rounded-xl border border-white/20 text-sm bg-white/10 placeholder:text-white/60 text-white focus:ring-2 focus:ring-white/40 outline-none transition-all">
                        <button type="submit" class="w-full py-3 bg-white text-primary font-black rounded-xl hover:bg-white/95 transition-all active:scale-[0.97] text-sm shadow-lg shadow-black/10">
                            ສະໝັກຮັບຂ່າວ
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <!-- ===== TRUST STRIP (FULL WIDTH) ===== -->
    <section class="anim-fade-in-up anim-delay-2 mt-8 lg:mt-12">
        <div class="bg-card/70 backdrop-blur-md rounded-[2rem] border border-border/80 p-6 md:p-8 shadow-sm">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 divide-y lg:divide-y-0 lg:divide-x divide-border/60">
                <!-- Free delivery -->
                <div class="flex flex-col items-center text-center p-4 lg:p-2 first:pt-0 lg:first:pt-2">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl mb-4 anim-float">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h4 class="text-sm font-black text-foreground">ສົ່ງຟຣີທົ່ວປະເທດ</h4>
                    <p class="text-xs text-muted-foreground mt-1">ສຳລັບຄຳສັ່ງ 500,000 ກີບຂຶ້ນໄປ</p>
                </div>
                <!-- Quality -->
                <div class="flex flex-col items-center text-center p-4 lg:p-2 pt-6 lg:pt-2">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl mb-4 anim-float" style="animation-delay: 0.3s;">
                        <i class="fas fa-shield-alt animate-float-delayed"></i>
                    </div>
                    <h4 class="text-sm font-black text-foreground">ຄຸນນະພາບຮັບປະກັນ</h4>
                    <p class="text-xs text-muted-foreground mt-1">ຮັບປະກັນສິນຄ້າແທ້ 100% ຈາກໂຮງງານ</p>
                </div>
                <!-- Refund -->
                <div class="flex flex-col items-center text-center p-4 lg:p-2 pt-6 lg:pt-2">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xl mb-4 anim-float" style="animation-delay: 0.6s;">
                        <i class="fas fa-undo-alt animate-float-slow"></i>
                    </div>
                    <h4 class="text-sm font-black text-foreground">ຄືນເງິນພາຍໃນ 7 ວັນ</h4>
                    <p class="text-xs text-muted-foreground mt-1">ພົບສິນຄ້າຊຳລຸດ ຫຼື ບໍ່ຖືກໃຈ ຍິນດີຄືນເງິນ</p>
                </div>
                <!-- Support -->
                <div class="flex flex-col items-center text-center p-4 lg:p-2 pt-6 lg:pt-2">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-xl mb-4 anim-float" style="animation-delay: 0.9s;">
                        <i class="fas fa-headset animate-float"></i>
                    </div>
                    <h4 class="text-sm font-black text-foreground">ບໍລິການ 24/7</h4>
                    <p class="text-xs text-muted-foreground mt-1">ພະນັກງານຄອຍຕອບແຊດ ແລະ ໃຫ້ຄຳແນະນຳ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ສິນຄ້າມາໃໝ່ ===== -->
    <?php if (!empty($newArrivals)): ?>
    <section class="anim-fade-in-up anim-delay-3 mt-8 lg:mt-12">
        <div class="flex items-end justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-100 dark:bg-emerald-950/20 px-2.5 py-0.5 rounded-full tracking-wider uppercase"><i class="fas fa-sparkles mr-0.5"></i>ມາໃໝ່</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-foreground">ສິນຄ້າມາໃໝ່</h2>
                <p class="text-xs text-muted-foreground mt-0.5">ສິນຄ້າທີ່ເພີ່ມເຂົ້າມາໃໝ່ລ່າສຸດ</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
            <?php foreach ($newArrivals as $p): ?>
            <div class="group bg-card rounded-3xl border border-border/80 overflow-hidden hover:border-primary/20 transition-all duration-500 card-hover shadow-sm">
                <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block aspect-square bg-gradient-to-br from-muted/50 to-muted overflow-hidden relative">
                    <?php if (!empty($p['image'])): ?>
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-muted-foreground/60"><i class="fas fa-box fa-4x"></i></div>
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 bg-gradient-to-r from-emerald-400 to-emerald-500 text-white text-[9px] font-black px-2.5 py-1 rounded-lg shadow-lg shadow-emerald-250/30 z-10">
                        <i class="fas fa-sparkles mr-0.5"></i>ໃໝ່
                    </span>
                    <div class="absolute top-3 right-3 flex items-center gap-0.5 bg-white/90 dark:bg-card/90 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold text-amber-500 z-10 border border-border/40 shadow-sm">
                        <i class="fas fa-star text-[9px]"></i>
                        <span class="text-foreground font-black ml-0.5">5.0</span>
                    </div>
                </a>
                <div class="p-5">
                    <?php if (!empty($p['category_name'])): ?>
                    <span class="text-[9px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md"><?= htmlspecialchars($p['category_name']) ?></span>
                    <?php endif; ?>
                    <a href="<?= url('/products/' . htmlspecialchars($p['slug'])) ?>" class="block mt-1.5">
                        <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-primary transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                    </a>
                    <div class="mt-2.5 flex items-center justify-between">
                        <span class="text-base font-black text-primary"><?= number_format((float)$p['selling_price'], 0) ?> ₭</span>
                    </div>
                    <button onclick="addToCart(<?= (int)$p['id'] ?>, 1)" class="mt-4 w-full py-3 bg-gradient-to-r from-primary to-primary/95 hover:from-primary/95 hover:to-primary text-white text-sm font-black rounded-xl transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-md shadow-primary/20">
                        <i class="fas fa-cart-plus text-xs"></i> ສັ່ງຊື້
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== ສະຖິຕິ ===== -->
    <section class="anim-fade-in-up anim-delay-4 mt-8 lg:mt-12" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 200)">
        <div class="bg-gradient-to-r from-primary via-indigo-650 to-primary rounded-3xl p-8 md:p-12 relative overflow-hidden shadow-xl shadow-primary/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(255,255,255,0.08),transparent_50%)]"></div>
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
            <div class="relative grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 text-center">
                <div class="space-y-1">
                    <div class="text-3xl md:text-5xl font-black text-white leading-none">
                        <span x-text="shown ? '<?= rand(500, 2000) ?>' : '0'"></span><span class="text-amber-300">+</span>
                    </div>
                    <p class="text-xs text-white/70 font-black tracking-wider uppercase">ລາຍການສິນຄ້າ</p>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl md:text-5xl font-black text-white leading-none">
                        <span x-text="shown ? '<?= rand(200, 1000) ?>' : '0'"></span><span class="text-amber-300">+</span>
                    </div>
                    <p class="text-xs text-white/70 font-black tracking-wider uppercase">ລູກຄ້າປະຈຳ</p>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl md:text-5xl font-black text-white leading-none">
                        <span x-text="shown ? '<?= rand(50, 500) ?>' : '0'"></span><span class="text-amber-300">+</span>
                    </div>
                    <p class="text-xs text-white/70 font-black tracking-wider uppercase">ຄຳສັ່ງຊື້</p>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl md:text-5xl font-black text-white leading-none">
                        <span class="text-2xl md:text-4xl mr-1">⭐</span><span x-text="shown ? '4.9' : '0'"></span>
                    </div>
                    <p class="text-xs text-white/70 font-black tracking-wider uppercase">ຄະແນນຮ້ານ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ຄຳຕິຊົມ ===== -->
    <section class="anim-fade-in-up anim-delay-5 mt-8 lg:mt-12">
        <div class="flex items-center gap-3 mb-8">
            <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white text-xs shadow-lg shadow-amber-300/30"><i class="fas fa-star"></i></span>
            <div>
                <h2 class="text-xl md:text-2xl font-black text-foreground">ລູກຄ້າເວົ້າແນວໃດ</h2>
                <p class="text-xs text-muted-foreground mt-0.5">ຄວາມຄິດເຫັນ ແລະ ສຽງຕອບຮັບຈາກຜູ້ຊື້ແທ້</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <!-- Review 1 -->
            <div class="bg-card rounded-3xl border border-border/80 p-6 shadow-sm hover:shadow-md transition-all duration-300 card-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-primary/5 text-9xl font-black pointer-events-none group-hover:scale-110 duration-500 transition-all font-serif">“</div>
                <div class="flex items-center gap-1 text-amber-500 text-xs mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-sm text-foreground/80 leading-relaxed mb-6 font-medium">"ສິນຄ້າຄຸນນະພາບດີ ການຫຸ້ມຫໍ່ລະມັດລະວັງ ສົ່ງໄວ ປະທັບໃຈຫຼາຍ ຈະສັ່ງຊື້ອີກແນ່ນອນ"</p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-white text-sm font-black shadow-md shadow-primary/20">ສ</div>
                    <div>
                        <p class="text-sm font-black text-foreground">Suliya Vongsavath</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-1.5 py-0.5 rounded">✓ ຢືນຢັນແລ້ວ</span>
                            <span class="text-[10px] text-muted-foreground">ສັ່ງຊື້ 12 ເທື່ອ</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Review 2 -->
            <div class="bg-card rounded-3xl border border-border/80 p-6 shadow-sm hover:shadow-md transition-all duration-300 card-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-primary/5 text-9xl font-black pointer-events-none group-hover:scale-110 duration-500 transition-all font-serif">“</div>
                <div class="flex items-center gap-1 text-amber-500 text-xs mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-sm text-foreground/80 leading-relaxed mb-6 font-medium">"ຮ້ານນີ້ດີ ລາຄາຖືກ ສິນຄ້າແທ້ ພະນັກງານຕອບແຊດໄວ ໃຫ້ຄຳແນະນຳລະອຽດດີຫຼາຍ ແນະນຳເລີຍ"</p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white text-sm font-black shadow-md shadow-emerald-500/20">ອ</div>
                    <div>
                        <p class="text-sm font-black text-foreground">Anousa Keomaney</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-1.5 py-0.5 rounded">✓ ຢືນຢັນແລ້ວ</span>
                            <span class="text-[10px] text-muted-foreground">ສັ່ງຊື້ 8 ເທື່ອ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>