<?php

require_once('phpmailer/PHPMailerAutoload.php');

$toemails = array();

$toemails[] = 	[
					'email' => 'sargilla@gmail.com',
					'name' => 'Santiago Argilla'
				];
// $toemails[] = 
// 				[
// 					'email' => '',
// 					'name' => ''
// 				];
				
$emailfrom = "prueba@papro.com.ar";

// Form Processing Messages
$message_success = 'Recibímos su correo, le contestaremos a la brevedad.';

// Add this only if you use reCaptcha with your Contact Forms
$recaptcha_secret = ''; // Your reCaptcha Secret

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "mail.papro.com.ar"; // SMTP a utilizar. Por ej. smtp.elserver.com
$mail->Username = "prueba@papro.com.ar"; // Correo completo a utilizar
$mail->Password = "probamesta"; // Contraseña
$mail->Port = 26; // Puerto a utilizar


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_POST['template-contactform-email'] != '' ) {

		$name = isset( $_POST['template-contactform-name'] ) ? $_POST['template-contactform-name'] : '';
		$email = isset( $_POST['template-contactform-email'] ) ? $_POST['template-contactform-email'] : '';
		$phone = isset( $_POST['template-contactform-phone'] ) ? $_POST['template-contactform-phone'] : '';
		$service = isset( $_POST['template-contactform-service'] ) ? $_POST['template-contactform-service'] : '';
		$subject = isset( $_POST['template-contactform-subject'] ) ? $_POST['template-contactform-subject'] : '';
		$message = isset( $_POST['template-contactform-message'] ) ? $_POST['template-contactform-message'] : '';

		$subject = isset($subject) ? $subject : 'Nuevo mensaje desde la página';

		$botcheck = $_POST['template-contactform-botcheck'];

		if( $botcheck == 'uvc2020' ) {

			$mail->SetFrom( $emailfrom , 'UVC Soluciones' );
			$mail->AddReplyTo( $email , $name );
			foreach( $toemails as $toemail ) {
				$mail->AddAddress( $toemail['email'] , $toemail['name'] );
			}
			$mail->Subject = $subject;

			$name = isset($name) ? "Nombre: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Telefono: $phone<br><br>" : '';
			$subject = isset($subject) ? "Asunto: $subject<br><br>" : '';
			$message = isset($message) ? "Mensaje: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Este mensaje lo envió: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $subject $message $referrer";

			// Runs only when File Field is present in the Contact Form
			if ( isset( $_FILES['template-contactform-file'] ) && $_FILES['template-contactform-file']['error'] == UPLOAD_ERR_OK ) {
				$mail->IsHTML(true);
				$mail->AddAttachment( $_FILES['template-contactform-file']['tmp_name'], $_FILES['template-contactform-file']['name'] );
			}

			// Runs only when reCaptcha is present in the Contact Form
			if( isset( $_POST['g-recaptcha-response'] ) ) {
				$recaptcha_response = $_POST['g-recaptcha-response'];
				$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ld68AgUAAAAAIsR4RLgWFgoO1PiS3AYdKLq517h" . $recaptcha_secret . "&response=" . $recaptcha_response );

				$g_response = json_decode( $response );

				if ( $g_response->success !== true ) {
					echo '{ "alert": "error", "message": "Codigo Incorrecto! Pruebe otra vez." }';
					die;
				}
			}

			// Uncomment the following Lines of Code if you want to Force reCaptcha Validation

			if( !isset( $_POST['g-recaptcha-response'] ) ) {
				echo '{ "alert": "error", "message": "No se registro el Captcha! Pruebe otra vez." }';
				die;
			}

			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

			if( $sendEmail == true ):
				echo '{ "alert": "success", "message": "' . $message_success . '" }';
			else:
				echo '{ "alert": "error", "message": "No pudimos enviar su mensaje. Pruebe mas tarde.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '" }';
			endif;
		} else {
			echo '{ "alert": "error", "message": "Bot <strong>Detected</strong>.! Clean yourself Botster.!" }';
		}
	} else {
		echo '{ "alert": "error", "message": "Por favor llene  <strong>todos</strong> los campos y pruebe nuevamente." }';
	}
} else {
	echo '{ "alert": "error", "message": "A ocurrido un error <strong>inesperado</strong>. Trate mas tarde." }';
}

?>
