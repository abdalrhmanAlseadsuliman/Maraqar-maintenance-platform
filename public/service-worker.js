self.addEventListener('push', function(event) {
    const data = event.data.json();

    self.registration.showNotification(data.title, {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        data: { url: data.url }
    });
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(clients.openWindow(event.notification.data.url));
});
