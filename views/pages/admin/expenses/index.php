<div class="min-h-screen bg-background p-4 md:p-8" x-data="expenseApp()" x-init="init()">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ລາຍຈ່າຍ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການລາຍຈ່າຍຂອງຮ້ານ</p>
            </div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                <i class="fas fa-plus"></i>
                <span>ເພີ່ມລາຍຈ່າຍ</span>
            </button>
        </div>

        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-muted-foreground">ເດືອນ</label>
                    <input type="month" name="month" x-model="selectedMonth" @change="fetchExpenses()"
                           class="px-3 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex-1"></div>
                <div class="text-right">
                    <p class="text-xs text-muted-foreground">ລວມລາຍຈ່າຍທັງໝົດ</p>
                    <p class="text-2xl font-black text-red-600" x-text="formatPrice(totalExpenses)"></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 text-center" style="width:40px">
                                <input type="checkbox" @click="toggleAllExpenses" :checked="allExpensesSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ໝວດ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍການ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຈຳນວນເງິນ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="expenses.length === 0">
                            <tr>
                                <td colspan="7" class="py-3 px-2">
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                            <i class="fas fa-money-bill-wave text-2xl"></i>
                                        </div>
                                        <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີລາຍຈ່າຍ</p>
                                        <p class="text-sm text-muted-foreground mt-1">ລາຍຈ່າຍຈະສະແດງຢູ່ນີ້</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(expense, index) in expenses" :key="expense.id">
                            <tr class="border-b border-gray-50 last:border-0">
                                <td class="py-3 px-2 text-center">
                                    <input type="checkbox" :value="expense.id" x-model="selectedExpenses" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                </td>
                                <td class="py-3 px-2 text-muted-foreground text-sm text-center" x-text="index + 1"></td>
                                <td class="py-3 px-2 text-foreground/70" x-text="expense.date"></td>
                                <td class="py-3 px-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold"
                                          :class="getCategoryColor(expense.category)">
                                        <span x-text="expense.category_name || expense.category"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-foreground font-medium" x-text="expense.description"></td>
                                <td class="py-3 px-2 font-bold text-red-600" x-text="formatPrice(expense.amount)"></td>
                                <td class="py-3 px-2">
                                    <div class="flex items-center gap-1">
                                        <button @click="openEditModal(expense)" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>
                                        <button @click="confirmDelete(expense.id)" class="icon-btn icon-btn-delete" title="ລຶບ">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot x-show="selectedExpenses.length > 0" style="display: none">
                        <tr>
                            <td colspan="7" class="px-2 py-0">
                                <div class="border border-red-200 bg-red-50/80 rounded-xl px-5 py-3 flex items-center justify-between transition-all">
                                    <span class="text-sm font-bold text-red-700">
                                        <i class="fas fa-check-circle mr-1.5"></i>
                                        ເລືອກ <span x-text="selectedExpenses.length" class="text-red-600 text-base"></span> ລາຍການ
                                    </span>
                                    <button @click="confirmBulkDeleteExpenses()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-sm" style="background:#dc2626">
                                        <i class="fas fa-trash-alt"></i>
                                        ລຶບທັງໝົດ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Mobile Bulk Action Bar -->
            <div x-show="selectedExpenses.length > 0" style="display: none" class="fixed bottom-0 left-0 right-0 bg-card border-t shadow-2xl px-4 py-3 z-50 md:hidden">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-foreground/70">
                        ເລືອກ <span x-text="selectedExpenses.length" class="text-primary font-black"></span> ລາຍການ
                    </span>
                    <button @click="confirmBulkDeleteExpenses()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-lg" style="background:#dc2626">
                        <i class="fas fa-trash-alt"></i>
                        ລຶບທັງໝົດ
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white shadow-lg shadow-purple-200">
                    <i class="fas fa-tags text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-foreground">ຈັດການໝວດລາຍຈ່າຍ</h2>
                    <p class="text-xs text-muted-foreground">ເພີ່ມ ຫຼື ຈັດການໝວດລາຍຈ່າຍ</p>
                </div>
            </div>
            <div x-data="categoryManager()">
                <form @submit.prevent="addCategory()" class="flex gap-3 mb-4">
                    <input type="text" x-model="newCategoryName" required placeholder="ຊື່ໝວດລາຍຈ່າຍໃໝ່"
                           class="flex-1 px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl font-bold text-sm hover:from-purple-600 hover:to-purple-700 transition-all shadow-lg shadow-purple-200 active:scale-[0.97]">
                        <i class="fas fa-plus"></i> ເພີ່ມ
                    </button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                                <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຊື່ໝວດ</th>
                                <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider" style="width:120px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="categories.length === 0">
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-muted-foreground text-sm">ຍັງບໍ່ມີໝວດລາຍຈ່າຍ</td>
                                </tr>
                            </template>
                            <template x-for="(cat, index) in categories" :key="cat.id">
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-2 text-muted-foreground text-sm text-center" x-text="index + 1"></td>
                                    <td class="py-3 px-2">
                                        <template x-if="editingId !== cat.id">
                                            <span class="font-medium text-foreground" x-text="cat.name"></span>
                                        </template>
                                        <template x-if="editingId === cat.id">
                                            <input type="text" x-model="editName" @keydown.enter="updateCategory(cat.id)"
                                                   class="w-full px-3 py-1.5 border border-primary/50 rounded-lg focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                                        </template>
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-1 justify-end">
                                            <template x-if="editingId !== cat.id">
                                                <button @click="startEdit(cat)" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>
                                            </template>
                                            <template x-if="editingId === cat.id">
                                                <button @click="updateCategory(cat.id)" class="icon-btn" style="background:#ecfdf5;color:#059669" title="ບັນທຶກ">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            </template>
                                            <button @click="deleteCategory(cat.id)" class="icon-btn icon-btn-delete" title="ລຶບ">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
            function categoryManager() {
                return {
                    categories: <?= json_encode(array_map(function($c) { return ['id' => $c['id'], 'name' => $c['name']]; }, $expenseCategories ?? [])) ?>,
                    newCategoryName: '',
                    editingId: null,
                    editName: '',

                    addCategory() {
                        if (!this.newCategoryName.trim()) return;
                        const formData = new FormData();
                        formData.append('name', this.newCategoryName);
                        fetch('<?= url('/admin/expenses/category/add') ?>', {
                            method: 'POST', body: formData
                        }).then(() => {
                            this.newCategoryName = '';
                            location.reload();
                        });
                    },

                    startEdit(cat) {
                        this.editingId = cat.id;
                        this.editName = cat.name;
                    },

                    updateCategory(id) {
                        if (!this.editName.trim()) return;
                        const formData = new FormData();
                        formData.append('id', id);
                        formData.append('name', this.editName);
                        fetch('<?= url('/admin/expenses/category/edit') ?>', {
                            method: 'POST', body: formData
                        }).then(() => {
                            this.editingId = null;
                            location.reload();
                        });
                    },

                    deleteCategory(id) {
                        Swal.fire({
                            title: 'ທ່ານແນ່ໃຈບໍ່?',
                            text: 'ຕ້ອງການລຶບໝວດລາຍຈ່າຍນີ້ແທ້ບໍ່?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'ລຶບ',
                            cancelButtonText: 'ຍົກເລີກ',
                            reverseButtons: true,
                            customClass: { popup: 'rounded-3xl' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('id', id);
                                fetch('<?= url('/admin/expenses/category/delete') ?>', {
                                    method: 'POST', body: formData
                                }).then(() => location.reload());
                            }
                        });
                    }
                };
            }
            </script>
        </div>
    </div>

    <div id="expenseModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="closeModal(event)">
        <div class="bg-card rounded-2xl shadow-xl p-6 md:p-8 w-full max-w-md" onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-400 to-red-500 flex items-center justify-center text-white shadow-lg shadow-red-200">
                    <i class="fas fa-money-bill-wave text-sm"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-foreground" x-text="isEditing ? 'ແກ້ໄຂລາຍຈ່າຍ' : 'ເພີ່ມລາຍຈ່າຍ'"></h3>
                </div>
            </div>
            <form @submit.prevent="saveExpense()" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ວັນທີ <span class="text-red-400">*</span></label>
                    <input type="date" x-model="form.date" required class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ໝວດ <span class="text-red-400">*</span></label>
                    <select x-model="form.category" required class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="">-- ເລືອກໝວດ --</option>
                        <?php foreach ($expenseCategories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ລາຍການ <span class="text-red-400">*</span></label>
                    <input type="text" x-model="form.description" required placeholder="ລາຍລະອຽດລາຍຈ່າຍ"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-foreground/85">ຈຳນວນເງິນ <span class="text-red-400">*</span></label>
                    <input type="number" x-model="form.amount" min="0" required placeholder="0"
                           class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                        <i class="fas fa-save"></i> <span x-text="isEditing ? 'ບັນທຶກການແກ້ໄຂ' : 'ບັນທຶກ'"></span>
                    </button>
                    <button type="button" @click="closeModalWindow()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-foreground/85 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                        <i class="fas fa-times"></i> ຍົກເລີກ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function expenseApp() {
    return {
        expenses: <?= json_encode($expenses ?? []) ?>,
        expenseCategories: <?= json_encode($categories ?? []) ?>,
        selectedMonth: new Date().toISOString().slice(0, 7),
        isEditing: false,
        editingId: null,
        selectedExpenses: [],
        form: { date: new Date().toISOString().slice(0, 10), category: '', description: '', amount: '' },

        get allExpensesSelected() {
            return this.expenses.length > 0 && this.selectedExpenses.length === this.expenses.length;
        },

        toggleAllExpenses() {
            if (this.allExpensesSelected) {
                this.selectedExpenses = [];
            } else {
                this.selectedExpenses = this.expenses.map(e => e.id);
            }
        },

        confirmBulkDeleteExpenses() {
            if (this.selectedExpenses.length === 0) return;
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລົບ ' + this.selectedExpenses.length + ' ລາຍການນີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ແມ່ນ, ລົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    this.selectedExpenses.forEach(id => formData.append('ids[]', id));
                    fetch('<?= url('/admin/expenses/bulk-delete') ?>', { method: 'POST', body: formData })
                    .then(() => {
                        this.selectedExpenses = [];
                        this.fetchExpenses();
                        Swal.fire({ icon: 'success', title: 'ສຳເລັດ', text: 'ລຶບຂໍ້ມູນຮຽບຮ້ອຍ', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
                    });
                }
            });
        },

        init() {
            this.fetchExpenses();
        },

        fetchExpenses() {
            fetch('<?= url('/admin/expenses') ?>?month=' + this.selectedMonth, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.expenses) this.expenses = data.expenses;
                if (data.total) this.totalExpenses = data.total;
            })
            .catch(() => {});
        },

        get totalExpenses() {
            return this.expenses.reduce((sum, e) => sum + parseFloat(e.amount || 0), 0);
        },

        formatPrice(amount) {
            return new Intl.NumberFormat('lo-LA').format(amount) + ' ກີບ';
        },

        getCategoryColor(category) {
            const colors = ['bg-blue-50 text-blue-600', 'bg-green-50 text-green-600', 'bg-purple-50 text-purple-600', 'bg-amber-50 text-amber-600', 'bg-rose-50 text-rose-600', 'bg-cyan-50 text-cyan-600'];
            return colors[parseInt(category) % colors.length] || 'bg-gray-50 text-foreground/70';
        },

        openAddModal() {
            this.isEditing = false;
            this.editingId = null;
            this.form = { date: new Date().toISOString().slice(0, 10), category: '', description: '', amount: '' };
            document.getElementById('expenseModal').classList.remove('hidden');
        },

        openEditModal(expense) {
            this.isEditing = true;
            this.editingId = expense.id;
            this.form = {
                date: expense.date ? expense.date.slice(0, 10) : new Date().toISOString().slice(0, 10),
                category: expense.category,
                description: expense.description,
                amount: expense.amount
            };
            document.getElementById('expenseModal').classList.remove('hidden');
        },

        saveExpense() {
            const formData = new FormData();
            formData.append('expense_date', this.form.date);
            formData.append('category_id', this.form.category);
            formData.append('amount', this.form.amount);
            formData.append('description', this.form.description);
            if (this.isEditing) {
                formData.append('id', this.editingId);
            }

            const url = this.isEditing
                ? '<?= url('/admin/expenses/edit') ?>'
                : '<?= url('/admin/expenses/add') ?>';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (res.redirected) {
                    this.fetchExpenses();
                    this.closeModalWindow();
                    Swal.fire({ icon: 'success', title: 'ສຳເລັດ', text: 'ບັນທຶກຂໍ້ມູນສຳເລັດ', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
                } else {
                    Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: 'ບໍ່ສາມາດບັນທຶກໄດ້', confirmButtonColor: '#0ea5e9', customClass: { popup: 'rounded-3xl' } });
                }
            });
        },

        confirmDelete(id) {
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ຕ້ອງການລຶບລາຍຈ່າຍນີ້ແທ້ບໍ່?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ລຶບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = new FormData(); deleteForm.append('id', id);
                    fetch('<?= url('/admin/expenses/delete') ?>', { method: 'POST', body: deleteForm })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.fetchExpenses();
                            Swal.fire({ icon: 'success', title: 'ສຳເລັດ', text: 'ລຶບຂໍ້ມູນຮຽບຮ້ອຍ', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
                        }
                    });
                }
            });
        },

        closeModalWindow() {
            document.getElementById('expenseModal').classList.add('hidden');
        }
    };
}

function closeModal(event) {
    if (event.target === event.currentTarget) {
        event.target.classList.add('hidden');
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('expenseApp', expenseApp);
});
</script>
