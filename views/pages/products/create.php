<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="<?= url('/products') ?>" class="h-10 w-10 rounded-2xl bg-card border border-border shadow-sm flex items-center justify-center text-muted-foreground hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ເພີ່ມສິນຄ້າໃໝ່</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ບັນທຶກຂໍ້ມູນສິນຄ້າໃໝ່ເຂົ້າສູ່ລະບົບ</p>
            </div>
        </div>

        <!-- Form -->
        <form action="<?= url('/products/store') ?>" method="POST" enctype="multipart/form-data" x-data="{ preview: null, active: true }">
            <div class="space-y-6">

                <!-- Section: Basic Info -->
                <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <i class="fas fa-info-circle text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ຂໍ້ມູນພື້ນຖານ</h2>
                            <p class="text-xs text-muted-foreground">ຂໍ້ມູນທົ່ວໄປຂອງສິນຄ້າ</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-tag text-[10px] text-primary"></i>
                                ຊື່ສິນຄ້າ <span class="text-red-400">*</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-box text-xs"></i>
                                </span>
                                <input type="text" name="name" required
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ປ້ອນຊື່ສິນຄ້າ">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-barcode text-[10px] text-primary"></i>
                                SKU
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-hashtag text-xs"></i>
                                </span>
                                <input type="text" name="sku"
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ລະຫັດສິນຄ້າ">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-folder text-[10px] text-primary"></i>
                                ໝວດສິນຄ້າ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                                <select name="category_id"
                                        class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm appearance-none">
                                    <option value="">-- ເລືອກໝວດ --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-weight text-[10px] text-primary"></i>
                                ຫົວໜ່ວຍ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-ruler text-xs"></i>
                                </span>
                                <input type="text" name="unit"
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300"
                                       placeholder="ຊິ້ນ, ກິໂລ, ລິດ...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Pricing & Stock -->
                <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-coins text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ລາຄາ ແລະ ສະຕ໋ອກ</h2>
                            <p class="text-xs text-muted-foreground">ກຳນົດລາຄາຂາຍ ແລະ ຈຳນວນສິນຄ້າ</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-arrow-down text-[10px] text-emerald-500"></i>
                                ລາຄາຕົ້ນທຶນ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none text-xs font-bold">₭</span>
                                <input type="number" name="cost_price" step="0.01"
                                       class="w-full pl-8 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-arrow-up text-[10px] text-red-500"></i>
                                ລາຄາຂາຍ <span class="text-red-400">*</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-primary font-extrabold pointer-events-none text-xs">₭</span>
                                <input type="number" name="selling_price" required step="0.01"
                                       class="w-full pl-8 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm font-bold text-foreground"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-cubes text-[10px] text-amber-500"></i>
                                ສະຕ໋ອກ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-boxes text-xs"></i>
                                </span>
                                <input type="number" name="stock" value="0"
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-triangle text-[10px] text-red-400"></i>
                                ສະຕ໋ອກຕ່ຳສຸດ
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                                    <i class="fas fa-bell text-xs"></i>
                                </span>
                                <input type="number" name="min_stock" value="0"
                                       class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Details & Media -->
                <div class="bg-card rounded-2xl border border-border shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-image text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-foreground">ລາຍລະອຽດ ແລະ ຮູບພາບ</h2>
                            <p class="text-xs text-muted-foreground">ຂໍ້ມູນເພີ່ມເຕີມ ແລະ ຮູບສິນຄ້າ</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-align-left text-[10px] text-violet-500"></i>
                                ລາຍລະອຽດ
                            </label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300 resize-none"
                                      placeholder="ລາຍລະອຽດສິນຄ້າ..."></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-foreground/85 flex items-center gap-1.5">
                                <i class="fas fa-camera text-[10px] text-violet-500"></i>
                                ຮູບສິນຄ້າ
                            </label>
                            <div class="relative">
                                <div class="border-2 border-dashed border-border rounded-2xl p-6 text-center hover:border-primary/40 hover:bg-primary/[0.02] transition-all cursor-pointer group"
                                     @click="$refs.fileInput.click()"
                                     x-show="!preview">
                                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-muted to-muted flex items-center justify-center mx-auto mb-3 group-hover:from-primary/5 group-hover:to-primary/10 transition-all">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 group-hover:text-primary/40 transition-colors"></i>
                                    </div>
                                    <p class="text-sm font-bold text-foreground/70">ຄລິກເພື່ອເລືອກຮູບ</p>
                                    <p class="text-xs text-muted-foreground mt-1">ຮອງຮັບ JPG, PNG, WEBP</p>
                                </div>
                                <div x-show="preview" class="relative rounded-2xl overflow-hidden border border-border group">
                                    <img :src="preview" class="w-full h-48 object-cover">
                                    <button type="button" @click="preview = null; $refs.fileInput.value = ''"
                                            class="absolute top-3 right-3 h-8 w-8 rounded-xl bg-card/90 backdrop-blur flex items-center justify-center text-muted-foreground hover:bg-red-500 hover:text-white transition-all shadow-lg">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/50 to-transparent p-4">
                                        <p class="text-white text-xs font-bold">ພ້ອມອັບໂຫຼດ</p>
                                    </div>
                                </div>
                                <input type="file" name="image" accept="image/*" x-ref="fileInput"
                                       @change="preview = URL.createObjectURL($event.target.files[0])"
                                       class="hidden">
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
                            <a href="<?= url('/products') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-foreground/70 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all border border-border">
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
