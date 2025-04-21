<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>


<!-- Page Heading -->

<div class="container-fluid">



    <div class="card-header shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-sm btn-warning " onclick="window.location='<?= site_url('product/index'); ?>'">
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
            <label for="qrcode" class="col-sm-4 col-form-label">Kode Barcode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="qrcode" name="qrcode" value="<?= $item_qr; ?>">
                <div class="errorQRcode invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="productname" class="col-sm-4 col-form-label">Nama Produk</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="productname" name="productname" value="<?= $item_name; ?>">
                <div class="errorName invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="productimage" class="col-sm-4 col-form-label">Gamber (<i>Jika Ada</i>)</label>
            <div class="col-sm-4">
                <img src="<?= base_url($item_image); ?>" alt="" style="width: 100%;" class="img-thumnail img-fluid ">
            </div>
        </div>
        <div class="form-group row">
            <label for="productstock" class="col-sm-4 col-form-label">Stok Tersedia</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="productstock" name="productstock" value="<?= $item_stock; ?>">
                <div class="errorStock invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="suppliername" class="col-sm-4 col-form-label">Nama Supplier</label>
            <div class="col-sm-4">
                <select class="form-control" id="suppliername" name="suppliername">
                    <?php
                    foreach ($list_supplier as $supplier) :
                        if ($supplier['id_supplier'] == $item_supplier) :
                            echo " <option value=\"$supplier[id_supplier]\" selected>$supplier[name_supplier]</option> ";
                        else :
                            echo "<option value=\"$supplier[id_supplier]\">$supplier[name_supplier]</option> ";
                        endif;
                    endforeach;
                    ?>
                    <option value=""></option>
                </select>

            </div>
        </div>
        <div class="form-group row">
            <label for="sellprice" class="col-sm-4 col-form-label">Harga Jual (Rp)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="sellprice" name="sellprice" style="text-align: right" value="<?= $item_sell; ?>">
                <div class="errorSellPrice invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="purchaseprice" class="col-sm-4 col-form-label">Harga Beli (Rp)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="purchaseprice" name="purchaseprice" style="text-align: right" value="<?= $item_buy; ?>">
                <div class="errorBuyPrice invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>


        <div class="form-group row">
            <label for="productimage" class="col-sm-4 col-form-label">Upload Gamber (<i>Jika ingin diganti</i>)</label>
            <div class="col-sm-4">
                <input type="file" class="form-control" name="productimage" id="productimage">
                <div class="errorImage invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <input type="hidden" id="productid" name="productid" value="<?= $item_id; ?>">

        <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label"></label>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success saveButton">
                    Simpan
                </button>
            </div>
        </div>
    </div>


    <?= form_close(); ?>


</div>

<script>
    $(document).ready(function() {

    });
    $(document).ready(function($) {
        $("#purchaseprice").autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2',
        });
        $("#sellprice").autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0',
        });
        $("#productstock").autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0',
        });
    });

    $('.saveButton').click(function(e) {
        e.preventDefault();


        let form = $('.formsimpanproduk')[0];
        let data = new FormData(form);
        $.ajax({
            type: "post",
            url: "<?= site_url('Product/updatedata'); ?>",
            data: data,
            dataType: "json",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('.saveButton').html('<i class="fa fa-spin fa-spinner"></i>');
                $('.saveButton').prop('disabled', true);
            },
            complete: function() {
                $('.saveButton').html('Update');
                $('.saveButton').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    let dataError = response.error;
                    if (dataError.errorQRcode) {
                        $('.errorQRcode').html(dataError.errorQRcode).show();

                        $('#qrcode').addClass('is-invalid');
                    } else {
                        $('.errorQRcode').fadeOut();
                        $('#qrcode').removeClass('is-invalid');
                        $('#qrcode').addClass('is-valid');
                    }

                    if (dataError.errorName) {
                        $('.errorName').html(dataError.errorName).show();

                        $('#productname').addClass('is-invalid');
                    } else {
                        $('.errorName').fadeOut();
                        $('#productname').removeClass('is-invalid');
                        $('#productname').addClass('is-valid');
                    }

                    if (dataError.errorStock) {
                        $('.errorStock').html(dataError.errorStock).show();

                        $('#productstock').addClass('is-invalid');
                    } else {
                        $('.errorStock').fadeOut();
                        $('#productstock').removeClass('is-invalid');
                        $('#productstock').addClass('is-valid');
                    }
                    if (dataError.errorSellPrice) {
                        $('.errorSellPrice').html(dataError.errorSellPrice).show();

                        $('#sellprice').addClass('is-invalid');
                    } else {
                        $('.errorSellPrice').fadeOut();
                        $('#sellprice').removeClass('is-invalid');
                        $('#sellprice').addClass('is-valid');
                    }
                    if (dataError.errorBuyPrice) {
                        $('.errorBuyPrice').html(dataError.errorBuyPrice).show();

                        $('#purchaseprice').addClass('is-invalid');
                    } else {
                        $('.errorBuyPrice').fadeOut();
                        $('#purchaseprice').removeClass('is-invalid');
                        $('#purchaseprice').addClass('is-valid');
                    }
                    if (dataError.errorImage) {
                        $('.errorImage').html(dataError.errorImage).show();

                        $('#productimage').addClass('is-invalid');
                    } else {
                        $('.errorImage').fadeOut();
                        $('#productimage').removeClass('is-invalid');
                        $('#productimage').addClass('is-valid');
                    }
                    // 'errorBuyPrice' => $validation->getError('sellprice'),
                    //     'errorSellPrice' => $validation->getError('purchaseprice'),
                    //     'errorImage' => $validation->getError('suppliername'),
                    //     'errorSupplier' => $validation->getError('suppliername')
                } else {
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
        })

    });
</script>


<?= $this->endSection(); ?>