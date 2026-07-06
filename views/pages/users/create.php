<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="<?= url('/users') ?>" class="h-10 w-10 rounded-2xl bg-card border border-border shadow-sm flex items-center justify-center text-muted-foreground hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ເພີ່ມພະນັກງານໃໝ່</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ບັນທຶກຂໍ້ມູນຜູ້ໃຊ້ງານໃໝ່</p>
            </div>
        </div>

        <!-- Form -->
        <form action="<?= url('/users/store') ?>" method="POST" x-data="{ active: true }">
            <div class="space-y-6">

                <!-- Section: User Info -->
                <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-user-plus text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນຜູ້ໃຊ້</h2>
                            <p class="text-xs text-muted-foreground">ຂໍ້ມູນສຳລັບເຂົ້າສູ່ລະບົບ</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-user text-[10px] text-primary"></i>
                                ຊື່ຜູ້ໃຊ້ <span class="text-red-400">*</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-user text-xs"></i>
                                </span>
                                <input type="text" name="username" required
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ປ້ອນຊື່ຜູ້ໃຊ້">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-user-tag text-[10px] text-primary"></i>
                                ຊື່ເຕັມ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-user-tag text-xs"></i>
                                </span>
                                <input type="text" name="fullname"
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ຊື່ເຕັມ">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-lock text-[10px] text-primary"></i>
                                ລະຫັດຜ່ານ <span class="text-red-400">*</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-lock text-xs"></i>
                                </span>
                                <input type="password" name="password" required
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ລະຫັດຜ່ານ">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-user-shield text-[10px] text-primary"></i>
                                ບົດບາດ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                                <select name="role"
                                        class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm appearance-none">
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Status & Submit -->
                <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <i class="fas fa-sliders-h text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ສະຖານະ ແລະ ບັນທຶກ</h2>
                            <p class="text-xs text-muted-foreground">ຕັ້ງຄ່າສະຖານະ ແລະ ບັນທຶກຂໍ້ມູນ</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <label class="text-sm font-bold text-foreground/85">ສະຖານະ</label>
                                <button type="button" @click="active = !active"
                                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300 focus:outline-none"
                                        :class="active ? 'bg-emerald-500' : 'bg-gray-300'">
                                    <span class="inline-block h-5 w-5 transform rounded-full bg-card shadow-sm transition-transform duration-300"
                                          :class="active ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                                <span class="text-sm" :class="active ? 'text-emerald-600 font-bold' : 'text-muted-foreground'">
                                    <span x-text="active ? 'ເປີດໃຊ້' : 'ປິດໃຊ້'"></span>
                                </span>
                            </div>
                            <input type="hidden" name="status" :value="active ? 'active' : 'inactive'">
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="<?= url('/users') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-foreground/70 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all border border-border">
                                <i class="fas fa-times"></i>
                                <span>ຍົກເລີກ</span>
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                                <i class="fas fa-save"></i>
                                <span>ບັນທຶກ</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>