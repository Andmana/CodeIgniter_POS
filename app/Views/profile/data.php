<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <section style="background-color: #eee;">
        <div class="container py-5">


            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="<?= base_url($image); ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                            <h5 class="my-3"><?= $username; ?></h5>
                            <p class="text-muted mb-2"><?= $level; ?></p>
                            <p class="text-muted mb-3"><?= $name; ?></p>
                            <div class="d-flex justify-content-center mb-1">
                                <button type="button" class="btn btn-primary" onclick="window.location='/profile/edit/<?= session()->get('id') ?>'">Edit profile</button>
                            </div>
                            <div class="d-flex justify-content-center mb-2">
                                <button type="button" class="btn btn-outline-primary ms-1" onclick="window.location='/profile/editpassword/<?= session()->get('id') ?>'">Ubah Password</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Nama Lengkap</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"> <?= $name; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Username</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?= $username; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">No. Ponsel</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?= $phone; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?= $email; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Alamat</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?= $address; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection(); ?>