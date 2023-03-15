<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = [
  'title' => 'User',
  'content' => 'User',
  'breadcrumb' => [
    'User' => 'active',
  ],
];

$setSidebar = activedSidebar($setSidebar, 'User');

if (isset($_POST['delete-user'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$_POST[id_user]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('user', ['id_user' => $_POST['id_user']]) && deleteFile($ada['foto_user'], $backurl . "uploads/users")) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Berhasil di Hapus!',
        'field'   => 'Data berhasil Dibapus dari Database!',
      ];
      header("location:" . $df['home'] . "user/");
      exit();
    } else {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'danger',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Data Gagal di Hapus!',
        'field'   => 'Ada kesalahan pada query, Silahkan cek lagi!!',
      ];
      header("location:" . $df['home'] . "user/");
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
    header("location:" . $df['home'] . "user/");
    exit();
  }
} else if (isset($_GET['id_user'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$_GET[id_user]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_user' => $ada['nm_user'],
      'akses_user' => $ada['akses_user'],
      'usrn' => $ada['usrn'],
      'pss' => $ada['pss'],
      'foto_user' => ($ada['foto_user'] == null) ? "Choose file" : substr($ada['foto_user'], strlen($ada['nm_user'] . ' - ')),
      'asal_foto_user' =>  $ada['foto_user'],
    ];
    $pset = [
      'title' => 'Update User',
      'content' => 'Update User',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'User' => $df['home'] . 'user/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE usrn='$_POST[usrn]' AND id_user!='$_GET[id_user]'")) > 0) {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Username Telah Digunakan!',
        ];
        $isiVal = [
          'nm_user' => $_POST['nm_user'],
          'notelp_user' => $_POST['notelp_user'],
          'usrn' => $_POST['usrn'],
          'pss' => $_POST['pss'],
          'foto_user' => $isiVal['foto_user'],
        ];
      } else {
        $upFile = uploadFile($_FILES['foto_user'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/users", $_POST['nm_user'] . ' - ', $isiVal['asal_foto_user']);
        if ($upFile == 'Wrong Extension') {
          $_SESSION['notifikasi'] = [
            'type'    => 'alert',
            'status'  => 'warning',
            'icon'    => 'icon fas fa-exclamation-triangle',
            'head'    => 'Warning!',
            'field'   => 'Esktensi File Tidak diperbolehkan!!',
          ];
          $isiVal = [
            'nm_user' => $_POST['nm_user'],
            'akses_user' => $_POST['akses_user'],
            'usrn' => $_POST['usrn'],
            'pss' => $_POST['pss'],
            'foto_user' => $isiVal['foto_user'],
          ];
        } else {
          $set = [
            'nm_user' => $_POST['nm_user'],
            'akses_user' => $_POST['akses_user'],
            'usrn' => $_POST['usrn'],
            'pss' => $_POST['pss'],
            'foto_user' => $upFile,
          ];
          $query = setUpdate($set, 'user', ['id_user' => $_GET['id_user']]);

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
            header("location:" . $df['home'] . "user/");
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
    header("location:" . $df['home'] . "user/");
    exit();
  }
} else {
  $isiVal = [
    'nm_user' => '',
    'akses_user' => '',
    'usrn' => '',
    'pss' => '',
    'foto_user' => 'Choose file',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE pss='$_POST[pss]'")) > 0) {
      $_SESSION['notifikasi'] = [
        'type'    => 'alert',
        'status'  => 'warning',
        'icon'    => 'icon fas fa-exclamation-triangle',
        'head'    => 'Warning!',
        'field'   => 'Username Telah Digunakan!',
      ];
      $isiVal = [
        'nm_user' => $_POST['nm_user'],
        'akses_user' => $_POST['akses_user'],
        'usrn' => $_POST['usrn'],
        'pss' => $_POST['pss'],
        'foto_user' => 'Choose file',
      ];
    } else {
      $upFile = uploadFile($_FILES['foto_user'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/users", $_POST['nm_user'] . ' - ');
      if ($upFile == 'Wrong Extension') {
        $_SESSION['notifikasi'] = [
          'type'    => 'alert',
          'status'  => 'warning',
          'icon'    => 'icon fas fa-exclamation-triangle',
          'head'    => 'Warning!',
          'field'   => 'Esktensi File Tidak diperbolehkan!!',
        ];
        $isiVal = [
          'nm_user' => $_POST['nm_user'],
          'akses_user' => $_POST['akses_user'],
          'usrn' => $_POST['usrn'],
          'pss' => $_POST['pss'],
          'foto_user' => 'Choose file',
        ];
      } else {
        $set = [
          'nm_user' => $_POST['nm_user'],
          'akses_user' => $_POST['akses_user'],
          'usrn' => $_POST['usrn'],
          'pss' => $_POST['pss'],
          'foto_user' => $upFile,
        ];
        $query = setInsert($set, 'user');
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
                          <div class="col-md-9 mb-2">
                            <label class="float-right" for="nm_user">Nama User</label>
                            <input type="text" name="nm_user" class="form-control" id="nm_user" placeholder="Nama User" value="<?= $isiVal['nm_user']; ?>" required>
                          </div>
                          <div class="col-md-3 mb-2">
                            <label class="float-right" for="akses_user">Akses User</label>
                            <select name="akses_user" id="akses_user" class="form-control custom-select" required>
                              <option value="admin" <?= cekSama($isiVal['akses_user'], 'admin'); ?>>Admin</option>
                              <option value="user" <?= cekSama($isiVal['akses_user'], 'user'); ?>>User</option>
                            </select>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-6 mb-2">
                            <label class="float-right" for="usrn">Username</label>
                            <input type="Username" name="usrn" class="form-control" id="usrn" placeholder="Username" value="<?= $isiVal['usrn']; ?>" required>
                          </div>
                          <div class="col-md-6 mb-2">
                            <label class="float-right" for="pss">Password</label>
                            <input type="text" name="pss" class="form-control" id="pss" placeholder="Password" value="<?= $isiVal['pss']; ?>" required>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item">
                        <div class="row w-100 mx-0">
                          <div class="col-md-12">
                            <label class="float-right" for="foto_user">Foto User</label>
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" name="foto_user" class="custom-file-input" id="foto_user">
                                <label class="custom-file-label" for="foto_user"><?= $isiVal['foto_user']; ?></label>
                              </div>
                            </div>
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
                      <div class="col-md-4 mb-2">
                        <select id="column_user" class="form-control custom-select">
                          <option value="1">Nama User</option>
                          <option value="0">Username</option>
                        </select>
                      </div>
                      <div class="col-md-8 mb-2">
                        <input type="text" class="form-control" placeholder="Cari Data" id="field_user">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="table_user" class="table table-bordered table-hover" style="min-width: 400px;">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Nama User</th>
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





    <div class="modal fade" id="delete-user">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus user</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus user ini?</h4>
            <input type="hidden" name="id_user" id="did_user">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-user" class="btn btn-outline-danger">Hapus</button>
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
      var table_user = $('#table_user').DataTable({
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
            "set_tables": "SELECT * FROM user WHERE id_user!='<?= $_SESSION['id_user'] ?>'",
            "query": true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle text-center",
          "data": "usrn",
          "width": "50px",
        }, {
          'className': "align-middle",
          "data": "nm_user",
        }, {
          'className': "align-middle text-center",
          "data": "id_user",
          "width": "50px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_user\').val(\'' + data + '\')" data-target="#delete-user"><i class="fa fa-trash-alt"></i></button><a href="' + host + 'user/' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
          }
        }],
      });
      $('#table_user_filter').hide();
      $('#field_user').keyup(function() {
        table_user.columns($('#column_user').val()).search(this.value).draw();
      });

    });
  </script>
</body>

</html>