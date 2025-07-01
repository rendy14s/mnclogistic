<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipment Invoice # <?= esc($shipment['id'] ?? '-') ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            line-height: 20px;
            position: relative;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 8px;
            vertical-align: top;
        }

        .invoice-box table tr.heading td {
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.total td:nth-child(5) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-20 { margin-bottom: 20px; }
        .mt-40 { margin-top: 40px; }

        .signature-block {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 140px;
            color: #000;
            opacity: 0.05;
            font-weight: bold;
            z-index: 0;
            width: 100%;
            text-align: center;
            pointer-events: none;
            white-space: nowrap;
        }

        img.logo {
            width: 120px;
        }

        img.qr {
            width: 80px;
        }

        .invoice-paid-stamp {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 140px;
            font-weight: bold;
            color: #28a745; /* Bootstrap success green */
            opacity: 0.08;
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-box">

        <!-- Watermark (optional) -->
        <?php if ($shipment['status_finance'] == '1'): ?>
            <div class="invoice-paid-stamp">PAID</div>
        <?php else: ?>
            <div class="watermark">INVOICE</div>
        <?php endif; ?>

        <table class="mb-20">
            <tr>
                <td>
                    <strong style="font-size: 14px;">Invoice No:</strong> <span style="font-size: 14px;"><?= esc($shipment['id']) ?></span><br><br>
                    <strong>From:</strong><br>
                    <?= esc($detail_shipment['sender_name'] ?? '-') ?><br>
                    <?= esc($detail_shipment['sender_address'] ?? '-') ?><br>
                    <?= esc($detail_shipment['sender_phone'] ?? '-') ?>
                </td>
                <td class="text-right">
                    <!-- <strong>To / C.q:</strong><br> -->
                    <?= esc($shipment['marking_code']) ?><br>
                    <?= esc($customer['address'] ?? '-') ?><br>
                    <?= esc($customer['phone_number'] ?? '-') ?>
                </td>
            </tr>
        </table>

        <table>
            <!-- First heading row -->
            <tr class="heading">
                <td rowspan="2" style="width:5%;">#</td>
                <td rowspan="2">Description</td>
                <td colspan="4" class="text-center">Dimension (cm)</td>
                <td rowspan="2" style="width:15%;">Real Weight</td>
                <td rowspan="2" style="width:20%;">Used Weight</td>
            </tr>

            <!-- Second heading row (sub-columns for Dimension) -->
            <tr class="heading">
                <td style="width:10%;">P</td>
                <td style="width:10%;">L</td>
                <td style="width:10%;">T</td>
                <td style="width:10%;">V</td>
            </tr>

            <?php
            $i = 1;
            foreach ($details as $item):
            ?>
            <tr class="item">
                <td><?= $i++ ?></td>
                <td><?= esc($item['description']) ?></td>
                <td><?= esc($item['dimension_p']) ?></td>
                <td><?= esc($item['dimension_l']) ?></td>
                <td><?= esc($item['dimension_t']) ?></td>
                <td><?= esc(ceil($item['dimension_v'])) ?></td>
                <td><?= esc($item['real_weight']) ?></td>
                <td><?= esc($item['used_weight']) ?></td>
            </tr>
            <?php endforeach; ?>

            <tr class="total">
                <td colspan="7" class="text-right">Grand Total:</td>
                <td colspan="2" class="text-left"><strong>Rp <?= number_format($shipment['total_price'], 0, ',', '.') ?></strong></td>
            </tr>
        </table>

        <!-- Signature Area -->
        <div class="signature-block">
            <div class="signature">
                <p><strong>Authorized by:</strong></p>
                <p style="margin-top: 40px;"><strong><?= esc($detail_shipment['sender_name']) ?></strong></p>
            </div>
        </div>
    </div>
</body>
</html>
