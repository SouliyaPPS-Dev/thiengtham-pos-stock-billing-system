<div class="flex flex-col items-center justify-center min-h-[70vh] p-4 text-center">
    <div class="relative mb-8">
        <div class="text-[120px] font-black text-primary/10 select-none">404</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <i class="fas fa-search-minus text-5xl text-primary animate-bounce"></i>
        </div>
    </div>
    
    <h2 class="text-3xl font-bold text-gray-800 mb-2">ບໍ່ພົບໜ້າທີ່ທ່ານຕ້ອງການ</h2>
    <p class="text-gray-500 max-w-md mx-auto mb-8">
        ຂໍອະໄພ, ໜ້າທີ່ທ່ານກຳລັງຊອກຫາອາດຖືກຍ້າຍ, ປ່ຽນຊື່ ຫຼື ຖືກລົບອອກຈາກລະບົບແລ້ວ.
    </p>
    
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="<?= url('/') ?>" class="inline-flex items-center justify-center rounded-2xl text-sm font-bold transition-all bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 h-12 px-8">
            <i class="fas fa-home mr-2"></i> ກັບຄືນໜ້າຫຼັກ
        </a>
        <button onclick="history.back()" class="inline-flex items-center justify-center rounded-2xl text-sm font-bold transition-all bg-white border text-gray-600 hover:bg-gray-50 h-12 px-8">
            <i class="fas fa-arrow-left mr-2"></i> ຍ້ອນກັບ
        </button>
    </div>
</div>

