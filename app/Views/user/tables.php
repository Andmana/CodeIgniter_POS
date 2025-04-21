<style>
    #cssTable td {
        text-align: center;
        vertical-align: middle;
    }
</style>
<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <div class="card-header shadow mb-4">
        <div class="card-header row py-3">
            <div class="col-sm-12 col-md-9">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-sm btn-primary " onclick="window.location='<?= site_url('/user/add'); ?>'">
                        <i class=" fa fa-plus"></i> Tambah data
                    </button>
                </h6>
            </div>
            <div class="col-sm-12 col-md-1">
                <label>Entries: </label>
            </div>
            <div class="col-sm-12 col-md-2 d-flex flex-row-reverse">
                <select class="custom-select custom-select-sm form-control form-control-sm" id="entries" name="entries">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= form_open('/user/index') ?>
                <?= csrf_field(); ?>
                <div class="card-body input-group mb-3">
                    <input type="text" class=" form-control bg-light border border-primary border-3 small" placeholder="Cari nama Pengguna / username Produk" aria-label="Search" aria-describedby="basic-addon2" name="finduser" value="" autofocus>
                    <div class="input-group-append">
                        <button id="tambah" class="btn btn-primary" type="submit" name="userfindbutton">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>


                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Hp</th>
                            <th scope="col">Email</th>
                            <th scope="col">Level</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1 + (($numpages - 1) * 10);
                        foreach ($users as $u) :
                        ?>
                            <tr>
                                <td><?= $index++; ?></td>
                                <td style="text-align: center; vertical-align: middle;"><img src="<?= base_url($u['image']); ?>" alt="avatar" class="img-fluid" style="width: 100px;"></td>
                                <td><?= $u['name']; ?></td>
                                <td><?= $u['address']; ?></td>
                                <td><?= $u['phone']; ?></td>
                                <td><?= $u['email']; ?></td>
                                <td><?= $u['name_level']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleuser('<?= $u['id']; ?>', '<?= $u['name']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" onclick="window.location='/user/edit/<?= $u['id']; ?>'">
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
                    <?= $pager->links('users', 'paging_data'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleuser(id, name) {
        Swal.fire({
            html: 'Hapus Pengguna <strong> ' + name + ' </strong>?',
            text: "Hapus data pengguna",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: "Tidak"
        }).then((result) => {

            $.ajax({
                type: "post",
                url: '<?= site_url('user/delete') ?>',
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