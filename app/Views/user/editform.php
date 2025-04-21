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
            <div class="col-sm-12 col-md-2 d-flex flex-row-reverse">
                <button type="buttoon" class="btn btn-primary" onclick="resetpass('<?= $id ?>', '<?= $name; ?>')">
                    Reset Password
                </button>
            </div>

        </div>




        <div class="card-body">
            <?= form_open_multipart('', ['class' => 'formsimpanproduk']) ?>
            <?= csrf_field(); ?>

            <div class="form-group row">
                <label for="iduser" class="col-sm-4 col-form-label">ID Pengguna</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="iduser" name="iduser" value="<?= $id; ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="username" name="username" value="<?= $username; ?>">
                    <div class="usernameerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="productimage" class="col-sm-4 col-form-label">Gamber (<i>Jika Ada</i>)</label>
                <div class="col-sm-4">
                    <img src="<?= base_url($image); ?>" alt="" style="width: 200px;" class="img-thumnail img-fluid ">
                </div>
            </div>
            <div class="form-group row">
                <label for="nameuser" class="col-sm-4 col-form-label">Nama Pengguna</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="nameuser" name="nameuser" value="<?= $name; ?>">
                    <div class="nameerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addressuser" class="col-sm-4 col-form-label">Alamat</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="addressuser" name="addressuser" value="<?= $address; ?>">
                    <div class="addresserror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="phoneuser" class="col-sm-4 col-form-label">No. Handphone</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="phoneuser" name="phoneuser" value="<?= $phone; ?>">
                    <div class="phoneerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="emailuser" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" id="emailuser" name="emailuser" value="<?= $email; ?>">
                    <div class="emailerror invalid-feedback" style="display: None;">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="leveluser" class="col-sm-4 col-form-label">Nama Supplier</label>
                <div class="col-sm-4">
                    <select class="form-control" id="leveluser" name="leveluser">
                        <?php
                        foreach ($list_level as $levels) :
                            if ($levels['id_level'] == $level) :
                                echo " <option value=\"$levels[id_level]\" selected>$levels[name_level]</option> ";
                            else :
                                echo "<option value=\"$levels[id_level]\">$levels[name_level]</option> ";
                            endif;
                        endforeach;
                        ?>
                        <option value=""></option>
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
        function resetpass(id, qr) {
            Swal.fire({
                html: 'Reset kata sandi <strong> ' + name + ' </strong>?',
                text: "Reset Kata Sandi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: "Batalkan!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: '<?= site_url('user/resetpassword') ?>',
                        data: {
                            id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.success,
                                }).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                })
                            }

                        },
                        error: function(xhr, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }


                    })
                }
            })
        }
    </script>
    <script>
        $(document).ready(function() {});

        $('.saveButton').click(function(e) {
            e.preventDefault();


            let form = $('.formsimpanproduk')[0];
            let data = new FormData(form);
            $.ajax({
                type: "post",
                url: "<?= site_url('user/updatedata'); ?>",
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
                        if (dataError.levelerror) {
                            $('.levelerror').html(dataError.levelerror).show();

                            $('#leveluser').addClass('is-invalid');
                        } else {
                            $('.levelerror').fadeOut();
                            $('#leveluser').removeClass('is-invalid');
                            $('#leveluser').addClass('is-valid');
                        }
                        if (dataError.imageerror) {
                            $('.imageerror').html(dataError.imageerror).show();

                            $('#iamge').addClass('is-invalid');
                        } else {
                            $('.imageerror').fadeOut();
                            $('#iamge').removeClass('is-invalid');
                            $('#iamge').addClass('is-valid');
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