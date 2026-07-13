<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໝວດສິນຄ້າ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການໝວດສິນຄ້າ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-tags text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ເພີ່ມໝວດໃໝ່</h2>
                            <p class="text-xs text-muted-foreground">ສ້າງໝວດສິນຄ້າໃໝ່</p>
                        </div>
                    </div>
                    <form action="<?= url('/admin/categories/store') ?>" method="POST" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-tag text-[10px] text-violet-500"></i>
                                ຊື່ໝວດ <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm" placeholder="ຊື່ໝວດສິນຄ້າ">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-align-left text-[10px] text-violet-500"></i>
                                ລາຍລະອຽດ
                            </label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm resize-none" placeholder="ລາຍລະອຽດໝວດສິນຄ້າ"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-500 to-violet-600 text-white rounded-xl font-bold text-sm hover:from-violet-600 hover:to-violet-700 transition-all shadow-lg shadow-violet-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            ບັນທຶກ
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-list text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ລາຍການໝວດສິນຄ້າ</h2>
                            <p class="text-xs text-muted-foreground">ຈັດການໝວດສິນຄ້າທັງໝົດ</p>
                        </div>
                    </div>
                    <?php $categoryIds = array_map('strval', array_column($categories, 'id')); ?>
                    <div x-data="categoriesBulkDelete()" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="py-3 px-2 text-center" style="width:40px">
                                        <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                    </th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຊື່ໝວດ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຍລະອຽດ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຈຳນວນສິນຄ້າ</th>
                                    <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="6" class="py-3 px-2">
                                        <div class="flex flex-col items-center justify-center py-12 text-center">
                                            <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                                <i class="fas fa-tags text-2xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີໝວດສິນຄ້າ</p>
                                            <p class="text-sm text-muted-foreground mt-1">ໝວດສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php $i = 0; ?>
                                <?php foreach ($categories as $cat): $i++; ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 text-center">
                                        <input type="checkbox" :value="<?= $cat['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                    </td>
                                    <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                                    <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                                    <td class="py-3 px-2 text-foreground/70"><?= (int)($cat['product_count'] ?? 0) ?></td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-1">
                                            <button onclick="editCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['description'] ?? '', ENT_QUOTES) ?>')" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                            <a href="<?= url('/admin/categories/' . $cat['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
                                                <i class="fas fa-trash text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (!empty($categories)): ?>
                        <tfoot x-show="selected.length > 0">
                            <tr>
                                <td colspan="6" class="px-2 py-0">
                                    <div class="border border-red-200 bg-red-50/80 rounded-xl px-5 py-3 flex items-center justify-between transition-all">
                                        <span class="text-sm font-bold text-red-700">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            ເລືອກ <span x-text="selected.length" class="text-red-600 text-base"></span> ລາຍການ
                                        </span>
                                        <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-sm" style="background:#dc2626">
                                            <i class="fas fa-trash-alt"></i>
                                            ລຶບທັງໝົດ
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Bulk Action Bar -->
                    <div x-show="selected.length > 0" class="fixed bottom-0 left-0 right-0 bg-card border-t shadow-2xl px-4 py-3 z-50 md:hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-foreground/70">
                                ເລືອກ <span x-text="selected.length" class="text-primary font-black"></span> ລາຍການ
                            </span>
                            <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-lg" style="background:#dc2626">
                                <i class="fas fa-trash-alt"></i>
                                ລຶບທັງໝົດ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="closeEditModal(event)">
    <div class="bg-card rounded-2xl shadow-xl p-6 md:p-8 w-full max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                <i class="fas fa-pen text-sm"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-foreground">ແກ້ໄຂໝວດສິນຄ້າ</h3>
                <p class="text-xs text-muted-foreground">ແກ້ໄຂຂໍ້ມູນໝວດສິນຄ້າ</p>
            </div>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                    <i class="fas fa-tag text-[10px] text-violet-500"></i>
                    ຊື່ໝວດ <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                    <i class="fas fa-align-left text-[10px] text-violet-500"></i>
                    ລາຍລະອຽດ
                </label>
                <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm resize-none"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-500 to-violet-600 text-white rounded-xl font-bold text-sm hover:from-violet-600 hover:to-violet-700 transition-all shadow-lg shadow-violet-200 active:scale-[0.97]">
                    <i class="fas fa-save"></i> ບັນທຶກຂໍ້ມູນ
                </button>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-foreground/85 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                    <i class="fas fa-times"></i> ຍົກເລີກ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function categoriesBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($categoryIds) ?>,
        get allSelected() {
            return this.allIds.length > 0 && this.selected.length === this.allIds.length;
        },
        toggleAll() {
            if (this.allSelected) {
                this.selected = [];
            } else {
                this.selected = [...this.allIds];
            }
        },
        confirmBulkDelete() {
            if (this.selected.length === 0) return;
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລົບ ' + this.selected.length + ' ລາຍການນີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ແມ່ນ, ລົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url('/admin/categories/bulk-delete') ?>';
                    this.selected.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    };
}

function editCategory(id, name, description) {
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editForm').action = '<?= url('/admin/categories') ?>/' + id + '/update';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal(event) {
    if (event.target === event.currentTarget) {
        document.getElementById('editModal').classList.add('hidden');
    }
}

function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບໝວດສິນຄ້ານີ້ແທ້ບໍ່?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ລຶບ',
        cancelButtonText: 'ຍົກເລີກ',
        reverseButtons: true,
        customClass: { popup: 'rounded-3xl' }
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
}
</script>
