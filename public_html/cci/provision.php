<?php
/**
 * CCI Provision Proxy — server-side relay to Google Apps Script.
 * Holds the SECRET_TOKEN; never exposed to the browser.
 * Session-guarded: only authenticated CCI staff can call this.
 */
session_name('techxel_cci');
session_start();

header('Content-Type: application/json');

// ── AUTH CHECK ───────────────────────────────
if (empty($_SESSION['authenticated'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// ── CONFIG (server-side only) ────────────────
define('SECRET_TOKEN',   'CapCare-2026-xK9mPqR7wZbN');
define('APPS_SCRIPT_URL','https://script.google.com/macros/s/AKfycby5Y8RroSulTmv3BONmqAvy5riSoKVzmbU6ZLx2wJO6_Ou_j16PFoFo6XaQ5m3MKxKPPw/exec');

// Internal group list (never sent to browser source)
$GROUPS = [
    ''                                          => '-- No Group --',
    'dl-ccidayprogramstaff@capitalcareinc.com'  => 'DL-CCIDayProgramStaff',
    'dl-ccihousemanagers@capitalcareinc.com'    => 'DL-CCIHouseManagers',
    'dl-ccinurses@capitalcareinc.com'           => 'DL-CCINurses',
    'dl-cciprogramcoordinators@capitalcareinc.com' => 'DL-CCIProgramCoordinators',
    'dl-ccistaff@capitalcareinc.com'            => 'DL-CCIStaff',
    'dl-hha@capitalcareinc.com'                 => 'DL-HHA',
    'dl-hhanurses@capitalcareinc.com'           => 'DL-HHANurses',
];
// ─────────────────────────────────────────────

// ── GET: return group list ────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['success' => true, 'groups' => $GROUPS]);
    exit;
}

// ── POST: proxy to Apps Script ────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $firstName     = trim($input['firstName']     ?? '');
    $lastName      = trim($input['lastName']      ?? '');
    $personalEmail = trim($input['personalEmail'] ?? '');
    $selectedGroup = trim($input['selectedGroup'] ?? '');

    // Basic server-side validation
    if (!$firstName || !$lastName || !filter_var($personalEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing or invalid required fields.']);
        exit;
    }

    // Validate group is in allowed list
    if ($selectedGroup !== '' && !array_key_exists($selectedGroup, $GROUPS)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid group selected.']);
        exit;
    }

    $params = http_build_query([
        'token'         => SECRET_TOKEN,
        'firstName'     => $firstName,
        'lastName'      => $lastName,
        'personalEmail' => $personalEmail,
        'selectedGroup' => $selectedGroup,
    ]);

    $url = APPS_SCRIPT_URL . '?' . $params;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        http_response_code(502);
        echo json_encode(['success' => false, 'error' => 'Could not reach provisioning service.']);
        exit;
    }

    // Pass response through from Apps Script
    http_response_code($httpCode ?: 200);
    echo $response;
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
