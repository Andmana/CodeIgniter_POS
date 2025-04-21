<div class="table-responsive">
    <link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <input type="hidden" id="group" name="group" value="<?= $group; ?>">

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Jumlah Transaksi</th>
                <th scope="col">Jumlah item</th>
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
                    <td><?= $row['date']; ?></td>
                    <td><?= $row['invoice']; ?></td>
                    <td><?= $row['invoice']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['total'], 0, ",", "."); ?></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success" onclick="checkcode('<?= $row['date']; ?>')">
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

    function checkcode(tgl) {
        $.ajax({
            type: 'post',
            url: "<?= site_url('/SaleReport/viewDataProduct'); ?>",
            dataType: 'json',
            data: {
                group: $('#group').val(),
                date: tgl,
            },
            success: function(response) {
                $('.viewmodals').html(response.viewmodal).show();
                $('#modalproduct').modal('show');
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        });
    }
</script>