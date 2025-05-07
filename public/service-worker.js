self.addEventListener('push', function(event) {
    console.log('📦 Push Event Raw:', event);

    if (event.data) {

        const data = event.data.json();
        const x = data.data.url
        console.log('✅ Push Event Data:', data);
        console.log('✅ Push Event Data:', x);

        self.registration.showNotification(data.title || 'إشعار', {
            body: data.body || 'لا توجد تفاصيل',
            icon:  'https://maintenance.maraqar.com/white-logo.webp',
            badge: 'https://maintenance.maraqar.com/white-logo.webp',
            data: {
                url: x || '/',
            },
        });
    } else {
        console.warn('❌ No data found in push event');
    }
});

self.addEventListener('notificationclick', function(event) {
    console.log('🔗 Notification Clicked, URL:', event.notification?.data?.url);
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification?.data?.url || '/')
    );
});



// self.addEventListener('push', function(event) {
//     const data = event.data.json();
//     console.log('Push event data:', data);

//     self.registration.showNotification('egrthrt', {
//         body: data.body,
//         icon: data.icon,
//         badge: data.badge,
//         data: { url: data.url }
//     });
// });

// self.addEventListener('notificationclick', function(event) {
//     event.notification.close();
//     event.waitUntil(clients.openWindow(event.notification.data.url));
// });
