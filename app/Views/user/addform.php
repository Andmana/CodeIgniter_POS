<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>


<!-- Page Heading -->

<div class="container-fluid">



    <div class="card-header shadow mb-4">
        <div class="card-header row py-3">
            <div class="col-sm-12 col-md-9">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-sm btn-warning " onclick="window.location='<?= site_url('user/index'); ?>'">
                        <i class=" fa fa-tables"></i> Kembali
                    </button>
                </h6>
            </div>
            <div class="col-sm-12 col-md-1">

            </div>

        </div>




        <div class="card-body">
            <?= form_open_multipart('', ['class' => 'formadduser']) ?>
            <?= csrf_field(); ?>

            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="username" name="username">
                    <div class="usernameerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="nameuser" class="col-sm-4 col-form-label">Nama Pengguna</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="nameuser" name="nameuser">
                    <div class="nameerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addressuser" class="col-sm-4 col-form-label">Alamat</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="addressuser" name="addressuser">
                    <div class="addresserror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="phoneuser" class="col-sm-4 col-form-label">No. Handphone</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="phoneuser" name="phoneuser">
                    <div class="phoneerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="emailuser" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" id="emailuser" name="emailuser">
                    <div class="emailerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="leveluser" class="col-sm-4 col-form-label">Level Pengguna</label>
                <div class="col-sm-4">
                    <select class="form-control" id="leveluser" name="leveluser">
                        <?php
                        foreach ($list_level as $level) :
                            echo "<option value=\"$level[id_level]\">$level[name_level]</option> ";
                        endforeach;
                        ?>
                    </select>

                </div>
            </div>


            <div class="form-group row">
                <label for="imageuser" class="col-sm-4 col-form-label">Upload Gamber (<i>Jika ingin diganti</i>)</label>
                <div class="col-sm-4">
                    <input type="file" class="form-control" name="imageuser" id="imageuser">
                    <div class="imageerror invalid-feedback" style="display: None;">
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
            <?= form_close(); ?>
        </div>




    </div>
    <script>
        $(document).ready(function() {});

        $('.saveButton').click(function(e) {
            e.preventDefault();


            let form = $('.formadduser')[0];
            let data = new FormData(form);
            $.ajax({
                type: "post",
                url: "<?= site_url('user/adduser'); ?>",
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
                        if (dataError.nameerror) {
                            $('.nameerror').html(dataError.nameerror).show();

                            $('#nameuser').addClass('is-invalid');
                        } else {
                            $('.nameerror').fadeOut();
                            $('#nameuser').removeClass('is-invalid');
                            $('#nameuser').addClass('is-valid');
                        }

                        if (dataError.usernameerror) {
                            $('.usernameerror').html(dataError.usernameerror).show();

                            $('#username').addClass('is-invalid');
                        } else {
                            $('.usernameerror').fadeOut();
                            $('#username').removeClass('is-invalid');
                            $('#username').addClass('is-valid');
                        }

                        if (dataError.emailerror) {
                            $('.emailerror').html(dataError.emailerror).show();

                            $('#emailuser').addClass('is-invalid');
                        } else {
                            $('.emailerror').fadeOut();
                            $('#emailuser').removeClass('is-invalid');
                            $('#emailuser').addClass('is-valid');
                        }
                        if (dataError.phoneerror) {
                            $('.phoneerror').html(dataError.phoneerror).show();

                            $('#phoneuser').addClass('is-invalid');
                        } else {
                            $('.phoneerror').fadeOut();
                            $('#phoneuser').removeClass('is-invalid');
                            $('#phoneuser').addClass('is-valid');
                        }
                        if (dataError.addresserror) {
                            $('.addresserror').html(dataError.addresserror).show();

                            $('#addressuser').addClass('is-invalid');
                        } else {
                            $('.addresserror').fadeOut();
                            $('#addressuser').removeClass('is-invalid');
                            $('#addressuser').addClass('is-valid');
                        }
                        if (dataError.imageerror) {
                            $('.imageerror').html(dataError.imageerror).show();

                            $('#iamge').addClass('is-invalid');
                        } else {
                            $('.imageerror').fadeOut();
                            $('#iamge').removeClass('is-invalid');
                            $('#iamge').addClass('is-valid');
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