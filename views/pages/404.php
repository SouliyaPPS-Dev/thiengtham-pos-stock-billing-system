<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-gray-50 via-white to-sky-50/30">
    <div class="text-center max-w-md">
        <div class="w-28 h-28 bg-gradient-to-br from-sky-50 to-sky-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-sky-100">
            <i class="fas fa-search text-5xl text-sky-300"></i>
        </div>
        <h1 class="text-6xl font-black text-gray-800 mb-2 tracking-tight">404</h1>
        <p class="text-gray-500 mb-2 text-lg">ບໍ່ພົບໜ້າທີ່ຕ້ອງການ</p>
        <p class="text-gray-400 text-sm mb-8">ໜ້າທີ່ທ່ານກຳລັງຊອກຫາບໍ່ມີຢູ່ ຫຼື ຖືກຍ້າຍອອກແລ້ວ</p>
        <div class="flex items-center justify-center gap-3">
            <button onclick="history.back()" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i> ກັບໄປໜ້າກ່ອນ
            </button>
            <a href="<?= url('/admin') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200">
                <i class="fas fa-home"></i> ໜ້າຫຼັກ
            </a>
        </div>
    </div>
</div>
