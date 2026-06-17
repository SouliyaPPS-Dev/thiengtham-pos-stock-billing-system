<?php
$currency = $rental['currency'] ?? '₭';
$totalRentalFee = 0; 
foreach ($items as $item) { 
    $totalRentalFee += $item['rental_price'] * $item['qty'];
}

$guarantees = [];
if (!empty($rental['guarantee_id_card'])) $guarantees[] = 'ບັດປະຈຳຕົວ';
if (!empty($rental['guarantee_passport'])) $guarantees[] = 'ພາດສະປອດ';
if (!empty($rental['guarantee_family_book'])) $guarantees[] = 'ສຳມະໂນຄົວ';
if (!empty($rental['guarantee_cash'])) $guarantees[] = 'ມັດຈຳເງິນ';

$paymentStatusText = ($rental['payment_status'] ?? 'Paid') === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້';
$paymentMethodName = htmlspecialchars($rental['payment_method_name'] ?? 'ເງິນສົດ');

// Logo handling: prioritize settings logo, fallback to public/logo.jpg
$storeLogo = $rental['store_logo'] ?? '';
if (empty($storeLogo)) {
    $storeLogo = url('/public/logo.jpg');
} elseif (strpos($storeLogo, 'http') !== 0) {
    $storeLogo = url($storeLogo);
}

$storeName = htmlspecialchars($rental['store_name'] ?? 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ');
$storePhone = htmlspecialchars($rental['store_phone'] ?? '');
$storeAddr = htmlspecialchars($rental['store_address'] ?? '');
$storeEmail = htmlspecialchars($rental['store_email'] ?? '');
$invNumber = htmlspecialchars($rental['invoice_number'] ?? '');
$customerName = htmlspecialchars($rental['customer_name'] ?? '');
$customerPhone = htmlspecialchars($rental['customer_phone'] ?? '');
$customerIdCard = htmlspecialchars($rental['customer_id_card'] ?? '');
$createdByName = htmlspecialchars($rental['created_by_name'] ?? '');
$paperSize = $rental['paper_size'] ?? '80mm';

// Map paper size to display/print width (preview matches print)
switch ($paperSize) {
    case '58mm':
        $displayWidth = '105mm';
        $printWidth = '105mm';
        $pageSize = '105mm auto';
        break;
    case 'A4':
        $displayWidth = '210mm';
        $printWidth = '210mm';
        $pageSize = 'A4';
        break;
    default: // 80mm
        $displayWidth = '130mm';
        $printWidth = '130mm';
        $pageSize = '130mm auto';
        break;
}
?>

<!DOCTYPE html> 
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ບິນ - <?= $invNumber ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: #f0f2f5;
            padding: 30px 10px;
            color: #000;
            font-size: <?= $paperSize === 'A4' ? '14px' : '13px' ?>;
            line-height: 1.4;
            display: flex;
            justify-content: center;
        }
        .invoice-wrap {
            width: <?= $displayWidth ?>;
            max-width: <?= $displayWidth ?>;
            background: #fff;
            padding: <?= $paperSize === 'A4' ? '18mm 22mm' : '10mm 12mm' ?>;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            border: 1px solid #e0e0e0;
        }
        
        @page {
            size: <?= $pageSize ?>;
            margin: 0;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .divider-double { border-top: 3px double #000; margin: 10px 0; }
        
        .header { margin-bottom: 12px; }
        .logo-wrap { text-align: center; margin-bottom: 6px; }
        .logo-wrap img { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; }
        .store-name { font-size: 18px; font-weight: 900; color: #000; margin-bottom: 2px; text-transform: uppercase; }
        .store-info { font-size: 11px; color: #333; }
        
        .bill-info { margin-bottom: 15px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .bill-title { font-size: 19px; font-weight: 900; margin-bottom: 3px; }
        .inv-number { font-size: 13px; font-weight: 700; }
        .meta-row { display: flex; justify-content: space-between; font-size: 11px; margin-top: 4px; }
        
        .customer-block { margin-bottom: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 4px 14px; }
        .customer-item { font-size: 12px; display: flex; gap: 4px; }
        .customer-label { color: #555; font-size: 11px; white-space: nowrap; }
         
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 10px 0;
        }
        table.items th {
            text-align: left;
            padding: 7px 5px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        table.items th.right {
            text-align: right;
        }
        table.items td {
            padding: 6px 5px;
            border-bottom: 0.5px solid #eee;
            vertical-align: top;
        }
        table.items td:first-child {
            width: auto;
        }
        table.items td:nth-child(2),
        table.items th:nth-child(2) {
            width: 55px;
            text-align: center;
        }
        table.items td:nth-child(3),
        table.items th:nth-child(3) {
            width: 90px;
        }
        .item-name { font-weight: 700; font-size: 12.5px; }
        
        .totals-block { margin: 8px 0; max-width: 360px; margin-left: auto; }
        .total-row { display: flex; justify-content: space-between; padding: 3px 0; font-size: 12px; }
        .total-row.grand {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 2px solid #000;
            font-size: 17px;
            font-weight: 900;
        } 
        
        .terms-block { font-size: 11px; line-height: 1.5; margin: 8px 0; padding: 10px 14px; border: 1px solid #eee; background: #fafafa; border-radius: 4px; }
        .terms-title { font-weight: 700; margin-bottom: 3px; font-size: 12px; color: #000; }
        
        .signature-section { margin-top: 20px; display: flex; justify-content: space-between; gap: 25px; }
        .sig-box { flex: 1; text-align: center; }
        .sig-line { border-bottom: 1px solid #000; height: 40px; margin-bottom: 4px; }
        .sig-text { font-size: 11px; font-weight: 700; }

        .footer { margin-top: 16px; border-top: 1px dashed #ccc; padding-top: 8px; }
        .footer-note { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
        .footer-copy { font-size: 10px; color: #888; }

        .actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 20px; border-radius: 8px; font-weight: 700; border: none;
            cursor: pointer; text-decoration: none; font-size: 13px;
            display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-print { background: #0ea5e9; color: #fff; }
        .btn-back { background: #fff; color: #475569; }

        @media print {
            body { background: #fff; padding: 0; display: flex; justify-content: center; }
            .invoice-wrap { 
                box-shadow: none; 
                border: none;
                width: <?= $printWidth ?>; 
                padding: <?= $paperSize === 'A4' ? '10mm 15mm' : '3mm 4mm' ?>; 
                margin: 0 auto; 
                max-width: none;
            }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <!-- Actions -->
    <div class="actions">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> ພິມໃບບິນ
        </button>
        <a href="<?= url('/rentals') ?>" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> ກັບຄືນ
        </a>
    </div>

    <div class="invoice-wrap">
        <!-- Header -->
        <div class="header center">
            <div class="logo-wrap">
                <img src="<?= $storeLogo ?>" alt="Logo">
            </div>
            <div class="store-name"><?= $storeName ?></div>
            <div class="store-info">
                <?php if ($storePhone): ?>ໂທ: <?= $storePhone ?> • <?php endif; ?>
                <?php if ($storeEmail): ?><?= $storeEmail ?><?php endif; ?>
                <?php if ($storeAddr): ?><div style="margin-top: 2px;"><?= $storeAddr ?></div><?php endif; ?>
            </div>
        </div>

        <div class="bill-info center">
            <div class="bill-title">ໃບບິນເຊົ່າເຄື່ອງ</div>
            <div class="inv-number">ເລກທີ: <?= $invNumber ?></div>
            <div class="meta-row">
                <span>ວັນທີ: <?= date('d/m/Y H:i', strtotime($rental['created_at'] ?? 'now')) ?></span>
                <span class="bold"><?= $paymentStatusText ?></span>
            </div>
        </div>

        <!-- Customer Section -->
        <div class="customer-block">
            <div class="customer-item">
                <span class="customer-label">ຊື່:</span>
                <span class="bold"><?= $customerName ?></span>
            </div>
            
            <div class="customer-item">
                <span class="customer-label">ວັນທີເຊົ່າ:</span>
                <span class="bold"><?= date('d/m/Y', strtotime($rental['pickup_date'])) ?></span>
            </div>
            <div class="customer-item">
                <span class="customer-label">ເບີໂທ:</span>
                <span class="bold"><?= $customerPhone ?></span>
            </div>
            <div class="customer-item">
                <span class="customer-label">ກຳນົດສົ່ງຄືນ:</span>
                <span class="bold"><?= date('d/m/Y', strtotime($rental['return_date'])) ?></span>
            </div>
        </div>

        <?php if ($customerIdCard || !empty($guarantees)): ?>
        <div class="divider"></div>
        <div style="font-size: 9px; margin-bottom: 5px;">
            <?php if ($customerIdCard): ?><strong>ບັດ/Passport:</strong> <?= $customerIdCard ?><?php endif; ?>
            <?php if (!empty($guarantees)): ?>
                <div style="margin-top: 2px;">
                    <strong>ຄ້ຳປະກັນ:</strong> <?= implode(', ', $guarantees) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Items Table -->
        <table class="items">
            <thead>
                <tr>
                    <th>ລາຍການສິນຄ້າ</th>
                    <th class="center">ຈຳນວນ</th>
                    <th class="right">ລາຄາລວມ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <span class="item-name"><?= htmlspecialchars($item['product_name'] ?? '') ?></span>
                    </td>
                    <td class="center" style="vertical-align: middle;"><?= (int)($item['qty'] ?? 1) ?></td>
                    <td class="right bold" style="vertical-align: middle;"><?= number_format(($item['rental_price'] ?? 0) * ($item['qty'] ?? 1)) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 

        <!-- Totals -->
        <div class="totals-block">
            <div class="total-row">
                <span>ຄ່າເຊົ່າລວມ:</span>
                <span class="bold"><?= number_format($totalRentalFee) ?></span>
            </div>
            <div class="total-row">
                <span>ຄ່າມັດຈຳ:</span>
                <span class="bold"><?= number_format($rental['total_deposit'] ?? 0) ?></span>
            </div>
            <?php if (!empty($rental['discount']) && $rental['discount'] > 0): ?>
            <div class="total-row" style="color: red;">
                <span>ສ່ວນຫຼຸດ:</span>
                <span class="bold">-<?= number_format($rental['discount']) ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row grand">
                <span>ລວມທັງໝົດ:</span>
                <span><?= number_format($rental['grand_total'] ?? 0) ?> <?= $currency ?></span>
            </div>
        </div>

        <!-- Terms -->
        <?php if (!empty($rental['rental_terms'])): ?>
        <div class="terms-block">
            <div class="terms-title">ເງື່ອນໄຂການເຊົ່າ:</div>
            <div style="white-space: pre-line; line-height: 1.6; word-break: break-word; margin-top: -14px;">
                <?= (htmlspecialchars($rental['rental_terms'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-text">ຜູ້ອອກບິນ</div>
                <div class="sig-line"></div>
                <div style="font-size: 8px;"><?= $createdByName ?: '...' ?></div>
            </div>
            <div class="sig-box">
                <div class="sig-text">ລູກຄ້າ / ຜູ້ຮັບເຄື່ອງ</div>
                <div class="sig-line"></div>
                <div style="font-size: 8px;"><?= $customerName ?: '...' ?></div>
            </div>
        </div>

        <div class="footer center">
            <div class="footer-note"><?= !empty($rental['receipt_footer']) ? htmlspecialchars($rental['receipt_footer']) : 'ຂອບໃຈທີ່ໃຊ້ບໍລິການ' ?></div>
            <div class="footer-copy">No: <?= $invNumber ?> | Print: <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>
</body>
</html>
