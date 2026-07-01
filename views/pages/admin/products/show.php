<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-5xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= url('/admin/products') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="text-sm text-gray-500 mt-0.5">ລາຍລະອຽດສິນຄ້າ</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= url('/admin/products/' . $product['id'] . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-100 text-amber-700 rounded-xl font-bold text-sm hover:bg-amber-200 transition-all shadow-lg shadow-amber-200 active:scale-[0.97]">
                    <i class="fas fa-pen"></i>
                    <span class="hidden sm:inline">ແກ້ໄຂ</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-image text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຮູບສິນຄ້າ</h2>
                            <p class="text-xs text-gray-400">ຮູບພາບສິນຄ້າ</p>
                        </div>
                    </div>
                    <?php
                    $allImages = [];
                    if (!empty($product['image'])) $allImages[] = $product['image'];
                    if (!empty($images)) {
                        foreach ($images as $img) {
                            if (!empty($img['image'])) $allImages[] = $img['image'];
                        }
                    }
                    ?>
                    <div x-data="{ activeImage: 0, images: <?= json_encode($allImages) ?> }">
                        <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden mb-4">
                            <template x-if="images.length > 0">
                                <img :src="images[activeImage]" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                            </template>
                            <template x-if="images.length === 0">
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-box fa-6x"></i>
                                </div>
                            </template>
                        </div>
                        <template x-if="images.length > 1">
                            <div class="flex gap-2 overflow-x-auto pb-1">
                                <template x-for="(img, i) in images" :key="i">
                                    <button @click="activeImage = i"
                                            class="flex-shrink-0 h-16 w-16 rounded-xl overflow-hidden border-2 transition-all"
                                            :class="activeImage === i ? 'border-sky-500 shadow-sm' : 'border-gray-100 hover:border-gray-300'">
                                        <img :src="img" class="h-full w-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-info-circle text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນສິນຄ້າ</h2>
                            <p class="text-xs text-gray-400">ລາຍລະອຽດສິນຄ້າທົ່ວໄປ</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ຊື່ສິນຄ້າ</span>
                            <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($product['name']) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">SKU</span>
                            <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($product['sku'] ?? '-') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Barcode</span>
                            <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($product['barcode'] ?? '-') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ໝວດສິນຄ້າ</span>
                            <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($product['category_name'] ?? '-') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ໜ່ວຍ</span>
                            <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($product['unit'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-coins text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ລາຄາ ແລະ ສະຕ໋ອກ</h2>
                            <p class="text-xs text-gray-400">ຂໍ້ມູນລາຄາ ແລະ ຈຳນວນສິນຄ້າຄົງເຫຼືອ</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ລາຄາຊື້ (Cost Price)</span>
                            <span class="text-sm font-bold text-gray-800"><?= number_format($product['cost_price'] ?? 0, 0) ?> ກີບ</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ລາຄາຂາຍ (Selling Price)</span>
                            <span class="text-sm font-bold text-primary"><?= number_format($product['selling_price'] ?? 0, 0) ?> ກີບ</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ສະຕ໋ອກປັດຈຸບັນ</span>
                            <?php
                            $stock = (int)($product['stock'] ?? 0);
                            $stockColor = $stock <= 0 ? 'text-red-600' : ($stock <= ($product['min_stock'] ?? 10) ? 'text-amber-600' : 'text-green-600');
                            ?>
                            <span class="text-sm font-bold <?= $stockColor ?>"><?= $stock ?> <?= htmlspecialchars($product['unit'] ?? 'ຊິ້ນ') ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ສະຕ໋ອກຕ່ຳສຸດ (Min Stock)</span>
                            <span class="text-sm font-bold text-gray-800"><?= $product['min_stock'] ?? 0 ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">ສະຖານະ</span>
                            <?php if (($product['status'] ?? 'active') === 'active'): ?>
                            <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-bold">
                                <i class="fas fa-circle text-[6px] mr-1"></i> ເປີດໃຊ້
                            </span>
                            <?php else: ?>
                            <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">
                                <i class="fas fa-circle text-[6px] mr-1"></i> ປິດໃຊ້
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($stock > 0): ?>
                    <div class="mt-6 pt-4 border-t border-gray-50">
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
                            <span>ສະຕ໋ອກ</span>
                            <span><?= $stock ?> / <?= max($product['min_stock'] * 3, $stock) ?></span>
                        </div>
                        <?php
                        $maxStock = max($product['min_stock'] * 3, 1);
                        $stockPercent = min(100, ($stock / $maxStock) * 100);
                        $barColor = $stock <= 0 ? 'bg-red-500' : ($stock <= ($product['min_stock'] ?? 10) ? 'bg-amber-500' : 'bg-green-500');
                        ?>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="<?= $barColor ?> h-full rounded-full transition-all duration-500" style="width: <?= $stockPercent ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($product['description'])): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-200">
                            <i class="fas fa-align-left text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ລາຍລະອຽດ</h2>
                            <p class="text-xs text-gray-400">ລາຍລະອຽດເພີ່ມເຕີມ</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white shadow-lg shadow-gray-200">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນລະບົບ</h2>
                            <p class="text-xs text-gray-400">ວັນທີສ້າງ ແລະ ອັບເດດ</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-500">ສ້າງເມື່ອ</span>
                            <span class="text-sm font-bold text-gray-800"><?= date('d/m/Y H:i', strtotime($product['created_at'])) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">ອັບເດດລ່າສຸດ</span>
                            <span class="text-sm font-bold text-gray-800"><?= !empty($product['updated_at']) ? date('d/m/Y H:i', strtotime($product['updated_at'])) : '-' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
