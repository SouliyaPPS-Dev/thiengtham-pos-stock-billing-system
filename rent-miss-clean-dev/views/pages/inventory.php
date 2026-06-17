<div class="p-4 md:p-8" x-data="inventoryModal()"> 
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-800">ສາງຊຸດໄໝທັງໝົດ</h1>
                <p class="text-gray-500 text-sm">ຈັດການຂໍ້ມູນຊຸດໄໝ ແລະ ກວດສອບສະຖານະ</p>
            </div>
            <div class="flex gap-2">
                <button @click="showCategoryModal = true" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200 h-11 px-6 border">
                    <i class="fas fa-tags mr-2"></i> ຈັດການໝວດໝູ່
                </button>
                <button @click="showAddModal = true" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 h-11 px-6">
                    <i class="fas fa-plus mr-2"></i> ເພີ່ມຊຸດໃໝ່
                </button>
            </div>
        </div>
        
        <!-- Search and Filters -->
        <div class="bg-white p-4 rounded-xl border flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" x-model="search" placeholder="ຄົ້ນຫາຊື່ຊຸດ ຫຼື ລະຫັດ..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="flex gap-2">
                <select x-model="categoryFilter" class="bg-white border rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
                    <option>ທັງໝົດ</option>
                    <?php foreach($categories as $cat): ?>
                    <option><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select x-model="statusFilter" class="bg-white border rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
                    <option>ທັງໝົດ</option>
                    <option value="Available">ພ້ອມເຊົ່າ</option>
                    <option value="Rented">ກຳລັງເຊົ່າ</option>
                    <option value="Cleaning">ກຳລັງຊັກລີດ</option>
                    <option value="Repairing">ກຳລັງສ້ອມແປງ</option>
                </select>
                <button @click="resetFilters()" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo mr-1"></i> ລ້າງຟິວເຕີ
                </button>
            </div>
        </div>

        <!-- Desktop table view -->
        <div class="hidden md:block bg-white rounded-xl border overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">ຮູບພາບ</th>
                        <th class="px-6 py-4">ລະຫັດ</th>
                        <th class="px-6 py-4">ຊື່ຊຸດໄໝ</th>
                        <th class="px-6 py-4">ປະເພດ</th>
                        <th class="px-6 py-4">ໄຊສ໌ (Size)</th>
                        <th class="px-6 py-4 text-center">ສະຕ໊ອກ</th>
                        <th class="px-6 py-4">ສະຖານະ</th>
                        <th class="px-6 py-4 text-right">ລາຄາເຊົ່າ</th>
                        <th class="px-6 py-4 text-right">ຄ່າມັດຈຳ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    <?php foreach($inventory as $item): ?>
                    <tr class="hover:bg-gray-50 transition-colors" 
                        x-show="(search === '' || '<?= strtolower($item['name']) ?>'.includes(search.toLowerCase()) || '<?= strtolower($item['code']) ?>'.includes(search.toLowerCase())) && (categoryFilter === 'ທັງໝົດ' || '<?= $item['category_name'] ?>' === categoryFilter) && (statusFilter === 'ທັງໝົດ' || '<?= $item['status'] ?>' === statusFilter)">
                        <td class="px-6 py-4">
                            <img src="<?= $item['image'] ?: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=100&h=100&fit=crop' ?>" 
                                 class="w-12 h-12 rounded-lg object-cover border cursor-pointer hover:scale-110 transition-transform" 
                                 alt="<?= $item['name'] ?>"
                                 @click="previewImage('<?= $item['image'] ?: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&h=800&fit=crop' ?>')">
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-500"><?= $item['code'] ?></td>
                        <td class="px-6 py-4 font-medium text-gray-800"><?= $item['name'] ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= $item['category_name'] ?></td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="px-2 py-1 bg-gray-100 rounded-md text-xs font-bold w-fit"><?= $item['size'] ?></span>
                                <?php if($item['bust'] || $item['waist'] || $item['hips']): ?>
                                <div class="text-[10px] text-gray-500 leading-tight">
                                    <?php if($item['bust']): ?>ເອິກ: <?= $item['bust'] ?><?php endif; ?>
                                    <?php if($item['waist']): ?> ແອວ: <?= $item['waist'] ?><?php endif; ?>
                                    <?php if($item['hips']): ?> ສະໂພກ: <?= $item['hips'] ?><?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="font-bold text-gray-800"><?= (int)($item['stock'] ?? 0) ?></span>
                                <button @click="openStockEdit(<?= htmlspecialchars(json_encode($item)) ?>)" class="p-1.5 text-gray-400 hover:text-primary transition-colors" title="ແກ້ໄຂສະຕ໊ອກ">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                            $statusClass = '';
                            $dotClass = '';
                            switch($item['status']) {
                                case 'Available': 
                                    $statusClass = 'bg-green-50 text-green-700 border-green-100'; 
                                    $dotClass = 'bg-green-500';
                                    $statusText = 'ພ້ອມເຊົ່າ';
                                    break;
                                case 'Rented': 
                                    $statusClass = 'bg-blue-50 text-blue-700 border-blue-100'; 
                                    $dotClass = 'bg-blue-500';
                                    $statusText = 'ກຳລັງເຊົ່າ';
                                    break;
                                case 'Cleaning': 
                                    $statusClass = 'bg-sky-50 text-sky-700 border-sky-100'; 
                                    $dotClass = 'bg-sky-500';
                                    $statusText = 'ກຳລັງຊັກລີດ';
                                    break;
                                case 'Repairing': 
                                    $statusClass = 'bg-amber-50 text-amber-700 border-amber-100'; 
                                    $dotClass = 'bg-amber-500';
                                    $statusText = 'ກຳລັງສ້ອມແປງ';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-50 text-gray-700 border-gray-100'; 
                                    $dotClass = 'bg-gray-500';
                                    $statusText = $item['status'];
                            }
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $statusClass ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span>
                                <?= $statusText ?>
                            </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-primary">
                            <?= number_format($item['rental_price']) ?>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-amber-600">
                            <?= number_format($item['deposit_price']) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit(<?= htmlspecialchars(json_encode($item)) ?>)" class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100" title="ແກ້ໄຂ">
                                    <i class="fas fa-edit text-base"></i>
                                </button>
                                <button @click="deleteItem(<?= htmlspecialchars(json_encode($item)) ?>)" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100" title="ລຶບ">
                                    <i class="fas fa-trash text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile card view -->
        <div class="md:hidden space-y-4">
            <?php foreach($inventory as $item): ?>
            <div class="bg-white rounded-2xl border p-4 space-y-4 shadow-sm"
                 x-show="(search === '' || '<?= strtolower($item['name']) ?>'.includes(search.toLowerCase()) || '<?= strtolower($item['code']) ?>'.includes(search.toLowerCase())) && (categoryFilter === 'ທັງໝົດ' || '<?= $item['category_name'] ?>' === categoryFilter) && (statusFilter === 'ທັງໝົດ' || '<?= $item['status'] ?>' === statusFilter)">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="<?= $item['image'] ?: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=100&h=100&fit=crop' ?>" 
                             class="w-10 h-10 rounded-xl object-cover border cursor-pointer hover:scale-110 transition-transform" 
                             alt="<?= $item['name'] ?>"
                             @click="previewImage('<?= $item['image'] ?: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&h=800&fit=crop' ?>')">
                        <div>
                            <div class="font-bold text-gray-800"><?= $item['name'] ?></div>
                            <div class="text-xs text-gray-400">Code: <?= $item['code'] ?></div>
                        </div>
                    </div>
                    <?php 
                    $statusClass = '';
                    $dotClass = '';
                    switch($item['status']) {
                        case 'Available': 
                            $statusClass = 'bg-green-50 text-green-700 border-green-100'; 
                            $dotClass = 'bg-green-500';
                            $statusText = 'ພ້ອມເຊົ່າ';
                            break;
                        case 'Rented': 
                            $statusClass = 'bg-blue-50 text-blue-700 border-blue-100'; 
                            $dotClass = 'bg-blue-500';
                            $statusText = 'ກຳລັງເຊົ່າ';
                            break;
                        case 'Cleaning': 
                            $statusClass = 'bg-sky-50 text-sky-700 border-sky-100'; 
                            $dotClass = 'bg-sky-500';
                            $statusText = 'ກຳລັງຊັກລີດ';
                            break;
                        case 'Repairing': 
                            $statusClass = 'bg-amber-50 text-amber-700 border-amber-100'; 
                            $dotClass = 'bg-amber-500';
                            $statusText = 'ກຳລັງສ້ອມແປງ';
                            break;
                    }
                    ?>
                    <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $statusClass ?>"> 
                        <span class="w-1 h-1 rounded-full <?= $dotClass ?>"></span>
                        <?= $statusText ?>
                    </span>
                </div>
                
                <div class="flex flex-col border-t pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 uppercase font-bold">Size & Measurements</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm"><?= $item['size'] ?></span>
                                <?php if($item['bust'] || $item['waist'] || $item['hips']): ?>
                                <span class="text-[10px] text-gray-500">
                                    (ເອິກ:<?= $item['bust'] ?> ແອວ:<?= $item['waist'] ?> ສະໂພກ:<?= $item['hips'] ?>)
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 uppercase font-bold">ສະຕ໊ອກ</span>
                            <div class="font-bold text-gray-800 text-sm"><?= (int)($item['stock'] ?? 0) ?></div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 uppercase font-bold">Rental Price</span>
                            <div class="font-black text-primary text-sm"><?= number_format($item['rental_price']) ?> ກີບ</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="openEdit(<?= htmlspecialchars(json_encode($item)) ?>)" class="flex-1 py-2 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center gap-2 text-xs font-bold hover:bg-gray-100 transition-colors">
                            <i class="fas fa-edit"></i> ແກ້ໄຂ
                        </button>
                        <button @click="deleteItem(<?= htmlspecialchars(json_encode($item)) ?>)" class="w-10 h-10 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-black/50 z-50 overflow-y-auto p-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showAddModal = false">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">ເພີ່ມຊຸດໃໝ່</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="<?= url('/inventory/add') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ລະຫັດຊຸດ *</label>
                        <input type="text" name="code" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ປະເພດ</label>
                        <select name="category_id" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            <option value="">ເລືອກ</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຊື່ຊຸດໄໝ *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ໄຊສ໌</label>
                        <input type="text" name="size" placeholder="S, M, L, XL..." class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສີ</label>
                        <input type="text" name="color" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ເອິກ (Bust)</label>
                        <input type="text" name="bust" placeholder="34" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ແອວ (Waist)</label>
                        <input type="text" name="waist" placeholder="28" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະໂພກ (Hips)</label>
                        <input type="text" name="hips" placeholder="36" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະຕ໊ອກ</label>
                        <input type="number" name="stock" value="0" min="0" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ລາຄາເຊົ່າ *</label>
                        <input type="number" name="rental_price" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ຄ່າມັດຈຳ *</label>
                        <input type="number" name="deposit_price" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ສະຖານະ</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Available">ພ້ອມເຊົ່າ</option>
                        <option value="Rented">ກຳລັງເຊົ່າ</option>
                        <option value="Cleaning">ກຳລັງຊັກລີດ</option>
                        <option value="Repairing">ກຳລັງສ້ອມແປງ</option>
                        <option value="Inactive">ປິດໃຊ້ງານ</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ຮູບພາບ</label>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 border border-dashed overflow-hidden flex-shrink-0">
                                <template x-if="addPreview">
                                    <img :src="addPreview" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!addPreview">
                                    <i class="fas fa-image text-sm"></i>
                                </template>
                            </div>
                            <input type="file" name="image" @change="handleFilePreview($event, 'add')" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer flex-1">
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2 border rounded-lg font-bold text-gray-600 hover:bg-gray-50 transition-colors">ຍົກເລີກ</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">ບັນທຶກ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 bg-black/50 z-50 overflow-y-auto p-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showEditModal = false">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-800">ແກ້ໄຂຂໍ້ມູນຊຸດ</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="<?= url('/inventory/edit') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" x-model="selectedItem.id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ລະຫັດຊຸດ *</label>
                        <input type="text" name="code" x-model="selectedItem.code" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ປະເພດ</label>
                        <select name="category_id" x-model="selectedItem.category_id" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            <option value="">ເລືອກ</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຊື່ຊຸດໄໝ *</label>
                    <input type="text" name="name" x-model="selectedItem.name" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ໄຊສ໌</label>
                        <input type="text" name="size" x-model="selectedItem.size" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສີ</label>
                        <input type="text" name="color" x-model="selectedItem.color" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ເອິກ (Bust)</label>
                        <input type="text" name="bust" x-model="selectedItem.bust" placeholder="34" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ແອວ (Waist)</label>
                        <input type="text" name="waist" x-model="selectedItem.waist" placeholder="28" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະໂພກ (Hips)</label>
                        <input type="text" name="hips" x-model="selectedItem.hips" placeholder="36" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະຕ໊ອກ</label>
                        <input type="number" name="stock" x-model="selectedItem.stock" min="0" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ລາຄາເຊົ່າ *</label>
                        <input type="number" name="rental_price" x-model="selectedItem.rental_price" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ຄ່າມັດຈຳ *</label>
                        <input type="number" name="deposit_price" x-model="selectedItem.deposit_price" required class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ສະຖານະ</label>
                    <select name="status" x-model="selectedItem.status" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Available">ພ້ອມເຊົ່າ</option>
                        <option value="Rented">ກຳລັງເຊົ່າ</option>
                        <option value="Cleaning">ກຳລັງຊັກລີດ</option>
                        <option value="Repairing">ກຳລັງສ້ອມແປງ</option>
                        <option value="Inactive">ປິດໃຊ້ງານ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຮູບພາບ</label>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 border border-dashed overflow-hidden flex-shrink-0">
                            <img :src="editPreview || selectedItem.image || 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=100&h=100&fit=crop'" class="h-full w-full object-cover">
                        </div>
                        <input type="file" name="image" @change="handleFilePreview($event, 'edit')" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer flex-1">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2 border rounded-lg font-bold text-gray-600 hover:bg-gray-50 transition-colors">ຍົກເລີກ</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">ບັນທຶກ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Management Modal -->
    <div x-show="showCategoryModal" x-cloak class="fixed inset-0 bg-black/50 z-50 overflow-y-auto p-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl my-auto max-h-[90vh] overflow-y-auto" @click.away="showCategoryModal = false">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">ຈັດການໝວດໝູ່ສິນຄ້າ</h3>
                <button @click="showCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <!-- Add/Edit Category Form -->
                <form :action="editingCategory ? '<?= url('/category/edit') ?>' : '<?= url('/category/add') ?>'" method="POST" class="mb-6 bg-gray-50 p-4 rounded-xl border border-dashed">
                    <input type="hidden" name="id" x-model="categoryForm.id">
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1" x-text="editingCategory ? 'ແກ້ໄຂໝວດໝູ່' : 'ເພີ່ມໝວດໝູ່ໃໝ່'"></label>
                            <input type="text" name="name" x-model="categoryForm.name" required placeholder="ລະບຸຊື່ໝວດໝູ່..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md shadow-primary/20" x-text="editingCategory ? 'ບັນທຶກ' : 'ເພີ່ມ'"></button>
                            <button type="button" x-show="editingCategory" @click="editingCategory = null; categoryForm = { id: '', name: '' }" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg font-bold hover:bg-gray-300 transition-all">ຍົກເລີກ</button>
                        </div>
                    </div>
                </form>

                <!-- Categories List -->
                <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2">
                    <template x-for="cat in categories" :key="cat.id">
                        <div class="flex items-center justify-between p-3 rounded-xl border hover:bg-gray-50 transition-all">
                            <span class="font-medium text-gray-700" x-text="cat.name"></span>
                            <div class="flex items-center gap-1">
                                <button @click="editingCategory = {...cat}; categoryForm = { id: cat.id, name: cat.name }" class="p-2 text-gray-400 hover:text-primary transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteCategory(cat)" class="p-2 text-gray-400 hover:text-red-500 transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Quick Edit Modal -->
    <div x-show="showStockModal" x-cloak class="fixed inset-0 bg-black/50 z-50 overflow-y-auto p-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl my-auto" @click.away="showStockModal = false">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">ແກ້ໄຂສະຕ໊ອກ</h3>
                    <p class="text-sm text-gray-500 mt-0.5" x-text="'ສຳລັບ: ' + (stockItem.name || '')"></p>
                </div>
                <button @click="showStockModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="<?= url('/inventory/update-stock') ?>" method="POST" class="space-y-4">
                <input type="hidden" name="id" x-model="stockItem.id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຈຳນວນສະຕ໊ອກ</label>
                    <input type="number" name="stock" x-model.number="stockItem.stock" min="0" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary outline-none text-lg font-bold text-center">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="stockItem.stock = Math.max(0, (stockItem.stock || 0) + 1)" class="py-2 rounded-lg bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
                        <i class="fas fa-plus mr-1"></i> +1
                    </button>
                    <button type="button" @click="stockItem.stock = Math.max(0, (stockItem.stock || 0) - 1)" class="py-2 rounded-lg bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
                        <i class="fas fa-minus mr-1"></i> -1
                    </button>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showStockModal = false" class="flex-1 px-4 py-2.5 border rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition-colors">ຍົກເລີກ</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition-colors">ບັນທຶກ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>

<script>
function inventoryModal() {
    return {
        showAddModal: false,
        showEditModal: false,
        showCategoryModal: false,
        showStockModal: false,
        selectedItem: {},
        stockItem: {},
        editingCategory: null,
        categoryForm: { id: '', name: '' },
        categories: <?= json_encode($categories) ?>,
        search: '',
        categoryFilter: 'ທັງໝົດ',
        statusFilter: 'ທັງໝົດ',
        addPreview: null,
        editPreview: null,
        
        resetFilters() {
            this.search = '';
            this.categoryFilter = 'ທັງໝົດ';
            this.statusFilter = 'ທັງໝົດ';
        },
        openStockEdit(item) {
            this.stockItem = {...item, stock: parseInt(item.stock || 0)};
            this.showStockModal = true;
        },
        openEdit(item) {
            this.selectedItem = {...item};
            this.editPreview = null;
            this.showEditModal = true;
        },
        handleFilePreview(event, type) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (type === 'add') {
                        this.addPreview = e.target.result;
                    } else {
                        this.editPreview = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        deleteCategory(cat) {
            Swal.fire({
                title: 'ຢືນຢັນການລຶບ?',
                text: 'ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບໝວດໝູ່ \"' + cat.name + '\"? ໝວດໝູ່ທີ່ມີສິນຄ້າຢູ່ຈະບໍ່ສາມາດລຶບໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ຢືນຢັນລຶບ',
                cancelButtonText: 'ຍົກເລີກ'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url("/category/delete") ?>';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = cat.id;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        },
        deleteItem(item) {
            Swal.fire({
                title: 'ຢືນຢັນການລຶບ?',
                text: 'ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຊຸດ \"' + item.name + '\"? ການດຳເນີນການນີ້ບໍ່ສາມາດກັບຄືນໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ຢືນຢັນລຶບ',
                cancelButtonText: 'ຍົກເລີກ'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url("/inventory/delete") ?>';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = item.id;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    }
}
</script>
 