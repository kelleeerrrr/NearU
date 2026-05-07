// Simple Admin Navigation - Direct approach
document.addEventListener('DOMContentLoaded', function() {
    // Handle admin navigation clicks
    const adminNavLinks = document.querySelectorAll('.action-card');
    
    adminNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            if (href && href !== window.location.pathname) {
                // Simple direct navigation
                window.location.href = href;
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
    
    // Handle notification mark as read functionality
    window.markNotificationAsRead = function(notificationId) {
        fetch(`/admin/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.csrfToken,
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                // Remove unread indicator and update UI
                const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                    notificationItem.classList.add('read');
                }
            }
        }).catch(error => {
            console.error('Error marking notification as read:', error);
        });
    };
});
