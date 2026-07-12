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
            font-size: 13px;
            line-height: 1.4;
        }
        @page { size: 148mm auto; margin: 0; }
        .page-wrap {
            width: 148mm;
            min-height: 210mm;
            height: auto;
            margin: 0 auto;
            background: #fff;
        }
        .inner {
            padding: 15px 20px 20px;
            height: auto;
        }
        /* ── Top Row: Logo + Bill No ── */
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
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
        .top-row .bill-no-box { text-align: right; }
        .top-row .bill-no-box .label-bill {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
        }
        .top-row .bill-no-box .value-bill {
            font-size: 16px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
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
            border-top: 1px solid <?= $template['logo_color'] ?>;
            font-size: 10px;
            color: #94a3b8;
            line-height: 2;
        }
        /* ── Meta Info ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 25px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .meta-grid .field { display: flex; flex-direction: column; }
        .meta-grid .field.right { text-align: right; }
        .meta-grid .field .lbl {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .meta-grid .field .val { font-weight: 600; color: #1e293b; }
        .meta-grid .field .sub { font-size: 11px; color: #64748b; }
        /* ── Items Table ── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.items thead th {
            background: <?= $template['logo_color'] ?>;
            color: #fff;
            padding: 6px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
            border-right: 1px solid rgba(255,255,255,0.15);
        }
        table.items thead th:last-child {
            border-right: none;
            text-align: right;
        }
        table.items thead th:first-child { border-radius: 4px 0 0 0; }
        table.items thead th:nth-child(2) { border-radius: 0; }
        table.items tbody td {
            padding: 5px 6px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            font-size: 12px;
        }
        table.items tbody td:last-child { border-right: none; text-align: right; font-weight: 600; }
        table.items tbody td:nth-child(3),
        table.items tbody td:nth-child(4) { text-align: center; }
        table.items tbody td:nth-child(5) { text-align: right; }
        table.items tbody tr:last-child td { border-bottom: none; }
        table.items thead th:nth-child(2) { text-align: center; }
        /* ── Totals ── */
        .totals {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-top: 5px;
        }
        .totals > div {
            width: 250px;
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 12px;
        }
        .totals .grand-total {
            font-size: 16px;
            font-weight: 900;
            color: <?= $template['logo_color'] ?>;
            border-top: 2px solid <?= $template['logo_color'] ?>;
            padding-top: 5px;
            margin-top: 3px;
        }
        /* ── Terms ── */
        .terms {
            margin-top: 10px;
            padding: 8px 15px;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 4px solid <?= $template['logo_color'] ?>;
        }
        .terms h4 {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .terms ul { list-style: none; padding: 0; }
        .terms ul li {
            font-size: 11px;
            color: #475569;
            padding: 0;
            line-height: 1.5;
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
            margin-top: 10px;
            gap: 20px;
        }
        .bill-terms-row .bill-terms {
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .signature { display: flex; justify-content: flex-end; }
        .signature div { text-align: center; width: 200px; max-width: 200px; }
        .signature .line {
            border-top: 1px solid #cbd5e1;
            margin-top: 15px;
            padding-top: 4px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .signature .sig-image { margin-bottom: 2px; max-width: 100%; overflow: hidden; }
        .signature .sig-image img { display: block; margin: 0 auto; object-fit: contain; }
        .signature .title { font-size: 11px; color: #94a3b8; }
        .signature .company-info { font-size: 9px; color: #64748b; line-height: 1.4; margin-bottom: 4px; }
        /* ── Print Button ── */
        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: <?= $template['logo_color'] ?>;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Noto Sans Lao', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 16px <?= $template['logo_color'] ?>40;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 100;
        }
        .btn-print:hover { opacity: 0.9; }
        @media print {
            body { background: #fff; }
            .page-wrap { box-shadow: none; margin: 0; width: 100%; min-height: auto; height: auto; }
            .inner { padding: 15px 20px 20px; height: auto; }
            .btn-print { display: none !important; }
            .no-print { display: none !important; }
        }
        @media (max-width: 600px) {
            .inner { padding: 10px; }
            .top-row { flex-direction: column; gap: 4px; }
            .top-row .bill-no-box { text-align: left; }
            .meta-grid { grid-template-columns: 1fr; }
            .meta-grid .field.right { text-align: left; }
            .totals > div { width: 100%; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div class="no-print" style="position:fixed;bottom:20px;right:20px;display:flex;gap:8px;z-index:100;">
        <button onclick="exportPDF()" style="background:#dc2626;color:#fff;border:none;border-radius:12px;padding:12px 24px;font-size:14px;font-weight:700;font-family:'Noto Sans Lao',sans-serif;cursor:pointer;display:flex;align-items:center;gap:6px;box-shadow:0 4px 16px rgba(220,38,38,0.3);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Export PDF
        </button>
        <button onclick="window.print()" style="background:<?= $template['logo_color'] ?>;color:#fff;border:none;border-radius:12px;padding:12px 24px;font-size:14px;font-weight:700;font-family:'Noto Sans Lao',sans-serif;cursor:pointer;display:flex;align-items:center;gap:6px;box-shadow:0 4px 16px <?= $template['logo_color'] ?>40;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            ພິມ
        </button>
    </div>
    <script>
    function exportPDF() {
        const element = document.querySelector('.page-wrap');
        const mmH = element.offsetHeight * 25.4 / 96;
        const useA4 = mmH > 195;
        const opt = {
            margin:       [0, 0, 0, 0],
            filename:     '<?= 'quotation-' . str_pad($quotation['id'], 4, '0', STR_PAD_LEFT) . '.pdf' ?>',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, allowTaint: true, letterRendering: true },
            jsPDF:        { unit: 'mm', format: useA4 ? 'a4' : 'a5', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }
    <?php if (!empty($_GET['pdf'])): ?>
    document.addEventListener('DOMContentLoaded', function() { setTimeout(exportPDF, 500); });
    <?php endif; ?>
    </script>

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
                <img src="data:image/png;base64,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" alt="Quotation" style="max-height:50px;width:auto">
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
                        <th style="width:22px">#</th>
                        <th style="width:64px;text-align:center">ຮູບ</th>
                        <th>ລາຍການ / Description</th>
                        <th style="width:42px;text-align:center">ຈຳນວນ</th>
                        <th style="width:38px;text-align:center">ຫົວໜ່ວຍ</th>
                        <th style="width:88px">ລາຄາ/ໜ່ວຍ<br><span style="font-weight:400;font-size:8px;">Price (<?= $template['currency'] ?>)</span></th>
                        <th style="width:88px">ຈຳນວນເງິນ<br><span style="font-weight:400;font-size:8px;">Amount (<?= $template['currency'] ?>)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotation['items'] ?? [] as $i => $item): ?>
                    <tr>
                        <td style="color:#94a3b8"><?= $i + 1 ?></td>
                        <td style="text-align:center;">
                            <?php if (!empty($item['product_image'])): ?>
                            <img src="<?= $item['product_image'] ?>" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:6px;display:inline-block;vertical-align:middle;box-shadow:0 1px 3px rgba(0,0,0,.15);">
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

            <?php if (!empty($quotation['terms'])): ?>
            <!-- Terms -->
            <div class="terms">
                <h4>ເງື່ອນໄຂ / Terms &amp; Conditions</h4>
                <ul>
                    <?php foreach (explode("\n", $quotation['terms']) as $term): ?>
                    <?php $t = trim($term); if (empty($t)) continue; ?>
                    <li><?= htmlspecialchars($t) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

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
                <?php if ($supplier && !empty($supplier['address'])): ?>
                ທີ່ຢູ່: <?= htmlspecialchars($supplier['address']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
