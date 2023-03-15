<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = [
  'title' => 'Barang',
  'content' => 'Barang',
  'breadcrumb' => [
    'Barang' => 'active',
  ],
];

$setSidebar = activedSidebar($setSidebar, 'Barang');

if (isset($_POST['delete-barang'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$_POST[id_barang]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('barang', ['id_barang' => $_POST['id_barang']]) && deleteFile($ada['foto_barang'], $backurl . "uploads/barangs")) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Berhasil di Hapus!',
        'field'   => 'Data berhasil Dibapus dari Database!',
      ];
      header("location:" . $df['home'] . "barang/");
      exit();
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Hapus!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
      header("location:" . $df['home'] . "barang/");
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
    header("location:" . $df['home'] . "barang/");
    exit();
  }
} else if (isset($_GET['id_barang'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$_GET[id_barang]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_barang' => $ada['nm_barang'],
      'id_kategori' => $ada['id_kategori'],
      'id_ss' => $ada['id_ss'],
      'id_sj' => $ada['id_sj'],
      'p_smj' => $ada['p_smj'],
      'p_keuntungan' => $ada['p_keuntungan'],
      'p_diskon' => $ada['p_diskon'],
      'foto_barang' => ($ada['foto_barang'] == null) ? "Choose file" : substr($ada['foto_barang'], strlen($ada['nm_barang'] . ' - ')),
      'asal_foto_barang' =>  $ada['foto_barang'],
    ];
    $pset = [
      'title' => 'Update Barang',
      'content' => 'Update Barang',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Barang' => $df['home'] . 'barang/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang WHERE nm_barang='$_POST[nm_barang]' AND id_barang!='$_GET[id_barang]'")) > 0) {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Barangname Telah Digunakan!',
        ];
        $isiVal = [
          'nm_barang' => $_POST['nm_barang'],
          'id_kategori' => $_POST['id_kategori'],
          'id_ss' => $_POST['id_ss'],
          'id_sj' => $_POST['id_sj'],
          'p_smj' => $_POST['p_smj'],
          'p_keuntungan' => $_POST['p_keuntungan'],
          'p_diskon' => $_POST['p_diskon'],
          'foto_barang' => $isiVal['foto_barang'],
        ];
      } else {
        $upFile = uploadFile($_FILES['foto_barang'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/barang", $_POST['nm_barang'] . ' - ', $isiVal['asal_foto_barang']);
        if ($upFile == 'Wrong Extension') {
          $_SESSION['notifikasi'] = [
            'type'    => 'alert',
            'status'  => 'warning',
            'icon'    => 'icon fas fa-exclamation-triangle',
            'head'    => 'Warning!',
            'field'   => 'Esktensi File Tidak diperbolehkan!!',
          ];
          $isiVal = [
            'nm_barang' => $_POST['nm_barang'],
            'id_kategori' => $_POST['id_kategori'],
            'id_ss' => $_POST['id_ss'],
            'id_sj' => $_POST['id_sj'],
            'p_smj' => $_POST['p_smj'],
            'p_keuntungan' => $_POST['p_keuntungan'],
            'p_diskon' => $_POST['p_diskon'],
            'foto_barang' => $isiVal['foto_barang'],
          ];
        } else {
          $set = [
            'nm_barang' => $_POST['nm_barang'],
            'id_kategori' => $_POST['id_kategori'],
            'id_ss' => $_POST['id_ss'],
            'id_sj' => $_POST['id_sj'],
            'p_smj' => $_POST['p_smj'],
            'p_keuntungan' => $_POST['p_keuntungan'],
            'p_diskon' => $_POST['p_diskon'],
            'foto_barang' => $upFile,
          ];
          $query = setUpdate($set, 'barang', ['id_barang' => $_GET['id_barang']]);

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
            header("location:" . $df['home'] . "barang/");
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
    header("location:" . $df['home'] . "barang/");
    exit();
  }
} else {
  $isiVal = [
    'nm_barang' => '',
    'id_kategori' => '',
    'id_ss' => '',
    'id_sj' => '',
    'p_smj' => '',
    'p_keuntungan' => '',
    'p_diskon' => '',
    'foto_barang' => 'Choose file',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang WHERE nm_barang='$_POST[nm_barang]'")) > 0) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Warning!',
        'field'   => 'Nama Barang Telah Digunakan!',
      ];
      $isiVal = [
        'nm_barang' => $_POST['nm_barang'],
        'id_kategori' => $_POST['id_kategori'],
        'id_ss' => $_POST['id_ss'],
        'id_sj' => $_POST['id_sj'],
        'p_smj' => $_POST['p_smj'],
        'p_keuntungan' => $_POST['p_keuntungan'],
        'p_diskon' => $_POST['p_diskon'],
        'foto_barang' => 'Choose file',
      ];
    } else {
      $upFile = uploadFile($_FILES['foto_barang'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/barang", $_POST['nm_barang'] . ' - ');
      if ($upFile == 'Wrong Extension') {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Esktensi File Tidak diperbolehkan!!',
        ];
        $isiVal = [
          'nm_barang' => $_POST['nm_barang'],
          'id_kategori' => $_POST['id_kategori'],
          'id_ss' => $_POST['id_ss'],
          'id_sj' => $_POST['id_sj'],
          'p_smj' => $_POST['p_smj'],
          'p_keuntungan' => $_POST['p_keuntungan'],
          'p_diskon' => $_POST['p_diskon'],
          'foto_barang' => 'Choose file',
        ];
      } else {
        $set = [
          'nm_barang' => $_POST['nm_barang'],
          'id_kategori' => $_POST['id_kategori'],
          'id_ss' => $_POST['id_ss'],
          'id_sj' => $_POST['id_sj'],
          'p_smj' => $_POST['p_smj'],
          'p_keuntungan' => $_POST['p_keuntungan'],
          'p_diskon' => $_POST['p_diskon'],
          'foto_barang' => $upFile,
        ];
        $query = setInsert($set, 'barang');
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
                          <div class="col-md-12 mb-2">
                            <label class="float-right" for="nm_barang">Nama Barang</label>
                            <input type="text" name="nm_barang" class="form-control" id="nm_barang" placeholder="Nama Barang" value="<?= $isiVal['nm_barang']; ?>" required>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-6 mb-2">
                            <label class="float-right" for="foto_barang">Foto Barang</label>
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" name="foto_barang" class="custom-file-input" id="foto_barang">
                                <label class="custom-file-label" for="foto_barang"><?= $isiVal['foto_barang']; ?></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="id_kategori">Kategori</label>
                              </div>
                              <div class="col-12">
                                <select name="id_kategori" id="id_kategori" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nm_kategori ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_kategori']; ?>" <?= cekSama($isiVal['id_kategori'], $Data['id_kategori']); ?>><?= $Data['nm_kategori']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-6 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="id_ss">Satuan Stok</label>
                              </div>
                              <div class="col-12">
                                <select name="id_ss" id="id_ss" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM satuan ORDER BY nm_satuan ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_satuan']; ?>" <?= cekSama($isiVal['id_ss'], $Data['id_satuan']); ?>><?= $Data['nm_satuan']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="id_sj">Satuan Jual</label>
                              </div>
                              <div class="col-12">
                                <select name="id_sj" id="id_sj" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM satuan ORDER BY nm_satuan ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_satuan']; ?>" <?= cekSama($isiVal['id_sj'], $Data['id_satuan']); ?>><?= $Data['nm_satuan']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-4 mb-2 mb-md-0">
                            <label class="float-right" for="p_smj">Perbandingan Stok</label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">1 /</span>
                              </div>
                              <input type="number" name="p_smj" class="form-control" id="p_smj" min="1" placeholder="Perbandingan" value="<?= $isiVal['p_smj']; ?>" required>
                            </div>
                          </div>
                          <div class="col-md-4 mb-2 mb-md-0">
                            <label class="float-right" for="p_keuntungan">Persentase Keuntungan</label>
                            <div class="input-group">
                              <input type="number" name="p_keuntungan" class="form-control" id="p_keuntungan" min="0" placeholder="Persentase Keuntungan" value="<?= $isiVal['p_keuntungan']; ?>" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <label class="float-right" for="p_diskon">Persentase Diskon</label>
                            <div class="input-group">
                              <input type="number" name="p_diskon" class="form-control" id="p_diskon" min="0" placeholder="Persentase Diskon" value="<?= $isiVal['p_diskon']; ?>" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
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
            <h4 class="modal-title">Data barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nama Barang</b><span class="float-right" id="nm_mbarang">x</span>
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
                <div class="btn-group w-100">
                  <button type="button" id="delete-barang" class="btn btn-sm btn-danger">Delete</button>
                  <a href="#" id="update-barang" class="btn btn-sm bg-info">Update</a>
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


    <div class="modal fade" id="delete-barang">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus barang ini?</h4>
            <input type="hidden" name="id_barang" id="did_barang">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-barang" class="btn btn-outline-danger">Hapus</button>
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
      var host = "<?= $df['home'] ?>";
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
      $('#form-utama').submit(function() {
        if ($('#form-utama #id_ss').val() == $('#form-utama #id_sj').val()) {
          toastr.warning('Satuan Stok & Satuan Jual Tidak Boleh Sama!');
          return false;
        }
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
              $('#data-barang #update-barang').attr('href', host + 'barang/' + id_barang);
              $('#data-barang #delete-barang').unbind().click(function() {
                $('#delete-barang #did_barang').val(id_barang);
                $('#delete-barang').modal('show');
              });


              $('#data-barang #sh').html(data['sisa_stok'] + ' ' + data['satuan_jual'] + ' / ' + formatRupiah(parseInt(data['sisa_uang']).toString()));

              if (data['harga_satuan'] != 0) {
                let harga_diskon = Math.ceil(parseInt(data['harga_satuan']) / 100 * (100 - parseInt(data['p_diskon'])) / 100) * 100;
                $('#data-barang #h_jd').html(formatRupiah(data['harga_satuan']) + " / " + formatRupiah(harga_diskon.toString()));
              } else {
                $('#data-barang #h_jd').html('0 / 0');
              }

              $('#data-barang .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });




    });
  </script>
</body>

</html>