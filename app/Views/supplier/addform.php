a<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>


<!-- Page Heading -->

<div class="container-fluid">



    <div class="card-header shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-sm btn-warning " onclick="window.location='<?= site_url('supplier/index'); ?>'">
                    <i class=" fa fa-tables"></i> Kembali
                </button>
            </h6>

        </div>
    </div>

    <div class="card-body">
        <?= form_open_multipart('', ['class' => 'formaddsupplier']) ?>
        <?= csrf_field(); ?>

        <div class="form-group row">
            <label for="suppliername" class="col-sm-4 col-form-label">Nama Supplier</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="suppliername" name="suppliername">
                <div class="suppliernameerror invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="supplierphone" class="col-sm-4 col-form-label">No. Kontak</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="supplierphone" name="supplierphone">
                <div class="supplierphoneerror invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="supplieraddress" class="col-sm-4 col-form-label">Alamat</label>
            <div class="col-sm-8">
                <textarea type="text" class="form-control" id="supplieraddress" name="supplieraddress"> </textarea>
                <div class="supplieraddresserror invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>

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

    $('.saveButton').click(function(e) {
        e.preventDefault();


        let form = $('.formaddsupplier')[0];
        let data = new FormData(form);
        $.ajax({
            type: "post",
            url: "<?= site_url('supplier/adddata'); ?>",
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
                    if (dataError.suppliernameerror) {
                        $('.suppliernameerror').html(dataError.suppliernameerror).show();

                        $('#suppliername').addClass('is-invalid');
                    } else {
                        $('.suppliernameerror').fadeOut();
                        $('#suppliername').removeClass('is-invalid');
                        $('#suppliername').addClass('is-valid');
                    }

                    if (dataError.supplierphoneerror) {
                        $('.supplierphoneerror').html(dataError.supplierphoneerror).show();

                        $('#supplierphone').addClass('is-invalid');
                    } else {
                        $('.supplierphoneerror').fadeOut();
                        $('#supplierphone').removeClass('is-invalid');
                        $('#supplierphone').addClass('is-valid');
                    }
                    if (dataError.supplieraddresserror) {
                        $('.supplieraddresserror').html(dataError.supplieraddresserror).show();

                        $('#supplieraddress').addClass('is-invalid');
                    } else {
                        $('.supplieraddresserror').fadeOut();
                        $('#supplieraddress').removeClass('is-invalid');
                        $('#supplieraddress').addClass('is-valid');
                    }

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