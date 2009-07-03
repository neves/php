<?
/**
 * Utilizar a funcao abaixo para escrever qualquer informacao no html:
 * ao inves de utilizar: 
 * <?= $sua_variavel_aqui ?>
 * ou
 * <?php echo $sua_variavel_aqui ?>
 * utilize sempre da forma abaixo:
 * <?=h( $sua_variavel_aqui )?>
 * para testar, remove a funca h do html abaixo e digite o seguinte texto dentro do textarea:
 * </textarea>FORA DO TEXTAREA
 * Sem a funcao h, ira ocorrer um html injection, onde a tag </textarea> ira fechar o textarea,
 * e tudo que for digitado depois ficara fora da tag, permitindo ser inserido ate javascript!
 */
function h($valor)
{
	if (get_magic_quotes_gpc()) $valor = stripslashes($valor);
	return htmlspecialchars($valor);
}

// EXEMPLO
$mensagem = @$_REQUEST["mensagem"];
?>

<form method="post">
	<p>Digite codigo html no campo abaixo:</p>
	<textarea name="mensagem" style="width: 400px; height: 300px"><?=h( $mensagem )?></textarea>
	<input type="submit" name="enviar" />
</form>