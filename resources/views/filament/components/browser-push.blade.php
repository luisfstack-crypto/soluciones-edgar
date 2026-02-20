<script>
    document.addEventListener('livewire:initialized', () => {
        let lastUnreadCount = -1;

        // Solicitar permiso de notificaciones inmediatamente
        if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('Permiso de notificaciones concedido.');
                }
            });
        }

        const playNotificationSound = () => {
            const audio = new Audio('/audio/notification.mp3');
            audio.play().catch(e => {
                console.warn('El sonido no se pudo reproducir. El navegador suele requerir que el usuario haga clic en la página al menos una vez antes de permitir sonidos automáticos.');
            });
        };

        const showBrowserNotification = (title, body) => {
            if ("Notification" in window && Notification.permission === "granted") {
                new Notification(title || 'Soluciones Edgar', {
                    body: body || 'Tienes nuevas notificaciones.',
                    icon: '/images/logo.png',
                });
            }
        };

        window.addEventListener('notify', (event) => {
            playNotificationSound();
            showBrowserNotification(event.detail.title, event.detail.body);
        });

        const observeBadge = () => {
            const badgeSelectors = [
                '.fi-topbar-item-badge', 
                '.fi-header-badge', 
                '.fi-icon-button-badge',
                '.fi-notifications-link-badge'
            ];
            
            const badge = document.querySelector(badgeSelectors.join(','));
            
            if (badge) {
                let currentCount = parseInt(badge.innerText.trim()) || 0;
                
                if (lastUnreadCount !== -1 && currentCount > lastUnreadCount) {
                    playNotificationSound();
                    showBrowserNotification('Nueva Notificación', 'Acabas de recibir una actualización en tu panel.');
                }
                lastUnreadCount = currentCount;
            } else {
                lastUnreadCount = 0;
            }
        };

        Livewire.hook('commit', ({ component, respond }) => {
            respond(() => {
                if (component.name === 'filament.notifications.database-notifications') {
                    observeBadge();
                }
            });
        });

        const observer = new MutationObserver(() => {
            observeBadge();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: true
        });
        
        observeBadge();
    });
</script>
