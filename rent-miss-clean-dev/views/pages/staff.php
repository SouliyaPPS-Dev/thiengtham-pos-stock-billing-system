<div class="p-4 md:p-8" x-data="staffModal()">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-800">ຈັດການພະນັກງານ</h1>
                <p class="text-gray-500 text-sm">ຈັດການຂໍ້ມູນພະນັກງານ ແລະ ການເຂົ້າເຖິງລະບົບ</p>
            </div>
            <button @click="openCreate()" class="inline-flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 h-11 px-6">
                <i class="fas fa-plus mr-2"></i> ເພີ່ມພະນັກງານໃໝ່
            </button>
        </div>
        
        <!-- Search and Filters -->
        <div class="bg-white p-4 rounded-xl border flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" x-model="search" placeholder="ຄົ້ນຫາຊື່ ຫຼື ຊື່ຜູ້ໃຊ້..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
            </div>
            <button @click="search = ''" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all border">
                <i class="fas fa-undo mr-2"></i>ລ້າງຟິວເຕີ
            </button>
        </div>

        <!-- Desktop table view -->
        <div class="hidden md:block bg-white rounded-xl border overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">ຊື່ເຕັມ</th>
                        <th class="px-6 py-4">ເບີໂທ</th>
                        <th class="px-6 py-4">ຊື່ຜູ້ໃຊ້</th>
                        <th class="px-6 py-4">ບົດບາດ</th>
                        <th class="px-6 py-4">ສະຖານະ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-700">
                    <?php foreach($staff as $member): ?>
                    <tr class="hover:bg-gray-50 transition-colors" x-show="matchesSearch(<?= htmlspecialchars(json_encode($member)) ?>)">
                        <td class="px-6 py-4 font-bold text-gray-500">#<?= $member['id'] ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($member['avatar'])): ?>
                                <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="" class="w-8 h-8 rounded-full object-cover border cursor-pointer hover:scale-110 transition-transform" @click="previewImage('<?= htmlspecialchars($member['avatar']) ?>')">
                                <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold"><?= strtoupper(mb_substr($member['full_name'], 0, 1)) ?></div>
                                <?php endif; ?>
                                <span class="font-medium text-gray-800"><?= $member['full_name'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><?= $member['phone'] ?? '-' ?></td>
                        <td class="px-6 py-4"><?= $member['username'] ?></td>
                        <td class="px-6 py-4">
                            <?php 
                            $roleClass = $member['role'] === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100';
                            $roleText = $member['role'] === 'admin' ? 'Admin' : 'Staff';
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $roleClass ?>">
                                <?= $roleText ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                            $statusClass = $member['status'] === 'Active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100';
                            $statusText = $member['status'] === 'Active' ? 'ໃຊ້ງານຢູ່' : 'ປິດໃຊ້ງານ';
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="viewStaff(<?= $member['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-sky-50 text-primary hover:bg-primary hover:text-white rounded-xl transition-all shadow-sm border border-sky-100 text-xs font-bold" title="ເບິ່ງ">
                                    <i class="fas fa-eye"></i> ເບິ່ງ
                                </button>
                                <button @click="editStaff(<?= $member['id'] ?>)" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-100 text-xs font-bold" title="ແກ້ໄຂ">
                                    <i class="fas fa-edit"></i> ແກ້ໄຂ
                                </button>
                                <button @click="deleteStaff(<?= $member['id'] ?>, '<?= $member['full_name'] ?>')" class="px-3 h-10 flex items-center justify-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm border border-red-100 text-xs font-bold" title="ລົບ">
                                    <i class="fas fa-trash-alt"></i> ລົບ
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
            <?php foreach($staff as $member): ?>
            <div class="bg-white rounded-2xl border p-4 space-y-4 shadow-sm" x-show="matchesSearch(<?= htmlspecialchars(json_encode($member)) ?>)">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <?php if (!empty($member['avatar'])): ?>
                        <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="" class="w-10 h-10 rounded-xl object-cover border cursor-pointer hover:scale-110 transition-transform" @click="previewImage('<?= htmlspecialchars($member['avatar']) ?>')">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user"></i>
                        </div>
                        <?php endif; ?>
                        <div @click="viewStaff(<?= $member['id'] ?>)">
                            <div class="font-bold text-gray-800"><?= $member['full_name'] ?></div>
                            <div class="text-xs text-gray-400">@<?= $member['username'] ?> • <?= $member['phone'] ?? '-' ?></div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <?php 
                        $roleClass = $member['role'] === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100';
                        $roleText = $member['role'] === 'admin' ? 'Admin' : 'Staff';
                        ?>
                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $roleClass ?>">
                            <?= $roleText ?>
                        </span>
                        <?php 
                        $statusClass = $member['status'] === 'Active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100';
                        $statusText = $member['status'] === 'Active' ? 'Active' : 'Inactive';
                        ?>
                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between border-t pt-4">
                    <div class="text-xs text-gray-400">
                        ID: #<?= $member['id'] ?> • <?= date('d/m/Y', strtotime($member['created_at'])) ?>
                    </div>
                    <div class="flex gap-2">
                        <button @click="viewStaff(<?= $member['id'] ?>)" class="flex-1 px-3 py-2 bg-sky-50 text-primary rounded-xl text-xs font-bold hover:bg-primary/10 transition-all text-center" title="ເບິ່ງ">
                            <i class="fas fa-eye mr-1"></i>ເບິ່ງ
                        </button>
                        <button @click="editStaff(<?= $member['id'] ?>)" class="flex-1 px-3 py-2 bg-amber-50 text-amber-600 rounded-xl text-xs font-bold hover:bg-amber-100 transition-all text-center" title="ແກ້ໄຂ">
                            <i class="fas fa-edit mr-1"></i>ແກ້ໄຂ
                        </button>
                        <button @click="deleteStaff(<?= $member['id'] ?>, '<?= $member['full_name'] ?>')" class="flex-1 px-3 py-2 bg-red-50 text-red-500 rounded-xl text-xs font-bold hover:bg-red-100 transition-all text-center" title="ລົບ">
                            <i class="fas fa-trash-alt mr-1"></i>ລົບ
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add/Edit Staff Modal -->
    <div x-show="showModal" x-cloak @keydown.window.enter="if(showModal) saveStaff()" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
            <div class="flex items-center justify-between mb-6">
                <h2 x-text="modalTitle" class="text-xl font-bold text-gray-800"></h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form @submit.prevent="saveStaff" class="space-y-4" enctype="multipart/form-data">
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
                    <input type="text" name="full_name" x-model="formData.full_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ເບີໂທ</label>
                    <input type="text" name="phone" x-model="formData.phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ຊື່ຜູ້ໃຊ້ (Username) *</label>
                    <input type="text" name="username" x-model="formData.username" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ລະຫັດຜ່ານ (Password) <span x-show="!formData.id">*</span></label>
                    <input type="password" name="password" x-model="formData.password" :required="!formData.id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none" placeholder="ປ້ອນລະຫັດຜ່ານ...">
                    <p x-show="formData.id" class="text-[10px] text-gray-400 mt-1">* ປະໄວ້ຫວ່າງຖ້າບໍ່ຕ້ອງການປ່ຽນ</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ບົດບາດ</label>
                        <select name="role" x-model="formData.role" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                            <option value="staff">ພະນັກງານ (Staff)</option>
                            <option value="admin">ຜູ້ດູແລລະບົບ (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ສະຖານະ</label>
                        <select name="status" x-model="formData.status" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none">
                            <option value="Active">ໃຊ້ງານຢູ່</option>
                            <option value="Inactive">ປິດໃຊ້ງານ</option>
                        </select>
                    </div>
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

    <!-- View Staff Modal -->
    <div x-show="viewModal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl mx-4 my-auto max-h-[90vh] overflow-y-auto" @click.away="viewModal = false">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <template x-if="viewData.avatar">
                        <img :src="viewData.avatar" class="w-14 h-14 rounded-full object-cover border cursor-pointer hover:opacity-80 transition-opacity" @click="previewImage(viewData.avatar)">
                    </template>
                    <template x-if="!viewData.avatar">
                        <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-bold" x-text="(viewData.full_name || '?')[0]"></div>
                    </template>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800" x-text="viewData.full_name || 'ລາຍລະອຽດພະນັກງານ'"></h2>
                        <p class="text-sm text-gray-500" x-text="'@' + viewData.username || ''"></p>
                    </div>
                </div>
                <button @click="viewModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ເບີໂທ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.phone || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ID ພະນັກງານ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="'#' + viewData.id || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ບົດບາດ</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold"
                                  :class="viewData.role === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100'"
                                  x-text="viewData.role === 'admin' ? 'Admin' : 'Staff'">
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
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ວັນທີສ້າງ</p>
                        <p class="font-medium text-gray-800 mt-1" x-text="viewData.created_at || '-'"></p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 pt-6">
                <button @click="viewModal = false; editStaff(viewData.id)" class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i> ແກ້ໄຂຂໍ້ມູນ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function staffModal() {
    return {
        showModal: false,
        viewModal: false,
        modalTitle: '',
        search: '',
        formData: {},
        viewData: {},
        avatarPreview: null,
        avatarFile: null,
        loading: false,
        staff: <?= json_encode(array_column($staff ?? [], null, 'id')) ?>,

        matchesSearch(member) {
            if (!this.search.trim()) return true;
            const term = this.search.toLowerCase();
            return member.full_name.toLowerCase().includes(term) || 
                   member.username.toLowerCase().includes(term) ||
                   (member.phone && member.phone.includes(term));
        },

        openCreate() {
            this.modalTitle = 'ເພີ່ມພະນັກງານໃໝ່';
            this.formData = {
                id: '', full_name: '', phone: '', username: '',
                password: '', role: 'staff', status: 'Active'
            };
            this.avatarPreview = null;
            this.avatarFile = null;
            this.showModal = true;
        },

        viewStaff(id) {
            this.viewData = this.staff[id] || {};
            this.viewModal = true;
        },

        editStaff(id) {
            const s = this.staff[id] || {};
            this.modalTitle = 'ແກ້ໄຂຂໍ້ມູນພະນັກງານ';
            this.formData = {
                id: id, full_name: s.full_name || '', phone: s.phone || '',
                username: s.username || '', password: '',
                role: s.role || 'staff', status: s.status || 'Active'
            };
            this.avatarPreview = s.avatar || null;
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

        saveStaff() {
            this.loading = true;
            const isEdit = !!this.formData.id;
            const url = isEdit ? '<?= url("/staff/edit") ?>' : '<?= url("/staff/add") ?>';

            const fd = new FormData();
            Object.keys(this.formData).forEach(key => {
                fd.append(key, this.formData[key]);
            });
            if (this.avatarFile) {
                fd.append('avatar', this.avatarFile);
            }

            fetch(url, {
                method: 'POST',
                body: fd
            })
            .then(res => {
                if (res.ok) {
                    window.location.href = '<?= url("/staff") ?>' + (isEdit ? '?updated=1' : '?success=1');
                } else {
                    this.loading = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'ເກີດຂໍ້ຜິດພາດ',
                        text: 'ບັນທຶກຂໍ້ມູນບໍ່ສຳເລັດ',
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

        deleteStaff(id, name) {
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລຶບພະນັກງານ "' + name + '" ນີ້ແທ້ບໍ່?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ລຶບອອກ',
                cancelButtonText: 'ຍົກເລີກ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= url("/staff/delete?id=") ?>' + id;
                }
            });
        }
    }
}
</script>

<style>[x-cloak] { display: none !important; }</style>
 