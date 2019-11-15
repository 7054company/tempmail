<?php
@session_name('xTempMail');
@session_start();
$pdo = new PDO("sqlite:xtempmail.db");

if (isset($_GET['change'])) {
	$login = urlencode($_POST['login']);
	$domain = $_POST['domain'];
	$ChangeEmail = $login."@".$domain;
	$_SESSION['email'] = $ChangeEmail;
	setcookie('email', base64_encode($ChangeEmail), time() + (86400 * 30), "/");
}

if (isset($_GET['del'])) {
	$del = $_GET['del'];
	$pdo->query("DELETE FROM `emails` WHERE `id`='$del'");
}

if (isset($_GET['new'])) {
	$create_new = true;
	include 'create.php';

}

if (isset($_GET['old'])) { 
    if ((isset($_COOKIE['email']) && $_COOKIE['email'] != '')) {
    	$_SESSION['email'] = base64_decode($_COOKIE['email']);
	}else{
		// include 'create.php';
	}
}

$ThisEmail = $_SESSION['email'];

if ($ThisEmail == "") {
	include 'create.php';
}


$counter = $pdo->query("SELECT count(id) FROM `emails` WHERE `to` LIKE '%$ThisEmail%'")->fetchColumn();
$emails = $pdo->query("SELECT * FROM `emails` WHERE `to` LIKE '%$ThisEmail%'")->fetchAll(PDO::FETCH_ASSOC);


$allowed_lang = array('en','fr','tr','ar');
if ((isset($_COOKIE['language']) && $_COOKIE['language'] != '')) {
	if (in_array($_COOKIE['language'], $allowed_lang)) {
		$xlang = $_COOKIE['language'];
	}else{ $xlang = "en"; }
}else{ $xlang = "en"; }

$lang = array(
	'fr' => array(
		'Qu\'est-ce que le courrier électronique temporaire jetable?',
		'Expéditeur',
		'Sujet',
		'Vue',
		'E-mail disponible',
		'Est un service qui permet de recevoir un courrier électronique à une adresse temporaire qui s\'est autodestructible après un certain temps. Il est également connu sous le nom de: tempmail, 10minutemail, e-mail jeté, faux-mail ou corbeille. Beaucoup de forums, de propriétaires Wi-Fi, de sites Web et de blogs demandent aux visiteurs de s\'inscrire avant de pouvoir afficher le contenu, poster des commentaires ou télécharger quelque chose. Temp-Mail - est le service de courrier électronique le plus avancé qui vous aide à éviter les spams et à rester en sécurité.'
	),
	'en' => $lang = array(
		'What is Disposable Temporary E-mail?',
		'Sender',
		'Subject',
		'View',
		'Disposable email',
		'is a service that allows to receive email at a temporary address that self-destructed after a certain time elapses. It is also known by names like : tempmail, 10minutemail, throwaway email, fake-mail or trash-mail. Many forums, Wi-Fi owners, websites and blogs ask visitors to register before they can view content, post comments or download something. Temp-Mail - is most advanced throwaway email service that helps you avoid spam and stay safe.',
	),
	'tr' => $lang = array(
		'Tek Kullanımlık Geçici E-posta nedir?',
		'Gönderici',
		'konu',
		'Görünüm',
		'Tek kullanımlık e-posta',
		'Belirli bir süre geçtikten sonra kendi kendine tahrip edilen geçici bir adrese e-postalar göndermenize izin veren bir hizmettir. Aynı zamanda adlarla bilinir: tempmail, 10 dakikalık e-posta, atılmış e-posta, sahte mail veya çöp postası. Birçok forum, Wi-Fi sahibi, web sitesi ve blog, ziyaretçilerin içeriği görüntülemesinden, yorumları gönderdikten veya bir şey indirmeden önce kayıt yapmalarını ister. Temp-Mail - spam önlemenize ve güvende kalmanıza yardımcı olan en gelişmiş atılmış e-posta servisidir.',
	),
	'ar' => $lang = array(
		'ما هو البريد الإلكتروني المؤقت المتاح؟',
		'مرسل',
		'موضوع',
		'معاينة',
		'البريد الإلكتروني القابل للتصرف',
		'هي الخدمة التي تسمح لتلقي البريد الإلكتروني في عنوان مؤقت أن تدمر ذاتيا بعد انقضاء وقت معين. ومن المعروف أيضا من قبل أسماء مثل: تيمبايل، 10minutemail، البريد الإلكتروني رمي، وهمية البريد الإلكتروني أو المهملات البريد. العديد من المنتديات، وأصحاب واي فاي، والمواقع والمدونات تطلب من الزوار للتسجيل قبل أن يتمكنوا من عرض المحتوى، إضافة تعليقات أو تحميل شيء. تيمب-ميل - هو خدمة البريد الإلكتروني الأكثر تطرفا التي تساعدك على تجنب البريد المزعج والبقاء آمنة.',
	)
);

?>
<div id="mail_list" data-counter="<?=$counter?>" data-email="<?=$ThisEmail?>">
	<table>
		<tr>
			<td><?=$lang[$xlang][1]?></td>
			<td><?=$lang[$xlang][2]?></td>
			<td><?=$lang[$xlang][3]?></td>
		</tr>
		<? foreach ($emails as $k => $m) { ?>
			<tr>
				<td><div><?=htmlentities($m['from'])?></div></td>
				<td><?=htmlentities($m['subject'])?></td>
				<td><div class="view_msg" data-message-id="<?=$m['id']?>"></div></td>
			</tr>
		<? } ?>
	</table>
</div>
<div id="info">
	<div id="info_title"><?=$lang[$xlang][0]?></div>
	<div id="info_text"><b><?=$lang[$xlang][4]?></b> - <?=$lang[$xlang][5]?></div>
</div>
