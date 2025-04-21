<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- 404 Error Text -->
    <div class="text-center">
        <div class="error mx-auto" data-text="<?= $ecode; ?>"><?= $ecode; ?></div>
        <p class="lead text-gray-800 mb-5"><?= $msg; ?></p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="/Dashboard/index">&larr; Back to Dashboard</a>
    </div>

</div>
<?= $this->endSection(); ?>