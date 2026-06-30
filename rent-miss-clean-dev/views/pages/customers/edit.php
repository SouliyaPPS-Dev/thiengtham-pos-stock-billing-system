<div class="p-4 md:p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Customer</h1>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($customer['fullname'] ?? '') ?></p>
        </div>
        <a href="<?= url('/customers') ?>" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <?php if (!empty($errors ?? [])): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
        <p class="font-medium mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside text-sm">
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border p-6" onsubmit="var btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.classList.add('opacity-60','cursor-not-allowed'); btn.innerHTML='<i class=\"fas fa-spinner fa-spin mr-2\"></i> ກຳລັງບັນທຶກ...';">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">ຮູບປະຈຳໂຕ</label>
                <?php if (!empty($customer['avatar'])): ?>
                <div class="flex items-center gap-3 mb-2">
                    <img src="<?= htmlspecialchars($customer['avatar']) ?>" alt="" class="w-12 h-12 rounded-full object-cover border">
                    <span class="text-sm text-gray-500">ຮູບປັດຈຸບັນ</span>
                </div>
                <?php endif; ?>
                <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($_POST['fullname'] ?? $customer['fullname'] ?? '') ?>" required
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $customer['phone'] ?? '') ?>" required
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $customer['email'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ID Card</label>
                <input type="text" name="id_card_no" value="<?= htmlspecialchars($_POST['id_card_no'] ?? $customer['id_card_no'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <select name="gender" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="">-- Select --</option>
                    <option value="Male" <?= ($_POST['gender'] ?? $customer['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($_POST['gender'] ?? $customer['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= ($_POST['gender'] ?? $customer['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                <select name="customer_type" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="Walk-in" <?= ($_POST['customer_type'] ?? $customer['customer_type'] ?? 'Walk-in') === 'Walk-in' ? 'selected' : '' ?>>Walk-in</option>
                    <option value="Regular" <?= ($_POST['customer_type'] ?? $customer['customer_type'] ?? '') === 'Regular' ? 'selected' : '' ?>>Regular</option>
                    <option value="VIP" <?= ($_POST['customer_type'] ?? $customer['customer_type'] ?? '') === 'VIP' ? 'selected' : '' ?>>VIP</option>
                    <option value="Corporate" <?= ($_POST['customer_type'] ?? $customer['customer_type'] ?? '') === 'Corporate' ? 'selected' : '' ?>>Corporate</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none"><?= htmlspecialchars($_POST['address'] ?? $customer['address'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                <input type="text" name="province" value="<?= htmlspecialchars($_POST['province'] ?? $customer['province'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                <input type="text" name="district" value="<?= htmlspecialchars($_POST['district'] ?? $customer['district'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="Active" <?= ($_POST['status'] ?? $customer['status'] ?? 'Active') === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($_POST['status'] ?? $customer['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="Blacklisted" <?= ($_POST['status'] ?? $customer['status'] ?? '') === 'Blacklisted' ? 'selected' : '' ?>>Blacklisted</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                <input type="text" name="occupation" value="<?= htmlspecialchars($_POST['occupation'] ?? $customer['occupation'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none"><?= htmlspecialchars($_POST['notes'] ?? $customer['notes'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t">
            <a href="<?= url('/customers') ?>" class="px-6 py-2.5 border text-gray-600 rounded-xl font-medium hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white rounded-xl font-medium hover:bg-sky-600">
                <i class="fas fa-save mr-2"></i> Save
            </button>
        </div>
    </form>
</div>