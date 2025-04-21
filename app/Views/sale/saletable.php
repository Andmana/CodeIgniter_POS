<div class="table-responsive">
    <link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Invoice</th>
                <th scope="col">Operator</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Jumlah harga</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            foreach ($table as $row) :
            ?>
                <tr>
                    <td><?= $index++; ?></td>
                    <td><?= $row['invoice_sell']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['date_sell']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalprice_sell'], 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalpay_sell'], 0, ",", "."); ?></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success" onclick="window.location='/SaleReport/printinvoice/<?= $row['invoice_sell']; ?>'">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="viewmodals" style="display: none;"></div>

</div>
<script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>