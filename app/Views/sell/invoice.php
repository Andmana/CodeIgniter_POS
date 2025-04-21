<style type="text/css" media="all">
    .body-main {
        background: #ffffff;
        border-bottom: 15px solid #1E1F23;
        border-top: 15px solid #1E1F23;
        margin-top: 30px;
        margin-bottom: 30px;
        padding: 40px 30px !important;
        position: relative;
        box-shadow: 0 1px 21px #808080;
        font-size: 10px;
    }

    .main thead {
        background: #1E1F23;
        color: #fff;
    }

    .img {
        height: 100px;
    }

    h1 {
        text-align: center;
    }

    @media print {
        .tombol {
            display: none;
        }
    }
</style>

<link href="<?= base_url(); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<script src="<?= base_url(); ?>/vendor/jquery/jquery.min.js"></script>
<link href="<?= base_url(); ?>/css/bootstrap.min.css" rel="stylesheet">



<div class="container">

    <div class="container" id="print-area">

        <div class="row">
            <div class="col-md-6 col-md-offset-3 body-main">
                <div class="col-md-12 ">
                    <div class="row tombol ">
                        <div class="col-md-12 text-right ">
                            <button type="button" href="#" class="btn btn-info btn-circle" onclick="window.print()">
                                <i class="fas fa-info-circle"></i>
                                Cetak PDF
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img class="img" alt="Invoce Template" src="<?= base_url(); ?>/img/invoice_cart2.png" />
                        </div>
                        <div class="col-md-8 text-right">
                            <h4 style="color: #F81D2D;"><strong>MiniMarket Cahaya</strong></h4>
                            <p>Sragi, Kab. Pekalongan</p>
                            <p>0814-2528-3613</p>
                            <p>19K10063@student.unika.ac.id</p>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2>INVOICE</h2>
                            <h5><?= $nota['invoice_sell']; ?></h5>

                        </div>
                    </div>
                    <br />
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <h5>Nama produk</h5>
                                    </th>
                                    <th>
                                        <h5>Qty</h5>
                                    </th>
                                    <th>
                                        <h5>Harga</h5>
                                    </th>
                                    <th>
                                        <h5>Sub total</h5>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $index = 1;
                                foreach ($products as $row) :
                                ?>
                                    <tr>
                                        <td class="col-md-6"><?= $row['name_product']; ?></td>
                                        <td class="col-md-3"> <?= $row['qtyproduct_Dsell']; ?></td>
                                        <td class="col-md-1"><?= $row['buyPrice_Dsell']; ?></td>
                                        <td class="col-md-2"><?= $row['subtotal_Dsell']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right" colspan="3">
                                        <p>
                                            <strong>Jumlah total:</strong>
                                        </p>
                                        <p>
                                            <strong>Diskon: </strong>
                                        </p>
                                        <p>
                                            <strong>Potongan: </strong>
                                        </p>
                                        <p>
                                            <strong>Jumlah bayar: </strong>
                                        </p>
                                        <p>
                                            <strong>Jumlah uang: </strong>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <strong><?= $nota['totalprice_sell']; ?> </strong>
                                        </p>
                                        <p>
                                            <strong><?= $nota['percentdisc_sell']; ?>%</strong>
                                        </p>
                                        <p>
                                            <strong><?= $nota['nominaldisc_sell']; ?> </strong>
                                        </p>
                                        <p>
                                            <strong><?= $nota['totalpay_sell']; ?></strong>
                                        </p>
                                        <p>
                                            <strong><?= $nota['pay_sell']; ?></strong>
                                        </p>
                                    </td>
                                </tr>
                                <tr style="color: #F81D2D;">
                                    <td class="text-right" colspan="3">
                                        <h4><strong>Kembalian:</strong></h4>
                                    </td>
                                    <td class="text-left">
                                        <h4><strong><?= $nota['change_sell']; ?></strong></h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="col-md-12">
                            <?php
                            $originalDate = $nota['date_sell'];
                            $newDate = date("j F Y", strtotime($originalDate));
                            echo "<p><b>Date :</b> " . $newDate . "</p>";
                            ?>
                            <br />
                            <p><b>Signature</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>