<script>
    document.addEventListener('livewire:initialized', () => {
        let lastUnreadCount = -1;
        
        const playNotificationSound = () => {
            const audio = new Audio('/audio/notification.mp3');
            audio.volume = 0.7;
            audio.play().catch(e => {});
        };

        const showBrowserNotification = (title, body) => {
            if ("Notification" in window && Notification.permission === "granted") {
                new Notification(title || 'Soluciones Edgar', {
                    body: body || 'Tienes una nueva actualización en tu panel.',
                    icon: '/images/logo.png',
                });
            }
        };

        window.addEventListener('notify', (event) => {
            playNotificationSound();
            showBrowserNotification(event.detail.title, event.detail.body);
        });

        window.addEventListener('filament-notifications-sent', (event) => {
            playNotificationSound();
        });

        const checkNotificationBadge = () => {
            const selectors = [
                '.fi-topbar-database-notifications-trigger .fi-icon-button-badge',
                '.fi-topbar-item-badge',
                '.fi-header-badge',
                '[data-notification-indicator]',
                '.fi-notifications-link-badge'
            ];
            
            const badge = document.querySelector(selectors.join(','));
            
            if (badge) {
                const currentCount = parseInt(badge.innerText.trim()) || 0;
                
                if (lastUnreadCount !== -1 && currentCount > lastUnreadCount) {
                    playNotificationSound();
                    showBrowserNotification('Nueva Notificación', 'Tienes actualizaciones pendientes en tu bandeja.');
                }
                lastUnreadCount = currentCount;
            } else {
                if (lastUnreadCount !== 0) {
                    lastUnreadCount = 0;
                }
            }
        };

        Livewire.hook('commit', ({ component, respond }) => {
            respond(() => {
                checkNotificationBadge();
            });
        });

        const observer = new MutationObserver(() => {
            checkNotificationBadge();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            characterData: true
        });

        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission();
        }

        checkNotificationBadge();
    });
</script>
