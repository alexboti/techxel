<?php
/**
 * CCI Printer Registry API
 *
 * GET  → returns all printers
 * POST → action: 'add' | 'update' | 'delete'
 */
session_name('techxel_cci');
session_start();

if (empty($_SESSION['authenticated'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$DATA_FILE = __DIR__ . '/printers.json';

function loadData($file) {
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function saveData($file, $data) {
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT), LOCK_EX);
}

function makeId() {
    return bin2hex(random_bytes(6));
}

// ── GET ──────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $printers = loadData($DATA_FILE);
    echo json_encode(['success' => true, 'printers' => array_values($printers)]);
    exit;
}

// ── POST ─────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['action'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing action']);
        exit;
    }

    $printers = loadData($DATA_FILE);
    $action   = $input['action'];

    // ── Add ──────────────────────────────────────────────────────────────
    if ($action === 'add') {
        $location = trim($input['location'] ?? '');
        $model    = trim($input['model']    ?? '');
        if (!$location || !$model) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'location and model are required']);
            exit;
        }
        $printers[] = ['id' => makeId(), 'location' => $location, 'model' => $model];
        saveData($DATA_FILE, $printers);
        echo json_encode(['success' => true, 'printers' => array_values($printers)]);
        exit;
    }

    // ── Update ───────────────────────────────────────────────────────────
    if ($action === 'update') {
        $id       = trim($input['id']       ?? '');
        $location = trim($input['location'] ?? '');
        $model    = trim($input['model']    ?? '');
        if (!$id || !$location || !$model) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'id, location and model are required']);
            exit;
        }
        $found = false;
        foreach ($printers as &$p) {
            if ($p['id'] === $id) {
                $p['location'] = $location;
                $p['model']    = $model;
                $found = true;
                break;
            }
        }
        unset($p);
        if (!$found) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Entry not found']);
            exit;
        }
        saveData($DATA_FILE, $printers);
        echo json_encode(['success' => true, 'printers' => array_values($printers)]);
        exit;
    }

    // ── Delete ───────────────────────────────────────────────────────────
    if ($action === 'delete') {
        $id = trim($input['id'] ?? '');
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'id is required']);
            exit;
        }
        $before   = count($printers);
        $printers = array_values(array_filter($printers, fn($p) => $p['id'] !== $id));
        if (count($printers) === $before) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Entry not found']);
            exit;
        }
        saveData($DATA_FILE, $printers);
        echo json_encode(['success' => true, 'printers' => $printers]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
