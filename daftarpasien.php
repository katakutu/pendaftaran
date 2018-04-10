<?php 
    require_once('config.php');
    require 'cekdatadisduk.php';
    ob_start();
    session_start();

?>
﻿
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $dataSettings['nama_instansi']; ?></title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="css/roboto.css" rel="stylesheet">

    <!-- Material Icon Css -->
    <link href="css/material-icon.css" rel="stylesheet">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Jquery UI Css -->
    <link href="plugins/jquery-ui/jquery-ui.css" rel="stylesheet">
        
    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/theme-blue.css" rel="stylesheet" />
</head>

<body class="login-page">
    <div class="login-box" style="margin: 20px;">
        <div class="logo">
            <a href="index.php"><?php echo $dataSettings['nama_instansi']; ?></a>
            <small><?php echo $dataSettings['alamat_instansi']; ?> - <?php echo $dataSettings['kabupaten']; ?></small>
        </div>
    </div>

    <div class="card">         
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg align-center"><h4>Informasi Data Pasien</h4></div>
                        <?php
                       //$get_rm = fetch_array(query("SELECT max(no_rkm_medis) FROM pasien"));
                            $get_rm = query("SELECT DISTINCT * FROM set_no_rkm_medis");
                            while ($row = fetch_array($get_rm)) {
                                            $kdrm = $row['no_rkm_medis']; 
                                        }
                            $lastRM = substr($kdrm , 0, 6);
                            $no_rm_next = 'WEB';
                            $no_rm_next .= sprintf('%06s', ($lastRM + 1)); 

                            $daftarpasien = getLocalData(@$_SESSION['daftar'][0]);
                        ?>
                    <h3 class="align-center">No. RM : <?php echo $no_rm_next; ?></h3>
                    <div class="msg">
                        NIK           : <?php echo $daftarpasien['NIK']; ?></br>
                        NAMA          : <?php echo $daftarpasien['NAMA_LGKP']; ?></br>
                        JENIS KELAMIN : <?php echo $daftarpasien['JENIS_KLMIN']; ?></br>
	                    TMP/TGL LAHIR : <?php echo $daftarpasien['TMPT_LHR'].'/'.$daftarpasien['TGL_LHR'];?></br>
	                    NAMA IBU      : <?php echo $daftarpasien['NAMA_LGKP_IBU'];?></br>
	                    GOL. DARAH    : <?php echo $daftarpasien['GOL_DARAH'];?></br>
	                    PEKERJAAN     : <?php echo $daftarpasien['JENIS_PKRJN'];?></br>
	                    PENDIDIKAN    : <?php echo $daftarpasien['PDDK_AKH']; ?></br>
	                    STATUS        : <?php echo $daftarpasien['STATUS_KAWIN'];?>
	                    AGAMA         : <?php echo $daftarpasien['AGAMA']; ?></br>
	                    ALAMAT        : <?php echo $daftarpasien['ALAMAT'];?></br>
	                    NO RW.        : <?php echo $daftarpasien['NO_RW'];?>
	                    NO RT.        : <?php echo $daftarpasien['NO_RT'];?></br>
	                    KELURAHAN 	  : <?php echo $daftarpasien['KEL_NAME'];?></br>
	                    KECAMATAN     : <?php echo $daftarpasien['KEC_NAME'];?></br>
	                    KABUPATEN     : <?php echo $daftarpasien['KAB_NAME'];?></br>
	                    NO. TELP      : <?php echo @$_SESSION['daftar'][1]; ?></br>
	                    EMAIL         : <?php echo @$_SESSION['daftar'][2]; ?></br> 
	                    </br>
                    </div>
                    
                    <input class="btn btn-block btn-lg bg-blue waves-effect" type="submit" name="cmdproses" value="DAFTARKAN SAYA">
                    <input class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="cmdcancel" value="DATA SALAH">
                    <!-- <div class="m-t-25 m-b--5 align-center">
                        <a href="login.php">Sudah terdaftar sebagai pasien?</a>
                    </div> -->
                </form>
                
                <?php 

                	if(@$_POST['cmdcancel']) {
                		redirect ('Validasinik.php');
                	} else {
                    	if(@$_POST['cmdproses']) { 
                    		$kd_kel=cekkelurahan($daftarpasien['KEL_NAME']);
						    $kd_kab =cekkabupaten($daftarpasien['KAB_NAME']);
						    $kd_kec=cekkecamatan($daftarpasien['KEC_NAME']);
						    if($daftarpasien['JENIS_KLMIN']=='LAKI-LAKI'){
			                    $jk='L';
			                } else{
			                    $jk='P';
			                }
			                $notlp =@$_SESSION['daftar'][1];
			                $email =@$_SESSION['daftar'][2];
						    query("START TRANSACTION");
						    $get_rm = query("SELECT DISTINCT * FROM set_no_rkm_medis");
						    while ($row = fetch_array($get_rm)) {
						                $kdrm = $row['no_rkm_medis']; 
						            }
						    $lastRM = substr($kdrm , 0, 6);
						    $no_rm_next = 'WEB';
						    $no_rm_next .= sprintf('%06s', ($lastRM + 1));
						    
							$insert = query("INSERT INTO pasien VALUES(
						        '$no_rm_next', 
						        '{$daftarpasien['NAMA_LGKP']}', 
						        '{$daftarpasien['NIK']}', 
						        '$jk', 
						        '{$daftarpasien['TMPT_LHR']}', 
						        '{$daftarpasien['TGL_LHR']}', 
						        '{$daftarpasien['NAMA_LGKP_IBU']}', 
						        '{$daftarpasien['ALAMAT']}', 
						        '{$daftarpasien['GOL_DARAH']}', 
						        '{$daftarpasien['JENIS_PKRJN']}', 
						        '{$daftarpasien['STATUS_KAWIN']}', 
						        '{$daftarpasien['AGAMA']}', 
						        '$date', 
						        '$notlp', 
						        '-', 
						        '-', 
						        '-', 
						        '-', 
						        'A01', 
						        '-', 
						        '$kd_kel', 
						        '$kd_kec', 
						        '$kd_kab', 
						        '-', 
						        '-', 
						        '-', 
						        '-', 
						        '-',
						        '$email'
						    )"); 

						    $RmUpdate = query("UPDATE set_no_rkm_medis SET no_rkm_medis=no_rkm_medis+1");
						    if($get_rm and $insert and $RmUpdate){
						        query("COMMIT");
						    } else {
						        query("ROLLBACK");
						    }


				            $to = @$_SESSION['daftar'][2];
				            $email_from = "no-replay@rsudef.batam.go.id";

				            $full_name = 'RSUD Embung Fatimah';
				            $from_mail = $full_name.'<'.$email_from.'>';



				            $subject = "Selamat Pendaftaran Pasien Anda Berhasil";
				            $message = "";
				            $message .= '
				                    <p>Selamat sdr/i '.$daftarpasien['NAMA_LGKP'].' untuk pendaftaran Pasien Baru. <br/>
				                    Untuk pendaftaran ke poli gunakan user dan password dibawah ini :<br/><br/>
				                    User Name (No.RM) :'.$no_rm_next.'<br/>
				                    password (No.KTP) :'.$daftarpasien['NIK'].'<br/><br/>
				                    untuk login silahkan kunjungi web site : http://rsudef.batam.go.id <br/>
				                    <br/>
				                    <strong>Terima kasih</strong><br/>
				            ';
				            $from = $from_mail;

				            $headers = "" .
				                       "Reply-To:" . $from . "\r\n" .
				                       "From:" . $from . "\r\n" .
				                       "X-Mailer: PHP/" . phpversion();
				            $headers .= 'MIME-Version: 1.0' . "\r\n";
				            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
				            mail($to,$subject,$message,$headers);

				            $Pesan='User Name (No.RM) :'.$no_rm_next."\n";
				            $Pesan.='password (No.KTP) :'.$daftarpasien['NIK'];

				            kirimsmszen(@$_SESSION['daftar'][1],$Pesan);

				            //kirimsmsnusa(@$_SESSION['daftar'][4],$Pesan);
				        ?>
				        <div class="card">         
				            <div class="body text-center">
				            <h5>Pendaftaran pasien baru telah sukses </h5><br> 
				            <p>Silahkan cek email anda SMS untuk Login dan pendaftaran poli</p>
				            <p>Silahkan klik tombol login dibawah</p><br>
				            <a href="login.php"><button type="button" class="btn bg-green btn-lg waves-effect">LOGIN</button></a>
				            </div>
				        </div>
				 <?php
				 	}
                 } // END PROSES
                 ?>

            </div>
        </div>
    
</body> <!-- end body -->
</html>


    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/jquery-ui/jquery-ui.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="plugins/momentjs/moment.js"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>



