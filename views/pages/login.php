<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-sky-50 via-white to-sky-50 p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl shadow-sky-100/50 border border-sky-100 p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-sky-200">
                    <i class="fas fa-cash-register text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-black text-gray-800">POS & Stock</h1>
                <p class="text-sm text-gray-500 mt-1">ລະບົບຂາຍ ແລະ ຈັດການສາງສິນຄ້າ</p>
            </div>

            <?php if (isset($error) && $error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ຊື່ຜູ້ໃຊ້</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" required
                               class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 outline-none transition-all"
                               placeholder="admin">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ລະຫັດຜ່ານ</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" required
                               class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 outline-none transition-all"
                               placeholder="123456">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>ເຂົ້າສູ່ລະບົບ
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-6">
                Demo: admin / 123456
            </p>
        </div>
    </div>
</div>
