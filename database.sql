/*Table structure for table `barang` */



DROP TABLE IF EXISTS `barang`;



CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nm_barang` varchar(255) DEFAULT NULL,
  `foto_barang` text,
  `id_kategori` int(11) DEFAULT NULL,
  `id_ss` int(11) DEFAULT NULL,
  `id_sj` int(11) DEFAULT NULL,
  `p_smj` int(11) DEFAULT NULL,
  `p_keuntungan` int(11) DEFAULT NULL,
  `p_diskon` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



/*Data for the table `barang` */



insert  into `barang`(`id_barang`,`nm_barang`,`foto_barang`,`id_kategori`,`id_ss`,`id_sj`,`p_smj`,`p_keuntungan`,`p_diskon`) values (1,'xxxxx',NULL,2,4,3,100,10,0),(2,'yyyy',NULL,3,2,3,10,10,0);



/*Table structure for table `kategori` */



DROP TABLE IF EXISTS `kategori`;



CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nm_kategori` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;



/*Data for the table `kategori` */



insert  into `kategori`(`id_kategori`,`nm_kategori`) values (2,'Sembako'),(3,'makanan Ringan');



/*Table structure for table `penjualan` */



DROP TABLE IF EXISTS `penjualan`;



CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` int(11) DEFAULT NULL,
  `tgl_penjualan` date DEFAULT NULL,
  `qty_beli` bigint(20) DEFAULT NULL,
  `hrg_barang` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_penjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;



/*Data for the table `penjualan` */



insert  into `penjualan`(`id_penjualan`,`id_barang`,`tgl_penjualan`,`qty_beli`,`hrg_barang`) values (7,1,'2021-07-08',100,110000),(8,2,'2021-08-01',10,110000),(9,2,'2021-08-02',200,2200000),(10,2,'2021-08-01',100,1100000);



/*Table structure for table `pt` */



DROP TABLE IF EXISTS `pt`;



CREATE TABLE `pt` (
  `id_pt` int(11) NOT NULL AUTO_INCREMENT,
  `nm_pt` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_pt`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;



/*Data for the table `pt` */



insert  into `pt`(`id_pt`,`nm_pt`) values (2,'PT ABC'),(3,'PT XXX');



/*Table structure for table `satuan` */



DROP TABLE IF EXISTS `satuan`;



CREATE TABLE `satuan` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `nm_satuan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;



/*Data for the table `satuan` */



insert  into `satuan`(`id_satuan`,`nm_satuan`) values (2,'Kilo'),(3,'Pcs'),(4,'Dus');



/*Table structure for table `stok` */



DROP TABLE IF EXISTS `stok`;



CREATE TABLE `stok` (
  `id_stok` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_masuk` date DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_pt` int(11) DEFAULT NULL,
  `hrg_barang` bigint(20) DEFAULT NULL,
  `id_ss` int(11) DEFAULT NULL,
  `id_sj` int(11) DEFAULT NULL,
  `j_masuk` bigint(20) DEFAULT NULL,
  `p_smj` bigint(11) DEFAULT NULL,
  `j_keluar` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_stok`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;



/*Data for the table `stok` */



insert  into `stok`(`id_stok`,`tgl_masuk`,`id_barang`,`id_pt`,`hrg_barang`,`id_ss`,`id_sj`,`j_masuk`,`p_smj`,`j_keluar`) values (1,'2021-08-01',1,2,1000000,4,3,10,100,100),(3,'2021-08-01',2,2,10000000,2,3,100,10,310);



/*Table structure for table `user` */



DROP TABLE IF EXISTS `user`;



CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nm_user` varchar(255) DEFAULT NULL,
  `foto_user` text,
  `usrn` varchar(255) DEFAULT NULL,
  `pss` varchar(255) DEFAULT NULL,
  `akses_user` enum('admin','kasir','pimpinan') DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;



/*Data for the table `user` */



insert  into `user`(`id_user`,`nm_user`,`foto_user`,`usrn`,`pss`,`akses_user`) values (1,'admin','admin - 789290_penguin_512x512.png','admin','admin','admin'),(2,'user',NULL,'user','user','kasir'),(3,'pimpinan',NULL,'pimpinan','pimpinan','pimpinan');



/*Table structure for table `login` */



DROP TABLE IF EXISTS `login`;



/*!50001 DROP VIEW IF EXISTS `login` */;

/*!50001 DROP TABLE IF EXISTS `login` */;


/*!50001 CREATE TABLE  `login`(
 `id_user` int(11) ,
 `username` varchar(255) ,
 `password` varchar(32) ,
 `akses` enum('admin','kasir','pimpinan') ,
 `nm_user` varchar(255) ,
 `foto_user` text 
)*/;


/*View structure for view login */



/*!50001 DROP TABLE IF EXISTS `login` */;

/*!50001 DROP VIEW IF EXISTS `login` */;



/*!50001 CREATE VIEW `login` AS (select `user`.`id_user` AS `id_user`,`user`.`usrn` AS `username`,md5(`user`.`pss`) AS `password`,`user`.`akses_user` AS `akses`,`user`.`nm_user` AS `nm_user`,`user`.`foto_user` AS `foto_user` from `user`) */;

