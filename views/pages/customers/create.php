<div class="p-4 md:p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="<?= url('/customers') ?>" class="h-9 w-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ເພີ່ມລູກຄ້າໃໝ່</h1>
            <p class="text-sm text-gray-500">ບັນທຶກຂໍ້ມູນລູກຄ້າໃໝ່</p>
        </div>
    </div>

    <div class="table-wrap">
        <form action="<?= url('/customers/store') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="form-label">ຊື່ລູກຄ້າ <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="form-input" placeholder="ປ້ອນຊື່ລູກຄ້າ">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ເບີໂທ</label>
                <input type="text" name="phone" class="form-input" placeholder="ເບີໂທລະສັບ">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ອີເມວ</label>
                <input type="email" name="email" class="form-input" placeholder="ອີເມວ">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ທີ່ຢູ່</label>
                <input type="text" name="address" class="form-input" placeholder="ທີ່ຢູ່">
            </div>
            <div class="space-y-1.5 md:col-span-2">
                <label class="form-label">ໝາຍເຫດ</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="ໝາຍເຫດ"></textarea>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກ</span>
                </button>
                <a href="<?= url('/customers') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>ຍົກເລີກ</span>
                </a>
            </div>
        </form>
    </div>
</div>
