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
                    <div class="msg align-center"><h4>Validasi Data Pasien</h4></div>
                        <?php
                       //$get_rm = fetch_array(query("SELECT max(no_rkm_medis) FROM pasien"));
                            $get_rm = query("SELECT DISTINCT * FROM set_no_rkm_medis");
                            while ($row = fetch_array($get_rm)) {
                                            $kdrm = $row['no_rkm_medis']; 
                                        }
                            $lastRM = substr($kdrm , 0, 6);
                            $no_rm_next = 'WEB';
                            $no_rm_next .= sprintf('%06s', ($lastRM + 1)); 
                            
                        ?>
                    <h3 class="align-center">No. RM : <?php echo $no_rm_next; ?></h3>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="no_ktp" placeholder="Nomor Induk Kependudukan" minlength="16" maxlength="16" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="color:transparent;">done</i>
                        </span>
                        <div class="form-line">
                            <input type="hidden" id="tmpt_lahir" name="tmpt_lahir">
                            <input type="text" id="tmpt_lahir" class="form-control" name="tmpt_lahir" placeholder="Tempat Lahir" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">phone</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="no_tlp" placeholder="Nomor Telepon" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="email" placeholder="Email Address" required>
                        </div>
                    </div>

                    <!--<button class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="cmdproses">
                        VALIDASI DATA
                    </button>-->
                    <input class="btn btn-block btn-lg bg-pink waves-effect" type="submit" name="cmdproses" value="VALIDASI DATA">
                    <!-- <?php $no_rm_pass=$no_rm_next; ?> -->
                    <div class="m-t-25 m-b--5 align-center">
                        <a href="login.php">Sudah terdaftar sebagai pasien?</a>
                    </div>
                </form>
                
                <?php 

                    if(@$_POST['cmdproses']) { 

                        if(empty($_POST['no_ktp'])) {
                            $errors[] = 'Kolom No KTP tidak boleh kosong';
                        }

                        if(nik_exits($_POST['no_ktp'])) {
                            $errors[] = 'No KTP telah terdaftar';
                        }

                        if(empty($_POST['no_tlp'])) {
                            $errors[] = 'Kolom no handphone tidak boleh kosong';
                        }

                        if(empty($_POST['tmpt_lahir'])) {
                            $errors[] = 'Kolom nama tempat lahir tidak boleh kosong';
                        }


                        if(empty($_POST['email'])) {
                        $errors[] = 'Kolom email tidak boleh kosong';
                        } else {
                            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                              $errors[] = 'Format email salah'; 
                            }

                        }

                        if(!empty($errors)) {
                            foreach($errors as $error) {
                                echo validation_errors($error);
                            }
                        } else {

                            if(!cekLocalData($_POST['no_ktp'])){
                                if(!getdisdukdata($_POST['no_ktp'])){
                                    $errors[] = 'No KTP tidak ada didatabase Dinas Pendudukan';       
                                } else {
                                    $getLocalData = getLocalData($_POST['no_ktp']);
                                    if (strtoupper($_POST['tmpt_lahir']) != $getLocalData['TMPT_LHR']){
                                        $errors[] = 'Data tidak sesusai'; 
                                        foreach($errors as $error) {
                                            echo validation_errors($error);
                                        }   
                                    } else {
                                        @$_SESSION['daftar'][0] =$getLocalData['NIK'];
                                        @$_SESSION['daftar'][1] =$_POST['no_tlp'];
                                        @$_SESSION['daftar'][2] =$_POST['email'];
                                        redirect ('daftarpasien.php');
                                    }
                                }
                            } else {
                                $getLocalData = getLocalData($_POST['no_ktp']);
                                if (strtoupper($_POST['tmpt_lahir']) != $getLocalData['TMPT_LHR']){
                                    $errors[] = 'Data tidak sesusai';
                                    foreach($errors as $error) {
                                        echo validation_errors($error);
                                    }    
                                } else {
                                    @$_SESSION['daftar'][0] =$getLocalData['NIK'];
                                    @$_SESSION['daftar'][1] =$_POST['no_tlp'];
                                    @$_SESSION['daftar'][2] =$_POST['email'];
                                    redirect ('daftarpasien.php');
                                }
                            }
                        }
                    }

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

    <script>

        $(document).ready(function() {

            //Textare auto growth
            autosize($('textarea.auto-growth'));

            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            });

        } );
    </script>
