<?php
// PHP logic (assume $expenses, $categories, $totalAmount, $currentMonth are available from controller)
?>
 
<div class="p-4 md:p-8" x-data="{ 
    showAddModal: false, 
    showEditModal: false,
    showCategoryModal: false,
    currentExpense: { id: '', expense_date: '<?= date('Y-m-d') ?>', category_id: '', amount: '', description: '' },
    currentCategory: { id: '', name: '' },
    deleteExpense(id) {
        Swal.fire({
            title: 'ທ່ານແນ່ໃຈບໍ່?',
            text: 'ທ່ານຕ້ອງການລຶບລາຍການນີ້ແທ້ບໍ່?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'ລຶບອອກ',
            cancelButtonText: 'ຍົກເລີກ',
            customClass: {
                popup: 'rounded-3xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const params = new URLSearchParams();
                params.append('id', id);
                
                fetch('<?= url('/expenses/delete') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: params.toString()
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = '<?= url("/expenses?deleted=1") ?>';
                    } else {
                        Swal.fire('ເກີດຂໍ້ຜິດພາດ', data.message || 'ລຶບບໍ່ສຳເລັດ', 'error');
                    }
                }).catch(() => {
                    Swal.fire('ເກີດຂໍ້ຜິດພາດ', 'ບໍ່ສາມາດຕິດຕໍ່ເຊີເວີໄດ້', 'error');
                });
            }
        });
    },
    openEdit(item) {
        this.currentExpense = Object.assign({}, item);
        this.showEditModal = true;
    },
    openEditCategory(cat) {
        this.currentCategory = Object.assign({}, cat);
    }
}">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-800">ບັນທຶກລາຍຈ່າຍ</h1>
                <p class="text-gray-500 text-sm">ຈັດການຂໍ້ມູນລາຍຈ່າຍປະຈຳເດືອນ</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showCategoryModal = true" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 h-11 px-4">
                    <i class="fas fa-tags mr-2"></i> ປະເພດລາຍຈ່າຍ
                </button>
                <button @click="showAddModal = true" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 h-11 px-6">
                    <i class="fas fa-plus mr-2"></i> ເພີ່ມລາຍຈ່າຍ
                </button>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-2xl border shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ລວມລາຍຈ່າຍເດືອນນີ້</p>
                        <h3 class="text-2xl font-black text-red-600"><?= number_format($totalAmount) ?> ກີບ</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters - Modern & Flexible -->
        <div class="bg-white p-2 sm:p-4 rounded-2xl border shadow-sm">
            <form id="filterForm" action="<?= url('/expenses') ?>" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="w-full sm:w-72">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                            <i class="far fa-calendar-alt text-sm"></i>
                        </div>
                        <input type="month" name="month" value="<?= $currentMonth ?>" 
                               @change="$el.form.submit()"
                               class="w-full bg-gray-50 border-none rounded-xl pl-10 pr-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all cursor-pointer">
                    </div>
                </div>
                
                <div class="flex items-center gap-2 flex-grow sm:flex-grow-0">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-primary text-white rounded-xl text-sm font-black hover:bg-primary/90 transition-all shadow-lg shadow-primary/10 whitespace-nowrap">
                        <i class="fas fa-filter mr-2"></i> ຄັດຕອງ
                    </button>

                    <a href="<?= url('/expenses') ?>" class="inline-flex items-center justify-center w-full sm:w-auto px-6 h-12 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-all border border-transparent text-sm font-black" title="Reset">
                        <i class="fas fa-redo mr-2"></i> 
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop table view -->
        <div class="hidden md:block bg-white rounded-2xl border overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">ວັນທີ</th>
                        <th class="px-6 py-4">ປະເພດລາຍຈ່າຍ</th>
                        <th class="px-6 py-4">ລາຍລະອຽດ</th>
                        <th class="px-6 py-4 text-right">ຈຳນວນເງິນ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    <?php if (empty($expenses)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-folder-open text-4xl text-gray-100"></i>
                                <p class="font-bold">ບໍ່ມີຂໍ້ມູນລາຍຈ່າຍໃນເດືອນນີ້</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($expenses as $item): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4"><?= date('d/m/Y', strtotime($item['expense_date'])) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-100 rounded-lg text-[10px] font-black uppercase tracking-tight"><?= htmlspecialchars($item['category_name']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium"><?= htmlspecialchars($item['description']) ?></td>
                            <td class="px-6 py-4 text-right font-black text-red-600"><?= number_format($item['amount']) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openEdit(<?= htmlspecialchars(json_encode($item)) ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100 text-xs font-bold" title="ແກ້ໄຂ">
                                        <i class="fas fa-edit"></i> ແກ້ໄຂ
                                    </button>
                                    <button @click="deleteExpense(<?= $item['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100 text-xs font-bold" title="ລຶບ">
                                        <i class="fas fa-trash"></i> ລຶບ
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile card view -->
        <div class="md:hidden space-y-4">
            <?php if (empty($expenses)): ?>
            <div class="bg-white rounded-2xl border p-12 text-center text-gray-400">
                <i class="fas fa-folder-open text-4xl mb-2 block text-gray-100"></i>
                <p class="font-bold">ບໍ່ມີຂໍ້ມູນລາຍຈ່າຍ</p>
            </div>
            <?php else: foreach($expenses as $item): ?>
            <div class="bg-white rounded-2xl border p-4 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest"><?= date('d/m/Y', strtotime($item['expense_date'])) ?></div>
                        <div class="font-bold text-gray-800"><?= htmlspecialchars($item['category_name']) ?></div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-black text-red-600"><?= number_format($item['amount']) ?> ກີບ</div>
                    </div>
                </div>
                <div class="text-sm text-gray-500 bg-gray-50 p-2 rounded-lg border border-dashed">
                    <?= htmlspecialchars($item['description']) ?>
                </div>
                <div class="flex gap-2 justify-end border-t border-gray-50 pt-3">
                    <button @click="openEdit(<?= htmlspecialchars(json_encode($item)) ?>)" class="flex-1 py-2.5 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center gap-1.5 text-xs font-bold hover:bg-amber-100 transition-colors" title="ແກ້ໄຂ">
                        <i class="fas fa-edit"></i> ແກ້ໄຂ
                    </button>
                    <button @click="deleteExpense(<?= $item['id'] ?>)" class="flex-1 py-2.5 rounded-xl bg-red-50 text-red-500 flex items-center justify-center gap-1.5 text-xs font-bold hover:bg-red-100 transition-colors" title="ລຶບ">
                        <i class="fas fa-trash"></i> ລຶບ
                    </button>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="showAddModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.window.escape="showAddModal = false"
         class="fixed inset-0 z-50 overflow-y-auto p-4 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showAddModal = false">
            <div class="p-6 border-b flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-gray-800">ເພີ່ມລາຍຈ່າຍໃໝ່</h3>
                <button @click="showAddModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= url('/expenses/add') ?>" method="POST" class="p-6 space-y-7">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ວັນທີລາຍຈ່າຍ</label>
                    <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required 
                           class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ປະເພດລາຍຈ່າຍ</label>
                    <select name="category_id" required 
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all appearance-none cursor-pointer">
                        <option value="">-- ເລືອກປະເພດ --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ຈຳນວນເງິນ (ກີບ)</label>
                    <input type="number" name="amount" placeholder="0" required 
                           class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-lg font-black text-red-600 outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ລາຍລະອຽດເພີ່ມເຕີມ</label>
                    <textarea name="description" rows="3" 
                              class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-medium outline-none focus:ring-2 focus:ring-primary transition-all" 
                              placeholder="ລະບຸລາຍລະອຽດ..."></textarea>
                </div>
                <br>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-sm">
                        <i class="fas fa-save mr-2"></i> ບັນທຶກລາຍຈ່າຍ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.window.escape="showEditModal = false"
         class="fixed inset-0 z-50 overflow-y-auto p-4 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showEditModal = false">
            <div class="p-6 border-b flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-gray-800">ແກ້ໄຂລາຍຈ່າຍ</h3>
                <button @click="showEditModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= url('/expenses/edit') ?>" method="POST" class="p-6 space-y-7">
                <input type="hidden" name="id" x-model="currentExpense.id">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ວັນທີລາຍຈ່າຍ</label>
                    <input type="date" name="expense_date" x-model="currentExpense.expense_date" required 
                           class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ປະເພດລາຍຈ່າຍ</label>
                    <select name="category_id" x-model="currentExpense.category_id" required 
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all appearance-none cursor-pointer">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ຈຳນວນເງິນ (ກີບ)</label>
                    <input type="number" name="amount" x-model="currentExpense.amount" required 
                           class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-lg font-black text-red-600 outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <br>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">ລາຍລະອຽດ</label>
                    <textarea name="description" x-model="currentExpense.description" rows="3" 
                              class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-medium outline-none focus:ring-2 focus:ring-primary transition-all"></textarea>
                </div>
                <br>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-sm">
                        <i class="fas fa-save mr-2"></i> ບັນທຶກການແກ້ໄຂ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Management Modal -->
    <div x-show="showCategoryModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.window.escape="showCategoryModal = false"
         class="fixed inset-0 z-50 overflow-y-auto p-4 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showCategoryModal = false">
            <div class="p-6 border-b flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-gray-800">ຈັດການປະເພດລາຍຈ່າຍ</h3>
                <button @click="showCategoryModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6">
                <!-- Add Category Form -->
                <form action="<?= url('/expenses/category/add') ?>" method="POST" class="flex items-center gap-3 mb-6">
                    <div class="flex-1">
                        <input type="text" name="name" placeholder="ປ້ອນຊື່ປະເພດລາຍຈ່າຍໃໝ່..." required
                               class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold outline-none focus:ring-2 focus:ring-primary transition-all">
                    </div>
                    <button type="submit" class="w-12 h-12 flex items-center justify-center bg-primary text-white rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex-shrink-0">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>

                <!-- Category List -->
                <div class="space-y-2">
                    <?php foreach($categories as $cat): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors group">
                        <span class="text-sm font-bold text-gray-700"><?= htmlspecialchars($cat['name']) ?></span>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="<?= url('/expenses/category/edit') ?>" method="POST" class="flex items-center gap-1" x-data="{ editing: false, name: '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>' }">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <template x-if="!editing">
                                    <button type="button" @click="editing = true" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all" title="ແກ້ໄຂ">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </template>
                                <template x-if="editing">
                                    <div class="flex items-center gap-1">
                                        <input type="text" name="name" x-model="name" required
                                               class="w-32 bg-white border border-gray-200 rounded-lg px-2 py-1.5 text-xs font-bold outline-none focus:ring-2 focus:ring-primary transition-all">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all" title="ບັນທຶກ">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button type="button" @click="editing = false" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-200 text-gray-500 hover:bg-gray-300 transition-all" title="ຍົກເລີກ">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </form>
                            <form action="<?= url('/expenses/category/delete') ?>" method="POST" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່? ຕ້ອງການລຶບປະເພດນີ້ແທ້ບໍ?')">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" title="ລຶບ">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
