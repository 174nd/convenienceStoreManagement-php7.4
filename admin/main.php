<?php
$backurl = '../';
require_once($backurl . 'admin/config/settings.php');
$pset = array(
  'title' => 'Dashboard',
  'content' => 'Dashboard',
  'breadcrumb' => array(
    'Dashboard' => 'active',
  ),
);

$setSidebar = activedSidebar($setSidebar, 'Dashboard');




if (isset($_POST['u-password'])) {
  $pass = md5($_POST['pass_lama']);
  if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE username LIKE '$_SESSION[username]' AND password LIKE '$pass'")) > 0) {
    if ($_POST['pass_baru1'] == $_POST["pass_baru2"]) {
      $set = array(
        'pass_user' => $_POST['pass_baru1'],
      );
      $val = array(
        'id_user' => $_SESSION['id_user'],
        'pass_user' => $_POST['pass_lama'],
      );
      $query = setUpdate($set, 'user', $val);
      if (!$query) {

        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'danger',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Data Gagal di Ubah!',
          'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
        ];
      } else {
        $_SESSION["password"] = md5($_POST['pass_baru1']);
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'success',
          'icon'    => 'icon fas fa-check',
          'head'    => 'Password Berhasil di Ubah!',
          'field'   => 'Password berhasil di Ubah dari Database!',
        ];
      }
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Kesalahan!',
        'field'   => 'Password Baru Berbeda / Tidak Sama!',
      ];
    }
  } else {
    $_SESSION['notifikasi'] = [
      'type'    => 'alert',
      'status'  => 'danger',
      'icon'    => 'icon fas fa-exclamation-triangle',
      'head'    => 'Kesalahan!',
      'field'   => 'Password yang Anda Masukan Salah!',
    ];
  }
}
if (isset($_POST['u-foto'])) {
  $upFile = uploadFile($_FILES['foto_user'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/users", $_SESSION['nm_user'] . ' - ');
  if ($upFile == 'Wrong Extension') {
    $_SESSION['notifikasi'] = [
      'type'    => 'alert',
      'status'  => 'warning',
      'icon'    => 'icon fas fa-exclamation-triangle',
      'head'    => 'Warning!',
      'field'   => 'Esktensi File Tidak diperbolehkan!!',
    ];
  } else {
    $query = setUpdate(['foto_user' => $upFile], 'user', ['id_user' => $_SESSION['id_user']]);
    if (!$query) {

      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Ubah!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
    } else {
      $_SESSION["foto_user"] = $upFile;
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'success',
        'icon'    => 'icon fas fa-check',
        'head'    => 'Foto Berhasil di Ubah!',
        'field'   => 'Foto berhasil di Ubah dari Database!',
      ];
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open text-sm">
  <div class="wrapper">
    <?php include $backurl . 'admin/config/header-sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php include $backurl . 'admin/config/content-header.php'; ?>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Main row -->
          <div class="row">

            <section class="col-md-8 connectedSortable">
              <div class="row">
                <div class="col-md-6">
                  <!-- small box -->
                  <div class="small-box bg-primary">
                    <div class="inner">
                      <h3>
                        <?php
                        $r = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id_barang) AS total FROM barang"));
                        echo $r['total'];
                        ?>
                        <sup style="font-size: 20px">Produk</sup>
                      </h3>

                      <p>Banyak Barang</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-pallet"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- small box -->
                  <div class="small-box bg-danger">
                    <div class="inner">
                      <h3>
                        <?php
                        $r = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id_kategori) AS total FROM kategori"));
                        echo $r['total'];
                        ?>
                        <sup style="font-size: 20px">Kategori</sup>
                      </h3>

                      <p>Banyak Kategori</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-boxes"></i>
                    </div>
                  </div>
                </div>
              </div>




              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h5 class="text-center w-100 mb-0">Statistik Penjualan 6 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="statistik-penjualan" style="min-width: 400px;height:300px;"></div>
                  </div>
                </div>
              </div>

              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Data Barang</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-2">
                      <select id="column_barang" class="form-control custom-select">
                        <option value="0">Nama Barang</option>
                        <option value="1">Kategori</option>
                      </select>
                    </div>
                    <div class="col-md-8 mb-2">
                      <input type="text" class="form-control" placeholder="Cari Data" id="field_barang">
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="table_barang" class="table table-bordered table-hover" style="min-width: 400px;">
                      <thead>
                        <tr>
                          <th>Nama Barang</th>
                          <th>Kategori</th>
                          <th>Act</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.nav-tabs-custom -->
            </section>

            <section class="col-md-4 connectedSortable">
              <div class="card">
                <div class="card-body bg-primary">
                  <button type="button" class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#tambah-penjualan">Tambah Data Penjualan</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Tambah Data Penjualan -->

              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h5 class="text-center w-100 mb-0">Penjualan Produk 6 Bulan Terakhir Per Kategori</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="statistik-pie_penjualan" style="min-width: 300px;height:250px;"></div>
                  </div>
                </div>
              </div>

              <div class="card card-primary" id="cari-penjualan">
                <div class="card-header">
                  <h3 class="card-title">Cari Data Penjualan</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <label class="float-right" for="tgl_penjualan">Tanggal Transaksi</label>
                      <div class="input-group">
                        <input type="text" name="tgl_penjualan" id="tgl_penjualan" class="form-control mydatepicker" placeholder="Tanggal Transaksi" required>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <button type="button" id="cari-data-penjualan" class="btn btn-block btn-primary">Cari Data Penjualan</button>
                    </li>
                  </ul>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Cari Data Penjualan -->

              <form class="card card-primary collapsed-card" id="laporan-penjualan" method="POST" enctype="multipart/form-data" autocomplete="off" action="<?= $df['home'] . 'export/laporan-penjualan.php' ?>">
                <div class="card-header">
                  <h3 class="card-title">Laporan Penjualan</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <label class="float-right" for="tgl_awal">Tanggal Mulai</label>
                      <div class="input-group">
                        <input type="text" name="tgl_awal" id="tgl_awal" class="form-control mydatepicker" placeholder="Tanggal Transaksi" required>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <label class="float-right" for="tgl_akhir">Tanggal Akhir</label>
                      <div class="input-group">
                        <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control mydatepicker" placeholder="Tanggal Transaksi" required>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <button type="submit" id="cetak-laporan" class="btn btn-block btn-primary">Cetak Laporan</button>
                    </li>
                  </ul>
                </div>
                <!-- /.card-body -->
              </form>
              <!-- /.Cari Data Penjualan -->
            </section>
          </div>
          <!-- /.row (main row) -->


        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="data-barang">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nama Barang</b><span class="float-right" id="nm_barang">x</span>
              </li>
              <li class="list-group-item">
                <b>Kategori</b><span class="float-right" id="nm_kategori">x</span>
              </li>
              <li class="list-group-item">
                <b>Satuan</b><span class="float-right" id="satuan">x</span>
              </li>
              <li class="list-group-item">
                <b>Perbandingan Stok</b><span class="float-right" id="perbandingan">x</span>
              </li>
              <li class="list-group-item">
                <b>Keuntungan</b><span class="float-right" id="p_keuntungan">x</span>
              </li>
              <li class="list-group-item">
                <b>Diskon</b><span class="float-right" id="p_diskon">x</span>
              </li>
              <li class="list-group-item">
                <b>Stok / Harga</b><span class="float-right" id="sh">x</span>
              </li>
              <li class="list-group-item">
                <b>Harga Jual / Harga Diskon</b><span class="float-right" id="h_jd">x</span>
              </li>
              <li class="list-group-item">
                <div class="table-responsive">
                  <table id="table_stok" class="table table-bordered table-hover" style="min-width: 300px; width:100%;">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Perusahaan</th>
                        <th>Stok / jual</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div class="modal fade" id="tambah-penjualan">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Tambah Data Penjualan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="row w-100 mx-0">
                  <div class="col-md-8 mb-2">
                    <div class="row">
                      <div class="col-12">
                        <label class="float-right" for="id_barang">Barang</label>
                      </div>
                      <div class="col-12">
                        <select name="id_barang" id="id_barang" class="form-control custom-select select2" required>
                          <?php
                          $sql = mysqli_query($conn, "SELECT * FROM barang ORDER BY nm_barang ASC");
                          for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                            <option value="<?= $Data['id_barang']; ?>"><?= $Data['nm_barang']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <label class="float-right" for="tgl_penjualan">Tanggal Transaksi</label>
                    <div class="input-group">
                      <input type="text" name="tgl_penjualan" id="tgl_penjualan" class="form-control mydatepicker" placeholder="Tanggal Transaksi" required>
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="row w-100 mx-0">
                  <div class="col-md-4 mb-2">
                    <label class="float-right" for="stok">stok</label>
                    <div class="input-group">
                      <input type="text" name="stok" class="form-control format_uang" id="stok" placeholder="stok" disabled>
                      <div class="input-group-append">
                        <span class="input-group-text" id="append_stok">/ Pcs</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mb-2">
                    <label class="float-right" for="hj">Harga Jual</label>
                    <div class="input-group">
                      <div class="input-group-append">
                        <span class="input-group-text">Rp.</span>
                      </div>
                      <input type="text" name="hj" id="hj" class="form-control format_uang" placeholder="Harga" value="0" disabled>
                      <div class="input-group-append">
                        <span class="input-group-text" id="append_hj">/ Pcs</span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="row w-100 mx-0">
                  <div class="col-md-5 mb-2 mb-md-0">
                    <label class="float-right" for="jumlah">Jumlah</label>
                    <div class="input-group">
                      <input type="number" name="jumlah" class="form-control" id="jumlah" placeholder="Jumlah" required>
                      <div class="input-group-append">
                        <span class="input-group-text" id="append_jumlah">/ Pcs</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <label class="float-right" for="hrg_barang">Harga</label>
                    <div class="input-group">
                      <div class="input-group-append">
                        <span class="input-group-text">Rp.</span>
                      </div>
                      <input type="text" name="hrg_barang" id="hrg_barang" class="form-control format_uang" placeholder="Harga" value="0" disabled>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-danger" data-dismiss="modal">Batal</button>
              <button type="button" id="simpan-penjualan" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="data-penjualan">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Penjualan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="table-responsive">
                  <table id="table_penjualan" class="table table-bordered table-hover" style="min-width: 300px; width:100%;">
                    <thead>
                      <tr>
                        <th>Produk</th>
                        <th>QTY</th>
                        <th>Harga</th>
                        <th>Act</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="delete-penjualan">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus Penjualan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus Data Penjualan ini?</h4>
            <input type="hidden" name="id_penjualan" id="did_penjualan">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="text" id="delete-data" class="btn btn-outline-danger">Hapus</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php include $backurl . 'admin/config/modal.php'; ?>
    <?php include $backurl . 'config/site/footer.php'; ?>
  </div>
  <!-- ./wrapper -->

  <?php include $backurl . 'config/site/script.php'; ?>
  <!-- page script -->
  <script>
    $(function() {
      var harga_jual = 0,
        qty_tersedia = 0,
        host = "<?= $df['home'] ?>";
      var table_barang = $('#table_barang').DataTable({
        'paging': true,
        'lengthChange': false,
        "pageLength": 10,
        'info': true,
        "order": [
          [0, "desc"]
        ],
        'searching': true,
        'ordering': true,
        "language": {
          "paginate": {
            "previous": "<",
            "next": ">"
          }
        },

        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": host + "../config/get-tables.php",
          "data": {
            "set_tables": "SELECT nm_barang, nm_kategori, id_barang FROM barang JOIN kategori WHERE barang.id_kategori=kategori.id_kategori",
            "query": true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nm_barang",
        }, {
          'className': "align-middle text-center",
          "data": "nm_kategori",
          "width": "70px",
        }, {
          'className': "align-middle text-center",
          "data": "id_barang",
          "width": "10px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#data-barang" id_barang="' + data + '"><i class="fa fa-eye"></i></button></div>';
          }
        }],
      });
      $('#table_barang_filter').hide();
      $('#field_barang').keyup(function() {
        table_barang.columns($('#column_barang').val()).search(this.value).draw();
      });

      var table_stok = $('#table_stok').DataTable({
        'paging': false,
        'info': false,
        'searching': false,
        'ordering': true,
        'autoWidth': false,
        "columns": [{
          'className': "align-middle text-center",
          "width": "10px",
        }, {
          'className': "align-middle",
        }, {
          'className': "align-middle text-center",
          "width": "70px",
        }, ],
        "order": [
          [0, "desc"]
        ],

      });

      table_barang.on('click', 'button[data-target="#data-barang"]', function() {
        $('#data-barang .overlay').removeClass('invisible');
        let id_barang = $(this).attr('id_barang');
        $.ajax({
          type: "POST",
          url: host + "get-barang.php",
          dataType: "JSON",
          data: {
            'set': 'get_barang',
            'id_barang': id_barang,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#data-barang #nm_barang').html(data['nm_barang']);
              $('#data-barang #nm_kategori').html(data['nm_kategori']);
              $('#data-barang #satuan').html(data['satuan_stok'] + ' / ' + data['satuan_jual']);
              $('#data-barang #perbandingan').html('1 / ' + data['p_smj']);
              $('#data-barang #p_keuntungan').html(data['p_keuntungan'] + "%");
              $('#data-barang #p_diskon').html(data['p_diskon'] + "%");

              $('#data-barang #sh').html(data['sisa_stok'] + ' ' + data['satuan_jual'] + ' / ' + formatRupiah(parseInt(data['sisa_uang']).toString()));

              if (data['harga_satuan'] != 0) {
                let harga_diskon = Math.ceil(parseInt(data['harga_satuan']) / 100 * (100 - parseInt(data['p_diskon'])) / 100) * 100;
                $('#data-barang #h_jd').html(formatRupiah(data['harga_satuan']) + " / " + formatRupiah(harga_diskon.toString()));
              } else {
                $('#data-barang #h_jd').html('0 / 0');
              }


              table_stok.clear().draw();

              $(data['data_stok']).each(function(index, hasil) {
                table_stok.row.add([hasil['tgl_masuk'], hasil['nm_pt'], (parseInt(hasil['j_masuk']) * parseInt(hasil['p_smj'])) + ' / ' + hasil['j_keluar']]).draw();
              });

              $('#data-barang .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      var table_penjualan = $('#table_penjualan').DataTable({
        'paging': false,
        'info': false,
        'searching': false,
        'ordering': true,
        'autoWidth': false,
        "columns": [{
          'className': "align-middle",
        }, {
          'className': "align-middle text-center",
          "width": "30px",
        }, {
          'className': "align-middle text-center",
          "width": "70px",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }, ],
        "order": [
          [0, "desc"]
        ],

      });

      $('button[data-target="#tambah-penjualan"]').click(function() {
        $('#tambah-penjualan #id_barang').trigger('change');
      });

      $('#tambah-penjualan #id_barang').change(function() {
        $('#tambah-penjualan .overlay').removeClass('invisible');
        let id_barang = $('#tambah-penjualan #id_barang').val();
        $.ajax({
          type: "POST",
          url: host + "get-barang.php",
          dataType: "JSON",
          data: {
            'set': 'get_barang',
            'id_barang': id_barang,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              harga_jual = (data['harga_satuan'] != 0) ? Math.ceil(parseInt(data['harga_satuan']) / 100 * (100 - parseInt(data['p_diskon'])) / 100) * 100 : 0;
              qty_tersedia = parseInt(data['sisa_stok']);
              $('#tambah-penjualan #append_hj').html('/ ' + data['satuan_jual']);
              $('#tambah-penjualan #append_stok').html('/ ' + data['satuan_jual']);
              $('#tambah-penjualan #stok').val(data['sisa_stok']).trigger('input');
              $('#tambah-penjualan #hj').val(data['harga_satuan']).trigger('input');
              $('#tambah-penjualan #append_jumlah').html('/ ' + data['satuan_jual']);
              $('#tambah-penjualan .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#tambah-penjualan #jumlah').keyup(function() {
        $('#tambah-penjualan #hrg_barang').val(harga_jual != 0 ? $(this).val() * harga_jual : 0).trigger('input');
      });

      $('#tambah-penjualan #simpan-penjualan').click(function() {
        if (harga_jual == 0) {
          toastr.error('Harga / Stok Barang Belum ada!');
        } else if ($('#tambah-penjualan #jumlah').val() == 0 || $('#tambah-penjualan #tgl_penjualan').val() == "") {
          toastr.warning('Lengkapi Data terlebih dahulu!');
        } else if ($('#tambah-penjualan #jumlah').val() > qty_tersedia) {
          toastr.warning('Masukan Jumlah Barang melebihi Stok yang Tersedia!');
        } else {
          $('#tambah-penjualan .overlay').removeClass('invisible');
          $.ajax({
            type: "POST",
            url: host + "get-barang.php",
            dataType: "JSON",
            data: {
              'set': 'save_penjualan',
              'id_barang': $('#tambah-penjualan #id_barang').val(),
              'tgl_penjualan': $('#tambah-penjualan #tgl_penjualan').val(),
              'jumlah': $('#tambah-penjualan #jumlah').val(),
            },
            success: function(data) {
              if (data['status'] == 'done') {
                toastr.success('Data Penjualan berhasil disimpan!');
                $('#tambah-penjualan #id_barang').prop('selectedIndex', 0).trigger('change');
                $('#tambah-penjualan #tgl_penjualan').val('');
                $('#tambah-penjualan #jumlah').val('');
                $('#tambah-penjualan #stok').val(0);
                $('#tambah-penjualan #hj').val(0);
                $('#tambah-penjualan #hrg_barang').val(0);
                $('#tambah-penjualan').modal('hide');
              } else {
                toastr.error('Terjadi Kesalahan!');
              }
              $('#tambah-penjualan .overlay').addClass('invisible');
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });

        }
      });

      $('#cari-penjualan #cari-data-penjualan').click(function() {
        if ($('#cari-penjualan #tgl_penjualan').val() == '') {
          toastr.warning('Masukan Tanggal Penjualan yang ingin di Cari!');
        } else {
          $('#data-penjualan').modal('show');
          $('#data-penjualan .overlay').removeClass('invisible');
          $.ajax({
            type: "POST",
            url: host + "get-barang.php",
            dataType: "JSON",
            data: {
              'set': 'get_penjualan',
              'tgl_penjualan': $('#cari-penjualan #tgl_penjualan').val(),
            },
            success: function(data) {
              if (data['status'] == 'done') {
                table_penjualan.clear().draw();
                $(data['data_penjualan']).each(function(index, hasil) {
                  let hasil_button = '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_penjualan\').val(\'' + hasil['id_penjualan'] + '\')" data-target="#delete-penjualan"><i class="fa fa-trash-alt"></i></button>';
                  table_penjualan.row.add([hasil['nm_barang'], hasil['qty_beli'], formatRupiah(hasil['hrg_barang'], 'Rp. '), hasil_button]).draw();
                });
              } else {
                toastr.error('Terjadi Kesalahan!');
              }
              $('#data-penjualan .overlay').addClass('invisible');
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        }
      });

      $('#delete-penjualan #delete-data').click(function() {
        if ($('#delete-penjualan #id_penjualan').val() == '') {
          toastr.error('Terjadi Kesalahan!');
        } else {
          $('#delete-penjualan .overlay').removeClass('invisible');
          $.ajax({
            type: "POST",
            url: host + "get-barang.php",
            dataType: "JSON",
            data: {
              'set': 'delete_penjualan',
              'id_penjualan': $('#delete-penjualan #did_penjualan').val(),
            },
            success: function(data) {
              if (data['status'] == 'done') {
                table_penjualan.clear().draw();
                $(data['data_penjualan']).each(function(index, hasil) {
                  let hasil_button = '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_penjualan\').val(\'' + hasil['id_penjualan'] + '\')" data-target="#delete-penjualan"><i class="fa fa-trash-alt"></i></button>';
                  table_penjualan.row.add([hasil['nm_barang'], hasil['qty_beli'], formatRupiah(hasil['hrg_barang'], 'Rp. '), hasil_button]).draw();
                });
                toastr.warning('Data Berhasil Dihapus!');
                $('#delete-penjualan').modal('hide');
              } else {
                toastr.error('Terjadi Kesalahan!');
              }
              $('#delete-penjualan .overlay').addClass('invisible');
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        }
      });


      var data_penjualan = {
        data: [
          <?php
          $isian_grafik = [];
          $sql = mysqli_query($conn, "SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 6 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 6 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 6 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 5 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 5 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 5 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 4 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 4 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 4 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 3 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 3 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 3 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 2 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS bulan, COALESCE((SELECT SUM(hrg_barang) AS total FROM penjualan WHERE MONTH(penjualan.tgl_penjualan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(penjualan.tgl_penjualan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) ,0) AS total");
          for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) {
            echo '[' . $i . ', ' . $Data['total'] . '],';
            $isian_grafik[] = $Data['bulan'];
          } ?>
        ],
        color: '<?= '#' . substr(md5(mt_rand()), 0, 6); ?>'
      }
      var barOpt_penjualan = {
        grid: {
          hoverable: true,
          borderColor: '#f3f3f3',
          borderWidth: 1,
          tickColor: '#f3f3f3'
        },
        series: {
          shadowSize: 0,
          lines: {
            show: true
          },
          points: {
            show: true
          }
        },
        lines: {
          fill: false,
          color: '#3c8dbc'
        },
        yaxis: {
          show: true
        },
        xaxis: {
          show: true,
          ticks: [
            <?php
            for ($i = 0; $i < count($isian_grafik); $i++) {
              echo "[" . ($i + 1) . ", '" . bulan_indo($isian_grafik[$i]) . "'],";
            } ?>
          ]
        }
      };
      new ResizeSensor($("#statistik-penjualan").parent().parent(), function() {
        $.plot($("#statistik-penjualan"), [data_penjualan], barOpt_penjualan);
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
          position: 'absolute',
          display: 'none',
          opacity: 0.8
        }).appendTo('body')
        $('#statistik-penjualan').bind('plothover', function(event, pos, item) {
          if (item) {
            var x = item.datapoint[0].toFixed(2),
              y = item.datapoint[1].toFixed(2)
            bulan = {
              <?php
              for ($i = 0; $i < count($isian_grafik); $i++) {
                echo ($i + 1)  ?>: <?php echo "'" . bulan_indo($isian_grafik[$i]) . "',";
                                  } ?>
            };
            $('#line-chart-tooltip').html('Penjualan Bulan ' + bulan[Math.round(x)] + ' = ' + formatRupiah(Math.round(y).toString(), 'Rp. '))
              .css({
                top: item.pageY + 5,
                left: item.pageX + 5
              })
              .fadeIn(200)
          } else {
            $('#line-chart-tooltip').hide()
          }

        })
      });


      var data_pie_penjualan = [
        <?php
        $sql = mysqli_query($conn, "SELECT kategori.nm_kategori, SUM(penjualan.hrg_barang) AS total FROM ((penjualan JOIN barang ON penjualan.id_barang=barang.id_barang) JOIN kategori ON barang.id_kategori=kategori.id_kategori) WHERE penjualan.tgl_penjualan BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE() GROUP BY kategori.id_kategori ORDER BY kategori.nm_kategori ASC");
        for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?> {
            label: "<?= $Data['nm_kategori'] ?>",
            set: "<?= $Data['nm_kategori'] ?>",
            data: <?= $Data['total'] ?>,
            color: '<?= '#' . substr(md5(mt_rand()), 0, 6); ?>'
          },
        <?php } ?>
      ];
      var barOpt_pie_penjualan = {
        series: {
          pie: {
            show: true,
            radius: 1,
            innerRadius: 0.5,
            label: {
              show: true,
              radius: 3 / 4,
              background: {
                opacity: 0.5,
                color: '#ffffff'
              },
            },
            combine: {
              color: '#999',
              threshold: 0.1
            }
          }
        },
        grid: {
          clickable: true,
          hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
          content: "%s : %y.0"
        }
      }
      new ResizeSensor($("#statistik-pie_penjualan").parent().parent(), function() {
        $.plot('#statistik-pie_penjualan', data_pie_penjualan, barOpt_pie_penjualan);
      });

    });
  </script>
</body>

</html>