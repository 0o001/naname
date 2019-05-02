<?php
require_once 'functions.php';
require_once 'classes/AnswerType.class.php';

//Hataları gösterme
error_reporting(0);

//Veriyi JSON formatında çıkart
header('Content-Type: text/json; charset=utf-8');

if(isset($_GET['request'])) {

	//MySQL bağlantısı
	try {

		$db = new PDO('mysql:host=localhost;dbname=talks;charset=utf8', 'root', '');

	} catch (PDOException $e) {

		die();

	}

	if($db) {

		$answer = null;

		$message = mb_strtolower(trim(str_replace(['!', '?', ',', '.', '\'','"'], '', $_GET['request'])));

		$query = $db->prepare("SELECT answer FROM messages WHERE type =:type AND message =:message");
		$query->execute(['type' => AnswerType::FULL_WORD, 'message' => $message]);

		$result = $query->fetch();

		if($result) {
			
			$answer = $result['answer'];

		} else {

			$query = $db->prepare("SELECT answer FROM messages WHERE type =:type AND RIGHT(:message, LENGTH(message)) = message");
			$query->execute(['type' => AnswerType::ADDITIONAL_WORD, 'message' => $message]);

			$result = $query->fetch();
			
			if($result) {
			
				$answer = $result['answer'];
	
			} else {

				//örn. merhaba nasılsın (string) => ['merhaba', 'nasılsın'] (array) => 'merhaba','nasılsın' (string)
				$splitMessage = '\'' . implode('\',\'', explode(' ', $message)) . '\'';

				$query = $db->prepare("SELECT answer FROM messages WHERE type =:type AND message IN($splitMessage)");
				$query->execute(['type' => AnswerType::FINDING_WORD]);
				
				$results = $query->fetchAll();
				
				if($results) {

					foreach ($results as $result) {

						$answer .= $result['answer'] . ' ';

					}

				} else {

					$longMessages = [ 'Sence yazdığın biraz uzun olmadı mı', 'Bu yazi sence de biraz uzun değil mi?',
									  'Destan yazsaydın? Belki daha kısa olurdu', 'Çok uzun okumaya üşendim :D',
									  'Yalnız hatırlatayım ben bir botum bu kadar yazıp ne yapacaksın :D',
									  'Çok uzattın ha (mesaj olarak :D )', 'Çok uzun bir yazı...' ];

					$shortMessages = [ 'Sence yazdığın biraz uzun olmadı mı :D',
									   $message, 'Vee?', 'Evet yavaş yavaş yazmayı da öğreneceksin sanırım',
									   'Yani?', 'Sana da ' . $message . ' tövbe tövbe manyak mıdır nedir...',
									   'Bence de ' . $message ];

					$notFoundMessages = [ 'Seni anlayamıyorum', 'Ne dediğini anlamadım', 'Bir daha söyler misin?',
										  'Düzgün yazarsan olur mu?', 'Anlamıyorum', 'Dediklerinden bir gram anlamadım',
										  'Pardon da dediklerini anlamadım', 'Üzgünüm seni anlamadım', 'Özür dilerim seni anlamadım',
										  'Anlamadım', 'Pardon... Anlamadım' ];

					if(strlen($message) >= 100) {
						
						$answer = $longMessages[ array_rand($longMessages) ];
						
					} else if(strlen($message) <= 1) {

						$answer = $shortMessages[ array_rand($shortMessages) ];

					} else {

						$answer = $notFoundMessages[ array_rand($notFoundMessages) ];

					}

				}

			}

		}
		
		//İlk harfi büyüt
		$answer = trim(ucfirst($answer));
		$json	= ['request' => $message, 'response' => $answer];

		echo json_encode($json, JSON_PRETTY_PRINT);

	} else {

		//MySQL bağlantısı kurulamazsa...
		$json = ['error' => 'Baglanti kurulamadi'];
		
		echo json_encode($json, JSON_PRETTY_PRINT);

	}

	//MySQL bağlantısını kapat
	$db = null;
	
}

?>