<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໃບສະເໜີລາຄາ #<?= htmlspecialchars($quotation['quotation_number']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans Lao', 'Sarabun', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.6;
        }
        .page-wrap {
            max-width: 210mm;
            margin: 24px auto;
            background: #fff;
            min-height: 297mm;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        .inner {
            padding: 24px 48px 36px;
        }
        /* ── Top Row: Logo + Bill No ── */
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .top-row .logo-box {
            width: <?= (int)($settings['bill_logo_width'] ?? 150) ?>px;
            height: <?= (int)($settings['bill_logo_height'] ?? 150) ?>px;
        }
        .top-row .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: <?= str_replace('-', ' ', $settings['bill_logo_position'] ?? 'top left') ?>;
        }
        .top-row .bill-no-box {
            text-align: right;
        }
        .top-row .bill-no-box .label-bill {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: block;
        }
        .top-row .bill-no-box .value-bill {
            font-size: 18px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            letter-spacing: 0.5px;
        }
        /* ── Title Image (center) ── */
        .title-row {
            text-align: center;
            margin-bottom: 10px;
        }
        .title-row img {
            max-height: 50px;
            width: auto;
        }
        .footer {
            text-align: center;
            padding-top: 12px;
            margin-top: 20px;
            border-top: 2px solid <?= $template['logo_color'] ?>;
            font-size: 11px;
            color: #94a3b8;
        }
        /* ── Meta Info ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 32px;
            padding-bottom: 10px;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .meta-grid .field {
            display: flex;
            flex-direction: column;
        }
        .meta-grid .field.right {
            text-align: right;
        }
        .meta-grid .field .lbl {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .meta-grid .field .val {
            font-weight: 600;
            color: #1e293b;
        }
        .meta-grid .field .sub {
            font-size: 12px;
            color: #64748b;
        }
        /* ── Items Table ── */
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
        table.items thead th:first-child { border-radius: 6px 0 0 0; }
        table.items thead th:last-child { border-radius: 0 6px 0 0; text-align: right; }
        table.items tbody td {
            padding: 9px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        table.items tbody td:last-child { text-align: right; font-weight: 600; }
        table.items tbody td:nth-child(3),
        table.items tbody td:nth-child(4) { text-align: center; }
        table.items tbody td:nth-child(5) { text-align: right; }
        table.items tbody tr:last-child td { border-bottom: none; }
        /* ── Totals ── */
        .totals {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-top: 8px;
        }
        .totals > div {
            width: 280px;
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }
        .totals .grand-total {
            font-size: 18px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            border-top: 2px solid <?= $template['logo_color'] ?>;
            padding-top: 8px;
            margin-top: 4px;
        }
        /* ── Terms ── */
        .terms {
            margin-top: 12px;
            padding: 10px 16px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid <?= $template['logo_color'] ?>;
        }
        .terms h4 {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .terms ul { list-style: none; padding: 0; }
        .terms ul li {
            font-size: 12px;
            color: #475569;
            padding: 2px 0;
        }
        .terms ul li::before {
            content: '• ';
            color: <?= $template['logo_color'] ?>;
            font-weight: 700;
        }
        /* ── Bill Terms & Signature Row ── */
        .bill-terms-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 8px;
            gap: 24px;
        }
        .bill-terms-row .bill-terms {
            font-size: 12px;
            color: #475569;
            line-height: 2;
            white-space: pre-wrap;
        }
        .bill-terms-row .signature {
            flex-shrink: 0;
        }
        .signature {
            display: flex;
            justify-content: flex-end;
        }
        .signature div {
            text-align: center;
            width: 200px;
        }
        .signature .line {
            border-top: 1px solid #cbd5e1;
            margin-top: 20px;
            padding-top: 6px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .signature .sig-image {
            margin-bottom: 4px;
        }
        .signature .sig-image img {
            display: block;
            margin: 0 auto;
        }
        .signature .title {
            font-size: 11px;
            color: #94a3b8;
        }
        /* ── Footer ── */
        /* ── Print Button ── */
        .btn-print {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: <?= $template['logo_color'] ?>;
            color: #fff;
            border: none;
            border-radius: 14px;
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
            .page-wrap { box-shadow: none; margin: 0; max-width: 100%; }
            .inner { padding: 20px 24px; }
            .btn-print { display: none !important; }
            .no-print { display: none !important; }
        }
        @media (max-width: 600px) {
            .inner { padding: 16px; }
            .top-row { flex-direction: column; gap: 8px; }
            .top-row .bill-no-box { text-align: left; }
            .meta-grid { grid-template-columns: 1fr; }
            .meta-grid .field.right { text-align: left; }
            .totals > div { width: 100%; }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        ພິມໃບສະເໜີລາຄາ
    </button>

    <div class="page-wrap">
        <div class="inner">
            <!-- Top Row: Logo + Bill No -->
            <div class="top-row">
                <div class="logo-box">
                    <img src="<?= !empty($settings['bill_logo']) ? $settings['bill_logo'] : url('/public/logo_bill.png') ?>" alt="Logo">
                </div>
                <div class="bill-no-box">
                    <span class="label-bill">Bill No. / ໃບທີ່</span>
                    <span class="value-bill">#<?= str_pad($quotation['id'], 4, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>

            <!-- Title Image (center) -->
            <div class="title-row">
                <img src="<?= url('/public/quatation.png') ?>" alt="Quotation">
            </div>

            <!-- Meta Info -->
            <div class="meta-grid">
                <div class="field">
                    <span class="lbl">ຜູ້ສະໜອງ / Supplier</span>
                    <span class="val"><?= htmlspecialchars($quotation['supplier_name'] ?: '-') ?></span>
                    <?php if (!empty($quotation['supplier_contact'])): ?>
                    <span class="sub"><?= htmlspecialchars($quotation['supplier_contact']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="field right">
                    <span class="lbl">ວັນທີ / Date</span>
                    <span class="val"><?= $quotation['date'] ? date('d/m/Y', strtotime($quotation['date'])) : date('d/m/Y') ?></span>
                    <?php if (!empty($quotation['ref_no'])): ?>
                    <span class="sub" style="margin-top:4px;"><span class="lbl">Ref No.</span> <?= htmlspecialchars($quotation['ref_no']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items">
                <thead>
                    <tr>
                        <th style="width:36px">#</th>
                        <th style="width:40px">ຮູບ</th>
                        <th>ລາຍການ / Description</th>
                        <th style="width:56px">ຈຳນວນ / Qty</th>
                        <th style="width:56px">ຫົວໜ່ວຍ / Unit</th>
                        <th style="width:110px">ລາຄາ/ໜ່ວຍ<br><span style="font-weight:400;font-size:10px;">Unit Price (<?= $template['currency'] ?>)</span></th>
                        <th style="width:110px">ຈຳນວນເງິນ<br><span style="font-weight:400;font-size:10px;">Amount (<?= $template['currency'] ?>)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotation['items'] ?? [] as $i => $item): ?>
                    <tr>
                        <td style="color:#94a3b8"><?= $i + 1 ?></td>
                        <td style="text-align:center;">
                            <?php if (!empty($item['product_image'])): ?>
                            <img src="<?= $item['product_image'] ?>" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:6px;display:inline-block;vertical-align:middle;">
                            <?php else: ?>
                            <span style="color:#cbd5e1;font-size:10px;">—</span>
                            <?php endif; ?>
                        </td>
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
                    <span>Sub Total (<?= $template['currency'] ?>)</span>
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
                    <span>Total (<?= $template['currency'] ?>)</span>
                    <span><?= number_format($quotation['grand_total'], 0) ?></span>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms">
                <h4>ເງື່ອນໄຂ / Terms &amp; Conditions</h4>
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

            <!-- Bill Terms + Signature Row -->
            <div class="bill-terms-row">
                <div class="bill-terms"><?= htmlspecialchars(!empty($settings['bill_terms']) ? $settings['bill_terms'] : "เสนอ ราคา ภายใน 60 วัน\nส่งสินค้าภายใน 14 วัน หลังออก PO\nเครดิต 30 วัน") ?></div>
                <div class="signature">
                    <div>
                        <?php if (!empty($settings['bill_signature'])): ?>
                        <div class="sig-image">
                            <img src="<?= $settings['bill_signature'] ?>" alt="Signature" style="width:<?= (int)($settings['bill_signature_width'] ?? 150) ?>px;height:<?= (int)($settings['bill_signature_height'] ?? 50) ?>px;object-fit:contain;object-position:<?= str_replace('-', ' ', $settings['bill_signature_position'] ?? 'center') ?>;">
                        </div>
                        <?php else: ?>
                        <div class="line"><?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? '______') ?></div>
                        <?php endif; ?>
                        <div class="title">ຜູ້ຈັດການ / Manager</div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <?= htmlspecialchars($template['company']) ?> &bull; <?= htmlspecialchars($template['address']) ?>
            </div>
        </div>
    </div>
</body>
</html>
