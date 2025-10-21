<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Waybill Jakarta <?= date('d-m-Y', strtotime($dateReports ?? 'now')) ?></title>
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

        .footer-signature-wrapper {
            display: flex !important;          /* paksa flexbox */
            flex-direction: row !important;    /* sejajarkan horizontal */
            justify-content: center !important;/* posisikan di tengah */
            align-items: flex-start !important;
            text-align: center;
            gap: 120px;                        /* jarak antar tanda tangan */
            margin-top: 100px;
            width: 100%;
            bottom: 40px !important;
        }

        .signature-block {
            width: 250px;
            display: inline-block;
        }

        .signature-space {
            height: 60px; /* ruang kosong untuk tanda tangan */
        }

    </style>
</head>
<body>
    <div class="header">
        <h3>MNC LOGISTIC</h3>
        <h2>SURAT JALAN BARANG</h2>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <th>Nomor</th>
                <td><?= date('Ymd-Hi') ?></td>
            </tr>
            <tr>
                <th>Tanggal Data Laporan</th>
                <td><?= date('d-m-Y', strtotime($dateReports ?? 'now')) ?></td>
            </tr>
            <tr>
                <th>Tanggal Cetak Way Bill</th>
                <td><?= date('d/m/Y H:i') ?></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($shipments)): ?>
        <?php foreach ($shipments as $i => $row): ?>
            <div class="shipment-block">
                <div class="shipment-title">Shipment #<?= $i + 1 ?> — ID: <?= esc($row['id'] ?? '-') ?></div>

                <!-- Shipment Detail -->
                <table>
                    <tr><th>Destination</th><td><?= esc($row['price_code']) ?></td></tr>
                    <tr><th>Consolidation</th><td><?= ($row['consolidation'] == 1) ? 'Yes' : 'No' ?></td></tr>
                </table>

                <!-- Shipment Packages -->
                <?php if (!empty($packages[$row['id']])): ?>
                    <table class="package-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Marking Code</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?= esc($row['marking_code'] ?? '-') ?></td>
                                <td><?= count($packages[$row['id']] ?? []) ?></td>
                                <td>Koli</td>
                            </tr>
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

    <div class="footer-signature-wrapper">
    <div class="signature-block">
        <p>Dikeluarkan Oleh</p>
        <div class="signature-space"></div>
        <p><strong>________________</strong></p>
    </div>
    <div class="signature-block">
        <p>Diterima Oleh</p>
        <div class="signature-space"></div>
        <p><strong>________________</strong></p>
    </div>
    </div>
</body>
</html>
