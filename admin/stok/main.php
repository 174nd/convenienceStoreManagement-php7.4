<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = [
  'title' => 'Stok',
  'content' => 'Stok',
  'breadcrumb' => [
    'Stok' => 'active',
  ],
];

$setSidebar = activedSidebar($setSidebar, 'Stok');

if (isset($_POST['delete-stok'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM stok WHERE id_stok = '$_POST[id_stok]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('stok', ['id_stok' => $_POST['id_stok']]) && deleteFile($ada['foto_stok'], $backurl . "uploads/stoks")) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Berhasil di Hapus!',
        'field'   => 'Data berhasil Dibapus dari Database!',
      ];
      header("location:" . $df['home'] . "stok/");
      exit();
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Hapus!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
      header("location:" . $df['home'] . "stok/");
      exit();
    }
  } else {
    $_SESSION['notifikasi'] = [
      'type'    => 'alert',
      'status'  => 'danger',
      'icon'    => 'icon fas fa-exclamation-triangle',
      'head'    => 'Data Gagal di Hapus!',
      'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
    ];
    header("location:" . $df['home'] . "stok/");
    exit();
  }
} else if (isset($_GET['id_stok'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM stok WHERE id_stok='$_GET[id_stok]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_stok' => $ada['nm_stok'],
      'id_kategori' => $ada['id_kategori'],
      'id_ss' => $ada['id_ss'],
      'id_sj' => $ada['id_sj'],
      'p_smj' => $ada['p_smj'],
      'p_keuntungan' => $ada['p_keuntungan'],
      'p_diskon' => $ada['p_diskon'],
      'foto_stok' => ($ada['foto_stok'] == null) ? "Choose file" : substr($ada['foto_stok'], strlen($ada['nm_stok'] . ' - ')),
      'asal_foto_stok' =>  $ada['foto_stok'],
    ];
    $pset = [
      'title' => 'Update Stok',
      'content' => 'Update Stok',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Stok' => $df['home'] . 'stok/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stok WHERE nm_stok='$_POST[nm_stok]' AND id_stok!='$_GET[id_stok]'")) > 0) {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Stokname Telah Digunakan!',
        ];
        $isiVal = [
          'nm_stok' => $_POST['nm_stok'],
          'id_kategori' => $_POST['id_kategori'],
          'id_ss' => $_POST['id_ss'],
          'id_sj' => $_POST['id_sj'],
          'p_smj' => $_POST['p_smj'],
          'p_keuntungan' => $_POST['p_keuntungan'],
          'p_diskon' => $_POST['p_diskon'],
          'foto_stok' => $isiVal['foto_stok'],
        ];
      } else {
        $upFile = uploadFile($_FILES['foto_stok'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/stok", $_POST['nm_stok'] . ' - ', $isiVal['asal_foto_stok']);
        if ($upFile == 'Wrong Extension') {
          $_SESSION['notifikasi'] = [
            'type'    => 'alert',
            'status'  => 'warning',
            'icon'    => 'icon fas fa-exclamation-triangle',
            'head'    => 'Warning!',
            'field'   => 'Esktensi File Tidak diperbolehkan!!',
          ];
          $isiVal = [
            'nm_stok' => $_POST['nm_stok'],
            'id_kategori' => $_POST['id_kategori'],
            'id_ss' => $_POST['id_ss'],
            'id_sj' => $_POST['id_sj'],
            'p_smj' => $_POST['p_smj'],
            'p_keuntungan' => $_POST['p_keuntungan'],
            'p_diskon' => $_POST['p_diskon'],
            'foto_stok' => $isiVal['foto_stok'],
          ];
        } else {
          $set = [
            'nm_stok' => $_POST['nm_stok'],
            'id_kategori' => $_POST['id_kategori'],
            'id_ss' => $_POST['id_ss'],
            'id_sj' => $_POST['id_sj'],
            'p_smj' => $_POST['p_smj'],
            'p_keuntungan' => $_POST['p_keuntungan'],
            'p_diskon' => $_POST['p_diskon'],
            'foto_stok' => $upFile,
          ];
          $query = setUpdate($set, 'stok', ['id_stok' => $_GET['id_stok']]);

          if (!$query) {
            $_SESSION['notifikasi'] = [
              'type'    => 'alert',
              'status'  => 'danger',
              'icon'    => 'icon fas fa-exclamation-triangle',
              'head'    => 'Data Gagal di Ubah!',
              'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
            ];
          } else {
            $_SESSION['notifikasi'] = [
              'type'    => 'alert',
              'status'  => 'success',
              'icon'    => 'icon fas fa-check',
              'head'    => 'Data Berhasil di Ubah!',
              'field'   => 'Data berhasil di Ubah dari Database!',
            ];
            header("location:" . $df['home'] . "stok/");
            exit();
          }
        }
      }
    }
  } else {
    $_SESSION['notifikasi'] = [
      'type'    => 'alert',
      'status'  => 'danger',
      'icon'    => 'icon fas fa-exclamation-triangle',
      'head'    => 'Data Gagal di Ditampilkan!',
      'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
    ];
    header("location:" . $df['home'] . "stok/");
    exit();
  }
} else {
  $isiVal = [
    'id_barang' => '',
    'j_masuk' => '',
    'tgl_masuk' => '',
    'id_pt' => '',
    'hrg_barang' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    $set = [
      'id_barang' => $_POST['id_barang'],
      'j_masuk' => $_POST['j_masuk'],
      'tgl_masuk' => $_POST['tgl_masuk'],
      'id_pt' => $_POST['id_pt'],
      'id_ss' => $_POST['id_ss'],
      'id_sj' => $_POST['id_sj'],
      'p_smj' => $_POST['p_smj'],
      'hrg_barang' => str_replace('.', '', $_POST['hrg_barang']),
      'j_keluar' => '0',
    ];
    $query = setInsert($set, 'stok');
    if (!$query) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Input!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'success',
        'icon'    => 'icon fas fa-check',
        'head'    => 'Data Berhasil di Input!',
        'field'   => 'Data berhasil diinput kedalam Database!',
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
          <form method="POST" enctype="multipart/form-data" autocomplete="off" id="form-utama">
            <div class="row">
              <div class="col-md-6">
                <div class="card card-warning card-outline">
                  <div class="overlay d-flex justify-content-center align-items-center invisible">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                  </div>
                  <div class="card-body">
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
                                    <option value="<?= $Data['id_barang']; ?>" <?= cekSama($isiVal['id_barang'], $Data['id_barang']); ?>><?= $Data['nm_barang']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 mb-2">
                            <label class="float-right" for="j_masuk">Stok</label>
                            <div class="input-group">
                              <input type="number" name="j_masuk" class="form-control" id="j_masuk" min="1" value="<?= $isiVal['j_masuk']; ?>" placeholder="Stok" required>
                              <div class="input-group-append">
                                <span class="input-group-text" id="append_j_masuk">/ Pcs</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-6 mb-2">
                            <label class="float-right" for="tgl_masuk">Tanggal Masuk</label>
                            <div class="input-group">
                              <input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control mydatepicker" placeholder="Tanggal Masuk" value="<?= $isiVal['tgl_masuk']; ?>" required>
                              <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="id_pt">Perusahaan</label>
                              </div>
                              <div class="col-12">
                                <select name="id_pt" id="id_pt" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM pt ORDER BY nm_pt ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_pt']; ?>" <?= cekSama($isiVal['id_pt'], $Data['id_pt']); ?>><?= $Data['nm_pt']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-5 mb-2">
                            <label class="float-right" for="perbandingan">Perbandingan</label>
                            <input type="text" name="perbandingan" class="form-control text-center" id="perbandingan" placeholder="Perbandingan Stok" disabled>
                          </div>
                          <div class="col-md-3 mb-2">
                            <label class="float-right" for="p_keuntungan">Keuntungan</label>
                            <div class="input-group">
                              <input type="text" name="p_keuntungan" class="form-control" id="p_keuntungan" placeholder="Keuntungan" disabled>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 mb-2">
                            <label class="float-right" for="total_stok">Total Stok</label>
                            <div class="input-group">
                              <input type="text" name="total_stok" class="form-control format_uang" id="total_stok" min="0" placeholder="Total" disabled>
                              <div class="input-group-append">
                                <span class="input-group-text" id="append_total_stok">/ Pcs</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-6 mb-2 mb-md-0">
                            <label class="float-right" for="hrg_barang">Harga</label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">Rp.</span>
                              </div>
                              <input type="text" name="hrg_barang" id="hrg_barang" class="form-control format_uang" placeholder="Harga" value="<?= $isiVal['hrg_barang']; ?>" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <label class="float-right" for="hrg_jual">Harga Jual</label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">Rp.</span>
                              </div>
                              <input type="text" name="hrg_jual" id="hrg_jual" class="form-control format_uang" placeholder="Harga" value="0" disabled>
                              <div class="input-group-append">
                                <span class="input-group-text" id="append_hrg_jual">/ Pcs</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>

                    <input type="hidden" name="id_ss">
                    <input type="hidden" name="id_sj">
                    <input type="hidden" name="p_smj">
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" name="Simpan" class="btn btn-block btn-warning">Simpan</button>
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="card card-warning card-outline">
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
              </div>
              <!-- /.col -->
            </div>
          </form>
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


    <div class="modal fade" id="delete-stok">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus stok</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus stok ini?</h4>
            <input type="hidden" name="id_stok" id="did_stok">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-stok" class="btn btn-outline-danger">Hapus</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
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
      var p_keuntungan, p_smj, sisa_uang, sisa_stok, host = "<?= $df['home'] ?>";
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
        }, {
          'className': "align-middle text-center",
          "width": "10px",
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
                let hasil_button = (hasil['j_keluar'] == 0) ? '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_stok\').val(\'' + hasil['id_stok'] + '\')" data-target="#delete-stok"><i class="fa fa-trash-alt"></i></button>' : '<button type="button" class="btn btn-sm btn-danger" disabled><i class="fa fa-trash-alt"></i></button>';
                table_stok.row.add([hasil['tgl_masuk'], hasil['nm_pt'], (parseInt(hasil['j_masuk']) * parseInt(hasil['p_smj'])) + ' / ' + hasil['j_keluar'], hasil_button]).draw();
              });

              $('#data-barang .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });


      $('#form-utama #id_barang').change(function() {
        $('#form-utama .overlay').removeClass('invisible');
        let id_barang = $(this).val();
        $.ajax({
          type: "POST",
          url: host + "get-barang.php",
          dataType: "JSON",
          data: {
            'set': 'get_barang',
            'id_barang': id_barang,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              $('#form-utama #p_keuntungan').val(data['p_keuntungan']);
              $('#form-utama input[name="id_ss"]').val(data['id_ss']);
              $('#form-utama input[name="id_sj"]').val(data['id_sj']);
              $('#form-utama input[name="p_smj"]').val(data['p_smj']);
              $('#form-utama #append_j_masuk').html('/ ' + data['satuan_stok']);
              $('#form-utama #append_total_stok').html('/ ' + data['satuan_jual']);
              $('#form-utama #append_hrg_jual').html('/ ' + data['satuan_jual']);
              $('#form-utama #perbandingan').val('1 ' + data['satuan_stok'] + ' / ' + data['p_smj'] + ' ' + data['satuan_jual']);
              p_smj = parseInt(data['p_smj']);
              p_keuntungan = parseInt(data['p_keuntungan']);
              sisa_uang = parseInt(data['sisa_uang']);
              sisa_stok = parseInt(data['sisa_stok']);
              $('#form-utama #j_masuk').trigger('keyup');



              $('#form-utama .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#form-utama #id_barang').trigger('change');


      $('#form-utama #j_masuk').keyup(function() {
        let isi_total_stok = 0;
        let isi_hrg_jual = 0;
        if ($(this).val() != '') {
          isi_total_stok = $(this).val() * p_smj;
          if ($('#form-utama #hrg_barang').val() != '') {
            isi_hrg_jual = (parseInt($('#form-utama #hrg_barang').val().replaceAll('.', '')) + sisa_uang) / (sisa_stok + (parseInt($(this).val()) * p_smj)) / 100 * (100 + p_keuntungan);
            isi_hrg_jual = Math.ceil(isi_hrg_jual / 100);
            isi_hrg_jual = isi_hrg_jual * 100;
          }
        }
        $('#form-utama #hrg_jual').val(isi_hrg_jual).trigger('input');
        $('#form-utama #total_stok').val(isi_total_stok).trigger('input');
      });

      $('#form-utama #hrg_barang').keyup(function() {
        let isi_hrg_jual = 0;
        if ($('#form-utama #j_masuk').val() != '' && $('#form-utama #j_masuk').val() != '') {
          isi_hrg_jual = (parseInt($(this).val().replaceAll('.', '')) + sisa_uang) / (sisa_stok + (parseInt($('#form-utama #j_masuk').val()) * p_smj)) / 100 * (100 + p_keuntungan);
          isi_hrg_jual = Math.ceil(isi_hrg_jual / 100);
          isi_hrg_jual = isi_hrg_jual * 100;
        }
        $('#form-utama #hrg_jual').val(isi_hrg_jual).trigger('input');
      });
    });
  </script>
</body>

</html>