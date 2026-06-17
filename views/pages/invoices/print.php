<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໃບບິນ #<?= htmlspecialchars($invoice['invoice_number'] ?? str_pad($invoice['id'], 6, '0', STR_PAD_LEFT)) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-wrap {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            padding: 48px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 32px;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 32px;
        }
        .store-info h1 {
            font-size: 22px;
            font-weight: 900;
            color: #0ea5e9;
        }
        .store-info p {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 28px;
            font-weight: 900;
            color: #0ea5e9;
        }
        .invoice-title p {
            font-size: 13px;
            color: #64748b;
        }
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 24px;
        }
        .meta-item label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 4px;
        }
        .meta-item span {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        thead th {
            text-align: left;
            padding: 12px 8px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #f1f5f9;
        }
        tbody td {
            padding: 12px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .totals {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-top: 16px;
            border-top: 2px solid #f1f5f9;
        }
        .totals > div {
            width: 280px;
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
        }
        .totals .grand-total {
            font-size: 20px;
            font-weight: 900;
            color: #0ea5e9;
            border-top: 2px solid #0ea5e9;
            padding-top: 12px;
            margin-top: 6px;
        }
        .invoice-footer {
            text-align: center;
            padding-top: 32px;
            border-top: 2px solid #f1f5f9;
            margin-top: 32px;
            font-size: 12px;
            color: #94a3b8;
        }
        .print-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0ea5e9;
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Noto Sans Lao', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(14,165,233,0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 100;
            transition: opacity 0.2s;
        }
        .print-btn:hover { opacity: 0.9; }
        @media print {
            body { background: #fff; }
            .invoice-wrap {
                box-shadow: none;
                border-radius: 0;
                padding: 24px;
                margin: 0;
                max-width: 100%;
            }
            .print-btn { display: none !important; }
            .no-print { display: none !important; }
        }
        @media (max-width: 600px) {
            .invoice-wrap { padding: 24px; }
            .invoice-header { flex-direction: column; }
            .invoice-title { text-align: left; margin-top: 12px; }
            .invoice-meta { grid-template-columns: 1fr; }
            .totals > div { width: 100%; }
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        <i class="fas fa-print"></i>
        ພິມໃບບິນ
    </button>

    <div class="invoice-wrap">
        <div class="invoice-header">
            <div class="store-info">
                <h1><?= htmlspecialchars($store_name ?? get_store_name()) ?></h1>
                <p><?= htmlspecialchars($store_address ?? '') ?></p>
                <p><?= htmlspecialchars($store_phone ?? '') ?></p>
            </div>
            <div class="invoice-title">
                <h2>ໃບເກັບເງິນ</h2>
                <p>INVOICE</p>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="meta-item">
                <label>ໃບບິນເລກທີ</label>
                <span>#<?= htmlspecialchars($invoice['invoice_number'] ?? str_pad($invoice['id'], 6, '0', STR_PAD_LEFT)) ?></span>
            </div>
            <div class="meta-item">
                <label>ວັນທີ</label>
                <span><?= htmlspecialchars(date('d/m/Y H:i', strtotime($invoice['created_at']))) ?></span>
            </div>
            <div class="meta-item">
                <label>ລູກຄ້າ</label>
                <span><?= htmlspecialchars($invoice['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></span>
            </div>
            <?php if (!empty($invoice['customer_phone'])): ?>
            <div class="meta-item">
                <label>ເບີໂທລູກຄ້າ</label>
                <span><?= htmlspecialchars($invoice['customer_phone']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>ລາຍການ</th>
                    <th class="text-center">ຈຳນວນ</th>
                    <th class="text-right">ລາຄາຕໍ່ໜ່ວຍ</th>
                    <th class="text-right">ຈຳນວນເງິນ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice['items'] ?? [] as $i => $item): ?>
                <tr>
                    <td class="text-center text-gray-400"><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($item['product_name'] ?? $item['name']) ?></td>
                    <td class="text-center"><?= (int)($item['quantity'] ?? $item['qty']) ?></td>
                    <td class="text-right"><?= number_format($item['price'], 0) ?></td>
                    <td class="text-right font-bold"><?= number_format(($item['price'] * ($item['quantity'] ?? $item['qty'])), 0) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div>
                <span>ລວມຍ່ອຍ</span>
                <span><?= number_format($invoice['total'], 0) ?> ກີບ</span>
            </div>
            <div class="grand-total">
                <span>ລວມທັງໝົດ</span>
                <span><?= number_format($invoice['total'], 0) ?> ກີບ</span>
            </div>
        </div>

        <div class="invoice-footer">
            <p>ຂໍຂອບໃຈທີ່ໃຊ້ບໍລິການ</p>
            <p style="margin-top:4px;"><?= htmlspecialchars($store_name ?? get_store_name()) ?> | <?= htmlspecialchars($store_phone ?? '') ?> | <?= htmlspecialchars($store_address ?? '') ?></p>
        </div>
    </div>
</body>
</html>
