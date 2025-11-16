<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Verificar token CSRF
if (!isset($_POST[CSRF_TOKEN_NAME]) || $_POST[CSRF_TOKEN_NAME] !== $_SESSION[CSRF_TOKEN_NAME]) {
    http_response_code(403);
    echo json_encode(['error' => 'Token CSRF inválido']);
    exit;
}

try {
    $response = [];
    
    // Processar arquivos
    if (isset($_FILES['front-file']) && $_FILES['front-file']['error'] === UPLOAD_ERR_OK) {
        if (validateFile($_FILES['front-file'])) {
            $frontFileName = uniqid() . '_' . $_FILES['front-file']['name'];
            $frontFilePath = UPLOAD_DIR . $frontFileName;
            
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['front-file']['tmp_name'], $frontFilePath)) {
                $response['front_file'] = $frontFileName;
            }
        }
    }
    
    if (isset($_FILES['back-file']) && $_FILES['back-file']['error'] === UPLOAD_ERR_OK) {
        if (validateFile($_FILES['back-file'])) {
            $backFileName = uniqid() . '_' . $_FILES['back-file']['name'];
            $backFilePath = UPLOAD_DIR . $backFileName;
            
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['back-file']['tmp_name'], $backFilePath)) {
                $response['back_file'] = $backFileName;
            }
        }
    }
    
    // Processar configurações
    $settings = [
        'doc_width' => floatval($_POST['doc-width'] ?? 8),
        'doc_height' => floatval($_POST['doc-height'] ?? 10),
        'horizontal_copies' => intval($_POST['horizontal-copies'] ?? 2),
        'vertical_copies' => intval($_POST['vertical-copies'] ?? 2),
        'horizontal_spacing' => floatval($_POST['horizontal-spacing'] ?? 5),
        'vertical_spacing' => floatval($_POST['vertical-spacing'] ?? 5),
        'auto_center' => isset($_POST['auto-center']) && $_POST['auto-center'] === 'on'
    ];
    
    $response['settings'] = $settings;
    $response['total_copies'] = $settings['horizontal_copies'] * $settings['vertical_copies'];
    $response['message'] = 'Configurações processadas com sucesso';
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor: ' . $e->getMessage()]);
}
?>