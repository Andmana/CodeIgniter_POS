<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<script src="<?= base_url('assets'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

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
                <input type="hidden" name="keywordcode" id="keywordcode" value="<?= $keyword; ?>">
                <div class="table-responsive">
                    <table id="dataproduct" class="table table-sm table-bordered table-striped dataTable dtr-inline collapsed" role="grid" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Supplier</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class=" modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#dataproduct').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('/stocktransaction/getList') ?>",
                "type": "POST",
                "data": {
                    keywordcode: $('#keywordcode').val()
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            },
            "columnDefs": [{
                "targets": [],
                "orderable": false,
            }, ],
        });
    });

    function choose(id, code, name, stock, price) {
        $("#productid").val(id);
        $("#barcode").val(code);
        $("#productname").val(name);
        $("#productstock").val(stock);
        $("#purchaseprice").val(price);
        $('#modalproduct').on('hidden.bs.modal', function(event) {
            calculatetotal();
            $('#productqty').focus();
        });
        $('#modalproduct').modal('hide');

    }
</script>