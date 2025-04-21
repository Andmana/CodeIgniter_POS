<?= $this->extend('auth/templates/index'); ?>

<?= $this->section('content'); ?>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Buat Akun!</h1>
                                </div>
                                <?= $validate->listErrors() ?>
                                <form action="/auth/register" class="user" method="post" id="myform">

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="fullName" name="fullName" placeholder="Nama Lengkap">
                                    </div>
                                    <div class="form-group">
                                        <input type="username" class="form-control form-control-user" id="username" name="username" placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Alamat Email">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Kata Sandi">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user" id="repeatPassword" name="repeatPassword" placeholder="Ulangi Kata Sandi">
                                        </div>
                                    </div>
                                    <button type="submit" class=" btn btn-primary btn-user btn-block">
                                        Daftar Akun
                                    </button>
                                    <hr>

                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="forgot-password.html">Lupa Kata Sandi?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="/">Sudah punya akun? Masuk!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?= $this->endSection(); ?>