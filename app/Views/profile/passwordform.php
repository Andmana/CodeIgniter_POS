<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>


<!-- Page Heading -->

<div class="container-fluid">



    <div class="card-header shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-sm btn-warning " onclick="window.location='<?= site_url('profile/index'); ?>'">
                    <i class=" fa fa-tables"></i> Kembali
                </button>
            </h6>

        </div>
    </div>
    <div class="card-body">
        <?= form_open_multipart('', ['class' => 'formsimpanproduk']) ?>
        <?= csrf_field(); ?>

        <div class="form-group row">
            <div class="col-sm-8">
                <input type="hidden" class="form-control" id="iduser" name="iduser" value="<?= $id; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="newpassword" class="col-sm-4 col-form-label">Kata Sandi Baru</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="newpassword" name="newpassword">
                <div class="newpassworderror invalid-feedback" style="display: None;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="repeatpassword" class="col-sm-4 col-form-label">Ketik ulang sandi</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="repeatpassword" name="repeatpassword">
                <div class="repeatpassworderror invalid-feedback" style="display: None;">
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


        let form = $('.formsimpanproduk')[0];
        let data = new FormData(form);
        $.ajax({
            type: "post",
            url: "<?= site_url('profile/changepassword'); ?>",
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
                $('.tombolsimpanproduk').html('Update');
                $('.tombolsimpanproduk').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    let dataError = response.error;
                    if (dataError.newpassworderror) {
                        $('.newpassworderror').html(dataError.newpassworderror).show();

                        $('#newpassword').addClass('is-invalid');
                    } else {
                        $('.newpassworderror').fadeOut();
                        $('#newpassword').removeClass('is-invalid');
                        $('#newpassword').addClass('is-valid');
                    }

                    if (dataError.repeatpassworderror) {
                        $('.repeatpassworderror').html(dataError.repeatpassworderror).show();

                        $('#repeatpassword').addClass('is-invalid');
                    } else {
                        $('.repeatpassworderror').fadeOut();
                        $('#repeatpassword').removeClass('is-invalid');
                        $('#repeatpassword').addClass('is-valid');
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