<?php
/**
 * ICS Email Account Review – Shared State API
 *
 * GET  /review-api.php          → returns current review state as JSON
 * POST /review-api.php          → updates one or more account statuses
 *   Body: { "updates": { "email@icsprograms.com": "keep"|"delete"|"verify"|null }, ... }
 *         null removes the status (resets to pending)
 *
 * DELETE /review-api.php        → resets all statuses (clears the file)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$STATE_FILE = __DIR__ . '/review-state.json';
$VALID_STATUSES = ['keep', 'delete', 'verify'];

// Load current state
function loadState($file)
{
    if (!file_exists($file))
        return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

// Save state atomically
function saveState($file, $state)
{
    file_put_contents($file, json_encode($state, JSON_PRETTY_PRINT), LOCK_EX);
}

// ──── GET: return current state ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $state = loadState($STATE_FILE);
    echo json_encode(['success' => true, 'state' => $state, 'count' => count($state)]);
    exit;
}

// ──── DELETE: reset all ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    saveState($STATE_FILE, []);
    echo json_encode(['success' => true, 'message' => 'All statuses reset']);
    exit;
}

// ──── POST: apply updates ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['updates']) || !is_array($input['updates'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Body must be JSON with an "updates" object']);
        exit;
    }

    $state = loadState($STATE_FILE);

    foreach ($input['updates'] as $email => $status) {
        // Basic email sanity check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            continue;

        if ($status === null || $status === '') {
            // null = remove (reset to pending)
            unset($state[$email]);
        } elseif (in_array($status, $VALID_STATUSES, true)) {
            $state[$email] = $status;
        }
    }

    saveState($STATE_FILE, $state);
    echo json_encode(['success' => true, 'state' => $state, 'count' => count($state)]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
