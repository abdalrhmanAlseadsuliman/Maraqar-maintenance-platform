document.addEventListener("DOMContentLoaded", function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(function(registration) {
                console.log('Service Worker Registered:', registration);
                subscribeUserToPush(registration);
            })
            .catch(function(error) {
                console.error('Service Worker Registration Failed:', error);
            });
    }
});

function subscribeUserToPush(registration) {
    const publicKey = "BGUzCrfprnPvGXVNj8cn5RJiieMffWb4DGAUYrMFc22FPaysfEaU1yAJd-_kjs_5zWBMVThsYrHTBtuoupbFlXQ"; // ضع هنا VAPID_PUBLIC_KEY من .env

    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicKey)
    }).then(function(subscription) {
        console.log('Push Subscription:', subscription);

        fetch('/save-subscription', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        }).then(response => response.json())
          .then(data => console.log('Subscription Saved:', data))
          .catch(error => console.error('Subscription Failed:', error));
    }).catch(function(error) {
        console.error('Push Subscription Failed:', error);
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');
    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
