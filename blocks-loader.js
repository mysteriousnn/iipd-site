// Функция загрузки блоков
function loadBlocks() {
    fetch('blocks-data.json')
        .then(response => response.json())
        .then(data => {
            const mainContainer = document.getElementById('main-blocks-container');
            const additionalContainer = document.getElementById('additional-blocks-container');
            
            if (!mainContainer || !additionalContainer) return;
            
            // Сохраняем блоки в глобальной переменной
            window.allBlocks = data;
            
            // Очищаем контейнеры
            mainContainer.innerHTML = '';
            additionalContainer.innerHTML = '';
            
            // Фильтруем блоки по типу
            const mainBlocks = data.filter(block => block.type === 'main')
                                   .sort((a, b) => a.order - b.order);
            const additionalBlocks = data.filter(block => block.type === 'additional')
                                         .sort((a, b) => a.order - b.order);
            
            // Загружаем основные блоки
            mainBlocks.forEach(block => {
                const blockElement = createBlockElement(block);
                mainContainer.appendChild(blockElement);
            });
            
            // Загружаем дополнительные блоки
            additionalBlocks.forEach(block => {
                const blockElement = createBlockElement(block);
                additionalContainer.appendChild(blockElement);
            });
            
            // После загрузки блоков добавляем обработчики
            setupBlockButtons();
        })
        .catch(error => {
            console.error('Ошибка загрузки блоков:', error);
        });
}

// Функция создания элемента блока
function createBlockElement(block) {
    const card = document.createElement('div');
    card.className = 'button-card';
    
    // Определяем тип контента
    const contentType = block.content_type || 'link';
    const hasContent = contentType === 'content' && block.content && block.content.trim().length > 0;
    
    // Ссылка для кнопки
    let buttonLink = '#';
    let buttonTarget = '_self';
    
    if (contentType === 'content' && block.content_slug) {
        // Для блока с контентом - открываем на той же странице
        buttonLink = `block.php?slug=${encodeURIComponent(block.content_slug)}`;
        buttonTarget = '_self';
    } else if (contentType === 'link' && block.link) {
        // Для обычной ссылки
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
            <span class="link-icon">${hasContent ? '📄' : '→'}</span>
        </a>
    `;
    
    return card;
}

// Функция для настройки обработчиков всех кнопок блоков
function setupBlockButtons() {
    // Находим все кнопки блоков с контентом
    const blockButtons = document.querySelectorAll('.block-content-button');
    
    blockButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const contentType = this.getAttribute('data-content-type');
            
            if (contentType === 'content') {
                // Для блока с контентом открываем на этой же странице
                const blockId = this.getAttribute('data-block-id');
                const block = window.allBlocks?.find(b => b.id === blockId);
                
                if (block && block.content_slug) {
                    e.preventDefault();
                    
                    // Отслеживание в Google Analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'block_content_click', {
                            'event_category': 'engagement',
                            'event_label': block.title
                        });
                    }
                    
                    // Переходим на страницу блока
                    window.location.href = `block.php?slug=${encodeURIComponent(block.content_slug)}`;
                }
            }
            // Для обычных ссылок оставляем стандартное поведение
        });
    });
}

// Функция для показа контента блока на главной странице (для обратной совместимости)
function loadBlockContentPage(block) {
    // Восстанавливаем отступ для body
    document.body.style.paddingTop = 'var(--header-height)';
    
    // Показываем весь контент, если был скрыт
    const contentContainer = document.querySelector('.content .container');
    if (contentContainer) {
        contentContainer.style.display = 'none';
    }
    
    // Создаем контейнер для контента
    const mainContent = document.querySelector('.content');
    if (!mainContent) return;
    
    // Используем content_title или title блока
    const contentTitle = block.content_title || block.title;
    
    // Создаем HTML-структуру страницы с контентом
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
    
    // Добавляем HTML в content
    mainContent.innerHTML = blockContentHTML;
    
    // Добавляем обработчики для кнопок
    document.getElementById('back-to-main-blocks').addEventListener('click', function() {
        location.reload(); // Перезагружаем страницу для возврата
    });
    
    document.getElementById('share-block').addEventListener('click', function() {
        const currentUrl = window.location.origin + '/block.php?slug=' + (block.content_slug || block.id);
        
        // Используем современный API Clipboard
        navigator.clipboard.writeText(currentUrl).then(() => {
            // Показать сообщение об успехе
            const successMsg = document.createElement('div');
            successMsg.className = 'copy-success';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Ссылка скопирована в буфер обмена!';
            document.body.appendChild(successMsg);
            
            // Скрыть сообщение через 2 секунды
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 2000);
        }).catch(err => {
            // Fallback для старых браузеров
            const textArea = document.createElement('textarea');
            textArea.value = currentUrl;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            // Показать сообщение об успехе
            const successMsg = document.createElement('div');
            successMsg.className = 'copy-success';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Ссылка скопирована в буфер обмена!';
            document.body.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 2000);
        });
    });
    
    // Обновляем заголовок страницы
    document.title = contentTitle + ' - iipd';
    
    // Прокручиваем в самый верх
    window.scrollTo(0, 0);
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Загружаем блоки
    loadBlocks();
});
