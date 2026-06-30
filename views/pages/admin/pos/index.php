<div class="h-full flex flex-col" x-data="posApp()" x-init="init()" x-cloak>
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">
        <div class="flex-1 flex flex-col overflow-hidden border-r">
            <div class="p-3 md:p-4 border-b bg-white space-y-3">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" x-model="search" @input="filterProducts()" placeholder="ຄົ້ນຫາສິນຄ້າ..." class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    </div>
                    <div>
                        <select x-model="selectedCategory" @change="filterProducts()" class="px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                            <option value="">ທຸກໝວດ</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 md:p-4">
                <template x-if="filteredProducts.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3"></i>
                        <p class="text-sm">ບໍ່ພົບສິນຄ້າ</p>
                    </div>
                </template>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 md:gap-3">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" :class="{'opacity-50 cursor-not-allowed': product.stock <= 0}" class="bg-white rounded-2xl border border-gray-100 p-3 hover:shadow-lg hover:border-primary/20 transition-all cursor-pointer active:scale-[0.98]">
                            <div class="h-16 w-full rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center text-gray-400 mb-2 overflow-hidden">
                                <template x-if="product.image">
                                    <img :src="product.image" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!product.image">
                                    <i class="fas fa-box text-lg"></i>
                                </template>
                            </div>
                            <p class="text-xs font-bold text-gray-800 truncate" x-text="product.name"></p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs font-bold text-primary" x-text="formatPrice(product.selling_price)"></span>
                                <span class="text-[10px] font-bold" :class="product.stock > 10 ? 'text-green-600' : (product.stock > 0 ? 'text-amber-600' : 'text-red-600')" x-text="'ສະຕ໋ອກ: ' + product.stock"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-96 xl:w-[420px] flex flex-col bg-white">
            <div class="p-3 md:p-4 border-b">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    ຕະກ້າຊື້ເຄື່ອງ
                    <span class="ml-auto text-sm font-bold bg-gray-100 px-2.5 py-0.5 rounded-full" x-text="cart.length + ' ລາຍການ'"></span>
                </h2>
            </div>

            <div class="flex-1 overflow-y-auto p-3 md:p-4 space-y-2">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-3">
                            <i class="fas fa-shopping-cart text-2xl"></i>
                        </div>
                        <p class="text-xs font-bold text-gray-600">ຍັງບໍ່ມີສິນຄ້າ</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">ເລືອກສິນຄ້າເພື່ອເພີ່ມໃສ່ກະຕ່າ</p>
                    </div>
                </template>
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0 mr-2">
                                <p class="text-xs font-bold text-gray-800 truncate" x-text="item.name"></p>
                                <p class="text-[10px] text-gray-500" x-text="formatPrice(item.price) + ' / ' + (item.unit || 'ຊິ້ນ')"></p>
                            </div>
                            <button @click="removeItem(index)" class="h-7 w-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center border rounded-lg bg-white">
                                <button @click="updateQty(index, item.qty - 1)" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-primary/10 hover:text-primary transition-all flex items-center justify-center text-xs font-bold text-gray-500">-</button>
                                <input type="number" x-model="item.qty" @input="updateQtyInput(index, $event)" min="1" class="h-8 w-12 text-center text-sm font-bold border-x outline-none" :max="item.maxQty || 9999">
                                <button @click="updateQty(index, item.qty + 1)" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-primary/10 hover:text-primary transition-all flex items-center justify-center text-xs font-bold text-gray-500">+</button>
                            </div>
                            <p class="text-xs font-bold text-primary" x-text="formatPrice(item.price * item.qty)"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="border-t p-3 md:p-4 space-y-3">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-600">ລູກຄ້າ</label>
                    <select x-model="customerId" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="0">ລູກຄ້າທົ່ວໄປ</option>
                        <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> <?= !empty($c['phone']) ? '- ' . htmlspecialchars($c['phone']) : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">ຍອດລວມ</span>
                        <span class="text-gray-800 font-bold" x-text="formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">ສ່ວນຫຼຸດ</span>
                        <input type="number" x-model="discount" min="0" class="w-24 text-right px-2 py-1 border rounded-lg text-sm font-bold" placeholder="0">
                    </div>
                    <div class="flex items-center justify-between py-2 border-t">
                        <span class="text-base font-bold text-gray-700">ລວມທັງໝົດ</span>
                        <span class="text-xl font-black text-primary" x-text="formatPrice(grandTotal)"></span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-600">ວິທີຊຳລະ</label>
                    <select x-model="paymentMethod" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="cash">ເງິນສົດ</option>
                        <option value="transfer">ໂອນທະນາຄານ</option>
                        <option value="qr">QR Scan</option>
                        <option value="credit">ເງິນກູ້</option>
                    </select>
                </div>

                <div class="space-y-1.5" x-show="paymentMethod === 'cash'">
                    <label class="text-xs font-bold text-gray-600">ຈຳນວນເງິນທີ່ຮັບ</label>
                    <input type="number" x-model="amountPaid" min="0" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm font-bold">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">ເງິນທອນ</span>
                        <span class="font-bold" :class="change >= 0 ? 'text-green-600' : 'text-red-600'" x-text="formatPrice(change)"></span>
                    </div>
                </div>

                <button type="button" @click="checkout()" :disabled="cart.length === 0" :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'" class="w-full py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.98] inline-flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>ດຳເນີນການຊຳລະເງິນ</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function posApp() {
    return {
        search: '',
        selectedCategory: '',
        products: <?= json_encode($products ?? []) ?>,
        filteredProducts: [],
        cart: [],
        customerId: 0,
        discount: 0,
        paymentMethod: 'cash',
        amountPaid: 0,
        loading: false,

        init() {
            this.filteredProducts = [...this.products];
        },

        filterProducts() {
            const s = this.search.toLowerCase().trim();
            const cat = this.selectedCategory;
            this.filteredProducts = this.products.filter(p => {
                const matchSearch = !s || p.name.toLowerCase().includes(s) || (p.sku && p.sku.toLowerCase().includes(s)) || (p.barcode && p.barcode.toLowerCase().includes(s));
                const matchCategory = !cat || String(p.category_id) === String(cat);
                return matchSearch && matchCategory;
            });
        },

        addToCart(product) {
            if (product.stock <= 0) return;
            const existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.qty < product.stock) {
                    existing.qty++;
                } else {
                    Swal.fire({ icon: 'warning', title: 'ສິນຄ້າບໍ່ພຽງພໍ', text: 'ສະຕ໋ອກຄົງເຫຼືອພຽງ ' + product.stock + ' ຊິ້ນ', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.selling_price),
                    qty: 1,
                    unit: product.unit || 'ຊິ້ນ',
                    maxQty: product.stock
                });
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        updateQty(index, qty) {
            if (qty < 1) {
                this.cart.splice(index, 1);
                return;
            }
            const item = this.cart[index];
            if (qty > item.maxQty) qty = item.maxQty;
            item.qty = qty;
        },

        updateQtyInput(index, event) {
            let val = parseInt(event.target.value) || 1;
            const item = this.cart[index];
            if (val < 1) val = 1;
            if (val > item.maxQty) val = item.maxQty;
            item.qty = val;
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get grandTotal() {
            return Math.max(0, this.subtotal - Number(this.discount));
        },

        get change() {
            return Number(this.amountPaid) - this.grandTotal;
        },

        formatPrice(amount) {
            return new Intl.NumberFormat('lo-LA').format(amount) + ' ກີບ';
        },

        checkout() {
            if (this.cart.length === 0) return;
            if (this.paymentMethod === 'cash' && Number(this.amountPaid) < this.grandTotal) {
                Swal.fire({ icon: 'warning', title: 'ເງິນບໍ່ພຽງພໍ', text: 'ກະລຸນາປ້ອນຈຳນວນເງິນທີ່ຮັບໃຫ້ຖືກຕ້ອງ', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                return;
            }
            this.loading = true;
            const btn = event.currentTarget;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ກຳລັງດຳເນີນການ...';

            fetch('<?= url('/admin/pos/checkout') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    customer_id: this.customerId,
                    discount: Number(this.discount),
                    payment_method: this.paymentMethod,
                    amount_paid: Number(this.amountPaid),
                    items: this.cart.map(item => ({
                        product_id: item.id,
                        quantity: item.qty,
                        price: item.price
                    }))
                })
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                btn.disabled = false;
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ຊຳລະເງິນສຳເລັດ',
                        html: '<p>ໃບເກັບເງິນ #' + data.invoice_number + '</p><p class="text-sm text-gray-500 mt-1">' + this.formatPrice(data.grand_total) + '</p>',
                        showCancelButton: true,
                        confirmButtonColor: '#0ea5e9',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-print"></i> ພິມໃບບິນ',
                        cancelButtonText: 'ປິດ',
                        reverseButtons: true,
                        customClass: { popup: 'rounded-3xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('<?= url('/admin/invoices') ?>/' + data.sale_id + '/print', '_blank');
                        }
                        this.cart = [];
                        this.discount = 0;
                        this.amountPaid = 0;
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: data.message || 'ບໍ່ສາມາດດຳເນີນການໄດ້', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> ດຳເນີນການຊຳລະເງິນ';
                }
            })
            .catch(err => {
                this.loading = false;
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: 'ການເຊື່ອມຕໍ່ລົ້ມເຫຼວ', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                btn.innerHTML = '<i class="fas fa-check-circle"></i> ດຳເນີນການຊຳລະເງິນ';
            });
        }
    };
}
</script>
