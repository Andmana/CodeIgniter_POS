<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>


<!-- Page Heading -->

<div class="container-fluid">


    <div class="card-header shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-sm btn-primary " onclick="window.location='<?= site_url('/product/add'); ?>'">
                    <i class=" fa fa-plus"></i> Tambah data
                </button>
            </h6>

        </div>

        <!-- DataTales  -->
        <div class="card-body">
            <div class="table-responsive">
                <?= form_open('/Product/index') ?>
                <?= csrf_field(); ?>
                <div class="card-body input-group mb-3">
                    <input type="text" class=" form-control bg-light border border-primary border-3 small" placeholder="Cari Kode Barcode / Nama Produk" aria-label="Search" aria-describedby="basic-addon2" name="findproduct" value="" autofocus>
                    <div class="input-group-append">
                        <button id="tambah" class="btn btn-primary" type="submit" name="findbuttonproduct">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>

                <table class="table table-sm table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Harga Jual</th>
                            <th>Harga Beli</th>
                            <th>Supplier</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $index = 1 + (($numPages - 1) * 10);
                        foreach ($dataproduct as $row) :
                        ?>
                            <tr>
                                <td><?= $index++; ?></td>
                                <td style="text-align: center; vertical-align: middle;"><img src="<?= base_url($row['image_product']); ?>" alt="Image Product" class="img-fluid" style="width: 100px;"></td>
                                <td><?= $row['qr_product']; ?></td>
                                <td><?= $row['name_product']; ?></td>
                                <td style="text-align: right;"><?= number_format($row['stock_product'], 0, ",", "."); ?></td>
                                <td style="text-align: right;"><?= number_format($row['sellPrice_product'], 2, ",", "."); ?></td>
                                <td style="text-align: right;"><?= number_format($row['purchasePrice_product'], 2, ",", "."); ?></td>
                                <td><?= $row['name_supplier']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="dele('<?= $row['name_product']; ?>', '<?= $row['id_product']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" onclick="window.location='/product/edit/<?= $row['id_product']; ?>'">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
                <div class="float-center">
                </div>
                <div class="d-flex justify-content-center">
                    <div>
                        <?= $pager->links('product', 'paging_data'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<script>
    function dele(name, id) {
        Swal.fire({
            html: 'Hapus produk <strong> ' + name + ' </strong>?',
            text: "Hapus Produk",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: "Tidak"
        }).then((result) => {
            $.ajax({
                type: "post",
                url: '<?= site_url('Product/delete') ?>',
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
        })
    }
</script>


<?= $this->endSection(); ?>