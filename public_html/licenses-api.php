<?php
/**
 * License Key API for Techxel Softwares Page
 * Reads and writes license keys to licenses.json
 * 
 * GET  /licenses-api.php          → returns all licenses as JSON
 * POST /licenses-api.php          → updates a license key
 *   Body: { "software_id": "ms_office_2024", "license": "NEW-KEY-HERE", "password": "your-password" }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ------- CONFIGURATION -------
// Change this password to something only you know
$ADMIN_PASSWORD = 'techxel2024';
$LICENSES_FILE  = __DIR__ . '/licenses.json';
// -----------------------------

// Ensure the licenses file exists
if (!file_exists($LICENSES_FILE)) {
    $default = [
        'ms_office_2024' => ['name' => 'Microsoft Office 2024', 'license' => 'XXXXX-XXXXX-XXXXX-XXXXX-XXXXX'],
        'eset_antivirus' => ['name' => 'ESET NOD32 Antivirus', 'license' => 'XXXX-XXXX-XXXX-XXXX-XXXX']
    ];
    file_put_contents($LICENSES_FILE, json_encode($default, JSON_PRETTY_PRINT));
}

// READ
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents($LICENSES_FILE), true);
    echo json_encode(['success' => true, 'licenses' => $data]);
    exit;
}

// WRITE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['software_id']) || !isset($input['license']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields: software_id, license, password']);
        exit;
    }

    // Verify password
    if ($input['password'] !== $ADMIN_PASSWORD) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Invalid password']);
        exit;
    }

    $data = json_decode(file_get_contents($LICENSES_FILE), true);
    $softwareId = $input['software_id'];

    if (!isset($data[$softwareId])) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Software ID not found']);
        exit;
    }

    $data[$softwareId]['license'] = $input['license'];
    file_put_contents($LICENSES_FILE, json_encode($data, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'License updated successfully', 'licenses' => $data]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
