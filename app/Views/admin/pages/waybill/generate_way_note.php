<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Waybill <?= date('d-m-Y', strtotime($dateReports ?? 'now')) ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }

        h2, h3 {
            margin: 0;
            text-align: center;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
            border: none;
        }
        .info-table th,
        .info-table td {
            text-align: left;
            padding: 3px 6px;
            border: none; /* hilangkan border */
        }
        .info-table th {
            width: 35%;
            white-space: nowrap;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            width: 30%;
        }

        .shipment-block {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }

        .shipment-title {
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: bold;
            border-left: 4px solid #333;
            padding-left: 6px;
        }

        .package-table th,
        .package-table td {
            text-align: center;
        }

        .no-data {
            font-style: italic;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        .footer-signature {
            margin-top: 50px;
            font-size: 12px;
        }

        .footer-signature table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }

        .footer-signature th,
        .footer-signature td {
            border: none;
            padding: 10px;
        }


    </style>
</head>
<body>
    <div class="header">
        <h2>WAYBILL</h2>
        <h3>MNClogistic</h3>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <th>Tanggal Laporan</th>
                <td><?= date('d-m-Y', strtotime($dateReports ?? 'now')) ?></td>
            </tr>
            <tr>
                <th>Tanggal Cetak Way Bill</th>
                <td><?= date('d/m/Y H:i') ?></td>
            </tr>
            <tr>
                <th>Total Shipment</th>
                <td><?= count($shipments ?? []) ?></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($shipments)): ?>
        <?php foreach ($shipments as $i => $row): ?>
            <div class="shipment-block">
                <div class="shipment-title">Shipment #<?= $i + 1 ?> — ID: <?= esc($row['id'] ?? '-') ?></div>

                <!-- Shipment Detail -->
                <table>
                    <tr><th>Marking Code</th><td><?= esc($row['marking_code'] ?? '-') ?></td></tr>
                    <tr><th>Consolidation</th><td><?= ($row['consolidation'] == 1) ? 'Yes' : 'No' ?></td></tr>
                    <tr><th>Destination</th><td><?= esc($row['price_code']) ?></td></tr>
                    <tr><th>Created By</th><td><?= esc($row['created_by_name']) ?></td></tr>
                    <tr><th>Created At</th><td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td></tr>
                </table>

                <!-- Shipment Packages -->
                <?php if (!empty($packages[$row['id']])): ?>
                    <table class="package-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Description / Tracking</th>
                                <th>PxLxT</th>
                                <th>Volume</th>
                                <th>Real Weight</th>
                                <th>Used Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packages[$row['id']] as $j => $pkg): ?>
                                <tr>
                                    <td><?= $j + 1 ?></td>
                                    <td><?= esc($pkg['description'] ?? '-') ?></td>
                                    <td><?= esc(($pkg['dimension_p'] ?? 0) . 'x' . ($pkg['dimension_l'] ?? 0) . 'x' . ($pkg['dimension_t'] ?? 0)) ?></td>
                                    <td><?= esc($pkg['dimension_v'] ?? '') ?></td>
                                    <td><?= esc($pkg['real_weight'] ?? 0) ?> kg</td>
                                    <td><?= esc($pkg['used_weight'] ?? 0) ?> kg</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Tidak ada package untuk shipment ini.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">Tidak ada data shipment</p>
    <?php endif; ?>

    <div class="page-break"></div>

    <div class="footer-signature">
        <p style="text-align:right; margin-bottom:20px;">
            Jakarta, <?= date('d-m-Y') ?>
        </p>
        <table>
            <tr>
                <th>Gudang</th>
                <th>Driver</th>
            </tr>
            <tr>
                <td style="height:80px;"></td>
                <td></td>
            </tr>
            <tr>
                <td>(________________)</td>
                <td>(________________)</td>
            </tr>
        </table>
    </div>

</body>
</html>
