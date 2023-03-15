<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = [
  'title' => 'Kategori',
  'content' => 'Kategori',
  'breadcrumb' => [
    'Kategori' => 'active',
  ],
];

$setSidebar = activedSidebar($setSidebar, 'Kategori');

if (isset($_POST['delete-kategori'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = '$_POST[id_kategori]'");
  if (mysqli_num_rows($cekdata) > 0) {
    if (setDelete('kategori', ['id_kategori' => $_POST['id_kategori']])) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Berhasil di Hapus!',
        'field'   => 'Data berhasil Dibapus dari Database!',
      ];
      header("location:" . $df['home'] . "kategori/");
      exit();
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Hapus!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
      header("location:" . $df['home'] . "kategori/");
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
    header("location:" . $df['home'] . "kategori/");
    exit();
  }
} else if (isset($_GET['id_kategori'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori='$_GET[id_kategori]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_kategori' => $ada['nm_kategori'],
    ];
    $pset = [
      'title' => 'Update Kategori',
      'content' => 'Update Kategori',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Kategori' => $df['home'] . 'kategori/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori WHERE nm_kategori='$_POST[nm_kategori]' AND id_kategori!='$_GET[id_kategori]'")) > 0) {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Nama Kategori Telah Digunakan!',
        ];
        $isiVal = [
          'nm_kategori' => $_POST['nm_kategori'],
        ];
      } else {
        $set = [
          'nm_kategori' => $_POST['nm_kategori'],
        ];
        $query = setUpdate($set, 'kategori', ['id_kategori' => $_GET['id_kategori']]);

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
          header("location:" . $df['home'] . "kategori/");
          exit();
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
    header("location:" . $df['home'] . "kategori/");
    exit();
  }
} else {
  $isiVal = [
    'nm_kategori' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori WHERE nm_kategori='$_POST[nm_kategori]'")) > 0) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Warning!',
        'field'   => 'Nama Kategori Telah Digunakan!',
      ];
      $isiVal = [
        'nm_kategori' => $_POST['nm_kategori'],
      ];
    } else {
      $set = [
        'nm_kategori' => $_POST['nm_kategori'],
      ];
      $query = setInsert($set, 'kategori');
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
                          <div class="col-md-12">
                            <label class="float-right" for="nm_kategori">Nama Kategori</label>
                            <input type="text" name="nm_kategori" class="form-control" id="nm_kategori" placeholder="Nama Kategori" value="<?= $isiVal['nm_kategori']; ?>" required>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <input type="hidden" id="status" value="simpan">
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
                      <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Cari Data" id="field_kategori">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="table_kategori" class="table table-bordered table-hover" style="min-width: 400px;">
                        <thead>
                          <tr>
                            <th>Nama Kategori</th>
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





    <div class="modal fade" id="delete-kategori">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus kategori ini?</h4>
            <input type="hidden" name="id_kategori" id="did_kategori">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-kategori" class="btn btn-outline-danger">Hapus</button>
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
      var table_kategori = $('#table_kategori').DataTable({
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
            "set_tables": "kategori",
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nm_kategori",
        }, {
          'className': "align-middle text-center",
          "data": "id_kategori",
          "width": "50px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_kategori\').val(\'' + data + '\')" data-target="#delete-kategori"><i class="fa fa-trash-alt"></i></button><a href="' + host + 'kategori/' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
          }
        }],
      });
      $('#table_kategori_filter').hide();
      $('#field_kategori').keyup(function() {
        table_kategori.columns($('#column_kategori').val()).search(this.value).draw();
      });

    });
  </script>
</body>

</html>