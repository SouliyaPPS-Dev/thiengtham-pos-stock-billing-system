<div class="min-h-[calc(100vh-56px)] lg:min-h-[calc(100vh-64px)] flex flex-col lg:flex-row bg-gray-100" x-data="posSystem()" @mousemove="onMouseMove($event)" @mouseup="onMouseUp()"> 
    <!-- Left Column: Product Selection -->
    <div class="flex flex-col min-w-0 bg-white shadow-sm" :style="{ width: isMobile ? '100%' : leftWidth + 'px' }">
        <!-- Search & Filter Bar -->
        <div class="p-4 border-b space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" x-model="search" placeholder="ຄົ້ນຫາຊື່ຊຸດ, ລະຫັດ ຫຼື ໄຊສ໌..." 
                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary outline-none transition-all">
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                    <button @click="categoryFilter = 'all'" 
                            :class="categoryFilter === 'all' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-6 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap">
                        ທັງໝົດ
                    </button>
                    <?php foreach($categories as $cat): ?>
                    <button @click="categoryFilter = '<?= $cat['id'] ?>'" 
                            :class="categoryFilter == '<?= $cat['id'] ?>' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-6 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap">
                        <?= $cat['name'] ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="p-4 bg-gray-50/30">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
                <template x-for="p in filteredProducts()" :key="p.id">
                    <div @click="addToCart(p)" 
                         class="group bg-white rounded-2xl border border-gray-100 p-2 hover:shadow-xl hover:border-primary/20 transition-all cursor-pointer relative overflow-hidden">
                        
                        <div class="absolute top-3 right-3 z-10">
                            <span :class="getStatusClass(p.status)" class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm" x-text="getStatusText(p.status)"></span>
                        </div>

                        <div class="aspect-[3/4] rounded-xl overflow-hidden mb-3 bg-gray-50 relative">
                            <img :src="p.image || 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=400&fit=crop'" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-colors"></div>
                        </div>

                        <div class="px-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="p.code"></span>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] font-black px-1.5 py-0.5 bg-gray-100 rounded-md text-gray-500" x-text="p.size"></span>
                                    <span :class="p.stock > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500'" class="text-[9px] font-black px-1.5 py-0.5 rounded-md" x-text="'ສະຕ໋ອກ: ' + p.stock"></span>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 truncate" x-text="p.name"></h3>
                            <div class="flex items-baseline justify-between pt-1">
                                <span class="text-xs font-black text-primary" x-text="formatPrice(p.rental_price)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <template x-if="filteredProducts().length === 0">
                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <p class="font-bold">ບໍ່ພົບລາຍການທີ່ທ່ານຄົ້ນຫາ</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Splitter Bar -->
    <div class="hidden lg:flex w-2 hover:bg-primary/30 cursor-col-resize items-center justify-center group transition-colors relative z-30" 
         @mousedown="onMouseDown($event)">
        <div class="w-1 h-12 bg-gray-300 rounded-full group-hover:bg-primary transition-colors"></div>
    </div>

    <!-- Right Column: Checkout & Cart -->
    <div class="flex flex-col bg-white border-l shadow-2xl z-20" :style="{ width: isMobile ? '100%' : rightWidth + 'px' }">
        <!-- Customer Selection (Searchable) -->
        <div class="p-4 border-b space-y-3">
            <div class="flex items-center justify-between">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">ຂໍ້ມູນລູກຄ້າ</label>
                <a href="<?= url('/customers/create') ?>" class="text-[10px] font-bold text-primary hover:underline"><i class="fas fa-plus-circle"></i> ເພີ່ມລູກຄ້າໃໝ່</a>
            </div> 
            
            <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                <div class="relative">
                    <input type="text"  
                           x-model="search"
                           @focus="open = true"
                           placeholder="ຄົ້ນຫາຊື່ລູກຄ້າ ຫຼື ເບີໂທ..."
                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary outline-none transition-all">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-user-search text-sm"></i>
                    </div>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300" x-show="selectedCustomer" @click="selectedCustomer = null; search = ''">
                        <i class="fas fa-times-circle cursor-pointer hover:text-red-500"></i>
                    </div>
                </div>

                <!-- Dropdown -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="absolute left-0 right-0 mt-2 bg-white border rounded-2xl shadow-xl z-50 max-h-64 overflow-y-auto">
                    <template x-for="c in customers.filter(c => c.fullname.toLowerCase().includes(search.toLowerCase()) || c.phone.includes(search))" :key="c.id">
                        <div @click="selectedCustomer = c; search = c.fullname; open = false; saveState()" 
                             class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-0 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-bold text-gray-800" x-text="c.fullname"></div>
                                <div class="text-[10px] text-gray-400" x-text="c.phone"></div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-sky-50 text-sky-600 rounded-full" x-text="c.customer_type"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Selected Customer Info -->
            <template x-if="selectedCustomer">
                <div class="flex items-center gap-3 bg-primary/5 p-3 rounded-2xl border border-primary/10">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-800" x-text="selectedCustomer.fullname"></p>
                        <p class="text-[10px] text-gray-500" x-text="selectedCustomer.phone"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Rental Dates -->
        <div class="p-4 border-b bg-gray-50/50">
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider">ວັນທີຮັບຊຸດ</label>
                    <input type="date" x-model="pickupDate" 
                           class="w-full px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider">ວັນທີສົ່ງຄືນ</label>
                    <input type="date" x-model="returnDate" 
                           class="w-full px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold text-red-600 focus:ring-2 focus:ring-red-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="p-4 space-y-3">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">ລາຍການໃນກະຕ່າ (<span x-text="cart.length"></span>)</h3>
                <button @click="clearCart()" x-show="cart.length > 0" class="text-[10px] font-bold text-red-500 hover:underline">ລ້າງກະຕ່າ</button>
            </div>

            <template x-for="(item, index) in cart" :key="index">
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 hover:border-primary/20 transition-all flex gap-3 group relative">
                    <div class="w-16 h-20 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                        <img :src="item.image" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0 flex flex-col justify-between py-0.5">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-gray-800 truncate pr-4" x-text="item.name"></h4>
                                <button @click="removeFromCart(index)" class="text-gray-300 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400" x-text="item.code + ' • Size ' + item.size"></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center bg-gray-50 rounded-lg p-0.5 border">
                                <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary"><i class="fas fa-minus text-[10px]"></i></button>
                                <span class="w-8 text-center text-xs font-black text-gray-700" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary"><i class="fas fa-plus text-[10px]"></i></button>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-primary" x-text="formatPrice(item.rental_price * item.qty)"></p>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-12 text-gray-300 opacity-50">
                    <i class="fas fa-shopping-basket text-4xl mb-4"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">ກະຕ່າຫວ່າງເປົ່າ</p>
                </div>
            </template>
        </div>

        <!-- Guarantee Options -->
        <div class="p-4 bg-white border-t space-y-3">
            <label class="text-[10px] font-black uppercase tracking-widest transition-colors" 
                   :class="!(guarantee.idCard || guarantee.passport || guarantee.familyBook || guarantee.cash) ? 'text-red-500' : 'text-gray-400'">
                ກະລຸນາກົດເລືອກ ເອກະສານຄ້ຳປະກັນ <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border cursor-pointer hover:bg-gray-100 transition-all">
                    <input type="checkbox" x-model="guarantee.idCard" class="w-4 h-4 rounded text-primary focus:ring-primary">
                    <span class="text-xs font-bold text-gray-600">ບັດປະຈຳຕົວ</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border cursor-pointer hover:bg-gray-100 transition-all">
                    <input type="checkbox" x-model="guarantee.passport" class="w-4 h-4 rounded text-primary focus:ring-primary">
                    <span class="text-xs font-bold text-gray-600">ພາດສະປອດ</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border cursor-pointer hover:bg-gray-100 transition-all">
                    <input type="checkbox" x-model="guarantee.familyBook" class="w-4 h-4 rounded text-primary focus:ring-primary">
                    <span class="text-xs font-bold text-gray-600">ສຳມະໂນຄົວ</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border cursor-pointer hover:bg-gray-100 transition-all">
                    <input type="checkbox" x-model="guarantee.cash" @change="calculateGrandTotal()" class="w-4 h-4 rounded text-primary focus:ring-primary">
                    <span class="text-xs font-bold text-gray-600">ມັດຈຳເງິນ</span>
                </label>
            </div>
        </div>

        <!-- Totals & Payment -->
        <div class="p-4 bg-gray-50 border-t space-y-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="space-y-2 bg-white p-4 rounded-2xl border shadow-sm">
                <div class="flex justify-between text-xs font-bold text-gray-500">
                    <span>ຄ່າເຊົ່າທັງໝົດ</span>
                    <span x-text="formatPrice(subtotal())"></span>
                </div>
                <template x-if="guarantee.cash">
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                        <span>ຄ່າມັດຈຳທັງໝົດ</span>
                        <input type="number" x-model="deposit" @input="calculateGrandTotal()" class="w-32 px-2 py-1 bg-gray-50 border border-gray-100 rounded-lg text-right text-xs font-black text-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </template>
                <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                    <span>ສ່ວນຫຼຸດ</span>
                    <input type="number" x-model="discount" @input="calculateGrandTotal()" class="w-32 px-2 py-1 bg-gray-50 border border-gray-100 rounded-lg text-right text-xs font-black text-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
                <div class="pt-2 border-t border-dashed flex justify-between items-end">
                    <span class="text-sm font-black text-gray-800 uppercase">ຍອດລວມຄ່າເຊົ່າ</span>
                    <span class="text-2xl font-black text-green-600" x-text="formatPrice(grandTotal)"></span>
                </div>

            </div> 

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <template x-for="method in paymentMethods" :key="method.id">
                        <button @click="selectedPaymentMethodId = method.id" 
                                :class="selectedPaymentMethodId == method.id ? 'bg-primary text-white border-primary shadow-md' : 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50'"
                                class="py-3.5 rounded-xl border text-[11px] font-black uppercase transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-credit-card"></i>
                            <span x-text="method.name === 'QR CODE' ? 'QR CODE' : method.name"></span>
                        </button>
                    </template>
                </div>
                
                <div class="relative" x-data="{ editingPaid: false }">
                    <label class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">ຮັບເງິນແລ້ວ</label>
                    <template x-if="!editingPaid">
                        <div @click="editingPaid = true" class="cursor-pointer w-full pl-20 pr-4 py-4 bg-white text-black border-2 border-gray-100 rounded-2xl text-2xl font-black text-right transition-all hover:border-primary/30" x-text="formatPrice(paidAmount)"></div>
                    </template>
                    <template x-if="editingPaid">
                        <input type="number" x-model="paidAmount" @blur="editingPaid = false" @keydown.enter="editingPaid = false" x-ref="paidInput" x-init="$nextTick(() => $refs.paidInput.focus())" class="w-full pl-20 pr-4 py-4 bg-white text-black border-2 border-gray-100 rounded-2xl text-2xl font-black text-right focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    </template> 
                </div>                
                
                <div class="bg-white p-4 rounded-2xl border border-dashed border-gray-200 flex justify-between items-center">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">ເງິນທອນ</span>
                    <span class="text-2xl font-black" :class="paidAmount - grandTotal >= 0 ? 'text-green-600' : 'text-red-500'" x-text="formatPrice(Math.max(0, paidAmount - grandTotal))"></span>
                </div>

                <!-- Payment Status Selection -->
                <div class="grid grid-cols-2 gap-3">
                    <button @click="paymentStatus = 'Paid'; saveState()" 
                            :class="paymentStatus === 'Paid' ? 'bg-green-500 text-white border-green-500 shadow-lg shadow-green-100' : 'bg-white text-green-500 border-green-100 hover:bg-green-50'"
                            class="py-3.5 rounded-xl border text-xs font-black uppercase transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>ຈ່າຍແລ້ວ</span>
                    </button>
                    <button @click="paymentStatus = 'Debt'; saveState()" 
                            :class="paymentStatus === 'Debt' ? 'bg-yellow-400 text-black border-yellow-400 shadow-lg shadow-yellow-200 ring-2 ring-yellow-200' : 'bg-yellow-50 text-yellow-700 border-yellow-300 hover:bg-yellow-100'"
                            class="py-3.5 rounded-xl border text-xs font-black uppercase transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>ຕິດໜີ້</span>
                    </button>
                </div>
            </div>

            <button @click="confirmRental()" 
                    class="w-full bg-primary hover:bg-primary/90 text-white py-4 rounded-2xl font-black text-lg uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                ຢືນຢັນການເຊົ່າ
            </button>        
            <br>
            <br>
        </div>
    </div>
</div>

<script>
function posSystem() {
    return {
        search: '',
        categoryFilter: 'all',
        products: <?= json_encode($products) ?>,
        customers: <?= json_encode($customers) ?>,
        paymentMethods: <?= json_encode($paymentMethods) ?>,
        settings: <?= json_encode($settings) ?>,
        
        cart: [],
        selectedCustomer: null,
        pickupDate: (() => { const d = new Date(); return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0'); })(),
        returnDate: (() => { const d = new Date(); d.setDate(d.getDate() + 3); return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0'); })(),
        discount: 0,
        deposit: 0,
        paidAmount: 0,
        paymentStatus: 'Paid',
        selectedPaymentMethodId: '<?= $paymentMethods[0]['id'] ?? '' ?>',
        grandTotal: 0,
        
        guarantee: {
            idCard: false,
            passport: false,
            familyBook: false,
            cash: false
        },

        // Splitter logic
        leftWidth: window.innerWidth * 0.7,
        rightWidth: window.innerWidth * 0.3,
        isResizing: false,
        isMobile: window.innerWidth < 1024,

        init() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (!this.isMobile) {
                    this.leftWidth = window.innerWidth * 0.7;
                    this.rightWidth = window.innerWidth * 0.3;
                }
            });
            this.loadState();
        },

        saveState() {
            const data = {
                cart: this.cart,
                selectedCustomerId: this.selectedCustomer?.id || null,
                pickupDate: this.pickupDate,
                returnDate: this.returnDate,
                discount: this.discount,
                deposit: this.deposit,
                paidAmount: this.paidAmount,
                paymentStatus: this.paymentStatus,
                selectedPaymentMethodId: this.selectedPaymentMethodId,
                guarantee: { ...this.guarantee }
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
                if (data.pickupDate) this.pickupDate = data.pickupDate;
                if (data.returnDate) this.returnDate = data.returnDate;
                if (data.discount !== undefined) this.discount = data.discount;
                if (data.deposit !== undefined) this.deposit = data.deposit;
                if (data.paidAmount !== undefined) this.paidAmount = data.paidAmount;
                if (data.paymentStatus) this.paymentStatus = data.paymentStatus;
                if (data.selectedPaymentMethodId) this.selectedPaymentMethodId = data.selectedPaymentMethodId;
                if (data.guarantee) this.guarantee = data.guarantee;
                this.calculateGrandTotal();
            } catch (e) {
                console.warn('Failed to load POS state', e);
            }
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
            const newLeftWidth = e.clientX;
            const newRightWidth = window.innerWidth - e.clientX;
            if (newLeftWidth > 300 && newRightWidth > 300) {
                this.leftWidth = newLeftWidth;
                this.rightWidth = newRightWidth;
            }
        },

        onMouseUp() {
            this.isResizing = false;
            document.body.style.cursor = 'default';
            document.body.style.userSelect = 'auto';
        },

        filteredProducts() {
            return this.products.filter(p => {
                const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                     p.code.toLowerCase().includes(this.search.toLowerCase()) ||
                                     p.size.toLowerCase().includes(this.search.toLowerCase());
                const matchesCategory = this.categoryFilter === 'all' || p.category_id == this.categoryFilter;
                return matchesSearch && matchesCategory;
            });
        },

        addToCart(product) {
            let existing = this.cart.find(item => item.id === product.id);
            let currentQty = existing ? existing.qty : 0;
            let requestedQty = currentQty + 1;
            if (requestedQty > product.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ສິນຄ້າບໍ່ພຽງພໍ',
                    html: `<div class="text-sm">ສິນຄ້ານີ້ມີສະຕ໊ອກພຽງ <strong>${product.stock}</strong> ຊຸດເທົ່ານັ້ນ</div>`,
                    confirmButtonText: 'ຕົກລົງ',
                    confirmButtonColor: '#0ea5e9',
                    borderRadius: '15px'
                });
                return;
            }
            if (existing) {
                existing.qty++;
            } else {
                this.cart.push({
                    id: product.id,
                    code: product.code,
                    name: product.name,
                    image: product.image,
                    size: product.size,
                    rental_price: parseFloat(product.rental_price),
                    qty: 1
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
            let item = this.cart[index];
            let newQty = item.qty + delta;
            let product = this.products.find(p => p.id === item.id);
            if (delta > 0 && product && newQty > product.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ສິນຄ້າບໍ່ພຽງພໍ',
                    html: `<div class="text-sm">ສິນຄ້ານີ້ມີສະຕ໊ອກພຽງ <strong>${product.stock}</strong> ຊຸດເທົ່ານັ້ນ</div>`,
                    confirmButtonText: 'ຕົກລົງ',
                    confirmButtonColor: '#0ea5e9',
                    borderRadius: '15px'
                });
                return;
            }
            this.cart[index].qty = newQty;
            if (this.cart[index].qty <= 0) {
                this.removeFromCart(index);
            }
            this.calculateGrandTotal();
            this.saveState();
        },

        clearCart() {
            this.cart = [];
            this.deposit = 0;
            this.calculateGrandTotal();
            this.saveState();
        },

        subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.rental_price * item.qty), 0);
        },

        calculateGrandTotal() {
            if (this.guarantee.cash) {
                this.grandTotal = this.subtotal() - parseFloat(this.discount || 0) + parseFloat(this.deposit || 0);
            } else {
                this.deposit = 0;
                this.grandTotal = this.subtotal() - parseFloat(this.discount || 0);
            }

            if (this.paidAmount === 0 || this.paidAmount != this.grandTotal) {
                this.paidAmount = this.grandTotal;
            }
        },

        canCheckout() {
            const hasGuarantee = this.guarantee.idCard || this.guarantee.passport || this.guarantee.familyBook || this.guarantee.cash;
            return this.cart.length > 0 && this.selectedCustomer !== null && this.pickupDate && this.returnDate && hasGuarantee;
        },

        confirmRental() {
            const missing = [];
            if (this.cart.length === 0) missing.push('ເລືອກສິນຄ້າກ່ອນ');
            if (this.selectedCustomer === null) missing.push('ເລືອກລູກຄ້າກ່ອນ');
            if (!this.pickupDate) missing.push('ກຳນົດວັນທີຮັບ');
            if (!this.returnDate) missing.push('ກຳນົດວັນທີສົ່ງ');
            const hasGuarantee = this.guarantee.idCard || this.guarantee.passport || this.guarantee.familyBook || this.guarantee.cash;
            if (!hasGuarantee) missing.push('ເລືອກເອກະສານຄ້ຳປະກັນຢ່າງໜ້ອຍ 1 ຢ່າງ');
            if (this.guarantee.cash && (!this.deposit || parseFloat(this.deposit) <= 0)) missing.push('ກະລຸນາປ້ອນຈຳນວນ ຄ່າມັດຈຳທັງໝົດ');

            if (missing.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ກະລຸນາຕື່ມຂໍ້ມູນທີ່ຈຳເປັນ',
                    html: `<div class="text-left space-y-2">
                            ${missing.map(m => `<div class="flex items-center gap-2 text-sm"><i class="fas fa-exclamation-circle text-red-400 text-xs"></i> ${m}</div>`).join('')}
                          </div>`,
                    confirmButtonText: 'ຕົກລົງ',
                    confirmButtonColor: '#0ea5e9',
                    borderRadius: '15px'
                });
                return;
            }

            const changeAmount = Math.max(0, this.paidAmount - this.grandTotal);

            Swal.fire({
                title: 'ຢືນຢັນການອອກບິນ?',
                html: `<div class="text-left text-sm space-y-3">
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <p class="text-gray-500 text-xs">ລູກຄ້າ</p>
                            <p class="font-bold text-gray-800">${this.selectedCustomer.fullname} (${this.selectedCustomer.phone})</p>
                        </div>
                        <div class="flex justify-between items-center px-1">
                            <span class="text-gray-500">ຍອດລວມທັງໝົດ</span>
                            <span class="font-black text-lg text-primary">${this.formatPrice(this.grandTotal)}</span>
                        </div>
                        <div class="flex justify-between items-center px-1">
                            <span class="text-gray-500">ຮັບເງິນມາ</span>
                            <span class="font-bold">${this.formatPrice(this.paidAmount)}</span>
                        </div>
                        <div class="flex justify-between items-center px-1 pb-2 border-b">
                            <span class="text-gray-500">ເງິນທອນ</span>
                            <span class="font-bold text-green-600">${this.formatPrice(changeAmount)}</span>
                        </div>
                        <div class="flex justify-between items-center px-1">
                            <span class="text-gray-500">ວັນທີຮັບ</span>
                            <span class="font-bold">${this.formatDate(this.pickupDate)}</span>
                        </div>
                        <div class="flex justify-between items-center px-1">
                            <span class="text-gray-500">ວັນທີສົ່ງ</span>
                            <span class="font-bold">${this.formatDate(this.returnDate)}</span>
                        </div>
                      </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ຢືນຢັນ, ອອກບິນ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.processCheckout();
                }
            });
        },

        processCheckout() {
            const data = {
                customer_id: this.selectedCustomer.id,
                pickup_date: this.pickupDate,
                return_date: this.returnDate,
                items: this.cart,
                subtotal: this.subtotal(),
                total_deposit: this.deposit,
                discount: this.discount,
                grand_total: this.grandTotal,
                paid_amount: this.paidAmount,
                payment_status: this.paymentStatus,
                payment_method_id: this.selectedPaymentMethodId,
                guarantee: this.guarantee
            };

            fetch('<?= url("/pos/checkout") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ສຳເລັດ!',
                        html: `<div class="text-center space-y-2">
                                <div class="bg-primary/5 p-3 rounded-xl">
                                    <p class="text-xs text-gray-500">ບິນເລກທີ</p>
                                    <p class="font-black text-lg text-primary">${res.invoice_number}</p>
                                </div>
                              </div>`,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fas fa-print mr-2"></i>ພິມບິນ',
                        confirmButtonColor: '#0ea5e9',
                        showCancelButton: true,
                        cancelButtonText: 'ປິດ',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        borderRadius: '15px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('<?= url("/print-invoice") ?>' + '/' + res.rental_id, '_blank');
                        }
                        this.clearState();
                        location.reload();
                    });
                } else {
                    Swal.fire('ເກີດຂໍ້ຜິດພາດ', res.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('ເກີດຂໍ້ຜິດພາດ', 'ບໍ່ສາມາດຕິດຕໍ່ເຊີເວີໄດ້', 'error');
            });
        },

        getStatusClass(status) {
            const classes = {
                'Available': 'bg-green-100 text-green-600',
                'Rented': 'bg-blue-100 text-blue-600',
                'Cleaning': 'bg-sky-100 text-sky-600',
                'Repairing': 'bg-amber-100 text-amber-600',
                'Inactive': 'bg-red-100 text-red-600'
            };
            return classes[status] || 'bg-gray-100 text-gray-600';
        },

        getStatusText(status) {
            const texts = {
                'Available': 'ພ້ອມເຊົ່າ',
                'Rented': 'ກຳລັງເຊົ່າ',
                'Cleaning': 'ຊັກລີດ',
                'Repairing': 'ສ້ອມແປງ',
                'Inactive': 'ປິດໃຊ້ງານ'
            };
            return texts[status] || status;
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        },

        formatPrice(price) {
            return new Intl.NumberFormat().format(price) + ' ' + (this.settings?.currency || 'ກີບ');
        }
    };
}
</script>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
    