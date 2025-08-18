import "./bootstrap";
import "flowbite";

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log('VAPID',window.VAPID_PUBLIC_KEY);
console.log('CSRF',csrfToken);
navigator.serviceWorker.register('/service-worker.js');
Notification.requestPermission().then(permission => {
    if (permission === 'granted') {
        // Kirim subscription ke server
        navigator.serviceWorker.ready.then(registration => {
            registration.pushManager.subscribe({
                userVisibleOnly: true,
                // applicationServerKey: window.VAPID_PUBLIC_KEY
                applicationServerKey: urlBase64ToUint8Array(
                    window.VAPID_PUBLIC_KEY
                )
            }).then(subscription => {
                // POST ke Laravel: simpan subscription
                console.log('SUBSCRIBE',subscription);
                fetch('/push/subscribe', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(subscription)
                });
            });
        });
    }
});

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