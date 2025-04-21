<link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

<div class="modal fade" id="modalproduct" tabindex="-1" aria-labelledby="modalproductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="text" value="<?= $where ?>">
                <input type="text" value="<?= $date ?>">
                <div class="table-responsive">
                    <table id="dataTables" class="table table-sm table-bordered table-striped dataTable dtr-inline collapsed" role="grid" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kode Barcode</th>
                                <th>Jumlah terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 1;
                            foreach ($lists as $row) :
                            ?>
                                <tr>
                                    <td><?= $index++; ?></td>
                                    <td><?= $row['name_product']; ?></td>
                                    <td><?= $row['qr_product']; ?></td>
                                    <td><?= $row['qtys']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class=" modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable();
    });
</script>