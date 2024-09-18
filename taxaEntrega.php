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

    // Função para obter coordenadas a partir do endereço
    function obterCoordenadas($endereco, $apiKey) {
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

    // Obter coordenadas da pizzaria e do cliente
    $coordenadasPizzaria = obterCoordenadas($enderecoPizzaria, $googleApiKey);
    $coordenadasCliente = obterCoordenadas($enderecoCliente, $googleApiKey);

    if ($coordenadasPizzaria && $coordenadasCliente) {
        $origem = "{$coordenadasPizzaria['lat']},{$coordenadasPizzaria['lng']}";
        $destino = "{$coordenadasCliente['lat']},{$coordenadasCliente['lng']}";

        // URL para o cálculo de distância
        $urlDistancia = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origem&destinations=$destino&key=$googleApiKey";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlDistancia);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseDistancia = curl_exec($ch);
        curl_close($ch);

        $dataDistancia = json_decode($responseDistancia, true);

        if (isset($dataDistancia['rows'][0]['elements'][0]['distance']['value'])) {
            $distanciaMetros = $dataDistancia['rows'][0]['elements'][0]['distance']['value'];
            $distanciaKm = $distanciaMetros / 1000;
            $taxaBase = 5;

            // Calcula a taxa de entrega
            if ($distanciaKm <= 5) {
                $taxaEntrega = $taxaBase;
            } else {
                $taxaAdicional = ($distanciaKm - 5) * 2;
                $taxaEntrega = $taxaBase + $taxaAdicional;
            }

            $taxaEntrega = round($taxaEntrega);

            echo json_encode([
                'status' => 'success',
                'distanciaMetros' => $distanciaMetros,
                'taxaEntrega' => number_format($taxaEntrega, 0, ',', '.')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Não foi possível calcular a distância.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao obter coordenadas.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros inválidos.']);
}
?>
