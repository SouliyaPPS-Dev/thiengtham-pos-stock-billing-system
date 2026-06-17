<div class="p-4 md:p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="/customers" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Delete Customer</h1>
                <p class="text-sm text-gray-500">Confirm deletion</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border p-6 max-w-lg mx-auto">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Are you sure?</h2>
            <p class="text-gray-500 mb-6">The customer "<strong><?= htmlspecialchars($customer['fullname'] ?? '') ?></strong>" will be permanently deleted.</p>
            
            <form method="POST" class="flex items-center justify-center gap-4">
                <a href="/customers/<?= $customer['id'] ?>" class="px-6 py-2.5 border text-gray-600 rounded-xl font-medium hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" name="confirm" value="1" class="px-6 py-2.5 bg-red-500 text-white rounded-xl font-medium hover:bg-red-600">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>