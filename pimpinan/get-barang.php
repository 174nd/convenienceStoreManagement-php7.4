<?php
$backurl = '../';
require_once($backurl . 'pimpinan/config/settings.php');

if ($_POST['set'] == 'get_barang') {
  $sql = mysqli_query($conn, "SELECT *,(SELECT nm_satuan FROM satuan WHERE satuan.id_satuan=barang.id_ss) AS satuan_stok, 
  (SELECT nm_satuan FROM satuan WHERE satuan.id_satuan=barang.id_sj) AS satuan_jual,COALESCE((SELECT SUM(stok.hrg_barang / (stok.j_masuk*stok.p_smj) * ((stok.j_masuk * stok.p_smj) - stok.j_keluar)) FROM stok WHERE (stok.j_masuk * stok.p_smj) - stok.j_keluar>0 AND stok.id_barang='$_POST[id_barang]'),0) AS sisa_uang,
  COALESCE((SELECT SUM((stok.j_masuk * stok.p_smj) - stok.j_keluar) FROM stok WHERE (stok.j_masuk * stok.p_smj) - stok.j_keluar>0 AND stok.id_barang='$_POST[id_barang]'),0) AS sisa_stok,
  COALESCE((SELECT CEIL(SUM(stok.hrg_barang / (stok.j_masuk*stok.p_smj) * ((stok.j_masuk * stok.p_smj) - stok.j_keluar)) / SUM((stok.j_masuk * stok.p_smj) - stok.j_keluar) / 100 * (100 + barang.p_keuntungan) / 100) * 100 FROM stok JOIN barang ON stok.id_barang=barang.id_barang WHERE (stok.j_masuk * stok.p_smj) - stok.j_keluar>0 AND stok.id_barang='$_POST[id_barang]'),0) AS harga_satuan
  FROM barang JOIN kategori WHERE barang.id_kategori=kategori.id_kategori AND barang.id_barang='$_POST[id_barang]'");
  if (mysqli_num_rows($sql) > 0) {
    $hasil = mysqli_fetch_assoc($sql);

    $data_stok = [];
    $sql = mysqli_query($conn, "SELECT * FROM stok JOIN pt ON stok.id_pt=pt.id_pt WHERE stok.id_barang='$_POST[id_barang]'");
    while ($data = mysqli_fetch_assoc($sql)) $data_stok[] = $data;


    $hasil['data_stok'] = $data_stok;
    $hasil['status'] = 'done';
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'save_penjualan') {
  $sql = mysqli_query($conn, "SELECT *,COALESCE((SELECT CEIL(SUM(stok.hrg_barang / (stok.j_masuk*stok.p_smj) * ((stok.j_masuk * stok.p_smj) - stok.j_keluar)) / SUM((stok.j_masuk * stok.p_smj) - stok.j_keluar) / 100 * (100 + barang.p_keuntungan) / 100 * (100 - barang.p_diskon) / 100) * 100 FROM stok JOIN barang ON stok.id_barang=barang.id_barang WHERE (stok.j_masuk * stok.p_smj) - stok.j_keluar>0 AND stok.id_barang='$_POST[id_barang]'),0) AS harga_diskon FROM barang WHERE barang.id_barang='$_POST[id_barang]'");
  if (mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);
    $sql = mysqli_query($conn, "SELECT * FROM stok WHERE j_keluar<j_masuk*p_smj AND stok.id_barang='$_POST[id_barang]' ORDER BY tgl_masuk ASC");
    $jumlah_beli = $_POST['jumlah'];
    while ($hasil = mysqli_fetch_assoc($sql)) {
      $sisa = $hasil['j_masuk'] * $hasil['p_smj'] - $hasil['j_keluar'];
      setUpdate(['j_keluar' => ($jumlah_beli >= $sisa) ? $hasil['j_masuk'] * $hasil['p_smj'] : $hasil['j_keluar'] + $jumlah_beli], 'stok', ['id_stok' => $hasil['id_stok']]);
      $jumlah_beli -= $sisa;
      if ($jumlah_beli <= 0) break;
    }


    $hasil['status'] = (setInsert([
      'id_barang'     => $_POST['id_barang'],
      'tgl_penjualan' => $_POST['tgl_penjualan'],
      'qty_beli'      => $_POST['jumlah'],
      'hrg_barang'    => $data['harga_diskon'] * $_POST['jumlah']
    ], 'penjualan')) ? 'done' : 'none';
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'get_penjualan') {

  $data_penjualan = [];
  $sql = mysqli_query($conn, "SELECT * FROM penjualan JOIN barang ON penjualan.id_barang=barang.id_barang WHERE penjualan.tgl_penjualan='$_POST[tgl_penjualan]'");
  while ($data = mysqli_fetch_assoc($sql)) $data_penjualan[] = $data;


  $hasil['data_penjualan'] = $data_penjualan;
  $hasil['status'] = 'done';
} else if ($_POST['set'] == 'delete_penjualan') {
  $sql = mysqli_query($conn, "SELECT * FROM penjualan WHERE id_penjualan='$_POST[id_penjualan]'");
  if (mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);
    $sql = mysqli_query($conn, "SELECT * FROM stok WHERE j_keluar!=0 AND stok.id_barang='$data[id_barang]' ORDER BY tgl_masuk DESC");
    $jumlah_beli = (int) $data['qty_beli'];
    while ($hasil = mysqli_fetch_assoc($sql)) {
      $sisa = (int) $hasil['j_keluar'];
      setUpdate(['j_keluar' => ($jumlah_beli >= $sisa) ? "0" : $sisa - $jumlah_beli], 'stok', ['id_stok' => $hasil['id_stok']]);
      $jumlah_beli -= $sisa;
      if ($jumlah_beli <= 0) break;
    }


    $hasil['status'] = (setDelete('penjualan', ['id_penjualan' => $_POST['id_penjualan']])) ? 'done' : 'none';
    $data_penjualan = [];
    $sql = mysqli_query($conn, "SELECT * FROM penjualan JOIN barang ON penjualan.id_barang=barang.id_barang WHERE penjualan.tgl_penjualan='$data[tgl_penjualan]'");
    while ($data = mysqli_fetch_assoc($sql)) $data_penjualan[] = $data;
    $hasil['data_penjualan'] = $data_penjualan;
  } else {
    $hasil['status'] = 'none';
  }
} else {
  $hasil['status'] = 'none';
}

echo json_encode($hasil);
