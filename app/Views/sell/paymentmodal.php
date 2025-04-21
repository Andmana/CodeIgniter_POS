<?php

use CodeIgniter\HTTP\Response;
?>
<script src="<?= base_url(); ?>/vendor/autoNumeric/autoNumeric.js"></script>
<?= form_open('sell/savePayment', ['class' => 'formpayment']) ?>
<div class="modal fade" id="paymentmodal" tabindex="-1" aria-labelledby="paymentmodalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentmodalLabel">Struktur Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="invoice" value="<?= $invoice ?>">
                <input type="hidden" name="idcashier" value="<?= $idcashier ?>">
                <input type="hidden" name="totalprice" id="totalprice" value="<?= $totalpay; ?>">

                <div class="row">
                    <div class="col">
                        <div class="form-grouo">
                            <label for="">Discount(%)</label>
                            <input type="text" name="percentdisc" id="percentdisc" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-grouo">
                            <label for="">Diskon uang(Rp)</label>
                            <input type="text" name="nominaldisc" id="nominaldisc" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Pembayaran</label>
                    <input type="text" name="totalnet" id="totalnet" class=" form-control form-control-lg" value="<?= $totalpay; ?>" style="font-weight: bold; text-align: right; color: blue; font-size: 24pt;" readonly>
                </div>
                <div class="form-group">
                    <label for="">Jumlah uang</label>
                    <input type="text" name="paysaldo" id="paysaldo" class=" form-control form-control-lg  frmpembayaran" style="font-weight: bold; text-align: right; color: green; font-size: 16pt;" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Sisa uang</label>
                    <input type="text" name="paychange" id="paychange" class=" form-control form-control-lg" style="font-weight: bold; text-align: right; color: red; font-size: 16pt;" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary saveButton">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        $("#percentdisc").autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '0',
        });

        $("#nominaldisc").autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '0',
        });

        $("#totalnet").autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '0',
        });

        $("#paysaldo").autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '0',
        });

        $("#paychange").autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '0',
        });

        $('#percentdisc').keyup(function(e) {
            calculateDiscount();
        });

        $('#nominaldisc').keyup(function(e) {
            calculateDiscount();
        });

        $('#paysaldo').keyup(function(e) {
            calculatechanges();
        });

        $('.formpayment').submit(function(e) {
            e.preventDefault();

            let paysaldo = ($('#paysaldo').val() == "") ? 0 : $('#paysaldo').autoNumeric('get');
            let paychange = ($('#paychange').val() == "") ? 0 : $('#paychange').autoNumeric('get');

            if (parseFloat(paysaldo) == 0 || parseFloat(paysaldo) == "") {
                Toast.fire({
                    icon: 'warning',
                    title: 'Jumlah uang belum diinput!'
                });
            } else if (parseFloat(paychange) < 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Jumlah uang kurang!'
                });
            } else {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.saveButton').prop('disabled', true);
                        $('.saveButton').html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.saveButton').prop('disabled', false);
                        $('.saveButton').html('Simpan');
                    },
                    success: function(response) {
                        if (response.success == "berhasil") {
                            Swal.fire({
                                title: 'Cetak Struk?',
                                text: "Apakah struk mau dicetak?!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Cetak!',
                                cancelmButtonText: 'Tidak!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.open('/SaleReport/printinvoice/' + response.invoice);
                                    window.location.reload();

                                } else {
                                    window.location.reload();
                                }
                            })
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    },

                });
            }


            return false;
        });
    });

    function calculateDiscount() {
        let totalprice = $('#totalprice').val();
        let percentdisc = ($('#percentdisc').val() == "") ? 0 : $('#percentdisc').autoNumeric('get');
        let nominaldisc = ($('#nominaldisc').val() == "") ? 0 : $('#nominaldisc').autoNumeric('get');

        let result;
        result = parseFloat(totalprice) - (parseFloat(totalprice) * parseFloat(percentdisc) / 100) - parseFloat(nominaldisc);
        $('#totalnet').val(result);

        let totalnet = $('#totalnet').val();
        $('#totalnet').autoNumeric('set', result);
    }

    function calculatechanges() {
        let totalpay = $('#totalnet').autoNumeric('get');
        let paysaldo = ($('#paysaldo').val() == "") ? 0 : $('#paysaldo').autoNumeric('get');

        changes = parseFloat(paysaldo) - parseFloat(totalpay);

        $('#paychange').val(changes);

        let paychanges = $('#paychange').val();
        $('#paychange').autoNumeric('set', paychanges);
    }
</script>