<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<?php if (allow(1)) : ?>
    <div class="container-fluid">



        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-warning btn-sm" onclick="window.location='<?= site_url('Dashboard/index') ?>'">&laquo; Kembali</button>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nofaktur">Faktur</label>
                            <input type="text" class="form-control form-control-sm" style="color:red;font-weight:bold;" name="nofaktur" id="nofaktur" value="<?= $invoice; ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="namakasir">Kasir</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm" name="namakasir" id="namakasir" value="<?= session()->get('name') ?>" readonly>
                                <input type="hidden" name="nokasir" id="nokasir" value="<?= session()->get('id') ?>">
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Aksi</label>
                            <div class="input-group">
                                <button class="btn btn-danger btn-sm" type="button" id="btnHapusTransaksi">
                                    <i class="fa fa-trash-alt"></i>
                                </button>&nbsp;
                                <button class="btn btn-success" type="button" id="btnSimpanTransaksi">
                                    <i class="fa fa-save"></i>
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kodebarcode">Kode Produk</label>
                            <input type="text" class="form-control form-control-sm" name="kodebarcode" id="kodebarcode" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product">Nama Produk</label>
                            <input type="text" class="form-control form-control-sm" name="product" id="product" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jml">Jumlah</label>
                            <input type="number" class="form-control form-control-sm" name="jumlah" id="jumlah" value="1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jml">Total Bayar</label>
                            <input type="text" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" value="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 dataDetailPenjualan">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="viewmodals" style="display: none;"></div>
    <div class="viewpaymentmodal" style="display: none;"></div>
<?php endif; ?>
<script>
    $(document).ready(function() {
        $('body').addClass("sidebar-collapse");
        dataDetailPenjualan();
        totalPay();

        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                checkcode();
            }
        });
        $('#btnHapusTransaksi').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Batalkan Transaksi...',
                text: "Yakin batalkan transaksi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!',
                cancelmButtonText: 'Tdak',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('sell/cancelTransaction'); ?>",
                        data: {
                            invoice: $('#nofaktur').val(),

                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success == 'berhasil') {
                                window.location.reload();
                            }

                        },
                        error: function(xhr, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        },

                    });
                }
            })
        });
        $('#btnSimpanTransaksi').click(function(e) {
            e.preventDefault();
            payment();
        });
        $('#jumlah').keydown(function(e) {
            if (e.keyCode == 27) {
                e.preventDefault();
                $('#kodebarcode').focus();
            }
            if (e.keyCode == 13) {
                e.preventDefault();
                $('#kodebarcode').focus();
                checkcode();
                $('#jumlah').val(1);
            }
        });
        $(this).keydown(function(e) {
            if (e.keyCode == 27) {
                e.preventDefault();
                $('#kodebarcode').focus();
            }
            if (e.keyCode == 115) {
                e.preventDefault();
                cancelTransac();
            }
            if (e.keyCode == 119) {
                e.preventDefault();
                payment();
            }
        });

    });


    function dataDetailPenjualan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('/sell/dataDetail') ?>",
            data: {
                nofaktur: $('#nofaktur').val(),
            },
            dataType: 'json',
            beforeSend: function() {
                $('.dataDetailPenjualan').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailPenjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        });
    }

    function checkcode() {
        let code = $('#kodebarcode').val();

        if (code.length == 0) {
            $.ajax({
                url: "<?= site_url('/sell/viewDataProduct'); ?>",
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
                url: "<?= site_url('sell/savetoTemp'); ?>",
                data: {
                    barcode: code,
                    productname: $('#product').val(),
                    qty: $('#jumlah').val(),
                    invoice: $('#nofaktur').val(),

                },
                dataType: "json",
                success: function(response) {
                    if (response.totaldata == 'banyak') {
                        $.ajax({
                            url: "<?= site_url('/sell/viewDataProduct'); ?>",
                            dataType: 'json',
                            data: {
                                keyword: code
                            },
                            type: 'POST',
                            success: function(response) {
                                $('.viewmodals').html(response.viewmodal).show();
                                $('#modalproduct').modal('show');
                                $('#kodebarcode').focus();

                            },
                            error: function(xhr, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            },
                        });

                    }
                    if (response.success == 'Berhasil') {
                        dataDetailPenjualan();
                        $('#kodebarcode').focus();
                        blank();
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

            })
        }
    }

    function blank() {
        $('#kodebarcode').val('');
        $('#product').val('');
        $('#kodebarcode').val('');
        $('#kodebarcode').focus();
        totalPay();
    }

    function totalPay() {
        $.ajax({
            url: "<?= site_url('/sell/countTotalPay'); ?>",
            dataType: 'json',
            data: {
                invoice: $('#nofaktur').val()
            },
            type: 'POST',
            success: function(response) {
                if (response.totalPay) {
                    $('#totalbayar').val(response.totalPay);
                }

            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        });

    }

    function payment() {
        let invoice = $('#nofaktur').val();
        $.ajax({
            url: "<?= site_url('/sell/payment'); ?>",
            dataType: 'json',
            data: {
                invoice: invoice,
                nocashier: $('#nokasir').val(),
                date: $('#tanggal').val(),
            },
            type: 'POST',
            success: function(response) {
                if (response.error) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Maaf itemnya belum ada!',
                    });
                }
                if (response.data) {
                    $('.viewpaymentmodal').html(response.data).show();
                    $('#paymentmodal').on('shown.bs.modal', function(event) {
                        $('#paysaldo').focus();
                    })
                    $('#paymentmodal').modal('show');

                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        });

    }

    function cancelTransac() {
        Swal.fire({
            title: 'Batalkan Transaksi...',
            text: "Yakin batalkan transaksi?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!',
            cancelmButtonText: 'Tdak',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('sell/cancelTransaction'); ?>",
                    data: {
                        invoice: $('#nofaktur').val(),

                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success == 'berhasil') {
                            window.location.reload();
                        }

                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    },

                });
            }
        })

    }
</script>

<?= $this->endSection(); ?>