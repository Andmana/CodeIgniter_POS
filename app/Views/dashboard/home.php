<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <div class="card-body">



        <!-- Page Heading -->

        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Transaksi (Tahunan)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $yearly['totalpay']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total transaksi (Bulanan)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $monthly['totalpay']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Transaski (harian)
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $daily['totalpay']; ?></div>
                                    </div>
                                    <div class="col">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-3000"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Transaksi berhasil (harian)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $daily['invoice']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Progres kamu hari ini</h6>
            </div>
            <div class="card-body">
                <section class="section about-section gray-bg" id="about">
                    <div class="container">
                        <div class="counter">
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="500" data-speed="500"><?= $usersale['invoice']; ?></h6>
                                        <p class="m-0px font-w-600">Transaksi</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="150" data-speed="150"><?= $usersale['totalpay']; ?></h6>
                                        <p class="m-0px font-w-600">Jumlah pemasukan</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="850" data-speed="850"><?= $usersale['qtys']; ?></h6>
                                        <p class="m-0px font-w-600">Item terjual</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="850" data-speed="850"><?= $usersale['items']; ?></h6>
                                        <p class="m-0px font-w-600">Jenis Item terjual</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection(); ?>