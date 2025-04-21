<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

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
        </div>
        <div class="row">
            <div class="col-md-12 saledatatable">

            </div>
        </div>


    </div>

    <script>
        $(document).ready(function() {
            SaleDetailData();
        });

        function SaleDetailData() {
            $.ajax({
                type: "post",
                url: "<?= site_url('/SaleReport/datatable') ?>",
                data: {
                    group: $('#entries').val(),
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.saledatatable').html('<i class="fa fa-spin fa-spinner"></i>')
                },
                success: function(response) {
                    if (response.data) {
                        $('.saledatatable').html(response.data);
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            });
        }
    </script>
    <?= $this->endSection(); ?>