<div class="min-h-[calc(100vh-56px)] lg:min-h-[calc(100vh-64px)] flex flex-col lg:flex-row bg-gray-50" x-data="posSystem()"
     @mousemove="onMouseMove($event)" @mouseup="onMouseUp()">

    <!-- Left Column: Product Selection -->
    <div class="flex flex-col min-w-0 bg-white shadow-sm" :style="{ width: isMobile ? '100%' : leftWidth + 'px' }">
        <!-- Search & Filter Bar -->
        <div class="p-3 md:p-4 border-b space-y-3">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-grow">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" x-model="search" placeholder="ຄົ້ນຫາຊື່, SKU, ບາໂຄດ..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                </div>
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    <button @click="categoryFilter = 'all'"
                            :class="categoryFilter === 'all' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap">
                        ທັງໝົດ
                    </button>
                    <?php foreach($categories as $cat): ?>
                    <button @click="categoryFilter = '<?= $cat['id'] ?>'"
                            :class="categoryFilter == '<?= $cat['id'] ?>' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap">
                        <?= $cat['name'] ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-3 md:p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                <template x-for="p in filteredProducts()" :key="p.id">
                    <div @click="addToCart(p)"
                         :class="p.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:shadow-xl hover:border-primary/20 active:scale-[0.97]'"
                         class="bg-white rounded-2xl border border-gray-100 p-2 transition-all relative overflow-hidden">

                        <div class="aspect-square rounded-xl overflow-hidden mb-2 bg-gray-50 relative">
                            <template x-if="p.image">
                                <img :src="p.image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </template>
                            <template x-if="!p.image">
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-box text-2xl"></i>
                                </div>
                            </template>
                            <div class="absolute top-1.5 right-1.5 flex gap-1">
                                <span :class="p.stock > 10 ? 'bg-green-100 text-green-700' : (p.stock > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600')" class="text-[9px] font-black px-1.5 py-0.5 rounded-md">
                                    <span x-text="'ສະຕ໋ອກ: ' + p.stock"></span>
                                </span>
                            </div>
                        </div>

                        <h3 class="text-xs font-bold text-gray-800 truncate px-0.5" x-text="p.name"></h3>
                        <p class="text-[10px] text-gray-400 truncate px-0.5" x-text="p.sku || ''"></p>
                        <div class="flex items-center justify-between mt-1.5 px-0.5">
                            <span class="text-sm font-black text-primary" x-text="formatPrice(p.selling_price)"></span>
                            <span class="text-[10px] font-bold text-gray-400" x-text="p.unit || 'ຊິ້ນ'"></span>
                        </div>
                    </div>
                </template>
            </div>

            <template x-if="filteredProducts().length === 0">
                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <p class="font-bold text-sm">ບໍ່ພົບລາຍການທີ່ທ່ານຄົ້ນຫາ</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Splitter Bar -->
    <div class="hidden lg:flex w-1.5 hover:bg-primary/30 cursor-col-resize items-center justify-center group transition-colors relative z-30 bg-gray-100"
         @mousedown="onMouseDown($event)">
        <div class="w-0.5 h-10 bg-gray-300 rounded-full group-hover:bg-primary transition-colors"></div>
    </div>

    <!-- Right Column: Checkout & Cart -->
    <div class="flex flex-col bg-white border-l shadow-xl z-20" :style="{ width: isMobile ? '100%' : rightWidth + 'px' }">

        <!-- Header -->
        <div class="p-3 md:p-4 border-b bg-white">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    ຕະກ້າຊື້ເຄື່ອງ
                </h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold bg-gray-100 px-2.5 py-1 rounded-full" x-text="cart.length + ' ລາຍການ'"></span>
                    <button @click="clearCart()" x-show="cart.length > 0" class="text-[10px] font-bold text-red-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Search -->
        <div class="p-3 md:p-4 border-b bg-gray-50/30">
            <div class="relative" x-data="{ open: false, cSearch: '' }" @click.away="open = false">
                <div class="relative">
                    <input type="text"
                           x-model="cSearch"
                           @focus="open = true"
                           placeholder="ຄົ້ນຫາຊື່ລູກຄ້າ ຫຼື ເບີໂທ..."
                           class="w-full pl-9 pr-8 py-2.5 bg-white border border-gray-100 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-user-search text-xs"></i>
                    </div>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300" x-show="selectedCustomer" @click="selectedCustomer = null; cSearch = ''" style="display:none">
                        <i class="fas fa-times-circle cursor-pointer hover:text-red-500"></i>
                    </div>
                </div>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="absolute left-0 right-0 mt-1 bg-white border rounded-2xl shadow-xl z-50 max-h-48 overflow-y-auto">
                    <template x-for="c in customers.filter(c => c.fullname.toLowerCase().includes(cSearch.toLowerCase()) || (c.phone || '').includes(cSearch))" :key="c.id">
                        <div @click="selectedCustomer = c; cSearch = c.fullname; open = false; saveState()"
                             class="p-2.5 hover:bg-gray-50 cursor-pointer border-b last:border-0 flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-gray-800" x-text="c.fullname"></div>
                                <div class="text-[10px] text-gray-400" x-text="c.phone || ''"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="customers.filter(c => c.fullname.toLowerCase().includes(cSearch.toLowerCase()) || (c.phone || '').includes(cSearch)).length === 0">
                        <div class="p-3 text-center text-xs text-gray-400">ບໍ່ພົບລູກຄ້າ</div>
                    </template>
                </div>
            </div>
            <template x-if="selectedCustomer">
                <div class="flex items-center gap-2 bg-primary/5 p-2 rounded-xl border border-primary/10 mt-2">
                    <div class="w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-xs">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate" x-text="selectedCustomer.fullname"></p>
                        <p class="text-[10px] text-gray-500" x-text="selectedCustomer.phone || ''"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-3 md:p-4 space-y-2">
            <template x-for="(item, index) in cart" :key="index">
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 hover:border-primary/20 transition-all flex gap-3 group">
                    <div class="w-14 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                        <template x-if="item.image">
                            <img :src="item.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!item.image">
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box text-lg"></i></div>
                        </template>
                    </div>
                    <div class="flex-grow min-w-0 flex flex-col justify-between py-0.5">
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-bold text-gray-800 truncate pr-2" x-text="item.name"></h4>
                            <button @click="removeFromCart(index)" class="text-gray-300 hover:text-red-500 transition-colors flex-shrink-0">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400" x-text="formatPrice(item.price) + ' / ' + (item.unit || 'ຊິ້ນ')"></p>
                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center bg-gray-50 rounded-lg p-0.5 border">
                                <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary rounded-md transition-colors"><i class="fas fa-minus text-[9px]"></i></button>
                                <span class="w-7 text-center text-xs font-bold text-gray-700" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary rounded-md transition-colors"><i class="fas fa-plus text-[9px]"></i></button>
                            </div>
                            <span class="text-xs font-bold text-primary" x-text="formatPrice(item.price * item.qty)"></span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-16 text-gray-300">
                    <i class="fas fa-shopping-basket text-4xl mb-3"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">ກະຕ່າຫວ່າງເປົ່າ</p>
                    <p class="text-[10px] text-gray-400 mt-1">ເລືອກສິນຄ້າຢູ່ເບື້ອງຊ້າຍ</p>
                </div>
            </template>
        </div>

        <!-- Totals & Payment -->
        <div class="border-t bg-gray-50/50 p-3 md:p-4 space-y-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="space-y-1.5 bg-white p-3 rounded-2xl border shadow-sm">
                <div class="flex justify-between text-xs font-bold text-gray-500">
                    <span>ຍອດລວມ</span>
                    <span x-text="formatPrice(subtotal())"></span>
                </div>
                <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                    <span>ສ່ວນຫຼຸດ</span>
                    <input type="number" x-model="discount" @input="calculateGrandTotal()" min="0" class="w-24 px-2 py-1 bg-gray-50 border border-gray-100 rounded-lg text-right text-xs font-bold text-primary focus:ring-1 focus:ring-primary outline-none" placeholder="0">
                </div>
                <div class="pt-2 border-t border-dashed flex justify-between items-end">
                    <span class="text-sm font-bold text-gray-800">ລວມທັງໝົດ</span>
                    <span class="text-xl font-black text-green-600" x-text="formatPrice(grandTotal)"></span>
                </div>
            </div>

            <div class="space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="method in paymentMethods" :key="method.id">
                        <button @click="selectedPaymentMethod = method.name"
                                :class="selectedPaymentMethod == method.name ? 'bg-primary text-white border-primary shadow-md' : 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50'"
                                class="py-2.5 rounded-xl border text-[10px] font-bold transition-all flex items-center justify-center gap-1.5">
                            <i class="fas fa-credit-card text-xs"></i>
                            <span x-text="method.name"></span>
                        </button>
                    </template>
                </div>

                <div class="relative" x-data="{ editingPaid: false }">
                    <label class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">ຮັບເງິນ</label>
                    <template x-if="!editingPaid">
                        <div @click="editingPaid = true"
                             class="cursor-pointer w-full pl-16 pr-3 py-3 bg-white border-2 border-gray-100 rounded-xl text-lg font-black text-right transition-all hover:border-primary/30"
                             x-text="formatPrice(paidAmount)"></div>
                    </template>
                    <template x-if="editingPaid">
                        <input type="number" x-model="paidAmount" @blur="editingPaid = false" @keydown.enter="editingPaid = false"
                               x-ref="paidInput" x-init="$nextTick(() => $refs.paidInput.focus())"
                               class="w-full pl-16 pr-3 py-3 bg-white border-2 border-primary/30 rounded-xl text-lg font-black text-right focus:ring-2 focus:ring-primary outline-none transition-all">
                    </template>
                </div>

                <div class="bg-white p-3 rounded-xl border border-dashed border-gray-200 flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ເງິນທອນ</span>
                    <span class="text-lg font-black" :class="paidAmount - grandTotal >= 0 ? 'text-green-600' : 'text-red-500'" x-text="formatPrice(Math.max(0, paidAmount - grandTotal))"></span>
                </div>

                <button @click="confirmCheckout()"
                        :disabled="cart.length === 0"
                        :class="cart.length === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:scale-[1.01] active:scale-[0.98]'"
                        class="w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white py-3.5 rounded-2xl font-bold text-sm uppercase tracking-wider shadow-lg shadow-sky-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-base"></i>
                    ຢືນຢັນການຊຳລະ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function posSystem() {
    return {
        search: '',
        categoryFilter: 'all',
        products: <?= json_encode($products ?? []) ?>,
        customers: <?= json_encode($customers ?? []) ?>,
        paymentMethods: <?= json_encode($paymentMethods ?? []) ?>,

        cart: [],
        selectedCustomer: null,
        discount: 0,
        paidAmount: 0,
        grandTotal: 0,
        selectedPaymentMethod: 'ເງິນສົດ',

        // Splitter
        leftWidth: window.innerWidth * 0.65,
        rightWidth: window.innerWidth * 0.35,
        isResizing: false,
        isMobile: window.innerWidth < 1024,

        init() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (!this.isMobile) {
                    this.leftWidth = window.innerWidth * 0.65;
                    this.rightWidth = window.innerWidth * 0.35;
                }
            });
            this.loadState();
        },

        saveState() {
            const data = {
                cart: this.cart,
                selectedCustomerId: this.selectedCustomer?.id || null,
                discount: this.discount,
                paidAmount: this.paidAmount,
                selectedPaymentMethod: this.selectedPaymentMethod,
            };
            localStorage.setItem('pos_state', JSON.stringify(data));
        },

        loadState() {
            const saved = localStorage.getItem('pos_state');
            if (!saved) return;
            try {
                const data = JSON.parse(saved);
                if (data.cart) this.cart = data.cart;
                if (data.selectedCustomerId) {
                    this.selectedCustomer = this.customers.find(c => c.id == data.selectedCustomerId) || null;
                }
                if (data.discount !== undefined) this.discount = data.discount;
                if (data.paidAmount !== undefined) this.paidAmount = data.paidAmount;
                if (data.selectedPaymentMethod) this.selectedPaymentMethod = data.selectedPaymentMethod;
                this.calculateGrandTotal();
            } catch (e) {}
        },

        clearState() {
            localStorage.removeItem('pos_state');
        },

        onMouseDown(e) {
            this.isResizing = true;
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
        },

        onMouseMove(e) {
            if (!this.isResizing) return;
            const newLeft = e.clientX;
            const newRight = window.innerWidth - e.clientX;
            if (newLeft > 350 && newRight > 320) {
                this.leftWidth = newLeft;
                this.rightWidth = newRight;
            }
        },

        onMouseUp() {
            this.isResizing = false;
            document.body.style.cursor = 'default';
            document.body.style.userSelect = 'auto';
        },

        filteredProducts() {
            return this.products.filter(p => {
                const s = this.search.toLowerCase().trim();
                const matchSearch = !s || p.name.toLowerCase().includes(s) || (p.sku || '').toLowerCase().includes(s) || (p.barcode || '').toLowerCase().includes(s);
                const matchCategory = this.categoryFilter === 'all' || String(p.category_id) === String(this.categoryFilter);
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
                    return;
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.selling_price),
                    qty: 1,
                    unit: product.unit || 'ຊິ້ນ',
                    image: product.image || null,
                    maxQty: product.stock
                });
            }
            this.calculateGrandTotal();
            this.saveState();
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.calculateGrandTotal();
            this.saveState();
        },

        updateQty(index, delta) {
            const item = this.cart[index];
            const newQty = item.qty + delta;
            if (newQty < 1) { this.removeFromCart(index); return; }
            if (delta > 0 && newQty > item.maxQty) {
                Swal.fire({ icon: 'warning', title: 'ສິນຄ້າບໍ່ພຽງພໍ', text: 'ສະຕ໋ອກຄົງເຫຼືອພຽງ ' + item.maxQty + ' ຊິ້ນ', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                return;
            }
            item.qty = newQty;
            this.calculateGrandTotal();
            this.saveState();
        },

        clearCart() {
            this.cart = [];
            this.discount = 0;
            this.calculateGrandTotal();
            this.saveState();
        },

        subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        calculateGrandTotal() {
            this.grandTotal = Math.max(0, this.subtotal() - Number(this.discount || 0));
            if (this.paidAmount === 0 || this.paidAmount < this.grandTotal) {
                this.paidAmount = this.grandTotal;
            }
        },

        formatPrice(amount) {
            return new Intl.NumberFormat('lo-LA').format(amount) + ' ກີບ';
        },

        confirmCheckout() {
            if (this.cart.length === 0) return;

            const missing = [];
            if (Number(this.paidAmount) < this.grandTotal) missing.push('ຈຳນວນເງິນທີ່ຮັບຕ້ອງຫຼາຍກວ່າ ຫຼື ເທົ່າກັບຍອດລວມ');

            if (missing.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ກະລຸນາກວດສອບຂໍ້ມູນ',
                    html: `<div class="text-left space-y-1">${missing.map(m => `<div class="flex items-center gap-2 text-sm"><i class="fas fa-exclamation-circle text-red-400 text-xs"></i> ${m}</div>`).join('')}</div>`,
                    confirmButtonText: 'ຕົກລົງ',
                    confirmButtonColor: '#0ea5e9',
                    customClass: { popup: 'rounded-3xl' }
                });
                return;
            }

            const changeAmount = Math.max(0, this.paidAmount - this.grandTotal);
            const customerName = this.selectedCustomer?.fullname || 'ລູກຄ້າທົ່ວໄປ';
            const customerPhone = this.selectedCustomer?.phone || '';

            Swal.fire({
                title: 'ຢືນຢັນການຊຳລະ?',
                html: `<div class="text-left text-sm space-y-2">
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <p class="text-gray-500 text-xs">ລູກຄ້າ</p>
                            <p class="font-bold text-gray-800">${customerName}${customerPhone ? ' (' + customerPhone + ')' : ''}</p>
                        </div>
                        <div class="flex justify-between px-1">
                            <span class="text-gray-500">ຍອດລວມ</span>
                            <span class="font-bold">${this.formatPrice(this.subtotal())}</span>
                        </div>
                        ${Number(this.discount) > 0 ? `<div class="flex justify-between px-1"><span class="text-gray-500">ສ່ວນຫຼຸດ</span><span class="font-bold text-red-500">-${this.formatPrice(Number(this.discount))}</span></div>` : ''}
                        <div class="flex justify-between px-1 pt-1 border-t font-bold">
                            <span>ລວມທັງໝົດ</span>
                            <span class="text-primary text-lg">${this.formatPrice(this.grandTotal)}</span>
                        </div>
                        <div class="flex justify-between px-1 text-xs">
                            <span class="text-gray-500">ຊຳລະດ້ວຍ</span>
                            <span class="font-bold">${this.selectedPaymentMethod}</span>
                        </div>
                        <div class="flex justify-between px-1 text-xs">
                            <span class="text-gray-500">ເງິນທອນ</span>
                            <span class="font-bold text-green-600">${this.formatPrice(changeAmount)}</span>
                        </div>
                      </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check-circle"></i> ຢືນຢັນ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) this.processCheckout();
            });
        },

        processCheckout() {
            const btn = document.querySelector('.w-full.bg-gradient-to-r');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ກຳລັງດຳເນີນການ...';

            fetch('<?= url('/admin/pos/checkout') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    customer_id: this.selectedCustomer?.id || null,
                    customer_name: this.selectedCustomer?.fullname || '',
                    customer_phone: this.selectedCustomer?.phone || '',
                    discount: Number(this.discount),
                    payment_method: this.selectedPaymentMethod,
                    amount_paid: Number(this.paidAmount),
                    items: this.cart.map(item => ({
                        product_id: item.id,
                        quantity: item.qty,
                        price: item.price
                    }))
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ຊຳລະເງິນສຳເລັດ',
                        html: `<div class="text-center space-y-2">
                                <div class="bg-primary/5 p-3 rounded-xl">
                                    <p class="text-xs text-gray-500">ໃບເກັບເງິນເລກທີ</p>
                                    <p class="font-bold text-lg text-primary">${data.invoice_number || ''}</p>
                                </div>
                                <p class="text-2xl font-black text-green-600">${this.formatPrice(this.grandTotal)}</p>
                              </div>`,
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
                        this.clearState();
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: data.message || 'ບໍ່ສາມາດດຳເນີນການໄດ້', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                    btn.innerHTML = 'ຢືນຢັນການຊຳລະ';
                }
            })
            .catch(() => {
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: 'ການເຊື່ອມຕໍ່ລົ້ມເຫຼວ', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                btn.innerHTML = 'ຢືນຢັນການຊຳລະ';
            });
        }
    };
}
</script>