document.addEventListener('DOMContentLoaded', function() {
    // Constantes
    const PAGE_WIDTH = 210;
    const PAGE_HEIGHT = 297;
    const MARGIN = 5;
    
    // Elementos
    const frontFileInput = document.getElementById('front-file');
    const backFileInput = document.getElementById('back-file');
    const frontFileArea = document.getElementById('front-file-area');
    const backFileArea = document.getElementById('back-file-area');
    const frontFileName = document.getElementById('front-file-name');
    const backFileName = document.getElementById('back-file-name');
    const docWidthInput = document.getElementById('doc-width');
    const docHeightInput = document.getElementById('doc-height');
    const horizontalCopiesInput = document.getElementById('horizontal-copies');
    const verticalCopiesInput = document.getElementById('vertical-copies');
    const horizontalSpacingInput = document.getElementById('horizontal-spacing');
    const verticalSpacingInput = document.getElementById('vertical-spacing');
    const autoCenterToggle = document.getElementById('auto-center');
    const totalCopiesInfo = document.getElementById('total-copies-info');
    const frontPreview = document.getElementById('front-preview');
    const backPreview = document.getElementById('back-preview');
    const statusMessage = document.getElementById('status-message');
    const resetBtn = document.getElementById('reset-btn');
    const testPdfBtn = document.getElementById('test-pdf-btn');
    const printBtn = document.getElementById('print-btn');
    
    // Estado
    let frontImageData = null;
    let backImageData = null;
    
    // Event Listeners
    frontFileArea.addEventListener('click', () => frontFileInput.click());
    backFileArea.addEventListener('click', () => backFileInput.click());
    
    frontFileInput.addEventListener('change', (e) => handleFileUpload('front', e));
    backFileInput.addEventListener('change', (e) => handleFileUpload('back', e));
    
    docWidthInput.addEventListener('input', updatePreview);
    docHeightInput.addEventListener('input', updatePreview);
    horizontalCopiesInput.addEventListener('input', updatePreview);
    verticalCopiesInput.addEventListener('input', updatePreview);
    horizontalSpacingInput.addEventListener('input', updatePreview);
    verticalSpacingInput.addEventListener('input', updatePreview);
    autoCenterToggle.addEventListener('change', updatePreview);
    resetBtn.addEventListener('click', resetSystem);
    testPdfBtn.addEventListener('click', generateTestPDF);
    printBtn.addEventListener('click', printDocument);
    
    // Funções
    function handleFileUpload(side, event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const fileNameElement = side === 'front' ? frontFileName : backFileName;
        fileNameElement.textContent = file.name;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (side === 'front') {
                    frontImageData = e.target.result;
                } else {
                    backImageData = e.target.result;
                }
                updatePreview();
                showStatus('Documento carregado com sucesso!', 'success');
            };
            reader.readAsDataURL(file);
        } else {
            showStatus('Apenas imagens são suportadas', 'error');
        }
    }
    
    function calculateLayout() {
        const docWidth = parseFloat(docWidthInput.value) || 8;
        const docHeight = parseFloat(docHeightInput.value) || 10;
        const horizontalCopies = parseInt(horizontalCopiesInput.value) || 2;
        const verticalCopies = parseInt(verticalCopiesInput.value) || 2;
        const horizontalSpacing = parseFloat(horizontalSpacingInput.value) || 5;
        const verticalSpacing = parseFloat(verticalSpacingInput.value) || 5;
        const autoCenter = autoCenterToggle.checked;
        
        const docWidthMm = docWidth * 10;
        const docHeightMm = docHeight * 10;
        const totalCopies = horizontalCopies * verticalCopies;
        
        const totalWidth = (horizontalCopies * docWidthMm) + ((horizontalCopies - 1) * horizontalSpacing);
        const totalHeight = (verticalCopies * docHeightMm) + ((verticalCopies - 1) * verticalSpacing);
        
        let extraMarginX = 0;
        let extraMarginY = 0;
        
        if (autoCenter) {
            extraMarginX = (PAGE_WIDTH - (2 * MARGIN) - totalWidth) / 2;
            extraMarginY = (PAGE_HEIGHT - (2 * MARGIN) - totalHeight) / 2;
        }
        
        totalCopiesInfo.textContent = `Total: ${totalCopies} cópias por folha (${horizontalCopies}x${verticalCopies})`;
        
        return {
            docWidth: docWidthMm,
            docHeight: docHeightMm,
            horizontalCopies,
            verticalCopies,
            horizontalSpacing,
            verticalSpacing,
            autoCenter,
            totalCopies,
            extraMarginX: Math.max(0, extraMarginX),
            extraMarginY: Math.max(0, extraMarginY)
        };
    }
    
    function updatePreview() {
        const layout = calculateLayout();
        updatePreviewContainer(frontPreview, layout, frontImageData, 'Frente');
        updatePreviewContainer(backPreview, layout, backImageData, 'Verso');
    }
    
    function updatePreviewContainer(container, layout, imageData, side) {
        container.innerHTML = '';
        
        container.style.gridTemplateColumns = `repeat(${layout.horizontalCopies}, ${layout.docWidth}mm)`;
        container.style.gridTemplateRows = `repeat(${layout.verticalCopies}, ${layout.docHeight}mm)`;
        container.style.gap = `${layout.verticalSpacing}mm ${layout.horizontalSpacing}mm`;
        
        if (layout.autoCenter) {
            container.style.marginLeft = `${layout.extraMarginX}mm`;
            container.style.marginTop = `${layout.extraMarginY}mm`;
        } else {
            container.style.marginLeft = `${MARGIN}mm`;
            container.style.marginTop = `${MARGIN}mm`;
        }
        
        let copyIndex = 0;
        
        for (let row = 0; row < layout.verticalCopies; row++) {
            for (let col = 0; col < layout.horizontalCopies; col++) {
                copyIndex++;
                
                const copySlot = document.createElement('div');
                copySlot.className = 'copy-slot';
                copySlot.style.width = `${layout.docWidth}mm`;
                copySlot.style.height = `${layout.docHeight}mm`;
                
                const copyNumber = document.createElement('div');
                copyNumber.className = 'copy-number';
                copyNumber.textContent = copyIndex;
                copySlot.appendChild(copyNumber);
                
                const previewContent = document.createElement('div');
                previewContent.className = 'preview-content';
                
                if (imageData) {
                    const img = document.createElement('img');
                    img.src = imageData;
                    img.alt = `Cópia ${copyIndex}`;
                    img.style.objectFit = 'contain';
                    img.style.objectPosition = 'center';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    previewContent.appendChild(img);
                } else {
                    const text = document.createElement('div');
                    text.innerHTML = `<p style="color: #64748b; font-size: 12px;">${side}<br>#${copyIndex}</p>`;
                    text.style.textAlign = 'center';
                    previewContent.appendChild(text);
                }
                
                copySlot.appendChild(previewContent);
                container.appendChild(copySlot);
            }
        }
    }
    
    function generateTestPDF() {
        if (!frontImageData && !backImageData) {
            showStatus('Carregue pelo menos um documento', 'error');
            return;
        }
        
        showStatus('Gerando PDF...', 'success');
        
        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: [PAGE_WIDTH, PAGE_HEIGHT]
            });
            
            const layout = calculateLayout();
            
            if (frontImageData) {
                addImagesToPDF(pdf, frontImageData, layout);
            }
            
            if (backImageData) {
                pdf.addPage();
                addImagesToPDF(pdf, backImageData, layout);
            }
            
            pdf.save(`printmaster-${layout.totalCopies}-copias.pdf`);
            showStatus('PDF gerado com sucesso!', 'success');
            
        } catch (error) {
            console.error('Erro ao gerar PDF:', error);
            showStatus('Erro ao gerar PDF', 'error');
        }
    }
    
    function addImagesToPDF(pdf, imageData, layout) {
        for (let row = 0; row < layout.verticalCopies; row++) {
            for (let col = 0; col < layout.horizontalCopies; col++) {
                let xPos, yPos;
                
                if (layout.autoCenter) {
                    xPos = MARGIN + layout.extraMarginX + (col * (layout.docWidth + layout.horizontalSpacing));
                    yPos = MARGIN + layout.extraMarginY + (row * (layout.docHeight + layout.verticalSpacing));
                } else {
                    xPos = MARGIN + (col * (layout.docWidth + layout.horizontalSpacing));
                    yPos = MARGIN + (row * (layout.docHeight + layout.verticalSpacing));
                }
                
                if (xPos + layout.docWidth <= PAGE_WIDTH - MARGIN && 
                    yPos + layout.docHeight <= PAGE_HEIGHT - MARGIN) {
                    
                    pdf.addImage(
                        imageData,
                        'JPEG',
                        xPos,
                        yPos,
                        layout.docWidth,
                        layout.docHeight
                    );
                }
            }
        }
    }
    
    function printDocument() {
        generateTestPDF();
    }
    
    function resetSystem() {
        frontFileInput.value = '';
        backFileInput.value = '';
        frontFileName.textContent = 'Nenhum arquivo selecionado';
        backFileName.textContent = 'Nenhum arquivo selecionado';
        docWidthInput.value = '8';
        docHeightInput.value = '10';
        horizontalCopiesInput.value = '2';
        verticalCopiesInput.value = '2';
        horizontalSpacingInput.value = '5';
        verticalSpacingInput.value = '5';
        autoCenterToggle.checked = true;
        frontImageData = null;
        backImageData = null;
        updatePreview();
        showStatus('Sistema redefinido', 'success');
    }
    
    function showStatus(message, type) {
        statusMessage.textContent = message;
        statusMessage.className = 'status-message';
        statusMessage.classList.add(`status-${type}`);
        statusMessage.style.display = 'block';
        
        setTimeout(() => {
            statusMessage.style.display = 'none';
        }, 3000);
    }
    
    // Inicializar
    updatePreview();
});