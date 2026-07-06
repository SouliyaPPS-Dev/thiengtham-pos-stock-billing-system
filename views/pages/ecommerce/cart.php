<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
        <a href="<?= url('/') ?>" class="hover:text-sky-600 transition-colors"><i class="fas fa-home"></i></a>
        <span>/</span>
        <span class="text-foreground/85 font-bold">ກະຕ່າສິນຄ້າ</span>
    </div>

    <h1 class="text-2xl md:text-3xl font-black text-foreground mb-8">ກະຕ່າສິນຄ້າ</h1>

    <?php if (empty($cart)): ?>
    <!-- Empty Cart -->
    <div class="text-center py-20">
        <div class="w-28 h-28 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-shopping-cart text-5xl text-gray-300"></i>
        </div>
        <h3 class="text-xl font-bold text-foreground mb-2">ກະຕ່າສິນຄ້າວ່າງເປົ່າ</h3>
        <p class="text-muted-foreground mb-8">ທ່ານຍັງບໍ່ໄດ້ເພີ່ມສິນຄ້າໃສ່ກະຕ່າ</p>
        <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-sky-700 transition-all shadow-lg shadow-sky-200">
            <i class="fas fa-shopping-bag"></i> ເລີ່ມຊື້ເລີຍ
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div id="cart-items-container" class="lg:col-span-2 space-y-4">
            <?php foreach ($cart as $item): ?>
            <div class="bg-card rounded-2xl border border-border p-4 md:p-6 flex items-center gap-4" id="cart-item-<?= (int)$item['product_id'] ?>">
                <!-- Product Image -->
                <a href="<?= url('/products/' . htmlspecialchars($item['slug'])) ?>" class="w-20 h-20 md:w-24 md:h-24 rounded-xl bg-gray-50 overflow-hidden flex-shrink-0">
                    <?php if (!empty($item['image'])): ?>
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                    <?php endif; ?>
                </a>

                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <a href="<?= url('/products/' . htmlspecialchars($item['slug'])) ?>" class="text-sm md:text-base font-bold text-foreground hover:text-sky-600 transition-colors line-clamp-2"><?= htmlspecialchars($item['name']) ?></a>
                    <p class="text-sm md:text-base font-black text-sky-600 mt-1"><?= number_format((float)($item['price'] ?? 0), 0) ?> ກີບ</p>
                </div>

                <!-- Quantity Controls -->
                <div x-data="{ qty: <?= (int)($item['quantity'] ?? 1) ?> }" class="flex items-center border border-border rounded-xl overflow-hidden flex-shrink-0">
                    <button @click="if(qty > 1) { qty--; updateCart(<?= (int)$item['product_id'] ?>, qty) }" class="h-10 w-10 flex items-center justify-center text-muted-foreground hover:bg-gray-50 transition-colors">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <input type="number" x-model="qty" readonly class="h-10 w-12 text-center text-sm font-bold border-x border-border outline-none bg-card" value="<?= (int)($item['quantity'] ?? 1) ?>">
                    <button @click="qty++; updateCart(<?= (int)$item['product_id'] ?>, qty)" class="h-10 w-10 flex items-center justify-center text-muted-foreground hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>

                <!-- Subtotal -->
                <div class="text-right flex-shrink-0 min-w-[100px]">
                    <p id="subtotal-<?= (int)$item['product_id'] ?>" class="text-sm md:text-base font-black text-foreground"><?= number_format((float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1), 0) ?> ກີບ</p>
                </div>

                <!-- Remove -->
                <button onclick="removeFromCart(<?= (int)$item['product_id'] ?>)" class="h-10 w-10 rounded-xl flex items-center justify-center text-muted-foreground hover:bg-red-50 hover:text-red-500 transition-all flex-shrink-0">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Cart Totals -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-2xl border border-border p-6 sticky top-28">
                <h3 class="text-lg font-black text-foreground mb-4">ສະຫຼຸບລາຄາ</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">ລາຄາລວມ</span>
                        <span class="font-bold text-foreground"><?= number_format((float)$subtotal, 0) ?> ກີບ</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">ຄ່າຈັດສົ່ງ</span>
                        <span class="font-bold text-foreground">--</span>
                    </div>
                    <div class="border-t border-border pt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-black text-foreground">ລວມທັງໝົດ</span>
                            <span id="cart-total-amount" class="text-xl font-black text-sky-600"><?= number_format((float)$subtotal, 0) ?> ກີບ</span>
                        </div>
                    </div>
                </div>
                <a href="<?= url('/checkout') ?>" class="mt-6 w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg shadow-sky-200">
                    <i class="fas fa-check-circle"></i> ດຳເນີນການຊຳລະ
                </a>
                <a href="<?= url('/products') ?>" class="mt-3 w-full py-3 border border-border text-foreground/70 font-bold rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> ຊື້ສິນຄ້າເພີ່ມ
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function updateCart(productId, quantity) {
    if (quantity <= 0) return;
    fetch('<?= url('/cart/update') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            var badge = document.getElementById('cart-count-badge');
            if (badge) {
                badge.textContent = data.cartCount;
                badge.classList.toggle('hidden', data.cartCount <= 0);
            }
            var itemSubtotal = document.getElementById('subtotal-' + productId);
            if (itemSubtotal) {
                itemSubtotal.textContent = new Intl.NumberFormat('lo-LA').format(data.subtotal) + ' ກີບ';
            }
            var totalEl = document.getElementById('cart-total-amount');
            if (totalEl) {
                totalEl.textContent = new Intl.NumberFormat('lo-LA').format(data.subtotal) + ' ກີບ';
            }
        } else {
            Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: data.message });
        }
    });
}

function removeFromCart(productId) {
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບສິນຄ້ານີ້ອອກຈາກກະຕ່າ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ລຶບ',
        cancelButtonText: 'ຍົກເລີກ',
        reverseButtons: true,
        customClass: { popup: 'rounded-2xl' }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= url('/cart/remove') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: 'product_id=' + productId
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    var badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        badge.textContent = data.cartCount;
                        badge.classList.toggle('hidden', data.cartCount <= 0);
                    }
                    var item = document.getElementById('cart-item-' + productId);
                    if (item) item.remove();
                    var totalEl = document.getElementById('cart-total-amount');
                    if (totalEl) {
                        totalEl.textContent = new Intl.NumberFormat('lo-LA').format(data.subtotal) + ' ກີບ';
                    }
                    var itemsContainer = document.getElementById('cart-items-container');
                    if (itemsContainer && itemsContainer.children.length === 0) {
                        location.reload();
                    }
                }
            });
        }
    });
}
</script>
