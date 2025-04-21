<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sidebar-collapse" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/Dashboard/index">>
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>

        </div>
        <div class="sidebar-brand-text mx-3">Aplikasi Kasir</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/Dashboard/index">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/Sell/index">
            <i class="fa fa-cart-arrow-down"></i>
            <span>Penjualan</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/StockTransaction/index">
            <i class="fa fa-cube"></i>
            <span>Barang Masuk</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/StockTransaction/stockout">
            <i class="fa fa-outdent"></i>
            <span>Barang Keluar</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="Stockin" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fa fa-tasks"></i>
            <span>Laporan Transaksi</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/SaleReport/index">Penjualan</a>
                <a class="collapse-item" href="/stockreport/index">Barang Masuk</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Distributor -->
    <li class="nav-item">
        <a class="nav-link" href="/Supplier/index">
            <i class="fas fa-truck"></i>
            <span>Suplier</span></a>
    </li>
    <!-- Nav Item - Product -->
    <li class="nav-item">
        <a class="nav-link" href="/product">
            <i class="fa fa-tag "></i>
            <span>Produk</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Profile -->
    <li class="nav-item">
        <a class="nav-link" href="/Profile/index">
            <i class="fas fa-user"></i>
            <span>Profil</span></a>
    </li>

    <!-- Nav Item -Users Profile -->
    <li class="nav-item">
        <a class="nav-link" href="/User/index">
            <i class="fa fa fa-users"></i>
            <span>Daftar Pengguna</span></a>
    </li>

    <!-- Nav Item - Edit Profile -->
    <li class="nav-item">
        <a class="nav-link" href="/auth/logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign Out</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->