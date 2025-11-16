// Adicione estas variáveis no início com as outras declarações
const copyPixBtn = document.getElementById('copy-pix');
const pixKeyElement = document.getElementById('pix-key');
const qrCanvas = document.getElementById('qr-canvas');

// Adicione este event listener com os outros
copyPixBtn.addEventListener('click', copyPixKey);

// Adicione estas funções no final do arquivo, antes do fechamento

function copyPixKey() {
    const pixKey = pixKeyElement.textContent;
    
    navigator.clipboard.writeText(pixKey).then(() => {
        // Feedback visual
        const originalText = copyPixBtn.innerHTML;
        copyPixBtn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        copyPixBtn.classList.add('copied-feedback');
        
        setTimeout(() => {
            copyPixBtn.innerHTML = originalText;
            copyPixBtn.classList.remove('copied-feedback');
        }, 2000);
        
        showStatus('Chave PIX copiada para a área de transferência!', 'success');
    }).catch(err => {
        console.error('Erro ao copiar: ', err);
        showStatus('Erro ao copiar chave PIX', 'error');
    });
}

function generateQRCode() {
    const pixKey = pixKeyElement.textContent;
    const qrText = `00020126580014br.gov.bcb.pix0136${pixKey}520400005303986540610.005802BR5913SEUNOMEAQUI6008BRASILIA62070503***6304`;
    
    try {
        // Usando uma biblioteca QR Code simples
        QRCode.toCanvas(qrCanvas, qrText, {
            width: 200,
            height: 200,
            margin: 1,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function(error) {
            if (error) {
                console.error('Erro ao gerar QR Code:', error);
                // Fallback: mostrar apenas a chave
                qrCanvas.getContext('2d').fillText('Chave: ' + pixKey, 10, 100);
            }
        });
    } catch (error) {
        console.error('QR Code não disponível:', error);
    }
}

// Inicializar QR Code quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // Aguardar um pouco para garantir que tudo carregou
    setTimeout(generateQRCode, 500);
    
    // ... o resto do código de inicialização
});