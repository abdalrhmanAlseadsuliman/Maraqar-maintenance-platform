if ('serviceWorker' in navigator) {
    // 🔹 كود Service Worker مدمج داخل `Blob`
    const serviceWorkerScript = `
        self.addEventListener('push', function(event) {
            const data = event.data ? event.data.json() : {};

            event.waitUntil(
                self.registration.showNotification(data.title || 'New Notification', {
                    body: data.body || 'You have a new message!',
                    icon: '/icon.png',
                    badge: '/badge.png',
                    data: { url: data.url || '/' },
                })
            );
        });

        self.addEventListener('notificationclick', function(event) {
            event.notification.close();
            event.waitUntil(clients.openWindow(event.notification.data.url));
        });
    `;

    // 🔹 إنشاء ملف مؤقت من الـ Blob
    const blob = new Blob([serviceWorkerScript], { type: 'application/javascript' });
    const serviceWorkerURL = URL.createObjectURL(blob);

    // 🔹 تسجيل الـ Service Worker من الـ Blob
    navigator.serviceWorker.register(serviceWorkerURL)
        .then((registration) => {
            console.log('✅ Service Worker Registered!', registration);
        });

    navigator.serviceWorker.ready.then((registration) => {
        registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: 'BMQKby0OfiJfPKJ9cY8ssB9_uRPiOjEYCt1rDqDg4cQ_8aHj-g2Hfox6QgRMUeBgiZvzhLzbSKcgJlHCQI-s6JU'
        }).then((subscription) => {
            fetch('/save-subscription', {
                method: 'POST',
                body: JSON.stringify(subscription),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => console.log('✅ Subscription saved:', data))
            .catch(error => console.error('❌ Error saving subscription:', error));
        });
    });
}
