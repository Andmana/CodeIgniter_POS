<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <div class="card-header shadow mb-4">
        <div class="card-header row py-3">
            <div class="col-sm-12 col-md-9">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-sm btn-primary " onclick="window.location='<?= site_url('supplier/add'); ?>'">
                        <i class=" fa fa-plus"></i> Tambah data
                    </button>
                </h6>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= form_open('/supplier/index') ?>
                <?= csrf_field(); ?>
                <div class="card-body input-group mb-3">
                    <input type="text" class=" form-control bg-light border border-primary border-3 small" placeholder="Cari Supplier" aria-label="Search" aria-describedby="basic-addon2" name="findsupplier" value="" autofocus>
                    <div class="input-group-append">
                        <button id="tambah" class="btn btn-primary" type="submit" name="supplierfindbutton">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">


                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">No Hp</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1 + (($numpages - 1) * 10);
                        foreach ($supplierdata as $supplier) :
                        ?>
                            <tr>
                                <td><?= $index++; ?></td>
                                <td><?= $supplier['name_supplier']; ?></td>
                                <td><?= $supplier['phone_supplier']; ?></td>
                                <td><?= $supplier['address_supplier']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="delesupp('<?= $supplier['id_supplier']; ?>', '<?= $supplier['name_supplier']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" onclick="window.location='/supplier/edit/<?= $supplier['id_supplier']; ?>'">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                <div>
                    <?= $pager->links('supplier', 'paging_data'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function delesupp(id, name) {
        Swal.fire({
            html: 'Hapus Supplier <strong> ' + name + ' </strong>?',
            text: "Hapus data Supplier",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: "Tidak"
        }).then((result) => {
            $.ajax({
                type: "post",
                url: '<?= site_url('supplier/delete') ?>',
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
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: response.success,
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