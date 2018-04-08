	<?php
			$to = 'daniyulianto55@gmail.com';
            $email_from = "no-replay@rsudef.batam.go.id";

            $full_name = 'RSUD Embung Fatimah';
            $from_mail = $full_name.'<'.$email_from.'>';



            $subject = "Selamat Pendaftaran Pasien Anda Berhasil";
            $message = "";
            $message .= '
                    <p>Selamat sdr/i '.@$_SESSION['daftar'][2].' untuk pendaftaran Pasien Baru. <br/>
                    Untuk pendaftaran ke poli gunakan user dan password dibawah ini :<br/><br/>
                    User Name (No.RM) :'.@$_SESSION['daftar'][0].'<br/>
                    password (No.KTP) :'.@$_SESSION['daftar'][1].'<br/><br/>
                    untuk login silahkan kunjungi web site : http://rsudef.batam.go.id <br/>
                    <br/>
                    <strong>Harap tunjukan bukti ini kepada petugas pendaftaran kami, mohon tidak membalas email ini. Terima kasih  </strong><br/>
            ';
            $from = $from_mail;

            $headers = "" .
                       "Reply-To:" . $from . "\r\n" .
                       "From:" . $from . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
            mail($to,$subject,$message,$headers);
    ?>