<?php
$layout = $layout ?? '58mm';
$isThermal = in_array($layout, ['58mm', '80mm']);
$paperWidth = $layout === '58mm' ? '58mm' : ($layout === '80mm' ? '80mm' : '210mm');
?>
<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ໃບເກັບເງິນ #<?= htmlspecialchars($sale['invoice_number'] ?? '') ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&family=Roboto+Mono&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Noto Sans Lao', 'Courier New', monospace;
        width: <?= $paperWidth ?>;
        margin: 0 auto;
        padding: 15px 10px;
        color: #1e293b;
        font-size: <?= $isThermal ? '12px' : '14px' ?>;
        line-height: 1.5;
    }

    .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px dashed #cbd5e1; }
    .header img { max-height: 60px; margin-bottom: 8px; }
    .header h1 { font-size: <?= $isThermal ? '14px' : '20px' ?>; font-weight: 900; }
    .header .info { font-size: <?= $isThermal ? '10px' : '12px' ?>; color: #64748b; }

    .meta { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: <?= $isThermal ? '10px' : '12px' ?>; }
    .meta .left { text-align: left; }
    .meta .right { text-align: right; }

    .customer { margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px dashed #cbd5e1; font-size: <?= $isThermal ? '10px' : '12px' ?>; }
    .customer strong { font-weight: 700; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: <?= $isThermal ? '10px' : '12px' ?>; }
    table th { border-bottom: 1px solid #94a3b8; padding: 4px 2px; font-weight: 700; text-align: left; font-size: <?= $isThermal ? '9px' : '11px' ?>; }
    table td { padding: 3px 2px; border-bottom: 1px dotted #e2e8f0; }
    table .right { text-align: right; }
    table .center { text-align: center; }

    .totals { margin-top: 10px; padding-top: 8px; border-top: 1px dashed #94a3b8; }
    .totals .row { display: flex; justify-content: space-between; padding: 2px 0; font-size: <?= $isThermal ? '10px' : '12px' ?>; }
    .totals .grand { font-size: <?= $isThermal ? '13px' : '18px' ?>; font-weight: 900; border-top: 1px solid #94a3b8; padding-top: 6px; margin-top: 4px; }

    .payment { margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: <?= $isThermal ? '10px' : '12px' ?>; }

    .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: <?= $isThermal ? '9px' : '11px' ?>; color: #64748b; }

    .signature { margin-top: 30px; text-align: center; font-size: <?= $isThermal ? '10px' : '12px' ?>; }
    .signature .line { margin-top: 35px; border-top: 1px solid #1e293b; width: 200px; display: inline-block; padding-top: 5px; }

    @media print {
        body { width: 100%; margin: 0; padding: 0; }
        .no-print { display: none !important; }
        @page { margin: 0; size: <?= $isThermal ? ($layout === '58mm' ? '58mm' : '80mm') : 'A4' ?> auto; }
    }

    .no-print {
        position: fixed;
        top: 0; left: 0; right: 0;
        background: #0f172a;
        padding: 12px 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
        z-index: 1000;
    }
    .no-print button, .no-print a {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-print { background: #0ea5e9; color: white; }
    .btn-print:hover { background: #0284c7; }
    .btn-whatsapp { background: #25D366; color: white; }
    .btn-whatsapp:hover { background: #1da851; }
    .btn-close { background: #475569; color: white; }
    .btn-close:hover { background: #334155; }

    body { padding-top: 60px; }
    @media print { body { padding-top: 0; } }
</style>
</head>
<body>
    <div class="no-print" id="printControls">
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> ພິມ</button>
        <a href="https://wa.me/?text=<?= urlencode('ໃບເກັບເງິນ #' . ($sale['invoice_number'] ?? '') . ' - ຍອດລວມ: ' . number_format($sale['grand_total'] ?? 0, 0) . ' ກີບ') ?>" target="_blank" class="btn-whatsapp"><i class="fab fa-whatsapp"></i> ແຊຣ໌</a>
        <button onclick="window.close()" class="btn-close"><i class="fas fa-times"></i> ປິດ</button>
    </div>

    <div class="header">
        <?php if (!empty($settings['store_logo'])): ?>
        <img src="<?= htmlspecialchars($settings['store_logo']) ?>" alt="Logo">
        <?php endif; ?>
        <h1><?= htmlspecialchars($settings['store_name'] ?? 'ຮ້ານຄ້າ') ?></h1>
        <div class="info">
            <?= htmlspecialchars($settings['store_address'] ?? '') ?><br>
            <?php if (!empty($settings['store_phone'])): ?>ໂທ: <?= htmlspecialchars($settings['store_phone']) ?><?php endif; ?>
            <?php if (!empty($settings['store_email'])): ?> | <?= htmlspecialchars($settings['store_email']) ?><?php endif; ?>
        </div>
    </div>

    <div class="meta">
        <div class="left">
            <strong>ໃບເກັບເງິນ / INVOICE</strong><br>
            #<?= htmlspecialchars($sale['invoice_number'] ?? '') ?>
        </div>
        <div class="right">
            <strong>ວັນທີ / DATE</strong><br>
            <?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?>
        </div>
    </div>

    <?php if (!empty($sale['customer_name'])): ?>
    <div class="customer">
        <strong>ຜູ້ຊື້ / BILL TO</strong><br>
        <?= htmlspecialchars($sale['customer_name']) ?><br>
        <?php if (!empty($sale['customer_phone'])): ?><?= htmlspecialchars($sale['customer_phone']) ?><br><?php endif; ?>
        <?php if (!empty($sale['customer_address'])): ?><?= htmlspecialchars($sale['customer_address']) ?><?php endif; ?>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th style="width:10%">ລ/ດ</th>
                <th style="width:<?= $isThermal ? '35%' : '30%' ?>">ລາຍການ</th>
                <th style="width:10%" class="center">ຈຳນວນ</th>
                <th style="width:15%" class="right">ລາຄາ/ໜ່ວຍ</th>
                <th style="width:20%" class="right">ຈຳນວນເງີນ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sale['items'] as $i => $item): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td class="center"><?= (int)$item['quantity'] ?> <?= htmlspecialchars($item['unit'] ?? '') ?></td>
                <td class="right"><?= number_format($item['price'], 0) ?></td>
                <td class="right"><?= number_format($item['price'] * $item['quantity'], 0) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div class="row">
            <span>ລວມຍ່ອຍ / Subtotal</span>
            <span><?= number_format($sale['subtotal'] ?? $sale['total'], 0) ?> ກີບ</span>
        </div>
        <?php if (!empty($sale['discount'])): ?>
        <div class="row">
            <span>ສ່ວນຫຼຸດ / Discount</span>
            <span>-<?= number_format($sale['discount'], 0) ?> ກີບ</span>
        </div>
        <?php endif; ?>
        <?php if (!empty($sale['tax'])): ?>
        <div class="row">
            <span>ອາກອນ / Tax</span>
            <span><?= number_format($sale['tax'], 0) ?> ກີບ</span>
        </div>
        <?php endif; ?>
        <div class="row grand">
            <span>ລວມທັງໝົດ / GRAND TOTAL</span>
            <span><?= number_format($sale['grand_total'] ?? $sale['total'], 0) ?> ກີບ</span>
        </div>
    </div>

    <div class="payment">
        <div class="row">
            <span>ຊຳລະດ້ວຍ / Payment: <?= ($sale['payment_method'] ?? 'cash') === 'cash' ? 'ເງິນສົດ / Cash' : (($sale['payment_method'] ?? '') === 'transfer' ? 'ໂອນ / Transfer' : (($sale['payment_method'] ?? '') === 'qr' ? 'QR Scan' : 'Credit')) ?></span>
        </div>
        <?php if (!empty($sale['amount_paid'])): ?>
        <div class="row">
            <span>ຊຳລະແລ້ວ / Amount Paid</span>
            <span><?= number_format($sale['amount_paid'], 0) ?> ກີບ</span>
        </div>
        <?php endif; ?>
        <?php if (!empty($sale['change'])): ?>
        <div class="row">
            <span>ເງິນທອນ / Change</span>
            <span><?= number_format($sale['change'], 0) ?> ກີບ</span>
        </div>
        <?php endif; ?>
    </div>

    <div class="signature">
        <div class="line">ຜູ້ຈັດການ / Manager</div>
    </div>

    <div class="footer">
        <?= nl2br(htmlspecialchars($settings['receipt_footer'] ?? 'ຂອບໃຈທີ່ໃຊ້ບໍລິການ')) ?><br>
        <?php if (!empty($settings['invoice_terms'])): ?>
        <br><?= nl2br(htmlspecialchars($settings['invoice_terms'])) ?>
        <?php endif; ?>
    </div>
</body>
</html>
