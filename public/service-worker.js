self.addEventListener("install", function(event) {
    console.log('install', event);
    self.skipWaiting();
});

self.addEventListener("activate", function(event) {
    console.log('activate', event);
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', function(event) {    
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        console.log("notifications aren't supported or permission not granted!");
        return;
    }

     try {
        const data = event.data.json();
        console.log('Push event received OK:', JSON.stringify(data));

        event.waitUntil(
            self.registration.showNotification(data.title || 'No title', {
                body: data.body || '',
                icon: data.icon || '',   
                badge: data.badge || '',
                actions: data.actions || [],
                data: data.data || []
            })
            .then(() => console.log('Notification shown'))
            .catch(e => console.error('Notification error:', e))
        );

    } catch (e) {
        console.error('Push payload parse error:', e);
    }

});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    console.log(event.notification)

    const uid = event.notification.data.uid;

    if (event.action === 'notification_action') {
        event.waitUntil(
            clients.openWindow(`/chat/${uid}`)
        );
    } else {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});