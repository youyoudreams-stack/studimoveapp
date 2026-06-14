<?php
declare(strict_types=1);

/**
 * StudiMove App — Cards API (public)
 * Retourne les cards "en ligne" depuis smv_cards pour l'app étudiants.
 * Pas d'auth requise — données publiques.
 */

require_once __DIR__ . '/../auth_config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60'); // cache 1 min côté client

function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function supabase_get(string $path): array {
    $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($path, '/');
    $headers = [
        'apikey: ' . SUPABASE_SERVICE_ROLE_KEY,
        'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
        'Accept: application/json',
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $body   = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        return ['ok' => false, 'data' => null, 'error' => $err];
    }
    $data = json_decode($body, true);
    return ['ok' => $status >= 200 && $status < 300, 'data' => $data];
}

// Lecture des cards "en ligne" pour la marque Studimove uniquement, triées par ordre
$query = 'smv_cards?status=eq.en%20ligne&marque=eq.studimove&order=ordre.asc.nullslast,updated_at.desc';

$res = supabase_get($query);

if (!$res['ok']) {
    json_response(['ok' => false, 'error' => 'CARDS_FETCH_FAILED'], 500);
}

$cards = is_array($res['data']) ? $res['data'] : [];

// On expose uniquement les champs utiles côté app (pas les données CRM internes)
$public = array_map(function(array $c): array {
    return [
        'id'          => $c['id']          ?? null,
        'title'       => $c['title']       ?? '',
        'tag'         => $c['tag']         ?? '',
        'date_text'   => $c['date_text']   ?? '',
        'price'       => $c['price']       ?? '',
        'image'       => $c['image']       ?? '',
        'image1'      => $c['image1']      ?? '',
        'image2'      => $c['image2']      ?? '',
        'image3'      => $c['image3']      ?? '',
        'image4'      => $c['image4']      ?? '',
        'slogan'      => $c['slogan']      ?? '',
        'desc_text'   => $c['desc_text']   ?? '',
        'ville'       => $c['ville']       ?? '',
        'cities'      => $c['cities']      ?? '',
        'duree'       => $c['duree']       ?? '',
        'stock'       => $c['stock']       ?? '',
        'categories'  => $c['categories']  ?? '',
        'billetterie' => $c['billetterie'] ?? '',
        'link'        => $c['link']        ?? '',
        'video'       => $c['video']       ?? '',
        'whatsapp'    => $c['whatsapp']    ?? '',
        'featured'    => (bool)($c['featured'] ?? false),
        'ordre'       => $c['ordre']       ?? null,
        'marque'      => $c['marque']      ?? '',
        'updated_at'  => $c['updated_at']  ?? null,
    ];
}, $cards);

json_response([
    'ok'    => true,
    'count' => count($public),
    'cards' => $public,
]);
