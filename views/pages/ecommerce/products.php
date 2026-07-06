<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
        <a href="<?= url('/') ?>" class="hover:text-sky-600 transition-colors"><i class="fas fa-home"></i></a>
        <span>/</span>
        <?php if (!empty($currentCategory)): ?>
        <a href="<?= url('/products') ?>" class="hover:text-sky-600 transition-colors">ສິນຄ້າທັງໝົດ</a>
        <span>/</span>
        <span class="text-foreground/85 font-bold"><?= htmlspecialchars($currentCategory['name']) ?></span>
        <?php else: ?>
        <span class="text-foreground/85 font-bold">ສິນຄ້າທັງໝົດ</span>
        <?php endif; ?>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <?php if (!empty($search)): ?>
            <h1 class="text-2xl md:text-3xl font-black text-foreground">ຜົນການຄົ້ນຫາ: "<?= htmlspecialchars($search) ?>"</h1>
            <p class="text-sm text-muted-foreground mt-1">ພົບ <?= (int)$total ?> ລາຍການ</p>
            <?php elseif (!empty($currentCategory)): ?>
            <h1 class="text-2xl md:text-3xl font-black text-foreground"><?= htmlspecialchars($currentCategory['name']) ?></h1>
            <p class="text-sm text-muted-foreground mt-1"><?= (int)$total ?> ລາຍການ</p>
            <?php else: ?>
            <h1 class="text-2xl md:text-3xl font-black text-foreground">ສິນຄ້າທັງໝົດ</h1>
            <p class="text-sm text-muted-foreground mt-1">ພົບ <?= (int)$total ?> ລາຍການ</p>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-3">
            <select onchange="window.location.href=this.value" class="px-4 py-2.5 bg-card border border-border rounded-xl text-sm font-bold text-foreground/85 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="<?= url('/products') . (!empty($search) ? '?search=' . urlencode($search) : '') . (!empty($categoryId) ? (!empty($search) ? '&' : '?') . 'category_id=' . $categoryId : '') ?>" <?= $sort === 'newest' ? 'selected' : '' ?>>
                    ມາໃໝ່ສຸດ
                </option>
                <option value="<?= url('/products') . '?sort=price_asc' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($categoryId) ? '&category_id=' . $categoryId : '') ?>" <?= $sort === 'price_asc' ? 'selected' : '' ?>>
                    ລາຄາຕ່ຳ-ສູງ
                </option>
                <option value="<?= url('/products') . '?sort=price_desc' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($categoryId) ? '&category_id=' . $categoryId : '') ?>" <?= $sort === 'price_desc' ? 'selected' : '' ?>>
                    ລາຄາສູງ-ຕ່ຳ
                </option>
            </select>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-card rounded-2xl border border-border p-5 sticky top-28">
                <h3 class="text-sm font-black text-foreground uppercase tracking-wider mb-4">ໝວດສິນຄ້າ</h3>
                <div class="space-y-1">
                    <a href="<?= url('/products') ?><?= !empty($search) ? '?search=' . urlencode($search) : '' ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold transition-all <?= empty($categoryId) ? 'bg-sky-50 text-sky-600' : 'text-foreground/70 hover:bg-gray-50' ?>">
                        <span>ທັງໝົດ</span>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <?php
                    $catUrl = url('/category/' . htmlspecialchars($cat['slug']));
                    if (!empty($search)) $catUrl .= '?search=' . urlencode($search);
                    ?>
                    <a href="<?= $catUrl ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold transition-all <?= (!empty($categoryId) && $categoryId == $cat['id']) || (!empty($categorySlug) && $categorySlug === $cat['slug']) ? 'bg-sky-50 text-sky-600' : 'text-foreground/70 hover:bg-gray-50' ?>">
                        <span><?= htmlspecialchars($cat['name']) ?></span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1 min-w-0">
            <?php if (empty($products)): ?>
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-foreground mb-2">ບໍ່ພົບສິນຄ້າ</h3>
                <p class="text-sm text-muted-foreground mb-6">ບໍ່ມີສິນຄ້າທີ່ກົງກັບການຄົ້ນຫາຂອງທ່ານ</p>
                <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-sky-700 transition-all">
                    <i class="fas fa-arrow-left"></i> ກັບໄປສິນຄ້າທັງໝົດ
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
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
                        <div class="mt-3 flex items-center gap-2">
                            <button onclick="addToCart(<?= (int)$product['id'] ?>, 1)" class="flex-1 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                <i class="fas fa-cart-plus"></i> ເພີ່ມໃສ່ກະຕ່າ
                            </button>
                            <a href="<?= url('/products/' . htmlspecialchars($product['slug'])) ?>" class="w-10 h-10 rounded-xl border border-border flex items-center justify-center text-muted-foreground hover:border-sky-200 hover:text-sky-600 transition-all">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="mt-10 flex items-center justify-center gap-2">
                <?php if ($page > 1): ?>
                <a href="<?= url('/products') ?>?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($categoryId) ? '&category_id=' . $categoryId : '' ?><?= $sort !== 'newest' ? '&sort=' . $sort : '' ?>" class="h-10 px-4 rounded-xl border border-border flex items-center justify-center text-sm font-bold text-foreground/70 hover:bg-sky-50 hover:border-sky-200 hover:text-sky-600 transition-all">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= url('/products') ?>?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($categoryId) ? '&category_id=' . $categoryId : '' ?><?= $sort !== 'newest' ? '&sort=' . $sort : '' ?>" class="h-10 w-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all <?= $i === $page ? 'bg-sky-600 text-white shadow-lg shadow-sky-200' : 'border border-border text-foreground/70 hover:bg-sky-50 hover:border-sky-200 hover:text-sky-600' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                <a href="<?= url('/products') ?>?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($categoryId) ? '&category_id=' . $categoryId : '' ?><?= $sort !== 'newest' ? '&sort=' . $sort : '' ?>" class="h-10 px-4 rounded-xl border border-border flex items-center justify-center text-sm font-bold text-foreground/70 hover:bg-sky-50 hover:border-sky-200 hover:text-sky-600 transition-all">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
