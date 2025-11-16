<?php
// Configurações do sistema
define('SITE_NAME', 'PrintMaster');
define('SITE_DESCRIPTION', 'Sistema profissional de impressão e layout');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_FILE_TYPES', ['image/jpeg', 'image/png', 'application/pdf']);

// Configurações de segurança
define('CSRF_TOKEN_NAME', 'csrf_token');

// Configurações de upload
define('UPLOAD_DIR', 'uploads/');
define('BACKUP_DIR', 'backups/');

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gerar token CSRF
if (empty($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// Função para validar arquivo
function validateFile($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_FILE_TYPES)) {
        return false;
    }
    
    return true;
}

// Função para sanitizar dados
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
?>