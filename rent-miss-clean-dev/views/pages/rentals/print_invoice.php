<?php
$currency = $rental['currency'] ?? 'ກີບ';
$totalRentalFee = 0;
foreach ($items as $item) {
    $totalRentalFee += $item['rental_price'] * $item['qty'];
}

$guarantees = [];
if (!empty($rental['guarantee_id_card'])) $guarantees[] = 'ບັດປະຈຳຕົວ';
if (!empty($rental['guarantee_passport'])) $guarantees[] = 'ພາດສະປອດ';
if (!empty($rental['guarantee_family_book'])) $guarantees[] = 'ສຳມະໂນຄົວ';
if (!empty($rental['guarantee_cash'])) $guarantees[] = 'ມັດຈຳເງິນ';

$allGuaranteeTypes = ['ບັດປະຈຳຕົວ', 'ພາດສະປອດ', 'ສຳມະໂນຄົວ', 'ມັດຈຳເງິນ'];

$paymentStatusText = ($rental['payment_status'] ?? 'Paid') === 'Paid' ? 'ຈ່າຍແລ້ວ' : 'ຕິດໜີ້';
$paymentMethodName = htmlspecialchars($rental['payment_method_name'] ?? 'ເງິນສົດ');

$statusLabel = $rental['status'] ?? 'Active';
$statusBadgeLabels = [
    'Active' => 'ກຳລັງເຊົ່າ',
    'Returned' => 'ສົ່ງຄືນແລ້ວ',
    'Overdue' => 'ເກີນກຳນົດ',
    'Cancelled' => 'ຍົກເລີກ'
];
$sl = $statusBadgeLabels[$statusLabel] ?? $statusLabel;

$statusBadgeColors = [
    'Active' => '#0d6efd',
    'Returned' => '#198754',
    'Overdue' => '#dc3545',
    'Cancelled' => '#6c757d'
];
$sc = $statusBadgeColors[$statusLabel] ?? '#6c757d';
$pc = ($rental['payment_status'] ?? 'Paid') === 'Paid' ? '#198754' : '#fd7e14';

$storeLogo = $rental['store_logo'] ?? '';
if (empty($storeLogo)) {
    $storeLogo = url('/public/logo.jpg');
} elseif (strpos($storeLogo, 'http') !== 0) {
    $storeLogo = url($storeLogo);
}

$storeName = htmlspecialchars($rental['store_name'] ?? 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ');
$storePhone = htmlspecialchars($rental['store_phone'] ?? '');
$storeAddr = htmlspecialchars($rental['store_address'] ?? '');
$invNumber = htmlspecialchars($rental['invoice_number'] ?? '');
$customerName = htmlspecialchars($rental['customer_name'] ?? '');
$currentUrl = url('/print-invoice/' . $rental['id']);
$customerPhone = htmlspecialchars($rental['customer_phone'] ?? '');
$customerIdCard = htmlspecialchars($rental['customer_id_card'] ?? '');
$createdByName = htmlspecialchars($rental['created_by_name'] ?? '');
$pickupDate = $rental['pickup_date'] ?? $rental['created_at'] ?? 'now';
$returnDate = $rental['return_date'] ?? 'now';
$rentalDays = max(1, round((strtotime($returnDate) - strtotime($pickupDate)) / 86400));
$paperSize = $rental['paper_size'] ?? '80mm';

$defaultTerms = '1. ສິນຄ້າທີຕົກລົງເຊົ່າ ຫຼື ຂາຍແລ້ວບໍ່ສາມາດຄືນເງີນໄດ້ໃນທຸກກໍລະນີ
2. ເຊັກເຄື່ອງລະອຽດທຸກຄັ້ງກ່ອນອອກຈາກຮ້ານ
3. ຫ້າມນໍາໄປຍັບ ຫຼື ແປງສະພາບເຄື່ອງ ກໍລະນິກວດພົບປັບໄໝ 100% ຂອງມູນຄ່າເຄື່ອງ
4. ກຳນົດເຊົ່າເຄື່ອງ 3 ມື້ ລວມມື້ເຊົ່າອອກຮ້ານ ກໍລະນີ ກາຍມື້ກຳນົດສົ່ງເຄື່ອງ ປັບໄໝ ມື້ລະ 300,000 ກີບ
5. ກໍລະນີ ເປື້ອນຄາບຈາກການໃຊ້ງານເລັກໜ້ອຍຊັກໃຫ້ຟີ, ກໍລະນີ ເປື້ອນຫຼາຍທາງຮ້ານຂໍ ອານຸຍາດ ເກັບຄ່າສະປາ 150,000 ກີບ
6. ຫຼັງຈາກເຊົ່າໄປແລ້ວຊຸດດ່າງຂາວ, ມີ ຮອຍຄາບທີ ມີ ຕໍານິ ຈົນ ບໍ່ ສາມາດ ນໍາ ໃຊ້ ຕໍ່ ໄດ້ ລູກຄ້າ ຕ້ອງ ຊື້ ເຕັມ ຈໍານວນ ມູນຄ່າ ເຄື່ອງ
7. ຫ້າມ ລູກຄ້າ ລິດ ຫຼື ຊັກ ເອງ ເດັດ ຂາດ, ນຸ່ງອອກ ແລ້ວ ສົ່ງ ກັບ ຮ້ານ ທັນທີ
8. ເອກະສານ ທີ ລູກຄ້າ ມັດຈໍາ ໄວ້ ຈະ ສົ່ງ ຄືນ ຕອນ ສົ່ງ ເຄື່ອງ (ກໍລະນິ ຝາກ ໄວ້ ເກີນ 3 ວັນ) ທາງຮ້ານ ຈະ ບໍ ຮັບ ຜິດ ຊອບ ທຸກ ກໍລະນີ.';
$terms = !empty($rental['rental_terms']) ? $rental['rental_terms'] : $defaultTerms;
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ບິນ - <?= $invNumber ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: #f0f4f8;
            padding: 24px 16px;
            color: #212529;
            font-size: 15px;
            line-height: 1.5;
        }
        .no-print { display: block; }
        .invoice-wrap {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,.10);
            overflow: hidden;
        }
        .inv-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: #fff;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .inv-header .logo-img { max-height: 56px; border-radius: 8px; background: #fff; padding: 4px; }
        .inv-header-left { flex: 1; display: flex; align-items: center; gap: 14px; min-width: 0; }
        .inv-header-left .store-name { font-size: 1rem; font-weight: 700; white-space: nowrap; }
        .inv-header-left .store-sub { font-size: .72rem; opacity: .85; }
        .inv-header-right { flex: 1; text-align: right; min-width: 0; }
        .inv-header-right .label { font-size: .75rem; opacity: .8; white-space: nowrap; }
        .inv-header-right .number { font-size: 1.2rem; font-weight: 900; white-space: nowrap; }
        .inv-body { padding: 24px 28px; }

        .section-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; margin-bottom: 2px; }
        .info-val { font-size: 1rem; font-weight: 600; }
        .status-badge {
            display: inline-block; border-radius: 20px; padding: 4px 16px; font-size: .82rem;
            font-weight: 700; color: #fff;
        }

        table.items { width: 100%; border-collapse: collapse; font-size: .9rem; margin: 12px 0; }
        table.items th {
            background: #f8f9fa; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em;
            padding: 10px 10px; border: 1px solid #dee2e6; font-weight: 700;
        }
        table.items td { padding: 10px; border: 1px solid #dee2e6; vertical-align: middle; }
        table.items .item-name { font-weight: 700; font-size: .9rem; }

        .collateral-box {
            padding: 14px 16px; border: 1px solid #ffc107; background: #fffdf0; border-radius: 8px; margin-bottom: 12px;
        }
        .collateral-box .label { font-weight: 700; font-size: .82rem; color: #856404; margin-bottom: 8px; }
        .collateral-items { display: flex; flex-wrap: wrap; gap: 6px 16px; }
        .collateral-items .item { display: inline-flex; align-items: center; gap: 6px; }

        .terms-box {
            font-size: .78rem; line-height: 1.7; text-align: justify;
            padding: 12px 16px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; margin-bottom: 12px;
        }
        .terms-box .label { font-weight: 700; font-size: .82rem; margin-bottom: 4px; }
        .terms-box ol { padding-left: 16px; }
        .terms-box ol li { margin-bottom: 2px; }

        .summary-table { max-width: 340px; margin-left: auto; }
        .summary-table .row { display: flex; justify-content: space-between; padding: 5px 0; font-size: .9rem; }
        .summary-table .row.grand {
            margin-top: 6px; padding-top: 8px; border-top: 2px solid #212529;
            font-size: 1.1rem; font-weight: 900;
        }
        .summary-table .row.grand .total { color: #0d6efd; }

        .signature-section { margin-top: 24px; display: flex; justify-content: space-between; gap: 30px; }
        .sig-box { flex: 1; text-align: center; }
        .sig-line { border-bottom: 2px dotted #bbb; height: 44px; margin-bottom: 4px; }
        .sig-text { font-size: .78rem; font-weight: 700; }

        .footer { margin-top: 16px; border-top: 1px dashed #dee2e6; padding-top: 12px; }
        .footer-note { font-size: .82rem; font-weight: 700; margin-bottom: 2px; }
        .footer-copy { font-size: .72rem; color: #888; }

        .actions {
            position: fixed; top: 24px; right: 24px;
            display: flex; flex-direction: column; gap: 10px; z-index: 1000;
        }
        .btn {
            padding: 12px 20px; border-radius: 10px; font-weight: 700; border: none;
            cursor: pointer; text-decoration: none; font-size: 13px;
            display: flex; align-items: center; gap: 10px;
            font-family: 'Noto Sans Lao', sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform .15s, box-shadow .15s;
            min-width: 155px; justify-content: center;
            position: relative;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .btn-print { background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #fff; }
        .btn-small { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
        .btn-whatsapp { background: linear-gradient(135deg, #25D366, #1da851); color: #fff; }
        .btn-copy { background: linear-gradient(135deg, #6b7280, #4b5563); color: #fff; }
        .btn-back { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }

        @page { size: A4; margin: 0; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; margin: 0; padding: 10mm; }
            .invoice-wrap { box-shadow: none; border-radius: 0; max-width: 100%; }
            .actions { display: none; }
            .inv-header { background: #fff !important; }
            .inv-header .store-name,
            .inv-header .store-sub { color: #000 !important; }
            .inv-header .number { color: #000 !important; }
            .inv-header-right span[style*="border"] { background: transparent !important; color: #198754 !important; }
        }
        @media (max-width: 640px) {
            body { padding: 8px 8px 76px; }
            .inv-header { flex-wrap: wrap; padding: 16px; gap: 10px; }
            .inv-header-left { flex: none; width: 100%; }
            .inv-header-left .store-name { font-size: .9rem; white-space: normal; }
            .inv-header-left .store-sub { font-size: .65rem; }
            .inv-header-right { flex: none; width: 100%; text-align: left; padding-top: 4px; border-top: 1px solid rgba(255,255,255,0.2); }
            .inv-header-right .number { font-size: 1rem; }
            .inv-body { padding: 14px 16px; }
            .inv-body > div:first-child { flex-direction: column !important; gap: 12px !important; }
            .inv-body > div:first-child > div { flex: none !important; min-width: 0 !important; width: 100% !important; text-align: left !important; padding: 12px; border-radius: 10px; background: #f8f9fa; }
            .inv-body > div:first-child > div + div { margin-top: 0; }
            .inv-body > div:first-child > div:last-child { border: 1px solid #e9ecef; background: #fff; }
            .status-badge { font-size: .78rem; padding: 3px 12px; }
            .collateral-box { padding: 10px 12px; }
            .collateral-items { gap: 4px 12px; }
            .terms-box { padding: 10px 12px; font-size: .75rem; }
            .summary-table { margin-left: 0; max-width: 100%; padding: 0 4px; }
            .signature-section { flex-direction: column; gap: 16px; }
            .sig-line { height: 36px; }

            table.items { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; font-size: .82rem; }
            table.items th,
            table.items td { padding: 8px 6px; }
            table.items th:first-child,
            table.items td:first-child { padding-left: 4px; }
            table.items th:last-child,
            table.items td:last-child { padding-right: 4px; }

            .actions {
                position: fixed; bottom: 0; left: 0; right: 0; top: auto;
                flex-direction: row; justify-content: center; gap: 6px;
                padding: 8px 10px; background: #fff;
                border-top: 1px solid #e2e8f0;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
                z-index: 999;
            }
            .btn {
                min-width: 0; flex: 1; padding: 12px 0; font-size: 18px; border-radius: 12px;
                justify-content: center; gap: 0; max-width: 64px;
            }
            .btn .btn-text { display: none; }
            .actions select.btn {
                padding: 0 !important; margin: 0 !important;
                border: none !important; border-radius: 12px !important;
                font-size: 16px !important; font-weight: 700 !important;
                font-family: 'Noto Sans Lao', sans-serif !important;
                text-align: center !important; text-align-last: center !important;
                background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important;
                color: #fff !important; min-width: 64px !important; max-width: 64px !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
                cursor: pointer;
            }
            .actions input[type="text"] { display: none !important; }
        }

        .small-bill .bill-title-print { display: block; }
        .small-bill .bill-title-screen { display: none; }

        @media print {
            .small-bill body { padding: 2mm !important; }
            .small-bill .invoice-wrap { margin: 0 auto !important; }
            .small-bill .inv-header { background: #fff !important; flex-wrap: wrap !important; gap: 0 !important; padding: 2px 4px 0 !important; align-items: flex-start !important; justify-content: center !important; }
            .small-bill .inv-header > .logo-img { display: block !important; flex: none !important; max-height: 52px !important; max-width: 90px !important; margin: 2px 6px 2px 0 !important; object-fit: contain !important; background: #fff !important; }
            .small-bill .store-info-print { display: block !important; flex: 1 !important; text-align: right !important; align-self: center !important; min-width: 0 !important; }
            .small-bill .store-info-print .store-name { font-size: .65rem !important; white-space: normal !important; font-weight: 700 !important; color: #000 !important; }
            .small-bill .store-info-print .store-sub { font-size: .42rem !important; color: #000 !important; white-space: normal !important; line-height: 1.2 !important; }
            .small-bill .bill-title-print { display: block !important; flex: 0 0 100% !important; text-align: center !important; font-size: .9rem !important; font-weight: 900 !important; color: #000 !important; padding: 1px 0 !important; }
            .small-bill .inv-header-left { flex: 1 !important; flex-direction: column !important; align-items: flex-start !important; gap: 0 !important; min-width: 0 !important; padding-right: 4px !important; }
            .small-bill .inv-header-left > div:first-child { display: none !important; }
            .small-bill .inv-header-left .store-name { font-size: .72rem !important; white-space: normal !important; font-weight: 700 !important; color: #000 !important; }
            .small-bill .inv-header-left .store-sub { font-size: .55rem !important; color: #000 !important; }
            .small-bill .inv-header-left .bill-customer-print { display: block !important; margin-top: 2px !important; padding-top: 2px !important; }
            .small-bill .inv-number-print { display: none !important; }
            .small-bill .inv-header-right .number { display: none !important; }
            .small-bill .bill-customer-print div:nth-child(2) { font-size: .6rem !important; color: #6c757d !important; }
            .small-bill .info-row > div:first-child { display: none !important; }
            .small-bill .inv-header-right { flex: 1 !important; text-align: right !important; padding-top: 0 !important; border-top: none !important; min-width: 0 !important; }
            .small-bill .inv-header-right .number { color: #000 !important; font-size: .75rem !important; }
            .small-bill .inv-header-right span[style*="border"] { font-size: .6rem !important; padding: 0 5px !important; background: transparent !important; }
            .small-bill .inv-header-right .bill-dates-print { display: block !important; margin-top: 4px !important; }
            .small-bill .inv-header-right .bill-dates-print div:first-child { color: #198754 !important; font-size: .65rem !important; }
            .small-bill .inv-header-right .bill-dates-print div:last-child { color: #dc3545 !important; font-size: .65rem !important; font-weight: 700 !important; }
            .small-bill .bill-title-screen { display: none !important; }
            .small-bill .inv-body { padding: 2px 4px !important; }
            .small-bill .info-row { margin-bottom: 2px !important; gap: 2px !important; display: flex !important; flex-wrap: nowrap !important; justify-content: space-between !important; }
            .small-bill .info-row > div:nth-child(2) { display: none !important; }
            .small-bill .info-row > div:last-child { display: none !important; }
            .small-bill .section-label { font-size: .55rem !important; color: #6c757d !important; margin-bottom: 0 !important; }
            .small-bill .info-val { font-size: .68rem !important; }
            .small-bill .info-row div[style*="color:#6c757d; font-size:.85rem"] { font-size: .6rem !important; }
            .small-bill table.items { font-size: .6rem !important; margin: 2px 0 !important; width: 100% !important; }
            .small-bill table.items th { font-size: .55rem !important; padding: 2px 2px !important; }
            .small-bill table.items td { padding: 2px 2px !important; }
            .small-bill table.items .item-name { font-size: .55rem !important; }
            .small-bill table.items td div[style*="font-size"] { font-size: .5rem !important; text-align: left !important; }
            .small-bill table.items th:first-child,
            .small-bill table.items td:first-child { width: 8% !important; }
            .small-bill table.items th:nth-child(2),
            .small-bill table.items td:nth-child(2) { width: 46% !important; }
            .small-bill table.items th:nth-child(3),
            .small-bill table.items td:nth-child(3) { width: 10% !important; }
            .small-bill table.items th:nth-child(4),
            .small-bill table.items td:nth-child(4),
            .small-bill table.items th:nth-child(5),
            .small-bill table.items td:nth-child(5) { width: 18% !important; }
            .small-bill .collateral-box,
            .small-bill .terms-box { padding: 2px 4px !important; font-size: .55rem !important; margin-bottom: 3px !important; }
            .small-bill .collateral-box .label,
            .small-bill .terms-box .label { font-size: .6rem !important; margin-bottom: 1px !important; }
            .small-bill .collateral-items { gap: 1px 3px !important; flex-wrap: nowrap !important; }
            .small-bill .collateral-items .item { font-size: .52rem !important; gap: 2px !important; white-space: nowrap !important; }
            .small-bill .collateral-items .item i { font-size: 10px !important; }
            .small-bill .summary-table { margin: 0 auto !important; padding: 0 !important; max-width: 100% !important; }
            .small-bill .summary-table .row { font-size: .62rem !important; padding: 1px 0 !important; }
            .small-bill .summary-table .row.grand { font-size: .72rem !important; }
            .small-bill .signature-section { margin-top: 8px !important; gap: 4px !important; display: flex !important; }
            .small-bill .sig-text { font-size: .55rem !important; margin-bottom: 4px !important; }
            .small-bill .sig-line { height: 24px !important; }
            .small-bill .footer { margin-top: 3px !important; padding-top: 2px !important; }
            .small-bill .footer-note { font-size: .55rem !important; }
            .small-bill .footer-copy { font-size: .5rem !important; }
            .small-bill .collateral-box { display: block !important; }
            .small-bill .terms-box { display: block !important; }
            .small-bill .sig-box-staff { display: block !important; text-align: right !important; }
            .small-bill .staff-footer { display: none !important; }
            .small-bill .inv-body > div[style*="display:flex"] { flex-direction: column !important; gap: 0 !important; }
            .small-bill .inv-body > div[style*="display:flex"] > div { flex: none !important; min-width: 0 !important; width: 100% !important; }
        }
    </style>
</head>
<body>
    <div class="actions no-print">
        <button onclick="window.print()" class="btn btn-print" title="ພິມໃບບິນ">
            <i class="fas fa-print"></i><span class="btn-text"> ພິມໃບບິນ</span>
        </button>
        <select id="paperSize" onchange="toggleCustomSize()" class="btn btn-small" title="ເລືອກຂະໜາດກະດາດ" style="padding:12px 8px;border-radius:10px;border:1.5px solid #d1d5db;font-family:'Noto Sans Lao',sans-serif;font-size:13px;font-weight:700;background:#fff;color:#374151;min-width:70px;box-shadow:0 4px 12px rgba(0,0,0,0.1);-webkit-appearance:none;appearance:none;text-align:center;text-align-last:center;cursor:pointer;">
            <option value="80mm" <?= $paperSize === '80mm' ? 'selected' : '' ?>>80mm</option>
            <option value="76mm" <?= $paperSize === '76mm' ? 'selected' : '' ?>>76mm</option>
            <option value="58mm" <?= $paperSize === '58mm' ? 'selected' : '' ?>>58mm</option>
            <option value="A4" <?= $paperSize === 'A4' ? 'selected' : '' ?>>A4</option>
            <option value="custom" <?= preg_match('/^\d/', $paperSize) && !in_array($paperSize, ['80mm','76mm','58mm','A4']) ? 'selected' : '' ?>>Custom</option>
        </select>
        <input type="text" id="customSize" placeholder="e.g. 100mm" onchange="savePaperSize()" value="<?= preg_match('/^\d/', $paperSize) && !in_array($paperSize, ['80mm','76mm','58mm','A4']) ? htmlspecialchars($paperSize) : '' ?>" style="display:<?= preg_match('/^\d/', $paperSize) && !in_array($paperSize, ['80mm','76mm','58mm','A4']) ? 'block' : 'none' ?>;padding:12px 8px;border-radius:10px;border:1.5px solid #d1d5db;font-family:'Noto Sans Lao',sans-serif;font-size:13px;font-weight:700;background:#fff;color:#374151;min-width:60px;flex:1;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <button onclick="printSmallBill()" class="btn btn-small" title="ພິມໃບບິນນ້ອຍ">
            <i class="fas fa-receipt"></i><span class="btn-text"> ພິມໃບບິນນ້ອຍ</span>
        </button>
        <a href="https://wa.me/?text=<?= urlencode("ສະບາຍດີ {$customerName}\nນີ້ຄືໃບບິນເຊົ່າ {$invNumber} ຂອງທ່ານ:\n{$currentUrl}") ?>" target="_blank" class="btn btn-whatsapp" title="ສົ່ງ WhatsApp">
            <i class="fab fa-whatsapp"></i><span class="btn-text"> ສົ່ງ WhatsApp</span>
        </a>
        <a href="<?= url('/rentals') ?>" class="btn btn-back" title="ກັບຄືນ">
            <i class="fas fa-arrow-left"></i><span class="btn-text"> ກັບຄືນ</span>
        </a>
    </div>

    <div class="invoice-wrap">
        <div class="inv-header">
            <?php if (!empty($storeLogo)): ?>
            <img src="<?= $storeLogo ?>" class="logo-img" alt="Logo">
            <?php endif; ?>
            <div class="store-info-print" style="display:none;text-align:right;">
                <div class="store-name"><?= $storeName ?></div>
                <div class="store-sub"><?php if ($storePhone): ?>ໂທ: <?= $storePhone ?> | <?php endif; ?><?= str_replace('ນະຄອນຫຼວງ', '<br>ນະຄອນຫຼວງ', $storeAddr) ?: '' ?></div>
            </div>
            <div class="bill-title-print" style="display:none;">ໃບບິນເຊົ່າເຄື່ອງ</div>
            <div class="inv-header-left">
                <div style="min-width:0;">
                    <div class="store-name"><?= $storeName ?></div>
                    <div class="store-sub"><?php if ($storePhone): ?>ໂທ: <?= $storePhone ?> | <?php endif; ?><?= $storeAddr ?: '' ?></div>
                </div>
                <div class="inv-number-print" style="display:none;font-size:.7rem;font-weight:700;color:#000;margin-bottom:2px;"><?= $invNumber ?></div>
                <div class="bill-customer-print" style="display:none; margin-top:1px;">
                    <div style="font-size:.6rem;font-weight:600;color:#000;"><span style="color:#6c757d;font-weight:400;">ລູກຄ້າ:</span> <?= $customerName ?></div>
                    <div style="font-size:.52rem;color:#6c757d;"><?= $customerPhone ?></div>
                    <?php if ($customerIdCard): ?>
                    <div style="font-size:.52rem;color:#6c757d;">ບັດ: <?= $customerIdCard ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="inv-header-right">
                <div style="font-size:.82rem; font-weight:700; margin-bottom:2px; color:#fff;">
                    <span style="display:inline-block; border:2px solid <?= $pc ?>; border-radius:20px; padding:1px 12px; background:<?= $pc ?>; color:#fff; font-weight:700;"><?= $paymentStatusText ?></span>
                </div>
                <div class="number"><?= $invNumber ?></div>
                <div class="bill-dates-print" style="display:none; margin-top:2px;">
                    <div style="font-size:.55rem;">ວັນທີເຊົ່າ: <?= date('d/m/Y', strtotime($pickupDate)) ?></div>
                    <div style="font-size:.55rem; font-weight:700;">ກຳນົດຄືນ: <?= date('d/m/Y', strtotime($returnDate)) ?></div>
                </div>
            </div>
        </div>

        <div class="inv-body">
            <!-- Info Row -->
            <div style="display:flex; flex-wrap:wrap; gap:16px 24px; margin-bottom:20px; align-items:flex-start;" class="info-row">
                <div style="flex:1; min-width:200px;">
                    <div class="section-label">ລູກຄ້າ</div>
                    <div class="info-val"><?= $customerName ?></div>
                    <div style="color:#6c757d; font-size:.85rem;"><?= $customerPhone ?></div>
                    <?php if ($customerIdCard): ?>
                    <div style="color:#6c757d; font-size:.85rem;">ບັດ: <?= $customerIdCard ?></div>
                    <?php endif; ?>
                </div>
                <div style="flex:1; min-width:200px; text-align:center;">
                    <div class="bill-title-screen" style="font-size:1.3rem; font-weight:900; color:#000; letter-spacing:0.03em;">
                        ໃບບິນເຊົ່າເຄື່ອງ
                    </div>
                </div>
                <div style="flex:1; min-width:200px; text-align:right;">
                    <div style="color:#198754; font-size:.95rem; margin-bottom:4px;">ວັນທີເຊົ່າ: <strong><?= date('d/m/Y', strtotime($pickupDate)) ?></strong></div>
                    <div style="color:#dc3545; font-size:1.05rem; font-weight:700; margin-bottom:4px;">ກຳນົດຄືນ: <strong><?= date('d/m/Y', strtotime($returnDate)) ?></strong></div>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items" style="margin-top:0;">
                <thead>
                    <tr>
                        <th style="width:32px;text-align:center;">#</th>
                        <th>ລາຍການເຊົ່າ</th>
                        <th style="width:55px;text-align:center;">ຈຳນວນ</th>
                        <th style="width:85px;text-align:right;">ຄ່າເຊົ່າ</th>
                        <th style="width:85px;text-align:right;">ລວມ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num = 1; ?>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="text-align:center;font-weight:700;color:#6c757d;"><?= $num++ ?></td>
                        <td>
                            <span class="item-name"><?= htmlspecialchars($item['product_name'] ?? '') ?></span>
                            <?php if (!empty($item['size'])): ?>
                            <div style="font-size:.72rem;color:#888;">ຂະໜາດ: <?= htmlspecialchars($item['size']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;"><?= (int)($item['qty'] ?? 1) ?></td>
                        <td style="text-align:right;"><?= number_format($item['rental_price'] ?? 0) ?></td>
                        <td style="text-align:right;font-weight:700;"><?= number_format(($item['rental_price'] ?? 0) * ($item['qty'] ?? 1)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Collateral + Terms + Summary -->
            <div style="display:flex; flex-wrap:wrap; gap:16px;">
                <div style="flex:1.2; min-width:280px;">
                    <!-- Collateral -->
                    <div class="collateral-box">
                        <div class="label">🛡 ເອກະສານຄ້ຳປະກັນ:</div>
                        <div class="collateral-items">
                            <?php foreach ($allGuaranteeTypes as $type): ?>
                            <?php $checked = in_array($type, $guarantees); ?>
                            <span class="item">
                                <i class="<?= $checked ? 'fas fa-check-circle' : 'far fa-circle' ?>" style="font-size:14px;color:<?= $checked ? '#198754' : '#6c757d' ?>;"></i>
                                <span style="font-size:.82rem;<?= $checked ? 'font-weight:700;color:#198754;' : 'color:#6c757d;' ?>"><?= $type ?></span>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="terms-box">
                        <div class="label">ເງື່ອນໄຂການເຊົ່າ</div>
                        <ol>
                            <?php
                            $termLines = preg_split('/\d+\.\s*/', $terms, -1, PREG_SPLIT_NO_EMPTY);
                            foreach ($termLines as $line):
                            ?>
                            <li><?= htmlspecialchars(trim($line)) ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>

                <!-- Summary -->
                <div style="flex:0.8; min-width:240px;">
                    <div class="summary-table">
                        <div class="row">
                            <span style="color:#6c757d;">ຄ່າເຊົ່າລວມ:</span>
                            <span class="bold"><?= number_format($totalRentalFee) ?></span>
                        </div>
                        <?php if (!empty($rental['guarantee_cash']) && !empty($rental['total_deposit']) && $rental['total_deposit'] > 0): ?>
                        <div class="row">
                            <span style="color:#6c757d;font-size:.82rem;">ຄ່າມັດຈຳ (ຮັບຄືນ):</span>
                            <span class="bold"><?= number_format($rental['total_deposit'] ?? 0) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($rental['discount']) && $rental['discount'] > 0): ?>
                        <div class="row" style="color:#dc3545;">
                            <span>ສ່ວນຫຼຸດ:</span>
                            <span class="bold">-<?= number_format($rental['discount']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="row grand">
                            <span>ຍອດລວມ:</span>
                            <span class="total"><?= number_format($rental['grand_total'] ?? 0) ?> <?= $currency ?></span>
                        </div>
                    </div>

                    <!-- Signatures -->
                    <div class="signature-section">
                        <div class="sig-box">
                            <div class="sig-text">ລູກຄ້າ / ຜູ້ເຊົ່າ</div>
                            <div class="sig-line"></div>
                            <div style="font-size:.72rem;color:#6c757d;"><?= $customerName ?: '...' ?></div>
                        </div>
                        <div class="sig-box sig-box-staff" style="text-align:right;display:none;">
                            <div class="sig-text">ພະນັກງານອອກບິນ:</div>
                            <div style="font-size:.72rem;color:#6c757d;margin-top:2px;"><?= $createdByName ?: 'Administrator' ?></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer text-center" style="text-align:center;">
                        <div style="font-size:.9rem; color:#6c757d; margin-bottom:10px;" class="staff-footer"><span style="font-weight:600;">ພະນັກງານອອກບິນ:</span><br><?= $createdByName ?: 'Administrator' ?></div>
                        <div class="footer-note" style="margin-bottom:6px;"><?= !empty($rental['receipt_footer']) ? htmlspecialchars($rental['receipt_footer']) : 'ຂອບໃຈທີ່ໃຊ້ບໍລິການ' ?></div>
                        <div class="footer-copy">No: <?= $invNumber ?> | Print: <?= date('d/m/Y H:i') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleCustomSize() {
            var sel = document.getElementById('paperSize');
            var inp = document.getElementById('customSize');
            if (sel && inp) {
                inp.style.display = sel.value === 'custom' ? 'block' : 'none';
            }
            savePaperSize();
        }
        function savePaperSize() {
            try {
                var sel = document.getElementById('paperSize');
                var inp = document.getElementById('customSize');
                if (sel) {
                    var data = { option: sel.value };
                    if (sel.value === 'custom' && inp) {
                        data.customValue = inp.value;
                    }
                    localStorage.setItem('smallBillPaperSize', JSON.stringify(data));
                }
            } catch(e) {}
        }
        function loadPaperSize() {
            try {
                var data = JSON.parse(localStorage.getItem('smallBillPaperSize'));
                if (!data) return;
                var sel = document.getElementById('paperSize');
                var inp = document.getElementById('customSize');
                if (!sel) return;
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value === data.option) {
                        sel.value = data.option;
                        break;
                    }
                }
                if (data.option === 'custom' && inp) {
                    inp.value = data.customValue || '';
                    inp.style.display = 'block';
                }
            } catch(e) {}
        }
        document.addEventListener('DOMContentLoaded', loadPaperSize);
        function printSmallBill() {
            var wrap = document.querySelector('.invoice-wrap');
            if (wrap) {
                var sel = document.getElementById('paperSize');
                var inp = document.getElementById('customSize');
                var size = '80mm';
                if (sel) {
                    size = sel.value === 'custom' ? (inp ? inp.value.trim() : '80mm') : sel.value;
                    if (!size) size = '80mm';
                }
                wrap.classList.add('small-bill');
                wrap.style.maxWidth = size;
                setTimeout(function() {
                    window.print();
                }, 50);
            } else {
                window.print();
            }
        }
        window.onafterprint = function() {
            var wrap = document.querySelector('.invoice-wrap');
            if (wrap) {
                wrap.classList.remove('small-bill');
                wrap.style.maxWidth = '';
            }
        };
    </script>
</body>
</html>
