function generateQRCode() {
    const pixKey = pixKeyElement.textContent;
    const ctx = qrCanvas.getContext('2d');
    
    // Limpar canvas
    ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
    
    // Desenhar um QR Code simples (apenas visual)
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, qrCanvas.width, qrCanvas.height);
    
    ctx.fillStyle = '#000000';
    ctx.font = '14px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('PIX: ' + pixKey, qrCanvas.width/2, qrCanvas.height/2 - 10);
    ctx.font = '12px Arial';
    ctx.fillText('Use a chave acima', qrCanvas.width/2, qrCanvas.height/2 + 10);
    ctx.fillText('no seu app bancário', qrCanvas.width/2, qrCanvas.height/2 + 25);
}