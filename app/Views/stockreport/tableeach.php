<div class="table-responsive">
    <link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">


    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Transaksi</th>
                <th scope="col">Produk</th>
                <th scope="col">Operator</th>
                <th scope="col">Tanggal</th>
                <th scope="col">qty</th>
                <th scope="col">harga</th>
                <th scope="col">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            foreach ($table as $row) :
            ?>
                <tr>
                    <td><?= $index++; ?></td>
                    <td><?= $row['type_trans']; ?></td>
                    <td><?= $row['name_product']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['date_trans']; ?></td>
                    <td><?= $row['qty_trans']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['purchasePrice_product'], 0, ",", "."); ?></td>
                    <td><?= $row['detail_trans']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>