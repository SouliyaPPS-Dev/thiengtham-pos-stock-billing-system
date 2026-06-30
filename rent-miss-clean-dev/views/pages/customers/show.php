<div class="p-4 md:p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="<?= url('/customers') ?>" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="flex items-center gap-4">
                <?php if (!empty($customer['avatar'])): ?>
                <img src="<?= htmlspecialchars($customer['avatar']) ?>" alt="" class="w-14 h-14 rounded-full object-cover border">
                <?php endif; ?>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($customer['fullname'] ?? '') ?></h1>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($customer['phone'] ?? '') ?></p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= url("/customers/" . $customer['id'] . "/edit") ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-xl font-medium hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="<?= url("/customers/" . $customer['id'] . "/delete") ?>" class="px-4 py-2 bg-red-500 text-white rounded-xl font-medium hover:bg-red-600">
                <i class="fas fa-trash mr-2"></i> Delete
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-user mr-2 text-sky-500"></i> Customer Info
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($customer['fullname'] ?? '') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($customer['phone'] ?? '') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($customer['email'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ID Card</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($customer['id_card_no'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Customer Type</p>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($customer['customer_type'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full <?= ($customer['status'] ?? 'Active') === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                            <?= $customer['status'] ?? 'Active' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-map-marker-alt mr-2 text-sky-500"></i> Address
                </h2>
                <p class="text-gray-600"><?= htmlspecialchars($customer['address'] ?? '-') ?></p>
                <p class="text-sm text-gray-500 mt-2">
                    <?= htmlspecialchars($customer['district'] ?? '') ?>, <?= htmlspecialchars($customer['province'] ?? '') ?>
                </p>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-history mr-2 text-sky-500"></i> Rental History
                </h2>
                <?php if (empty($rentalHistory)): ?>
                <p class="text-gray-500 text-center py-4">No rental history</p>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-500">Date</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-500">Amount</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($rentalHistory as $rental): ?>
                            <tr>
                                <td class="px-3 py-2 text-sm"><?= $rental['rental_date'] ? date('d/m/Y', strtotime($rental['rental_date'])) : '-' ?></td>
                                <td class="px-3 py-2 text-sm"><?= number_format($rental['total_amount'] ?? 0) ?> LAK</td>
                                <td class="px-3 py-2 text-sm"><?= htmlspecialchars($rental['status'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-sticky-note mr-2 text-sky-500"></i> Notes
                </h2>
                <p class="text-gray-600"><?= nl2br(htmlspecialchars($customer['notes'] ?? 'No notes')) ?></p>
            </div>

            <div class="bg-white rounded-2xl border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2 text-sky-500"></i> Info
                </h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Created by</p>
                        <p class="font-medium"><?= htmlspecialchars($customer['created_by_name'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Created at</p>
                        <p class="font-medium"><?= $customer['created_at'] ? date('d/m/Y H:i', strtotime($customer['created_at'])) : '-' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>