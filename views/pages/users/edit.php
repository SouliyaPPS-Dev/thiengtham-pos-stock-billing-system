<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="<?= url('/users') ?>" class="h-10 w-10 rounded-2xl bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ແກ້ໄຂພະນັກງານ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ແກ້ໄຂຂໍ້ມູນ: <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></p>
            </div>
        </div>

        <!-- Form -->
        <form action="<?= url('/users/' . $user['id'] . '/update') ?>" method="POST" x-data="{ active: '<?= $user['status'] ?? 'active' ?>' === 'active' }">
            <div class="space-y-6">

                <!-- Section: User Info -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-user-edit text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ຂໍ້ມູນຜູ້ໃຊ້</h2>
                            <p class="text-xs text-gray-400">ຂໍ້ມູນສຳລັບເຂົ້າສູ່ລະບົບ</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-user text-[10px] text-primary"></i>
                                ຊື່ຜູ້ໃຊ້ <span class="text-red-400">*</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-user text-xs"></i>
                                </span>
                                <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>"
                                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-user-tag text-[10px] text-primary"></i>
                                ຊື່ເຕັມ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-user-tag text-xs"></i>
                                </span>
                                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>"
                                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-lock text-[10px] text-primary"></i>
                                ລະຫັດຜ່ານໃໝ່
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-lock text-xs"></i>
                                </span>
                                <input type="password" name="password"
                                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300"
                                       placeholder="ປະໄວ້ວ່າງ ຖ້າບໍ່ຕ້ອງການປ່ຽນ">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">ປະໄວ້ວ່າງ ຖ້າບໍ່ຕ້ອງການປ່ຽນລະຫັດ</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-user-shield text-[10px] text-primary"></i>
                                ບົດບາດ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                                <select name="role"
                                        class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm appearance-none">
                                    <option value="staff" <?= ($user['role'] === 'staff') ? 'selected' : '' ?>>Staff</option>
                                    <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Status & Submit -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <i class="fas fa-sliders-h text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ສະຖານະ ແລະ ບັນທຶກ</h2>
                            <p class="text-xs text-gray-400">ຕັ້ງຄ່າສະຖານະ ແລະ ບັນທຶກຂໍ້ມູນ</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <label class="text-sm font-bold text-gray-700">ສະຖານະ</label>
                                <button type="button" @click="active = !active"
                                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300 focus:outline-none"
                                        :class="active ? 'bg-emerald-500' : 'bg-gray-300'">
                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition-transform duration-300"
                                          :class="active ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                                <span class="text-sm" :class="active ? 'text-emerald-600 font-bold' : 'text-gray-400'">
                                    <span x-text="active ? 'ເປີດໃຊ້' : 'ປິດໃຊ້'"></span>
                                </span>
                            </div>
                            <input type="hidden" name="status" :value="active ? 'active' : 'inactive'">
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="<?= url('/users') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all border border-gray-200">
                                <i class="fas fa-times"></i>
                                <span>ຍົກເລີກ</span>
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                                <i class="fas fa-save"></i>
                                <span>ບັນທຶກຂໍ້ມູນ</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>