<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'stats':
        $stats = [];
        $stats['total']    = $pdo->query("SELECT COUNT(*) FROM discount_applications")->fetchColumn();
        $stats['pending']  = $pdo->query("SELECT COUNT(*) FROM discount_applications WHERE status = 'pending'")->fetchColumn();
        $stats['approved'] = $pdo->query("SELECT COUNT(*) FROM discount_applications WHERE status = 'approved'")->fetchColumn();
        $stats['rejected'] = $pdo->query("SELECT COUNT(*) FROM discount_applications WHERE status = 'rejected'")->fetchColumn();
        echo json_encode($stats);
        break;

    case 'list':
        $stmt = $pdo->query("
            SELECT 
                da.id,
                da.user_id,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                da.type,
                da.status,
                da.notes,
                da.id_image_path,
                da.created_at
            FROM discount_applications da
            JOIN users u ON u.id = da.user_id
            ORDER BY 
                FIELD(da.status, 'pending', 'rejected', 'approved'),
                da.created_at DESC
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'decide':
        $body   = json_decode(file_get_contents('php://input'), true);
        $id     = (int)($body['id']     ?? 0);
        $status = $body['status'] ?? '';
        $notes  = trim($body['notes']   ?? '');

        if (!$id || !in_array($status, ['approved', 'rejected'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
            break;
        }

        // Update the application
        $stmt = $pdo->prepare("
            UPDATE discount_applications
            SET status = :status, notes = :notes
            WHERE id = :id
        ");
        $stmt->execute([':status' => $status, ':notes' => $notes, ':id' => $id]);

        // Also sync the user's discount_status
        $stmt2 = $pdo->prepare("
            UPDATE users u
            JOIN discount_applications da ON da.user_id = u.id
            SET u.discount_status = :status
            WHERE da.id = :id
        ");
        $stmt2->execute([':status' => $status, ':id' => $id]);

        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action.']);
        break;
}