<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintMaster - Sistema de Impressão</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
            <section class="config-section">
                <h2><i class="fas fa-sliders-h"></i> Configurações</h2>
                
                <form id="print-form" action="process.php" method="post" enctype="multipart/form-data">
                    <div class="section-group">
                        <h3 class="section-title"><i class="fas fa-file-upload"></i> Documentos</h3>
                        
                        <div class="file-input">
                            <label>Documento da Frente</label>
                            <div class="file-input-area" id="front-file-area">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Clique para selecionar arquivo</p>
                                <input type="file" id="front-file" name="front-file" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                            </div>
                            <div class="file-name" id="front-file-name">Nenhum arquivo selecionado</div>
                        </div>
                        
                        <div class="file-input">
                            <label>Documento do Verso</label>
                            <div class="file-input-area" id="back-file-area">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Clique para selecionar arquivo</p>
                                <input type="file" id="back-file" name="back-file" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                            </div>
                            <div class="file-name" id="back-file-name">Nenhum arquivo selecionado</div>
                        </div>
                    </div>
                    
                    <div class="section-group">
                        <h3 class="section-title"><i class="fas fa-ruler"></i> Dimensões</h3>
                        
                        <div class="controls-grid">
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-h"></i> Largura (cm)</label>
                                <input type="number" id="doc-width" name="doc-width" min="1" max="21" value="8" step="0.1">
                            </div>
                            
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-v"></i> Altura (cm)</label>
                                <input type="number" id="doc-height" name="doc-height" min="1" max="29.7" value="10" step="0.1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-group">
                        <h3 class="section-title"><i class="fas fa-th"></i> Layout</h3>
                        
                        <div class="controls-grid">
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-h"></i> Na Horizontal</label>
                                <input type="number" id="horizontal-copies" name="horizontal-copies" min="1" max="10" value="2">
                            </div>
                            
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-v"></i> Na Vertical</label>
                                <input type="number" id="vertical-copies" name="vertical-copies" min="1" max="10" value="2">
                            </div>
                        </div>
                        
                        <div class="controls-grid">
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-h"></i> Espaço Horizontal (mm)</label>
                                <input type="number" id="horizontal-spacing" name="horizontal-spacing" min="0" max="50" value="5" step="0.5">
                            </div>
                            
                            <div class="control-group">
                                <label><i class="fas fa-arrows-alt-v"></i> Espaço Vertical (mm)</label>
                                <input type="number" id="vertical-spacing" name="vertical-spacing" min="0" max="50" value="5" step="0.5">
                            </div>
                        </div>
                        
                        <div class="toggle-group">
                            <label for="auto-center">Centralização Automática</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="auto-center" name="auto-center" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="total-copies" id="total-copies-info">
                            Total: 4 cópias por folha (2x2)
                        </div>
                    </div>
                    
                    <div class="status-message" id="status-message"></div>
                    
                    <div class="actions">
                        <button type="button" class="btn-secondary" id="reset-btn">
                            <i class="fas fa-redo"></i> Limpar
                        </button>
                        <button type="button" class="btn-success" id="test-pdf-btn">
                            <i class="fas fa-file-pdf"></i> Gerar PDF
                        </button>
                        <button type="button" class="btn-primary" id="print-btn">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                </form>
            </section>
            
            <section class="preview-section">
                <h2><i class="fas fa-eye"></i> Pré-visualização</h2>
                
                <div class="preview-area">
                    <div class="page-preview">
                        <div class="multi-copy-container" id="front-preview">
                            <!-- As cópias serão geradas aqui pelo JavaScript -->
                        </div>
                    </div>
                    <div class="preview-label">FRENTE</div>
                    
                    <div class="page-preview" style="margin-top: 30px;">
                        <div class="multi-copy-container" id="back-preview">
                            <!-- As cópias serão geradas aqui pelo JavaScript -->
                        </div>
                    </div>
                    <div class="preview-label">VERSO</div>
                </div>
            </section>
        </div>

        <section class="github-section">
            <h2><i class="fab fa-github"></i> Open Source</h2>
            <p style="color: var(--text-light); font-size: 0.9rem;">Contribua com o projeto no GitHub</p>
            
            <div class="github-buttons">
                <a href="https://github.com" class="github-btn" target="_blank">
                    <i class="fab fa-github"></i> Ver Código
                </a>
                <a href="https://github.com" class="github-btn" target="_blank">
                    <i class="fas fa-code-branch"></i> Fazer Fork
                </a>
                <a href="https://github.com" class="github-btn" target="_blank">
                    <i class="fas fa-bug"></i> Reportar Bug
                </a>
            </div>

            <div class="extension-info">
                <h3><i class="fab fa-chrome"></i> Extensão Chrome</h3>
                <p>Instale nossa extensão para acesso rápido</p>
                <button class="btn-primary" style="margin-top: 10px; background: white; color: #ee5a24; border: none; padding: 8px 16px; font-size: 0.8rem;">
                    <i class="fab fa-chrome"></i> Instalar
                </button>
            </div>
        </section>
    </div>

    <script src="js/script.js"></script>
</body>
</html>