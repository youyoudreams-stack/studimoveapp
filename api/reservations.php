<?php
/**
 * StudiMove App — Reservations API V1
 * Path conseillé : /app/api/reservations.php
 *
 * Objectif V1 :
 * - Lire le user connecté via le même cookie/session que api/auth.php
 * - Trouver ses réservations avec buyer_email_normalized
 * - Créer automatiquement le lien supertrip_reservation_users role=BUYER
 * - Retourner les réservations + items pour la future vue “Mes réservations”
 */

declare(strict_types=1);

require_once __DIR__ . '/../auth_config.php';

header('Content-Type: application/json; charset=utf-8');

function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function input_data(): array {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw ?: '', true);
    if (is_array($json)) return $json;
    return $_POST ?: [];
}

function normalize_email(string $email): string {
    return mb_strtolower(trim($email));
}

function token_hash(string $token): string {
    return hash('sha256', $token);
}

function random_token(int $bytes = 32): string {
    return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
}

function supabase_request(string $method, string $path, ?array $payload = null, array $query = [], ?string $prefer = null): array {
    $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($path, '/');
    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }

    $headers = [
        'apikey: ' . SUPABASE_SERVICE_ROLE_KEY,
        'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
        'Content-Type: application/json',
        'Accept: application/json',
        'Prefer: ' . ($prefer ?: 'return=representation')
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    $body = curl_exec($ch);
    $err = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false) {
        return ['ok' => false, 'status' => 0, 'error' => $err ?: 'CURL_ERROR', 'data' => null, 'url' => $url];
    }

    $data = json_decode($body, true);
    if ($data === null && $body !== '' && json_last_error() !== JSON_ERROR_NONE) {
        $data = $body;
    }

    return [
        'ok' => $status >= 200 && $status < 300,
        'status' => $status,
        'data' => $data,
        'error' => $status >= 200 && $status < 300 ? null : $data,
        'url' => $url
    ];
}

function get_user_by_id(string $id): ?array {
    $res = supabase_request('GET', 'supertrip_users', null, [
        'select' => '*',
        'id' => 'eq.' . $id,
        'limit' => 1
    ]);
    if (!$res['ok'] || empty($res['data'][0])) return null;
    return $res['data'][0];
}

function current_session(): ?array {
    $token = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
    if (!$token) return null;

    $hash = token_hash($token);
    $res = supabase_request('GET', 'supertrip_sessions', null, [
        'select' => '*',
        'session_token_hash' => 'eq.' . $hash,
        'expires_at' => 'gt.' . gmdate('c'),
        'limit' => 1
    ]);

    if (!$res['ok'] || empty($res['data'][0])) return null;
    return $res['data'][0];
}

function current_user(): ?array {
    $session = current_session();
    if (!$session) return null;

    $user = get_user_by_id((string)$session['user_id']);
    if (!$user || empty($user['is_active'])) return null;

    supabase_request('PATCH', 'supertrip_sessions', [
        'last_seen_at' => gmdate('c')
    ], [
        'id' => 'eq.' . $session['id']
    ]);

    return $user;
}

function in_filter(array $ids): string {
    $clean = [];
    foreach ($ids as $id) {
        $id = trim((string)$id);
        if ($id !== '') $clean[] = '"' . str_replace('"', '\\"', $id) . '"';
    }
    return 'in.(' . implode(',', $clean) . ')';
}

function group_items_by_reservation(array $items): array {
    $map = [];
    foreach ($items as $item) {
        $rid = (string)($item['reservation_id'] ?? '');
        if ($rid === '') continue;
        if (!isset($map[$rid])) $map[$rid] = [];
        $map[$rid][] = $item;
    }
    return $map;
}

function fetch_events_by_ids(array $eventIds): array {
    $eventIds = array_values(array_unique(array_filter(array_map('strval', $eventIds))));
    if (!$eventIds) return [];

    $res = supabase_request('GET', 'supertrip_events', null, [
        'select' => 'id,name,start_date,end_date,status',
        'id' => in_filter($eventIds)
    ]);
    if (!$res['ok'] || !is_array($res['data'])) return [];

    $map = [];
    foreach ($res['data'] as $ev) {
        if (!empty($ev['id'])) $map[(string)$ev['id']] = $ev;
    }
    return $map;
}

function ensure_reservation_user_links(array $links): void {
    $payload = [];
    foreach ($links as $link) {
        if (empty($link['reservation_id']) || empty($link['user_id']) || empty($link['role'])) continue;
        $payload[] = [
            'reservation_id' => $link['reservation_id'],
            'user_id' => $link['user_id'],
            'role' => $link['role'],
            'status' => $link['status'] ?? 'active'
        ];
    }

    if (!$payload) return;

    supabase_request(
        'POST',
        'supertrip_reservation_users',
        $payload,
        ['on_conflict' => 'reservation_id,user_id,role'],
        'return=minimal,resolution=merge-duplicates'
    );
}

function same_email(?string $a, ?string $b): bool {
    $a = normalize_email((string)$a);
    $b = normalize_email((string)$b);
    return $a !== '' && $b !== '' && $a === $b;
}

function assign_user_to_matching_items(array $items, array $user): void {
    if (empty($user['id'])) return;

    $emailNorm = (string)($user['email_normalized'] ?? '');
    if ($emailNorm === '') $emailNorm = normalize_email((string)($user['email'] ?? ''));
    if ($emailNorm === '') return;

    foreach ($items as $item) {
        $itemId = (string)($item['id'] ?? '');
        if ($itemId === '') continue;
        if (!empty($item['assigned_user_id'])) continue;

        $itemEmail = (string)($item['email'] ?? '');
        if (!same_email($itemEmail, $emailNorm)) continue;

        supabase_request('PATCH', 'supertrip_reservation_items', [
            'assigned_user_id' => $user['id'],
            'updated_at' => gmdate('c')
        ], [
            'id' => 'eq.' . $itemId
        ], 'return=minimal');
    }
}


function reservation_user_can_manage(array $reservation, array $user): bool {
    $userId = (string)($user['id'] ?? '');
    $emailNorm = (string)($user['email_normalized'] ?? '');
    if ($emailNorm === '') $emailNorm = normalize_email((string)($user['email'] ?? ''));
    $userMarque = trim((string)($user['marque'] ?? ''));

    if ($userMarque !== '' && trim((string)($reservation['marque'] ?? '')) !== '' && strcasecmp($userMarque, trim((string)$reservation['marque'])) !== 0) {
        return false;
    }

    if (same_email((string)($reservation['buyer_email_normalized'] ?? $reservation['buyer_email'] ?? ''), $emailNorm)) {
        return true;
    }

    if ($userId !== '') {
        $ru = supabase_request('GET', 'supertrip_reservation_users', null, [
            'select' => 'id',
            'reservation_id' => 'eq.' . (string)($reservation['id'] ?? ''),
            'user_id' => 'eq.' . $userId,
            'role' => 'eq.BUYER',
            'status' => 'eq.active',
            'limit' => 1
        ]);
        if ($ru['ok'] && !empty($ru['data'][0])) return true;
    }

    return false;
}

function fetch_reservation_by_id(string $reservationId): ?array {
    if ($reservationId === '') return null;
    $r = supabase_request('GET', 'supertrip_reservations', null, [
        'select' => 'id,supertrip_event_id,source,source_order_id,buyer_email,buyer_email_normalized,buyer_first_name,buyer_last_name,buyer_phone,status,validation_status,created_at,updated_at,marque',
        'id' => 'eq.' . $reservationId,
        'limit' => 1
    ]);
    if (!$r['ok'] || empty($r['data'][0])) return null;
    return $r['data'][0];
}

function fetch_item_by_id(string $itemId): ?array {
    if ($itemId === '') return null;
    $r = supabase_request('GET', 'supertrip_reservation_items', null, [
        'select' => 'id,reservation_id,attendee_bw_id,event_bw_id,item_type,item_status,title,first_name,last_name,email,phone,assigned_user_id,is_confirmed_by_buyer,is_confirmed_by_participant,created_at,updated_at,marque',
        'id' => 'eq.' . $itemId,
        'limit' => 1
    ]);
    if (!$r['ok'] || empty($r['data'][0])) return null;
    return $r['data'][0];
}

function confirm_item_for_me(): void {
    $user = current_user();
    if (!$user) json_response(['ok' => false, 'authenticated' => false, 'error' => 'NOT_AUTHENTICATED'], 401);

    $input = input_data();
    $itemId = trim((string)($input['item_id'] ?? ''));
    if ($itemId === '') json_response(['ok' => false, 'error' => 'ITEM_ID_REQUIRED'], 400);

    $item = fetch_item_by_id($itemId);
    if (!$item) json_response(['ok' => false, 'error' => 'ITEM_NOT_FOUND'], 404);

    $reservation = fetch_reservation_by_id((string)$item['reservation_id']);
    if (!$reservation) json_response(['ok' => false, 'error' => 'RESERVATION_NOT_FOUND'], 404);

    if (!reservation_user_can_manage($reservation, $user)) {
        json_response(['ok' => false, 'error' => 'FORBIDDEN_RESERVATION'], 403);
    }

    $payload = [
        'assigned_user_id' => $user['id'],
        'is_confirmed_by_buyer' => true,
        'updated_at' => gmdate('c')
    ];

    // Si l'item n'a pas encore d'infos participant, on préremplit avec le user.
    if (trim((string)($item['email'] ?? '')) === '') $payload['email'] = $user['email'] ?? null;
    if (trim((string)($item['first_name'] ?? '')) === '') $payload['first_name'] = $user['first_name'] ?? null;
    if (trim((string)($item['last_name'] ?? '')) === '') $payload['last_name'] = $user['last_name'] ?? null;
    if (trim((string)($item['phone'] ?? '')) === '') $payload['phone'] = $user['phone'] ?? null;

    $up = supabase_request('PATCH', 'supertrip_reservation_items', $payload, [
        'id' => 'eq.' . $itemId
    ]);

    if (!$up['ok']) json_response(['ok' => false, 'error' => 'ITEM_CONFIRM_FOR_ME_FAILED', 'detail' => $up['error']], 500);

    ensure_reservation_user_links([[
        'reservation_id' => $reservation['id'],
        'user_id' => $user['id'],
        'role' => 'BUYER',
        'status' => 'active'
    ],[
        'reservation_id' => $reservation['id'],
        'user_id' => $user['id'],
        'role' => 'PARTICIPANT',
        'status' => 'active'
    ]]);

    json_response(['ok' => true, 'item' => (is_array($up['data']) && !empty($up['data'][0])) ? $up['data'][0] : null]);
}

function confirm_item_external(): void {
    $user = current_user();
    if (!$user) json_response(['ok' => false, 'authenticated' => false, 'error' => 'NOT_AUTHENTICATED'], 401);

    $input = input_data();
    $itemId = trim((string)($input['item_id'] ?? ''));
    if ($itemId === '') json_response(['ok' => false, 'error' => 'ITEM_ID_REQUIRED'], 400);

    $item = fetch_item_by_id($itemId);
    if (!$item) json_response(['ok' => false, 'error' => 'ITEM_NOT_FOUND'], 404);

    $reservation = fetch_reservation_by_id((string)$item['reservation_id']);
    if (!$reservation) json_response(['ok' => false, 'error' => 'RESERVATION_NOT_FOUND'], 404);

    if (!reservation_user_can_manage($reservation, $user)) {
        json_response(['ok' => false, 'error' => 'FORBIDDEN_RESERVATION'], 403);
    }

    $firstName = trim((string)($input['first_name'] ?? ''));
    $lastName = trim((string)($input['last_name'] ?? ''));
    $email = trim((string)($input['email'] ?? ''));
    $phone = trim((string)($input['phone'] ?? ''));

    if ($firstName === '' || $lastName === '') {
        json_response(['ok' => false, 'error' => 'PARTICIPANT_NAME_REQUIRED'], 422);
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(['ok' => false, 'error' => 'PARTICIPANT_EMAIL_INVALID'], 422);
    }

    $payload = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email !== '' ? $email : null,
        'phone' => $phone !== '' ? $phone : null,
        'is_confirmed_by_buyer' => true,
        'updated_at' => gmdate('c')
    ];

    $up = supabase_request('PATCH', 'supertrip_reservation_items', $payload, [
        'id' => 'eq.' . $itemId
    ]);

    if (!$up['ok']) json_response(['ok' => false, 'error' => 'ITEM_CONFIRM_EXTERNAL_FAILED', 'detail' => $up['error']], 500);

    ensure_reservation_user_links([[
        'reservation_id' => $reservation['id'],
        'user_id' => $user['id'],
        'role' => 'BUYER',
        'status' => 'active'
    ]]);

    json_response(['ok' => true, 'item' => (is_array($up['data']) && !empty($up['data'][0])) ? $up['data'][0] : null]);
}

function reservation_from_token(): void {
    $user = current_user();
    if (!$user) json_response(['ok' => false, 'authenticated' => false, 'error' => 'NOT_AUTHENTICATED'], 401);

    $input = input_data();
    $token = trim((string)($input['token'] ?? $_GET['token'] ?? ''));
    if ($token === '') json_response(['ok' => false, 'error' => 'TOKEN_REQUIRED'], 400);

    $hash = token_hash($token);
    $r = supabase_request('GET', 'supertrip_reservation_access_tokens', null, [
        'select' => '*',
        'token_hash' => 'eq.' . $hash,
        'expires_at' => 'gt.' . gmdate('c'),
        'limit' => 1
    ]);

    if (!$r['ok'] || empty($r['data'][0])) {
        json_response(['ok' => false, 'error' => 'TOKEN_INVALID_OR_EXPIRED'], 400);
    }

    $row = $r['data'][0];
    $reservation = fetch_reservation_by_id((string)$row['reservation_id']);
    if (!$reservation) json_response(['ok' => false, 'error' => 'RESERVATION_NOT_FOUND'], 404);

    $userEmail = (string)($user['email_normalized'] ?? '');
    if ($userEmail === '') $userEmail = normalize_email((string)($user['email'] ?? ''));

    $tokenEmail = normalize_email((string)($row['email_normalized'] ?? $row['email'] ?? ''));
    $buyerEmail = normalize_email((string)($reservation['buyer_email_normalized'] ?? $reservation['buyer_email'] ?? ''));

    if ($tokenEmail !== '' && $userEmail !== $tokenEmail) {
        json_response(['ok' => false, 'error' => 'TOKEN_EMAIL_MISMATCH'], 403);
    }
    if ($buyerEmail !== '' && $userEmail !== $buyerEmail) {
        json_response(['ok' => false, 'error' => 'BUYER_EMAIL_MISMATCH'], 403);
    }

    ensure_reservation_user_links([[
        'reservation_id' => $reservation['id'],
        'user_id' => $user['id'],
        'role' => 'BUYER',
        'status' => 'active'
    ]]);

    supabase_request('PATCH', 'supertrip_reservation_access_tokens', [
        'used_at' => gmdate('c')
    ], [
        'id' => 'eq.' . $row['id']
    ], 'return=minimal');

    json_response(['ok' => true, 'reservation_id' => $reservation['id']]);
}



function app_base_url(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    return $host ? ($scheme . '://' . $host . '/studimove_app_test/') : '/studimove_app_test/';
}
function create_reservation_token(array $reservation): ?string {
    $rid = (string)($reservation['id'] ?? '');
    $email = trim((string)($reservation['buyer_email'] ?? ''));
    $emailNorm = trim((string)($reservation['buyer_email_normalized'] ?? ''));
    if ($emailNorm === '') $emailNorm = normalize_email($email);
    if ($rid === '' || $emailNorm === '') return null;
    $token = random_token(48);
    $r = supabase_request('POST', 'supertrip_reservation_access_tokens', [
        'reservation_id' => $rid,
        'email' => $email !== '' ? $email : $emailNorm,
        'email_normalized' => $emailNorm,
        'token_hash' => token_hash($token),
        'expires_at' => gmdate('c', time() + 30 * 86400),
    ]);
    if (!$r['ok']) return null;
    return $token;
}
function send_reservation_access_email_to_buyer(array $reservation, string $token): bool {
    $to = trim((string)($reservation['buyer_email'] ?? $reservation['buyer_email_normalized'] ?? ''));
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
    $link = app_base_url() . '?reservation_token=' . rawurlencode($token);
    $eventName = 'ta réservation';
    if (!empty($reservation['supertrip_event_id'])) {
        $ev = fetch_events_by_ids([(string)$reservation['supertrip_event_id']]);
        $eventName = $ev[(string)$reservation['supertrip_event_id']]['name'] ?? $eventName;
    }
    $subject = 'Accède à ta réservation StudiMove';
    $message = "Bonjour,\n\nTa réservation pour " . $eventName . " est disponible dans ton espace StudiMove.\n\nClique ici pour te connecter ou créer ton compte et attribuer tes billets :\n" . $link . "\n\nÀ très vite,\nL’équipe StudiMove";
    $from = defined('MAIL_FROM') ? MAIL_FROM : 'contact@studimove.fr';
    $headers = ['From: StudiMove <' . $from . '>', 'Reply-To: ' . $from, 'Content-Type: text/plain; charset=UTF-8'];
    return @mail($to, $subject, $message, implode("\r\n", $headers));
}
function send_reservation_access_email(): void {
    $user = current_user();
    if (!$user) json_response(['ok' => false, 'authenticated' => false, 'error' => 'NOT_AUTHENTICATED'], 401);
    $input = input_data();
    $reservationId = trim((string)($input['reservation_id'] ?? ''));
    if ($reservationId === '') json_response(['ok' => false, 'error' => 'RESERVATION_ID_REQUIRED'], 400);
    $reservation = fetch_reservation_by_id($reservationId);
    if (!$reservation) json_response(['ok' => false, 'error' => 'RESERVATION_NOT_FOUND'], 404);
    if (!reservation_user_can_manage($reservation, $user)) json_response(['ok' => false, 'error' => 'FORBIDDEN_RESERVATION'], 403);
    $token = create_reservation_token($reservation);
    if (!$token) json_response(['ok' => false, 'error' => 'TOKEN_CREATE_FAILED'], 500);
    $sent = send_reservation_access_email_to_buyer($reservation, $token);
    json_response(['ok'=>true,'sent'=>$sent,'link'=>app_base_url() . '?reservation_token=' . rawurlencode($token)]);
}

function my_reservations(): void {
    $user = current_user();
    if (!$user) {
        json_response(['ok' => false, 'authenticated' => false, 'error' => 'NOT_AUTHENTICATED'], 401);
    }

    $email = (string)($user['email'] ?? '');
    $emailNorm = (string)($user['email_normalized'] ?? '');
    if ($emailNorm === '') $emailNorm = normalize_email($email);

    $userId = (string)($user['id'] ?? '');
    $userMarque = trim((string)($user['marque'] ?? ''));

    if ($emailNorm === '') {
        json_response(['ok' => true, 'authenticated' => true, 'data' => [], 'count' => 0, 'message' => 'USER_EMAIL_MISSING']);
    }

    // V2 : on lit les réservations de la même marque que le user.
    // 1) Acheteur : buyer_email_normalized = email connecté
    // 2) Participant : item.email = email connecté ou item.assigned_user_id = user connecté
    $query = [
        'select' => 'id,supertrip_event_id,source,source_order_id,buyer_email,buyer_email_normalized,buyer_first_name,buyer_last_name,buyer_phone,status,validation_status,created_at,updated_at,marque',
        'status' => 'in.(pending,active)',
        'order' => 'created_at.desc',
        'limit' => 300
    ];

    if ($userMarque !== '') {
        $query['marque'] = 'eq.' . $userMarque;
    }

    $res = supabase_request('GET', 'supertrip_reservations', null, $query);

    if (!$res['ok']) {
        json_response(['ok' => false, 'error' => 'RESERVATIONS_FETCH_FAILED', 'detail' => $res['error']], 500);
    }

    $allReservations = is_array($res['data']) ? $res['data'] : [];
    $allReservationIds = array_values(array_filter(array_map(fn($r) => (string)($r['id'] ?? ''), $allReservations)));

    $itemsByReservationAll = [];
    if ($allReservationIds) {
        $itemsRes = supabase_request('GET', 'supertrip_reservation_items', null, [
            'select' => 'id,reservation_id,attendee_bw_id,event_bw_id,item_type,item_status,title,first_name,last_name,email,phone,assigned_user_id,is_confirmed_by_buyer,is_confirmed_by_participant,created_at,updated_at,marque',
            'reservation_id' => in_filter($allReservationIds),
            'order' => 'created_at.asc'
        ]);

        if (!$itemsRes['ok']) {
            json_response(['ok' => false, 'error' => 'RESERVATION_ITEMS_FETCH_FAILED', 'detail' => $itemsRes['error']], 500);
        }

        $itemsByReservationAll = group_items_by_reservation(is_array($itemsRes['data']) ? $itemsRes['data'] : []);
    }

    $reservations = [];
    $linksToCreate = [];

    foreach ($allReservations as $r) {
        $rid = (string)($r['id'] ?? '');
        if ($rid === '') continue;

        $items = $itemsByReservationAll[$rid] ?? [];

        $isBuyer = same_email((string)($r['buyer_email_normalized'] ?? $r['buyer_email'] ?? ''), $emailNorm);
        $isParticipant = false;

        foreach ($items as $it) {
            if (!empty($it['assigned_user_id']) && (string)$it['assigned_user_id'] === $userId) {
                $isParticipant = true;
                break;
            }
            if (same_email((string)($it['email'] ?? ''), $emailNorm)) {
                $isParticipant = true;
                break;
            }
        }

        if (!$isBuyer && !$isParticipant) continue;

        $reservations[] = $r;

        if ($isBuyer) {
            $linksToCreate[] = [
                'reservation_id' => $rid,
                'user_id' => $userId,
                'role' => 'BUYER',
                'status' => 'active'
            ];
        }

        if ($isParticipant) {
            $linksToCreate[] = [
                'reservation_id' => $rid,
                'user_id' => $userId,
                'role' => 'PARTICIPANT',
                'status' => 'active'
            ];
        }

        assign_user_to_matching_items($items, $user);
    }

    ensure_reservation_user_links($linksToCreate);

    $reservationIds = array_values(array_filter(array_map(fn($r) => (string)($r['id'] ?? ''), $reservations)));
    $itemsByReservation = [];
    if ($reservationIds) {
        // Relecture après assignation éventuelle.
        $itemsRes = supabase_request('GET', 'supertrip_reservation_items', null, [
            'select' => 'id,reservation_id,attendee_bw_id,event_bw_id,item_type,item_status,title,first_name,last_name,email,phone,assigned_user_id,is_confirmed_by_buyer,is_confirmed_by_participant,created_at,updated_at,marque',
            'reservation_id' => in_filter($reservationIds),
            'order' => 'created_at.asc'
        ]);

        if (!$itemsRes['ok']) {
            json_response(['ok' => false, 'error' => 'RESERVATION_ITEMS_REFETCH_FAILED', 'detail' => $itemsRes['error']], 500);
        }

        $itemsByReservation = group_items_by_reservation(is_array($itemsRes['data']) ? $itemsRes['data'] : []);
    }

    $eventIds = [];
    foreach ($reservations as $r) {
        if (!empty($r['supertrip_event_id'])) $eventIds[] = (string)$r['supertrip_event_id'];
    }
    $eventsById = fetch_events_by_ids($eventIds);

    $out = [];
    foreach ($reservations as $r) {
        $rid = (string)($r['id'] ?? '');
        $items = $itemsByReservation[$rid] ?? [];

        $counts = [
            'PARTICIPANT' => 0,
            'TRANSPORT' => 0,
            'OPTION' => 0,
            'ASSURANCE' => 0,
            'WAITING' => 0,
            'total' => count($items),
        ];
        foreach ($items as $it) {
            $t = (string)($it['item_type'] ?? '');
            if (isset($counts[$t])) $counts[$t]++;
        }

        $event = null;
        if (!empty($r['supertrip_event_id']) && isset($eventsById[(string)$r['supertrip_event_id']])) {
            $event = $eventsById[(string)$r['supertrip_event_id']];
        }

        $linkedAs = [];
        if (same_email((string)($r['buyer_email_normalized'] ?? $r['buyer_email'] ?? ''), $emailNorm)) $linkedAs[] = 'BUYER';
        foreach ($items as $it) {
            if ((!empty($it['assigned_user_id']) && (string)$it['assigned_user_id'] === $userId) || same_email((string)($it['email'] ?? ''), $emailNorm)) {
                $linkedAs[] = 'PARTICIPANT';
                break;
            }
        }
        $linkedAs = array_values(array_unique($linkedAs));

        $out[] = [
            'id' => $rid,
            'supertrip_event_id' => $r['supertrip_event_id'] ?? null,
            'source' => $r['source'] ?? null,
            'source_order_id' => $r['source_order_id'] ?? null,
            'marque' => $r['marque'] ?? null,
            'status' => $r['status'] ?? null,
            'validation_status' => $r['validation_status'] ?? null,
            'buyer' => [
                'email' => $r['buyer_email'] ?? null,
                'first_name' => $r['buyer_first_name'] ?? null,
                'last_name' => $r['buyer_last_name'] ?? null,
                'phone' => $r['buyer_phone'] ?? null,
            ],
            'event' => $event,
            'items_count' => $counts,
            'items' => $items,
            'created_at' => $r['created_at'] ?? null,
            'updated_at' => $r['updated_at'] ?? null,
            'linked_as' => $linkedAs
        ];
    }

    json_response([
        'ok' => true,
        'authenticated' => true,
        'user' => [
            'id' => $user['id'] ?? null,
            'marque' => $userMarque,
            'email' => $email,
            'email_normalized' => $emailNorm,
            'first_name' => $user['first_name'] ?? null,
            'last_name' => $user['last_name'] ?? null,
        ],
        'count' => count($out),
        'data' => $out
    ]);
}
$input = input_data();
$action = $input['action'] ?? $_GET['action'] ?? 'my_reservations';

try {
    switch ($action) {
        case 'my_reservations':
            my_reservations();
            break;

        case 'confirm_item_for_me':
            confirm_item_for_me();
            break;

        case 'confirm_item_external':
            confirm_item_external();
            break;

        case 'reservation_from_token':
            reservation_from_token();
            break;

        case 'send_reservation_access_email':
            send_reservation_access_email();
            break;

        default:
            json_response(['ok' => false, 'error' => 'INVALID_ACTION'], 400);
    }
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'SERVER_ERROR', 'message' => $e->getMessage()], 500);
}
