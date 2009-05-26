<?
/**
 * Utilizar a funcao abaixo para escrever qualquer informacao no html:
 * ao inves de utilizar: 
 * <?= $sua_variavel_aqui ?>
 * ou
 * <?php echo $sua_variavel_aqui ?>
 * utilize sempre da forma abaixo:
 * <?=h( $sua_variavel_aqui )?>
 * para testar, remove a funca h na linha 25 e digite o seguinte texto dentro do textarea:
 * </textarea>FORA DO TEXTAREA
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