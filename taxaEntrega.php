<?php
// Cabeçalhos para permitir requisições CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

$googleApiKey = 'AIzaSyD75y85lHmcaYraJsfSMtk245adprAqWFw';

if (isset($_GET['enderecoCliente']) && isset($_GET['enderecoPizzaria'])) {
    $enderecoCliente = $_GET['enderecoCliente'];
    $enderecoPizzaria = $_GET['enderecoPizzaria'];

    function obterCoordenadas($endereco, $apiKey)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($endereco) . "&key=" . $apiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if (!empty($data['results'])) {
            return $data['results'][0]['geometry']['location'];
        } else {
            return null;
        }
    }

    $coordenadasPizzaria = obterCoordenadas($enderecoPizzaria, $googleApiKey);
    $coordenadasCliente = obterCoordenadas($enderecoCliente, $googleApiKey);

    if ($coordenadasPizzaria && $coordenadasCliente) {
        $origem = "{$coordenadasPizzaria['lat']},{$coordenadasPizzaria['lng']}";
        $destino = "{$coordenadasCliente['lat']},{$coordenadasCliente['lng']}";

        $urlDistancia = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origem&destinations=$destino&key=$googleApiKey";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlDistancia);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseDistancia = curl_exec($ch);
        curl_close($ch);

        $dataDistancia = json_decode($responseDistancia, true);

        if (isset($dataDistancia['rows'][0]['elements'][0]['distance']['value']) && isset($dataDistancia['rows'][0]['elements'][0]['duration']['value'])) {

            $distanciaMetros = $dataDistancia['rows'][0]['elements'][0]['distance']['value'];
            $distanciaKm = $distanciaMetros / 1000;

            $duracaoSegundos = $dataDistancia['rows'][0]['elements'][0]['duration']['value'];
            $duracaoMinutos = round($duracaoSegundos / 60);


            $valorPadrao = 20;
            $duracaoMinutos += $valorPadrao;

            $taxaBase = 7;
            if ($distanciaKm <= 7) {
                $taxaEntrega = $taxaBase;
            } else {
                $taxaAdicional = ($distanciaKm - 5) * 2;
                $taxaEntrega = $taxaBase + $taxaAdicional;
            }
            $taxaEntrega = round($taxaEntrega);

            echo json_encode([
                'status' => 'success',
                'distanciaMetros' => $distanciaMetros,
                'distanciaKm' => number_format($distanciaKm, 2, ',', '.'),
                'duracaoMinutos' => $duracaoMinutos,
                'taxaEntrega' => number_format($taxaEntrega, 0, ',', '.')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Não foi possível calcular a distância ou duração.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao obter coordenadas.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros inválidos.']);
}
