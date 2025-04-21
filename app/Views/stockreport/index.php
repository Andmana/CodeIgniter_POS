<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

<div class="container-fluid">


    <div class="card-header shadow mb-4">
        <div class="card-header row py-3">

            <div class="col-sm-12 col-md-3">
                <select class="custom-select custom-select-sm form-control form-control-sm" id="entries" name="entries" onchange=SaleDetailData()>
                    <option value="onebyone">Transaksi</option>
                    <option value="day">Harian</option>
                    <option value="month">Bulanan</option>
                    <option value="year">Tahunan</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-2">
                <select class="custom-select custom-select-sm form-control form-control-sm" id="types" name="types" onchange=SaleDetailData()>
                    <option value="IN">Masuk</option>
                    <option value="OUT">Keluar</option>
                    <option value="BOTH">Keduanya</option>
                </select>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 stockintable">

        </div>
    </div>



</div>
<script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        SaleDetailData();
    });

    function SaleDetailData() {
        $.ajax({
            type: "post",
            url: "<?= site_url('/stockreport/datatable') ?>",
            data: {
                group: $('#entries').val(),
                types: $('#types').val(),
            },
            dataType: 'json',
            beforeSend: function() {
                $('.stockintable').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.stockintable').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        });
    }
</script>
<?= $this->endSection(); ?>