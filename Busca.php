<?php

/**
 * API de busca do Google
 *
 */
class googleSearchAPI {
	protected $url = 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=large&start=%s&q=%s';
	var $resultado, $pagina, $keywords;

	function __construct() {
		if (!function_exists('curl_init')) {
			trigger_error('A biblioteca cURL não está instalada!');
			return false;
		}
		if (!function_exists('json_decode')) {
			trigger_error('A biblioteca para manipulação de JSON não está instalada!');
			return false;
		}
	}

	/**
	 * Pega o resultado HTTP de uma URL
	 */
	protected function httpRequest($url) {
		$cURL = curl_init($url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
		$resultado = curl_exec($cURL);
		$resposta = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
		curl_close($cURL);
		return $resultado;
	}

	/**
	 * Executa a busca
	 */
	function busca($keywords = null, $pagina = 1, $site = null) {
		$keywords = (is_null($keywords)) ? $this->keywords : $keywords;
		$start = (is_null($pagina)) ? 0 : (($pagina - 1) * 8);

		$bkeywords = (!is_null($site)) ? ($keywords . ' site:' . $site) : $keywords;

		$url = sprintf($this->url, (int)$start, urlencode($bkeywords));
		$resultado = $this->httpRequest($url);
		if (!$resultado) {
			trigger_error('Não foi possível acessar a URL de busca:<br />' . $url);
			return false;
		}
		$resultado = json_decode($resultado, true);

		$this->resultado = $resultado['responseData'];
		$this->keywords = $keywords;
		$this->pagina = $pagina;
	}

	/**
	 * Pega os resultados encontrados
	 */
	function resultadoSites() {
		return $this->resultado['results'];
	}

	/**
	 * Pega o total de sites encontrados
	 */
	function resultadoTotal() {
		return $this->resultado['cursor']['estimatedResultCount'];
	}
}

?>

