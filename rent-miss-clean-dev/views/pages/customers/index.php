<div class="p-4 md:p-8" x-data="customerModal()">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-800">ຈັດການລູກຄ້າ</h1>
                <p class="text-gray-500 text-sm">ຈັດການຂໍ້ມູນລູກຄ້າ ແລະ ປະຫວັດການເຊົ່າ</p>
            </div>
            <div class="flex gap-2">
                <button @click="openTypeModal()" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-white text-gray-700 border hover:bg-gray-50 h-11 px-6 shadow-sm">
                    <i class="fas fa-tags mr-2 text-primary"></i> ຈັດການປະເພດ
                </button>
                <button @click="openCreate()" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 h-11 px-6">
                    <i class="fas fa-plus mr-2"></i> ເເພີ່ມລູກຄ້າໃໝ່
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white p-4 rounded-xl border flex flex-col md:flex-row gap-4">
            <form method="GET" id="filterForm" class="flex flex-col md:flex-row gap-4 w-full">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                        placeholder="ຄົ້ນຫາຊື່, ເບີໂທ, ອີເມວ..." 
                        oninput="autoFilter()"
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="flex gap-2 flex-wrap items-center">
                    <select name="customer_type" onchange="this.form.submit()" class="bg-white border rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
                        <option value="">ທຸກປະເພດ</option>
                        <?php foreach ($customer_types as $type): ?>
                        <option value="<?= htmlspecialchars($type['name']) ?>" <?= ($customer_type ?? '') === $type['name'] ? 'selected' : '' ?>><?= htmlspecialchars($type['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status" onchange="this.form.submit()" class="bg-white border rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
                        <option value="">ທຸກສະຖານະ</option>
                        <option value="Active" <?= ($status ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= ($status ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <a href="<?= url('/customers') ?>" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-bold text-gray-600 transition-all flex items-center" title="ລຶບຄ່າກອງ">
                        <i class="fas fa-redo mr-2 text-xs"></i> ຄືນຄ່າ
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop table view -->
        <div class="hidden md:block bg-white rounded-xl border overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">ຊື່ລູກຄ້າ</th>
                        <th class="px-6 py-4">ເບີໂທ</th>
                        <th class="px-6 py-4">ປະເພດ</th>
                        <th class="px-6 py-4">ສະຖານະ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-2 block"></i>
                            ບໍ່ມີຂໍ້ມູນລູກຄ້າ
                        </td>
                    </tr>
                    <?php else: foreach ($customers as $index => $customer): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-500"><?= $index + 1 ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($customer['avatar'])): ?>
                                <img src="<?= htmlspecialchars($customer['avatar']) ?>" alt="" class="w-8 h-8 rounded-full object-cover border cursor-pointer hover:scale-110 transition-transform" @click="previewImage('<?= htmlspecialchars($customer['avatar']) ?>')">
                                <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold"><?= strtoupper(mb_substr($customer['fullname'], 0, 1)) ?></div>
                                <?php endif; ?>
                                <div>
                                    <button @click="viewCustomer(<?= $customer['id'] ?>)" class="font-bold text-gray-800 hover:text-primary text-left">
                                        <?= htmlspecialchars($customer['fullname']) ?>
                                    </button>
                                    <?php if (!empty($customer['email'])): ?>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($customer['email']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($customer['phone']) ?></td>
                        <td class="px-6 py-4">
                            <?php 
                            $type = $customer['customer_type'] ?? 'Walk-in';
                            $typeClass = $type === 'VIP' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : ($type === 'Corporate' ? 'bg-purple-50 text-purple-700 border-purple-100' : ($type === 'Regular' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-gray-50 text-gray-700 border-gray-100'));
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $typeClass ?>">
                                <?= $type ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                            $st = $customer['status'] ?? 'Active';
                            $stClass = $st === 'Active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100';
                            $stText = $st === 'Active' ? 'ໃຊ້ງານຢູ່' : 'ປິດໃຊ້ງານ';
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $stClass ?>">
                                <?= $stText ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="viewCustomer(<?= $customer['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-sm border border-blue-100 text-xs font-bold" title="ເບິ່ງລາຍລະອຽດ">
                                    <i class="fas fa-eye"></i> ເບິ່ງ
                                </button>
                                <button @click="editCustomer(<?= $customer['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100 text-xs font-bold" title="ແກ້ໄຂ">
                                    <i class="fas fa-edit"></i> ແກ້ໄຂ
                                </button>
                                <button @click="deleteCustomer(<?= $customer['id'] ?>, '<?= htmlspecialchars(addslashes($customer['fullname'])) ?>')" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100 text-xs font-bold" title="ລຶບ">
                                    <i class="fas fa-trash"></i> ລຶບ
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile card view -->
        <div class="md:hidden space-y-4">
            <?php if (empty($customers)): ?>
            <div class="bg-white rounded-2xl border p-8 text-center text-gray-400">
                <i class="fas fa-folder-open text-4xl mb-2 block"></i>
                ບໍ່ມີຂໍ້ມູນລູກຄ້າ
            </div>
            <?php else: foreach ($customers as $customer): ?>
            <div class="bg-white rounded-2xl border p-4 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <?php if (!empty($customer['avatar'])): ?>
                        <img src="<?= htmlspecialchars($customer['avatar']) ?>" alt="" class="w-10 h-10 rounded-xl object-cover border cursor-pointer hover:scale-110 transition-transform" @click="previewImage('<?= htmlspecialchars($customer['avatar']) ?>')">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user"></i>
                        </div>
                        <?php endif; ?>
                        <div>
                            <div class="font-bold text-gray-800"><?= htmlspecialchars($customer['fullname']) ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($customer['phone']) ?></div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <?php 
                        $type = $customer['customer_type'] ?? 'Walk-in';
                        $mTypeClass = $type === 'VIP' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : ($type === 'Corporate' ? 'bg-purple-50 text-purple-700 border-purple-100' : ($type === 'Regular' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-gray-50 text-gray-700 border-gray-100'));
                        ?>
                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $mTypeClass ?>">
                            <?= $type ?>
                        </span>
                        <?php 
                        $st = $customer['status'] ?? 'Active';
                        $mStClass = $st === 'Active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100';
                        $mStText = $st === 'Active' ? 'ໃຊ້ງານຢູ່' : 'ປິດໃຊ້ງານ';
                        ?>
                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $mStClass ?>">
                            <?= $mStText ?>
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t pt-4">
                    <div class="text-xs text-gray-400">
                        <?php if (!empty($customer['email'])): ?>
                        <?= htmlspecialchars($customer['email']) ?>
                        <?php else: ?>
                        ບໍ່ມີອີເມວ
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-2">
                        <button @click="viewCustomer(<?= $customer['id'] ?>)" class="flex-1 px-3 py-2 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center gap-1.5 text-xs font-bold hover:bg-blue-600 hover:text-white transition-all border border-blue-100" title="ເບິ່ງລາຍລະອຽດ">
                            <i class="fas fa-eye"></i> ເບິ່ງ
                        </button>
                        <button @click="editCustomer(<?= $customer['id'] ?>)" class="flex-1 px-3 py-2 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center gap-1.5 text-xs font-bold hover:bg-amber-600 hover:text-white transition-all border border-amber-100" title="ແກ້ໄຂ">
                            <i class="fas fa-edit"></i> ແກ້ໄຂ
                        </button>
                        <button @click="deleteCustomer(<?= $customer['id'] ?>, '<?= htmlspecialchars(addslashes($customer['fullname'])) ?>')" class="flex-1 px-3 py-2 rounded-xl bg-red-50 text-red-600 flex items-center justify-center gap-1.5 text-xs font-bold hover:bg-red-600 hover:text-white transition-all border border-red-100" title="ລຶບ">
                            <i class="fas fa-trash"></i> ລຶບ
                        </button> 
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div x-show="showModal" x-cloak @keydown.window.enter="if(showModal) saveCustomer()" class="fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
            <div class="flex items-center justify-between mb-6">
                <h2 x-text="modalTitle" class="text-xl font-bold text-gray-800"></h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form @submit.prevent="saveCustomer" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" name="id" x-model="formData.id">
                <div class="flex items-center gap-4 mb-2">
                    <div class="relative w-16 h-16 rounded-full border-2 border-dashed border-gray-300 overflow-hidden flex-shrink-0 bg-gray-50">
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!avatarPreview">
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-camera text-lg"></i>
                            </div>
                        </template>
                        <input type="file" name="avatar" @change="handleAvatarPreview($event)" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">ຮູບປະຈຳໂຕ</p>
                        <p class="text-[10px] text-gray-400">ຄລິກເພື່ອເລືອກຮູບ (ບໍ່ມີກໍໄດ້)</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຊື່ ແລະ ນາມສະກຸນ *</label>
                    <input type="text" name="fullname" x-model="formData.fullname" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ເບີໂທ *</label>
                    <input type="text" name="phone" x-model="formData.phone" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ອີເມວ</label>
                    <input type="email" name="email" x-model="formData.email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ເລກບັດປະຈຳຕົວ</label>
                        <input type="text" name="id_card_no" x-model="formData.id_card_no" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ເພດ</label>
                        <select name="gender" x-model="formData.gender" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                            <option value="">ເລືອກ</option>
                            <option value="Male">ຊາຍ</option>
                            <option value="Female">ຍິງ</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ທີ່ຢູ່</label>
                    <textarea name="address" x-model="formData.address" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-gray-700">ປະເພດ</label>
                            <button type="button" @click="openTypeModal()" class="text-[10px] text-primary hover:underline">ຈັດການ</button>
                        </div>
                        <select name="customer_type" x-model="formData.customer_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                            <template x-for="type in customerTypes" :key="type.id">
                                <option :value="type.name" x-text="type.name" :selected="formData.customer_type === type.name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະຖານະ</label>
                        <select name="status" x-model="formData.status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                            <option value="Active">ໃຊ້ງານຢູ່</option>
                            <option value="Inactive">ປິດໃຊ້ງານ</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ໝາຍເຫດ</label>
                    <textarea name="notes" x-model="formData.notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border rounded-lg font-bold text-gray-600 hover:bg-gray-50 transition-colors">ຍົກເລີກ</button>
                    <button type="submit" :disabled="loading" class="flex-1 px-4 py-2 rounded-lg font-bold text-white transition-colors" :class="loading ? 'bg-sky-500 opacity-60 cursor-not-allowed' : 'bg-sky-500 hover:bg-sky-600'">
                        <i x-show="loading" class="fas fa-spinner fa-spin mr-1"></i>
                        <span x-text="loading ? 'ກຳລັງບັນທຶກ...' : 'ບັນທຶກ'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div x-show="viewModal" x-cloak class="fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl mx-4 my-auto max-h-[90vh] overflow-y-auto" @click.away="viewModal = false">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <template x-if="viewData.avatar">
                        <img :src="viewData.avatar" class="w-14 h-14 rounded-full object-cover border cursor-pointer hover:opacity-80 transition-opacity" @click="previewImage(viewData.avatar)">
                    </template>
                    <template x-if="!viewData.avatar">
                        <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-bold" x-text="(viewData.fullname || '?')[0]"></div>
                    </template>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800" x-text="viewData.fullname || 'ລາຍລະອຽດລູກຄ້າ'"></h2>
                        <p class="text-sm text-gray-500" x-text="viewData.phone || ''"></p>
                    </div>
                </div>
                <button @click="viewModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ອີເມວ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.email || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ເລກບັດປະຈຳຕົວ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.id_card_no || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ປະເພດ</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold"
                                  :class="viewData.customer_type === 'VIP' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : (viewData.customer_type === 'Corporate' ? 'bg-purple-50 text-purple-700 border-purple-100' : (viewData.customer_type === 'Regular' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-gray-50 text-gray-700 border-gray-100'))"
                                  x-text="viewData.customer_type || 'Walk-in'">
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ສະຖານະ</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold"
                                  :class="viewData.status === 'Active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100'"
                                  x-text="viewData.status === 'Active' ? 'ໃຊ້ງານຢູ່' : 'ປິດໃຊ້ງານ'">
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ເພດ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.gender ? (viewData.gender === 'Male' ? 'ຊາຍ' : 'ຍິງ') : '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ວັນທີສ້າງ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.created_at || '-'"></p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ທີ່ຢູ່</p>
                    <p class="font-medium text-gray-800 mt-1" x-text="viewData.address || '-'"></p>
                </div>
                <div x-show="viewData.notes">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ໝາຍເຫດ</p>
                    <p class="font-medium text-gray-800 mt-1" x-text="viewData.notes"></p>
                </div>
            </div>
            <div class="flex gap-3 pt-6">
                <button @click="viewModal = false; editCustomer(viewData.id)" class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i> ແກ້ໄຂຂໍ້ມູນ
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Type Management Modal -->
    <div x-show="showTypeModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showTypeModal = false"></div>
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative z-10" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">ຈັດການປະເພດລູກຄ້າ</h2>
                <button @click="showTypeModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Add New Type -->
                <div class="flex gap-2">
                    <input type="text" x-model="newTypeName" placeholder="ຊື່ປະເພດໃໝ່" 
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                    <button @click="saveType()" class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Type List -->
                <div class="border rounded-xl overflow-hidden max-h-[300px] overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-bold">
                            <tr>
                                <th class="px-4 py-2 text-left border-b">ຊື່ປະເພດ</th>
                                <th class="px-4 py-2 text-right border-b">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <template x-for="type in customerTypes" :key="type.id">
                                <tr>
                                    <td class="px-4 py-2">
                                        <template x-if="editingTypeId !== type.id">
                                            <span x-text="type.name"></span>
                                        </template>
                                        <template x-if="editingTypeId === type.id">
                                            <input type="text" x-model="editingTypeName" 
                                                class="w-full px-2 py-1 border rounded focus:ring-1 focus:ring-primary outline-none">
                                        </template>
                                    </td>
                                    <td class="px-4 py-2 text-right flex justify-end gap-2">
                                        <template x-if="editingTypeId !== type.id">
                                            <button @click="startEditType(type)" class="px-2.5 py-1.5 text-amber-600 hover:bg-amber-50 rounded-lg text-[10px] font-bold flex items-center gap-1 transition-all" title="ແກ້ໄຂ">
                                                <i class="fas fa-edit"></i> ແກ້ໄຂ
                                            </button>
                                        </template>
                                        <template x-if="editingTypeId === type.id">
                                            <button @click="updateType()" class="px-2.5 py-1.5 text-green-600 hover:bg-green-50 rounded-lg text-[10px] font-bold flex items-center gap-1 transition-all" title="ບັນທຶກ">
                                                <i class="fas fa-check"></i> ບັນທຶກ
                                            </button>
                                        </template>
                                        <button @click="deleteType(type.id)" class="px-2.5 py-1.5 text-red-600 hover:bg-red-50 rounded-lg text-[10px] font-bold flex items-center gap-1 transition-all" title="ລຶບ">
                                            <i class="fas fa-trash"></i> ລຶບ
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function customerModal() {
    return {
        showModal: false,
        viewModal: false,
        showTypeModal: false,
        modalTitle: '',
        formData: {},
        viewData: {},
        avatarPreview: null,
        avatarFile: null,
        loading: false,
        customers: <?= json_encode(array_column($customers ?? [], null, 'id')) ?>,
        customerTypes: <?= json_encode($customer_types ?? []) ?>,
        newTypeName: '',
        editingTypeId: null,
        editingTypeName: '',

        openCreate() {
            this.fetchTypes();
            this.modalTitle = 'ເພີ່ມລູກຄ້າໃໝ່';
            this.formData = {
                id: '', fullname: '', phone: '', email: '', id_card_no: '', gender: '',
                customer_type: this.customerTypes.length > 0 ? this.customerTypes[0].name : 'Walk-in', 
                address: '', status: 'Active', notes: ''
            };
            this.avatarPreview = null;
            this.avatarFile = null;
            this.showModal = true;
        },

        editCustomer(id) {
            this.fetchTypes();
            const c = this.customers[id] || {};
            this.modalTitle = 'ແກ້ໄຂຂໍ້ມູນລູກຄ້າ';
            this.formData = {
                id: id, fullname: c.fullname || '', phone: c.phone || '', email: c.email || '',
                id_card_no: c.id_card_no || '', gender: c.gender || '',
                customer_type: c.customer_type || (this.customerTypes.length > 0 ? this.customerTypes[0].name : 'Walk-in'), 
                address: c.address || '',
                status: c.status || 'Active', notes: c.notes || ''
            };
            this.avatarPreview = c.avatar || null;
            this.avatarFile = null;
            this.viewModal = false;
            this.showModal = true;
        },

        handleAvatarPreview(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.avatarFile = file;
            const reader = new FileReader();
            reader.onload = (e) => { this.avatarPreview = e.target.result; };
            reader.readAsDataURL(file);
        },

        openTypeModal() {
            this.showModal = false;
            this.fetchTypes();
            this.showTypeModal = true;
        },

        fetchTypes() {
            fetch('<?= url("/customer-types") ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.customerTypes = data.types;
                    }
                });
        },

        saveType() {
            if (!this.newTypeName.trim()) return;
            
            const params = new URLSearchParams();
            params.append('name', this.newTypeName);

            fetch('<?= url("/customer-types/store") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.newTypeName = '';
                    this.fetchTypes();
                } else {
                    alert(data.message);
                }
            });
        },

        startEditType(type) {
            this.editingTypeId = type.id;
            this.editingTypeName = type.name;
        },

        updateType() {
            if (!this.editingTypeName.trim()) return;

            const params = new URLSearchParams();
            params.append('name', this.editingTypeName);

            fetch('<?= url("/customer-types") ?>/' + this.editingTypeId + '/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.editingTypeId = null;
                    this.fetchTypes();
                } else {
                    alert(data.message);
                }
            });
        },

        deleteType(id) {
            if (!confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບ?')) return;

            fetch('<?= url("/customer-types") ?>/' + id + '/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.fetchTypes();
                } else {
                    alert(data.message);
                }
            });
        },

        saveCustomer() {
            this.loading = true;
            const isEdit = !!this.formData.id;
            const url = isEdit ? '<?= url("/customers") ?>/' + this.formData.id + '/edit' : '<?= url("/customers/store") ?>';

            const fd = new FormData();
            Object.keys(this.formData).forEach(key => {
                fd.append(key, this.formData[key]);
            });
            if (this.avatarFile) {
                fd.append('avatar', this.avatarFile);
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= url("/customers") ?>' + (isEdit ? '?updated=1' : '?success=1');
                } else {
                    this.loading = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'ເກີດຂໍ້ຜິດພາດ',
                        text: data.message || 'ບັນທຶກຂໍ້ມູນບໍ່ສຳເລັດ',
                        confirmButtonColor: '#0ea5e9'
                    });
                }
            })
            .catch(() => {
                this.loading = false;
                Swal.fire({
                    icon: 'error',
                    title: 'ເກີດຂໍ້ຜິດພາດ',
                    text: 'ກະລຸນາລອງໃໝ່ອີກຄັ້ງ',
                    confirmButtonColor: '#0ea5e9'
                });
            });
        },

        viewCustomer(id) {
            this.viewData = this.customers[id] || {};
            this.viewModal = true;
        },

        deleteCustomer(id, name) {
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລຶບລູກຄ້າ "' + name + '" ນີ້ແທ້ບໍ່?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ລຶບອອກ',
                cancelButtonText: 'ຍົກເລີກ'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= url("/customers") ?>/' + id + '/delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'confirm=1'
                    }).then(res => {
                        if (res.ok) {
                            window.location.href = '<?= url("/customers?deleted=1") ?>';
                        }
                    });
                }
            });
        }
    }
}

let filterTimeout;
function autoFilter() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 400);
}
</script>

<style>[x-cloak] { display: none !important; }</style>
