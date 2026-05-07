// Admin Navigation Handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle admin navigation clicks
    const adminNavLinks = document.querySelectorAll('.action-card');
    
    adminNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const href = this.getAttribute('href');
            if (href && href !== window.location.pathname) {
                // Show loading state
                this.style.opacity = '0.6';
                
                // Navigate with fetch for smoother experience
                fetch(href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                }).then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Navigation failed');
                }).then(html => {
                    // Extract the main content area
                    const contentMatch = html.match(/<div class="screen active"[^>]*>([\s\S]*?)<\/div>/);
                    if (contentMatch && contentMatch[1]) {
                        const currentScreen = document.querySelector('.screen.active');
                        if (currentScreen) {
                            currentScreen.innerHTML = contentMatch[1];
                        }
                        
                        // Update URL without full reload
                        history.pushState({}, '', href);
                        
                        // Update active state
                        document.querySelectorAll('.action-card').forEach(card => {
                            card.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                }).catch(error => {
                    console.error('Navigation error:', error);
                    this.style.opacity = '1';
                    // Fallback to regular navigation
                    window.location.href = href;
                });
            }
        });
    });
    
    // Set active state based on current URL
    const currentPath = window.location.pathname;
    document.querySelectorAll('.action-card').forEach(card => {
        const href = card.getAttribute('href');
        if (href && currentPath.includes(href)) {
            card.classList.add('active');
        } else {
            card.classList.remove('active');
        }
    });
});
