<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໃບສະເໜີລາຄາ #<?= htmlspecialchars($quotation['quotation_number']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans Lao', 'Sarabun', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.5;
        }
        .page-wrap {
            max-width: 210mm;
            margin: 20px auto;
            background: #fff;
            min-height: 297mm;
            position: relative;
        }
        .inner {
            padding: 48px 48px 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 32px;
            border-bottom: 2px solid <?= $template['logo_color'] ?>;
            margin-bottom: 24px;
        }
        .header-left h1 {
            font-size: 20px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            line-height: 1.3;
            max-width: 360px;
        }
        .header-left p {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            max-width: 360px;
        }
        .header-right {
            text-align: right;
        }
        .header-right h2 {
            font-size: 24px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            letter-spacing: 2px;
        }
        .header-right p {
            font-size: 12px;
            color: #94a3b8;
        }
        .header-right .ref-box {
            margin-top: 8px;
            padding: 6px 14px;
            background: <?= $template['logo_color'] ?>08;
            border: 1px solid <?= $template['logo_color'] ?>20;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            color: <?= $template['logo_color'] ?>;
            display: inline-block;
        }
        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 32px;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        .meta .label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 2px;
        }
        .meta .value {
            font-weight: 600;
            color: #1e293b;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items thead th {
            background: <?= $template['logo_color'] ?>;
            color: #fff;
            padding: 10px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
        }
        table.items thead th:first-child {
            border-radius: 8px 0 0 0;
        }
        table.items thead th:last-child {
            border-radius: 0 8px 0 0;
            text-align: right;
        }
        table.items tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        table.items tbody td:last-child {
            text-align: right;
            font-weight: 600;
        }
        table.items tbody td:nth-child(3),
        table.items tbody td:nth-child(4) {
            text-align: center;
        }
        table.items tbody td:nth-child(5) {
            text-align: right;
        }
        table.items tbody tr:last-child td {
            border-bottom: none;
        }
        .totals {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-top: 12px;
        }
        .totals > div {
            width: 260px;
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }
        .totals .grand-total {
            font-size: 18px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            border-top: 2px solid <?= $template['logo_color'] ?>;
            padding-top: 10px;
            margin-top: 4px;
        }
        .terms {
            margin-top: 24px;
            padding: 16px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid <?= $template['logo_color'] ?>;
        }
        .terms h4 {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .terms ul {
            list-style: none;
            padding: 0;
        }
        .terms ul li {
            font-size: 13px;
            color: #475569;
            padding: 2px 0;
        }
        .terms ul li::before {
            content: '• ';
            color: <?= $template['logo_color'] ?>;
            font-weight: 700;
        }
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .signature div {
            text-align: center;
            width: 200px;
        }
        .signature .line {
            border-top: 1px solid #cbd5e1;
            margin-top: 48px;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .signature .title {
            font-size: 11px;
            color: #94a3b8;
        }
        .footer {
            text-align: center;
            padding-top: 24px;
            margin-top: 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 11px;
            color: #94a3b8;
        }
        .btn-print {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: <?= $template['logo_color'] ?>;
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Noto Sans Lao', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 16px <?= $template['logo_color'] ?>40;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 100;
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.9; }
        @media print {
            body { background: #fff; }
            .page-wrap {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            .inner { padding: 24px; }
            .btn-print { display: none !important; }
            .no-print { display: none !important; }
        }
        @media (max-width: 600px) {
            .inner { padding: 20px; }
            .header { flex-direction: column; gap: 12px; }
            .header-right { text-align: left; }
            .meta { grid-template-columns: 1fr; }
            .totals > div { width: 100%; }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        <i class="fas fa-print"></i>
        ພິມໃບສະເໜີລາຄາ
    </button>

    <div class="page-wrap">
        <div class="inner">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1><?= htmlspecialchars($template['company']) ?></h1>
                    <p><i class="fas fa-map-marker-alt" style="width:14px;text-align:center"></i> <?= htmlspecialchars($template['address']) ?></p>
                </div>
                <div class="header-right">
                    <h2>QUOTATION</h2>
                    <p>ໃບສະເໜີລາຄາ</p>
                    <div class="ref-box">
                        <i class="fas fa-hashtag"></i> <?= htmlspecialchars($quotation['quotation_number']) ?>
                    </div>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="meta">
                <div>
                    <span class="label">ຜູ້ສະໜອງ / Supplier</span>
                    <span class="value"><?= htmlspecialchars($quotation['supplier_name'] ?: '-') ?></span>
                    <?php if (!empty($quotation['supplier_contact'])): ?>
                    <br><span style="font-size:12px;color:#64748b"><?= htmlspecialchars($quotation['supplier_contact']) ?></span>
                    <?php endif; ?>
                </div>
                <div style="text-align:right">
                    <span class="label">ວັນທີ / Date</span>
                    <span class="value"><?= $quotation['date'] ? date('d/m/Y', strtotime($quotation['date'])) : date('d/m/Y') ?></span>
                    <?php if (!empty($quotation['ref_no'])): ?>
                    <br><span class="label" style="margin-top:4px">ເລກອ້າງອີງ / Ref No.</span>
                    <span class="value"><?= htmlspecialchars($quotation['ref_no']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>ລາຍການ / Description</th>
                        <th style="width:60px">ຈຳນວນ / Qty</th>
                        <th style="width:60px">ຫົວໜ່ວຍ / Unit</th>
                        <th style="width:120px">ລາຄາ/ໜ່ວຍ / Unit Price (<?= $template['currency'] ?>)</th>
                        <th style="width:120px">ຈຳນວນເງິນ / Amount (<?= $template['currency'] ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotation['items'] ?? [] as $i => $item): ?>
                    <tr>
                        <td style="color:#94a3b8"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= (float)$item['quantity'] ?></td>
                        <td><?= htmlspecialchars($item['unit'] ?: 'SET') ?></td>
                        <td><?= number_format($item['unit_price'], 0) ?></td>
                        <td><?= number_format($item['amount'], 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals">
                <div>
                    <span>Sub Total <?= $template['currency'] ?></span>
                    <span><?= number_format($quotation['subtotal'], 0) ?></span>
                </div>
                <?php if (!empty($quotation['discount']) && (float)$quotation['discount'] > 0): ?>
                <div>
                    <span>Discount</span>
                    <span>-<?= number_format($quotation['discount'], 0) ?></span>
                </div>
                <?php endif; ?>
                <?php if ((float)$quotation['tax_percent'] > 0): ?>
                <div>
                    <span>VAT <?= (float)$quotation['tax_percent'] ?>%</span>
                    <span><?= number_format($quotation['tax_amount'], 0) ?></span>
                </div>
                <?php endif; ?>
                <div class="grand-total">
                    <span>Total <?= $template['currency'] ?></span>
                    <span><?= number_format($quotation['grand_total'], 0) ?></span>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms">
                <h4><i class="fas fa-file-contract"></i> ເງື່ອນໄຂ / Terms & Conditions</h4>
                <ul>
                    <?php if (!empty($quotation['terms'])): ?>
                        <?php foreach (explode("\n", $quotation['terms']) as $term): ?>
                        <?php $t = trim($term); if (empty($t)) continue; ?>
                        <li><?= htmlspecialchars($t) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($template['terms'] as $term): ?>
                        <li><?= htmlspecialchars($term) ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Signature -->
            <div class="signature">
                <div>
                    <div class="line"><?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? '______') ?></div>
                    <div class="title">ຜູ້ຈັດການ / Manager</div>
                </div>
            </div>

            <div class="footer">
                <?= htmlspecialchars($template['company']) ?> &bull; <?= htmlspecialchars($template['address']) ?>
            </div>
        </div>
    </div>
</body>
</html>
