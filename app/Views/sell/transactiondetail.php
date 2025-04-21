<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-sm table-nordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga jual</th>
                    <th>Sub total</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                foreach ($datadetail->getResultArray() as $r) :
                ?>
                    <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= $r['qrcode']; ?></td>
                        <td><?= $r['product']; ?></td>
                        <td><?= $r['qty']; ?></td>
                        <td style="text-align: right;"><?= number_format($r['sellprice'], 0, ",", "."); ?></td>
                        <td style="text-align: right;"><?= number_format($r['subtotal'], 0, ",", "."); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteitem('<?= $r['id'] ?>', '<?= $r['product'] ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<script>
    function deleteitem(id, name) {
        Swal.fire({
            title: 'Hapus item',
            icon: 'warning',
            html: 'Yakin akan menghapus data produk <strong>' + name + '</strong>?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Tidak!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('/sell/deleteitem'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    type: 'POST',
                    success: function(response) {
                        if (response.success == 'berhasil') {
                            dataDetailPenjualan();
                            blank();
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    },
                });
            }
        })
    }
</script>