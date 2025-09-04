import "./bootstrap";
import "flowbite";
// import { Drawer } from 'flowbite';

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log('VAPID',window.VAPID_PUBLIC_KEY);
console.log('CSRF',csrfToken);

navigator.serviceWorker.register('/service-worker.js').then(registration => {
    Notification.requestPermission().then(async permission => {
        if (permission !== 'granted') return;

        try {
            const existingSubscription = await registration.pushManager.getSubscription();

            if (existingSubscription) {
                const key = urlBase64ToUint8Array(window.VAPID_PUBLIC_KEY);
                const isSameKey = existingSubscription.options.applicationServerKey &&
                                  compareKeys(existingSubscription.options.applicationServerKey, key);

                if (!isSameKey) {
                    await existingSubscription.unsubscribe();
                    console.log('Existing subscription unsubscribed due to different VAPID key');
                } else {
                    console.log('Already subscribed with the same key');
                    return;
                }
            }

            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(window.VAPID_PUBLIC_KEY)
            });

            console.log('SUBSCRIBE', subscription);

            await fetch('/push/subscribe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(subscription)
            });
        } catch (err) {
            console.error('Push subscription failed:', err);
        }
    });
});

function compareKeys(a, b) {
    if (a.byteLength !== b.byteLength) return false;
    const arrA = new Uint8Array(a);
    const arrB = new Uint8Array(b);
    for (let i = 0; i < arrA.length; i++) {
        if (arrA[i] !== arrB[i]) return false;
    }
    return true;
}

function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}