<div class="table-responsive">
    <link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">


    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Jenis Transaksi</th>
                <th scope="col">Jumlah Transaksi</th>
                <th scope="col">Jumlah item</th>
                <th scope="col">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            foreach ($table as $row) :
            ?>
                <tr>
                    <td><?= $index++; ?></td>
                    <td><?= $row['date']; ?></td>
                    <td><?= $row['types']; ?></td>
                    <td><?= $row['transaction']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['items'], 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['total'], 0, ",", "."); ?></td>
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