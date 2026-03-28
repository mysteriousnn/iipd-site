function loadBlocks() {
    fetch('blocks-data.json')
        .then(response => response.json())
        .then(data => {
            const mainContainer = document.getElementById('main-blocks-container');
            const additionalContainer = document.getElementById('additional-blocks-container');
            
            if (!mainContainer || !additionalContainer) return;
            
            
            window.allBlocks = data;
            
            
            mainContainer.innerHTML = '';
            additionalContainer.innerHTML = '';
            
            
            const mainBlocks = data.filter(block => block.type === 'main')
                                   .sort((a, b) => a.order - b.order);
            const additionalBlocks = data.filter(block => block.type === 'additional')
                                         .sort((a, b) => a.order - b.order);
            
            
            mainBlocks.forEach(block => {
                const blockElement = createBlockElement(block);
                mainContainer.appendChild(blockElement);
            });
            
           
            additionalBlocks.forEach(block => {
                const blockElement = createBlockElement(block);
                additionalContainer.appendChild(blockElement);
            });
            
            
            setupBlockButtons();
        })
        .catch(error => {
            console.error('Ошибка загрузки блоков:', error);
        });
}


function createBlockElement(block) {
    const card = document.createElement('div');
    card.className = 'button-card';
    
    
    const contentType = block.content_type || 'link';
    const hasContent = contentType === 'content' && block.content && block.content.trim().length > 0;
    
    
    let buttonLink = '#';
    let buttonTarget = '_self';
    
    if (contentType === 'content' && block.content_slug) {
       
        buttonLink = `block.php?slug=${encodeURIComponent(block.content_slug)}`;
        buttonTarget = '_self';
    } else if (contentType === 'link' && block.link) {
       
        buttonLink = block.link;
        buttonTarget = '_blank';
    }
    
    card.innerHTML = `
        <div class="button-icon-container">
            <i class="${block.icon}"></i>
        </div>
        <h3 class="button-title">${block.title}</h3>
        <p class="button-description">${block.description}</p>
        <a href="${buttonLink}" 
           class="button-link block-content-button" 
           data-block-id="${block.id}"
           ${hasContent ? 'data-has-content="true"' : ''}
           ${buttonTarget === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''}
           data-content-type="${contentType}">
            <span>${block.button_text}</span>
            <span class="link-icon">${hasContent ? '' : '→'}</span>
        </a>
    `;
    
    return card;
}


function setupBlockButtons() {
    
    const blockButtons = document.querySelectorAll('.block-content-button');
    
    blockButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const contentType = this.getAttribute('data-content-type');
            
            if (contentType === 'content') {
               
                const blockId = this.getAttribute('data-block-id');
                const block = window.allBlocks?.find(b => b.id === blockId);
                
                if (block && block.content_slug) {
                    e.preventDefault();
                    
              
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'block_content_click', {
                            'event_category': 'engagement',
                            'event_label': block.title
                        });
                    }
                    
            
                    window.location.href = `block.php?slug=${encodeURIComponent(block.content_slug)}`;
                }
            }
       
        });
    });
}


function loadBlockContentPage(block) {
    
    document.body.style.paddingTop = 'var(--header-height)';
    
   
    const contentContainer = document.querySelector('.content .container');
    if (contentContainer) {
        contentContainer.style.display = 'none';
    }
    
   
    const mainContent = document.querySelector('.content');
    if (!mainContent) return;
    
   
    const contentTitle = block.content_title || block.title;
    
    
    const blockContentHTML = `
        <div class="container">
            <section class="load-level-section" style="border-left: 5px solid #888;">
                <div class="level-header">
                    <h2 class="level-title">
                        <span class="level-icon" style="background: rgba(136, 136, 136, 0.1); color: #888;">
                            <i class="${block.icon}"></i>
                        </span>
                        ${contentTitle}
                    </h2>
                    <div class="current-level" style="background: rgba(136, 136, 136, 0.1); border-color: #888; color: #888;">
                        <i class="fas fa-cube"></i>
                        <span>Информация</span>
                    </div>
                </div>
                
                <div class="level-description" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color);">
                    <div class="level-description-text" style="font-size: 1.1rem; line-height: 1.7;">
                        ${block.content || '<p style="text-align:center;color:rgba(255,255,255,0.5);font-style:italic;padding:40px;">Контент не найден</p>'}
                    </div>
                </div>
                
                <div class="comment-actions">
                    <button id="share-block" class="button-link share-button">
                        <i class="fas fa-share-alt"></i>
                        <span>Поделиться</span>
                    </button>
                    <button id="back-to-main-blocks" class="button-link back-button">
                        <i class="fas fa-arrow-left"></i>
                        <span>Вернуться к блокам</span>
                    </button>
                </div>
            </section>
        </div>
    `;
    
   
    mainContent.innerHTML = blockContentHTML;
    
   
    document.getElementById('back-to-main-blocks').addEventListener('click', function() {
        location.reload();
    });
    
    document.getElementById('share-block').addEventListener('click', function() {
        const currentUrl = window.location.origin + '/block.php?slug=' + (block.content_slug || block.id);
        
    
        navigator.clipboard.writeText(currentUrl).then(() => {
       
            const successMsg = document.createElement('div');
            successMsg.className = 'copy-success';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Ссылка скопирована в буфер обмена!';
            document.body.appendChild(successMsg);
            
         
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 2000);
        }).catch(err => {
       
            const textArea = document.createElement('textarea');
            textArea.value = currentUrl;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
      
            const successMsg = document.createElement('div');
            successMsg.className = 'copy-success';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Ссылка скопирована в буфер обмена!';
            document.body.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 2000);
        });
    });
    
   
    document.title = contentTitle + ' - iipd';
    
   
    window.scrollTo(0, 0);
}


document.addEventListener('DOMContentLoaded', function() {
  
    loadBlocks();
});
