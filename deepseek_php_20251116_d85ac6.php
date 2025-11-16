<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintMaster - Sistema de Impressão</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'config.php'; ?>
    
    <div class="container">
        <!-- Header Simplificado -->
        <div class="simple-header">
            <div class="logo">
                <i class="fas fa-print logo-icon"></i>
                <h1>PrintMaster</h1>
            </div>
            <p class="tagline">Sistema profissional de impressão e layout</p>
            <div class="quick-features">
                <span class="feature-badge">Frente e Verso</span>
                <span class="feature-badge">Múltiplas Cópias</span>
                <span class="feature-badge">PDF Automático</span>
                <span class="feature-badge">Centralização</span>
            </div>
        </div>
        
        <div class="main-content">
            <!-- ... conteúdo existente ... -->
        </div>

        <!-- Seção GitHub -->
        <section class="github-section">
            <!-- ... conteúdo existente ... -->
        </section>

        <!-- NOVA SEÇÃO PIX -->
        <section class="pix-section">
            <h2><i class="fas fa-qrcode"></i> Apoie o Projeto</h2>
            <div class="pix-content">
                <div class="pix-info">
                    <h3>Faça uma doação via PIX</h3>
                    <p>Se este projeto foi útil para você, considere fazer uma doação para ajudar no desenvolvimento.</p>
                    
                    <div class="pix-key">
                        <div class="key-type">Chave PIX (Celular):</div>
                        <div class="key-value" id="pix-key">13988089754</div>
                        <button class="btn-copy" id="copy-pix">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                    
                    <div class="pix-qr">
                        <div class="qr-code">
                            <canvas id="qr-canvas" width="200" height="200"></canvas>
                        </div>
                        <p class="qr-instruction">Aponte a câmera do seu app bancário</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="js/script.js"></script>
</body>
</html>