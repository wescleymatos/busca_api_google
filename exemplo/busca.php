<?php

$keywords = '';
$pagina = (isset($_GET['p'])) ? (int)$_GET['p'] : 1;

$gs = new googleSearchAPI();
//$gs->busca($keywords, $pagina); // Busca normal
$gs->busca($keywords, $pagina, 'site da pesquisa'); // Busca em um site específico

$total = $gs->resultadoTotal();

echo "Total estimado de resultados: " . $total;
echo "<br />";
echo "<h2>Pagina: " . $gs->pagina . "</h2>";

foreach ($gs->resultadoSites() as $item) {
	echo "<h3>" . $item['title'] . "</h3>";
	echo "<p>" . $item['content'] . "</p>";
	echo '<a href="' . $item['unescapedUrl'] . '">' . $item['visibleUrl'] . "</a>";
}

echo "<hr />";

// Paginadores:

if (($pagina - 5) > 1) echo '...&nbsp;';

for ($n = 1; $n <= ceil($total / 8); $n++) {
	if (($n < ($pagina - 5)) OR ($n > ($pagina + 5))) continue;
	echo '<a href="?q='.$keywords.'&p='.$n.'">'.$n.'</a>&nbsp;';
}

if (($pagina + 5) < $total) echo '...';

?>

