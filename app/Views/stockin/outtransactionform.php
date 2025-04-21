<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>



<!-- Page Heading -->

<div class="container-fluid">
    <div class="card-header shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-sm btn-warning " onclick="window.location='<?= site_url('dashboard/index'); ?>'">
                    <i class=" fa fa-tables"></i> Kembali
                </button>
            </h6>

        </div>
        <!-- Try -->

        <!-- DataTales  -->
    </div>
    <div class="card-body">
        <?= form_open_multipart('', ['class' => 'formsimpanproduk']) ?>
        <?= csrf_field(); ?>
        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d'); ?>">
            </div>

        </div>
        <div class="form-group row">
            <label for="barcode" class="col-sm-2 col-form-label">Kode Barcode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="barcode" name="barcode" autofocus>
                <input type="hidden" class="form-control" id="productid" name="productid">
                <input type="hidden" class="form-control" id="userid" name="userid" value="<?= session()->get('id'); ?>">
                <div class="errorcode invalid-feedback" style="display: None;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-info" onclick="checkcode()">
                <i class="fas fa-pencil-alt"></i>
            </button>
        </div>
        <div class="form-group row">
            <label for="productname" class="col-sm-2 col-form-label">Nama Produk</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="productname" name="productname" readonly>
                <div class="errorproduct invalid-feedback" style="display: None;">
                </div>

            </div>
        </div>
        <div class="form-group row">
            <label for="purchaseprice" class="col-sm-2 col-form-label">Harga Beli (Rp)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="purchaseprice" name="purchaseprice" style="text-align: right" readonly>
            </div>
        </div>
        <div class="form-group row">

            <label for="productstock" class="col-sm-2 col-form-label">Stok Tersedia</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="productstock" name="productstock" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="productqty" class="col-sm-2 col-form-label">Quantity</label>
            <div class="col-sm-2">
                <input type="number" class="form-control form-control-sm" name="productqty" id="productqty" value="1">
            </div>
            <div class="errorqty invalid-feedback" style="display: None;">
            </div>
            <label for="totalprice" class="col-sm-2 col-form-label">Total harga</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="totalprice" name="totalprice" readonly>
            </div>

        </div>
        <div class="form-group row">

        </div>
        <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="description" name="description">
            </div>
        </div>
        <div class="form-group row">
            <label for="suppliername" class="col-sm-2 col-form-label">Nama Supplier</label>
            <div class="col-sm-4">
                <select class="form-control" id="suppliername" name="suppliername">
                </select>

            </div>
        </div>



        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success saveButton">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <?= form_close(); ?>
    <div class="viewmodals" style="display: none;"></div>

</div>

<script>
    $('#barcode').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            checkcode();
        }
    });
    $(document).ready(function() {
        showSupplier();
        $('#productqty').keyup(function(e) {
            calculatetotal();
        });


    });
    $(document).ready(function($) {
        $('#productqty').bind('keyup mouseup', function() {
            calculatetotal();
        });
    });

    $('.saveButton').click(function(e) {
        e.preventDefault();


        let form = $('.formsimpanproduk')[0];
        let data = new FormData(form);
        $.ajax({
            type: "post",
            url: "<?= site_url('StockTransaction/reducetransaction'); ?>",
            data: data,
            dataType: "json",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('.tombolsimpanproduk').html('<i class="fa fa-spin fa-spinner"></i>');
                $('.tombolsimpanproduk').prop('disabled', true);
            },
            complete: function() {
                $('.tombolsimpanproduk').html('Simpan');
                $('.tombolsimpanproduk').prop('disabled', false);
            },
            success: function(response) {
                if (response.data) {
                    alert(response.data);
                }
                if (response.error) {
                    let dataError = response.error;
                    if (dataError.errorcode) {
                        $('.errorcode').html(dataError.errorcode).show();

                        $('#barcode').addClass('is-invalid');
                    } else {
                        $('.errorcode').fadeOut();
                        $('#barcode').removeClass('is-invalid');
                        $('#barcode').addClass('is-valid');
                    }
                    if (dataError.errorproduct) {
                        $('.errorproduct').html(dataError.errorproduct).show();

                        $('#productname').addClass('is-invalid');
                    } else {
                        $('.errorproduct').fadeOut();
                        $('#productname').removeClass('is-invalid');
                        $('#productname').addClass('is-valid');
                    }
                    if (dataError.errorqty) {
                        $('.errorqty').html(dataError.errorqty).show();

                        $('#productqty').addClass('is-invalid');
                    } else {
                        $('.errorqty').fadeOut();
                        $('#productqty').removeClass('is-invalid');
                        $('#productqty').addClass('is-valid');
                    }
                    if (dataError.errordate) {
                        $('.errordate').html(dataError.errordate).show();

                        $('#date').addClass('is-invalid');
                    } else {
                        $('.errordate').fadeOut();
                        $('#date').removeClass('is-invalid');
                        $('#date').addClass('is-valid');
                    }
                }
                if (response.notfound) {
                    alert(response.notfound);
                    window.location.reload();
                }
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: response.sukses,
                        // showConfirmButton: false,
                        timer: 1500,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            Swal.fire('Saved', '', 'success')
                            window.location.reload();
                        }
                    });
                }

            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

    });

    function showSupplier() {
        $.ajax({
            url: "<?= site_url('/StockTransaction/getSupplier'); ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#suppliername').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }

        });
    }

    function calculatetotal() {
        let qty = $('#productqty').val();
        let price = ($('#purchaseprice').val() == "") ? 0 : $('#purchaseprice').val();

        result = parseFloat(price) * parseFloat(qty);

        $('#totalprice').val(result);
    }

    function checkcode() {
        let code = $('#barcode').val();
        if (code.length == 0) {
            $.ajax({
                url: "<?= site_url('/StockTransaction/viewDataProduct'); ?>",
                dataType: 'json',
                success: function(response) {
                    $('.viewmodals').html(response.viewmodal).show();
                    $('#modalproduct').modal('show');
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            });
        } else {
            $.ajax({
                type: "POST",
                url: "<?= site_url('StockTransaction/finddata'); ?>",
                data: {
                    barcode: code,
                },
                dataType: "json",
                success: function(response) {
                    if (response.totaldata == 'banyak') {
                        $.ajax({
                            url: "<?= site_url('/StockTransaction/viewDataProduct'); ?>",
                            dataType: 'json',
                            data: {
                                keyword: code,
                            },
                            type: 'post',
                            success: function(response) {
                                $('.viewmodals').html(response.viewmodal).show();
                                $('#modalproduct').modal('show');
                            },
                            error: function(xhr, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            },
                        });

                    }
                    if (response.data == "satu") {
                        $("#productname").val(response.name);
                        $("#productstock").val(response.stock);
                        $("#purchaseprice").val(response.price);
                        $("#productid").val(response.price);
                        calculatetotal();
                    }
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: "Error...",
                            html: response.error,
                        });
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            });
        }

    }
</script>


<?= $this->endSection(); ?>