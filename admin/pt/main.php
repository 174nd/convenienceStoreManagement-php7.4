<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = [
  'title' => 'Perusahaan',
  'content' => 'Perusahaan',
  'breadcrumb' => [
    'Perusahaan' => 'active',
  ],
];

$setSidebar = activedSidebar($setSidebar, 'Perusahaan');

if (isset($_POST['delete-pt'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM pt WHERE id_pt = '$_POST[id_pt]'");
  if (mysqli_num_rows($cekdata) > 0) {
    if (setDelete('pt', ['id_pt' => $_POST['id_pt']])) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Berhasil di Hapus!',
        'field'   => 'Data berhasil Dibapus dari Database!',
      ];
      header("location:" . $df['home'] . "pt/");
      exit();
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Hapus!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
      header("location:" . $df['home'] . "pt/");
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
    header("location:" . $df['home'] . "pt/");
    exit();
  }
} else if (isset($_GET['id_pt'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM pt WHERE id_pt='$_GET[id_pt]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_pt' => $ada['nm_pt'],
    ];
    $pset = [
      'title' => 'Update Perusahaan',
      'content' => 'Update Perusahaan',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Perusahaan' => $df['home'] . 'pt/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pt WHERE nm_pt='$_POST[nm_pt]' AND id_pt!='$_GET[id_pt]'")) > 0) {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Nama Perusahaan Telah Digunakan!',
        ];
        $isiVal = [
          'nm_pt' => $_POST['nm_pt'],
        ];
      } else {
        $set = [
          'nm_pt' => $_POST['nm_pt'],
        ];
        $query = setUpdate($set, 'pt', ['id_pt' => $_GET['id_pt']]);

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
          header("location:" . $df['home'] . "pt/");
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
    header("location:" . $df['home'] . "pt/");
    exit();
  }
} else {
  $isiVal = [
    'nm_pt' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pt WHERE nm_pt='$_POST[nm_pt]'")) > 0) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Warning!',
        'field'   => 'Nama Perusahaan Telah Digunakan!',
      ];
      $isiVal = [
        'nm_pt' => $_POST['nm_pt'],
      ];
    } else {
      $set = [
        'nm_pt' => $_POST['nm_pt'],
      ];
      $query = setInsert($set, 'pt');
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
                            <label class="float-right" for="nm_pt">Nama Perusahaan</label>
                            <input type="text" name="nm_pt" class="form-control" id="nm_pt" placeholder="Nama Perusahaan" value="<?= $isiVal['nm_pt']; ?>" required>
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
                        <input type="text" class="form-control" placeholder="Cari Data" id="field_pt">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="table_pt" class="table table-bordered table-hover" style="min-width: 400px;">
                        <thead>
                          <tr>
                            <th>Nama Perusahaan</th>
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





    <div class="modal fade" id="delete-pt">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus pt</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus pt ini?</h4>
            <input type="hidden" name="id_pt" id="did_pt">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-pt" class="btn btn-outline-danger">Hapus</button>
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
      var table_pt = $('#table_pt').DataTable({
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
            "set_tables": "pt",
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nm_pt",
        }, {
          'className': "align-middle text-center",
          "data": "id_pt",
          "width": "50px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_pt\').val(\'' + data + '\')" data-target="#delete-pt"><i class="fa fa-trash-alt"></i></button><a href="' + host + 'pt/' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
          }
        }],
      });
      $('#table_pt_filter').hide();
      $('#field_pt').keyup(function() {
        table_pt.columns($('#column_pt').val()).search(this.value).draw();
      });

    });
  </script>
</body>

</html>