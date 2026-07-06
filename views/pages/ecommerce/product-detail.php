<?php if (!$product): ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-exclamation-circle text-4xl text-gray-300"></i>
    </div>
    <h1 class="text-2xl font-bold text-foreground mb-4">ບໍ່ພົບສິນຄ້າ</h1>
    <p class="text-muted-foreground mb-8">ສິນຄ້າທີ່ທ່ານຊອກຫາບໍ່ມີຢູ່ໃນລະບົບ</p>
    <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-sky-700 transition-all">
        <i class="fas fa-arrow-left"></i> ກັບໄປສິນຄ້າທັງໝົດ
    </a>
</div>
<?php else: ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
        <a href="<?= url('/') ?>" class="hover:text-sky-600 transition-colors"><i class="fas fa-home"></i></a>
        <span>/</span>
        <a href="<?= url('/products') ?>" class="hover:text-sky-600 transition-colors">ສິນຄ້າທັງໝົດ</a>
        <?php if (!empty($product['category_name'])): ?>
        <span>/</span>
        <a href="<?= url('/category/' . htmlspecialchars($product['category_slug'] ?? '')) ?>" class="hover:text-sky-600 transition-colors"><?= htmlspecialchars($product['category_name']) ?></a>
        <?php endif; ?>
        <span>/</span>
        <span class="text-foreground/85 font-bold"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Product Detail -->
    <div class="bg-card rounded-2xl border border-border overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Product Images -->
            <div class="p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-border">
                <div x-data="{ activeImage: 0, images: [<?php
                    $imgUrls = [];
                    if (!empty($product['image'])) $imgUrls[] = "'" . htmlspecialchars($product['image'], ENT_QUOTES) . "'";
                    if (!empty($images)) {
                        foreach ($images as $img) {
                            if (!empty($img['image'])) $imgUrls[] = "'" . htmlspecialchars($img['image'], ENT_QUOTES) . "'";
                        }
                    }
                    echo implode(',', $imgUrls);
                ?>] }">
                    <!-- Main Image -->
                    <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden mb-4 relative">
                        <template x-if="images.length > 0">
                            <img :src="images[activeImage]" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                        </template>
                        <template x-if="images.length === 0">
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-box fa-6x"></i>
                            </div>
                        </template>
                        <?php if (!empty($product['compare_price']) && (float)$product['compare_price'] > (float)$product['selling_price']): ?>
                        <span class="absolute top-4 left-4 bg-red-500 text-white text-sm font-black px-3 py-1.5 rounded-lg">
                            -<?= round((1 - (float)$product['selling_price'] / (float)$product['compare_price']) * 100) ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <!-- Thumbnails -->
                    <div class="flex gap-3 overflow-x-auto scrollbar-hide" x-show="images.length > 1">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeImage = index" class="w-20 h-20 rounded-xl overflow-hidden border-2 flex-shrink-0 transition-all" :class="activeImage === index ? 'border-sky-500' : 'border-border hover:border-gray-300'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-6 lg:p-8 flex flex-col">
                <?php if (!empty($product['category_name'])): ?>
                <span class="inline-flex self-start text-[10px] font-bold text-sky-600 bg-sky-50 px-3 py-1 rounded-lg mb-3">
                    <?= htmlspecialchars($product['category_name']) ?>
                </span>
                <?php endif; ?>

                <h1 class="text-2xl lg:text-3xl font-black text-foreground mb-4"><?= htmlspecialchars($product['name']) ?></h1>

                <?php if (!empty($product['short_description'])): ?>
                <p class="text-muted-foreground text-sm mb-4"><?= htmlspecialchars($product['short_description']) ?></p>
                <?php endif; ?>

                <!-- Price -->
                <div class="flex items-baseline gap-3 mb-6">
                    <span class="text-3xl font-black text-sky-600"><?= number_format((float)$product['selling_price'], 0) ?> ກີບ</span>
                    <?php if (!empty($product['compare_price']) && (float)$product['compare_price'] > (float)$product['selling_price']): ?>
                    <span class="text-lg text-muted-foreground line-through"><?= number_format((float)$product['compare_price'], 0) ?> ກີບ</span>
                    <?php endif; ?>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center gap-2 mb-6">
                    <?php $stock = (int)($product['stock'] ?? 0); ?>
                    <?php if ($stock > 0): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        ມີສິນຄ້າ (<?= $stock ?> <?= htmlspecialchars($product['unit'] ?? 'ຊິ້ນ') ?>)
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 text-sm font-bold rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        �​ົດສິນຄ້າ
                    </span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($product['sku'])): ?>
                <p class="text-xs text-muted-foreground mb-4">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                <?php endif; ?>

                <!-- Description -->
                <?php if (!empty($product['description'])): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-black text-foreground mb-2">ລາຍລະອຽດ</h3>
                    <p class="text-sm text-foreground/70 leading-relaxed whitespace-pre-line"><?= htmlspecialchars($product['description']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Add to Cart -->
                <div class="mt-auto space-y-4">
                    <?php if ($stock > 0): ?>
                    <div class="flex items-center gap-4">
                        <div id="qty-selector" x-data="{ qty: 1 }" class="flex items-center border border-border rounded-xl overflow-hidden">
                            <button @click="qty = Math.max(1, qty - 1)" class="h-11 w-11 flex items-center justify-center text-muted-foreground hover:bg-gray-50 transition-colors">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="number" x-model="qty" min="1" max="<?= $stock ?>" class="h-11 w-16 text-center text-sm font-bold border-x border-border outline-none" value="1">
                            <button @click="qty = Math.min(<?= $stock ?>, qty + 1)" class="h-11 w-11 flex items-center justify-center text-muted-foreground hover:bg-gray-50 transition-colors">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                        <button @click="addToCart(<?= (int)$product['id'] ?>, (document.querySelector('#qty-selector')?.__x?.$data?.qty) || 1)" class="flex-1 h-11 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg shadow-sky-200">
                            <i class="fas fa-cart-plus"></i> ເພີ່ມໃສ່ກະຕ່າ
                        </button>
                    </div>
                    <?php else: ?>
                    <button disabled class="w-full h-11 bg-gray-300 text-muted-foreground font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> ສິນຄ້າໝົດ
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <section class="mt-12">
        <h2 class="text-2xl font-black text-foreground mb-6">ສິນຄ້າທີ່ກ່ຽວຂ້ອງ</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($related as $r): ?>
            <div class="group bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:shadow-sky-100/30 transition-all duration-300 hover:-translate-y-1">
                <a href="<?= url('/products/' . htmlspecialchars($r['slug'])) ?>" class="block aspect-[4/3] bg-gray-50 overflow-hidden">
                    <?php if (!empty($r['image'])): ?>
                    <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fas fa-box fa-4x"></i>
                    </div>
                    <?php endif; ?>
                </a>
                <div class="p-4">
                    <a href="<?= url('/products/' . htmlspecialchars($r['slug'])) ?>" class="block">
                        <h3 class="text-sm font-bold text-foreground line-clamp-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($r['name']) ?></h3>
                    </a>
                    <div class="mt-2">
                        <span class="text-lg font-black text-sky-600"><?= number_format((float)$r['selling_price'], 0) ?> ກີບ</span>
                    </div>
                    <button onclick="addToCart(<?= (int)$r['id'] ?>, 1)" class="mt-3 w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fas fa-cart-plus"></i> ເພີ່ມໃສ່ກະຕ່າ
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>
<?php endif; ?>
