<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title><?= isset($TITULO) ? $TITULO : $TITULO_PADRAO ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="">
<meta name="description" content="" />
<meta name="keywords" content="" />

<link rel="stylesheet" href="css/layout.css" />

<script wsrc="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="js/global.js"></script>

</head>
<body>

<div id="site">
	<table id="topo" class="layout">
		<tr>
			<td>LOGO</td>
			<td>BANNER</td>
		</tr>
	</table>

	<table class="layout">
		<tr>
			<td id="menu">
				<h4>MENU</h4>
				<ul>
					<li><a href="?">HOME</a></li>
					<li>
						<a href="?pag=empresa">EMPRESA</a>
						<ul>
							<li><a href="?pag=empresa/visao">VISÃO</a></li>
							<li><a href="?pag=empresa/inexistente">INEXISTENTE</a></li>
						</ul>
					</li>
					<li><a href="?pag=contato">CONTATO</a></li>
				</ul>
			</td>
			<td id="conteudo"><?= $TPL ?></td>
			<td id="lateral">
			<ul>
			<? foreach($banners as $banner): ?>
				<li><?= $banner ?></li>
			<? endforeach ?>
			</ul>
			</td>
		</tr>
	</table>

	<div id="rodape">
		RODAPÉ
	</div>
</div>

</body>
</html>