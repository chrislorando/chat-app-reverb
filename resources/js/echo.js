import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

// window.Echo.channel("channel").listen("PushMessage", (e) => {
//     alert("ALL");
// });

// window.Echo.private("channel.{id}").listen("PushMessage", (e) => {
//     alert("PRIVATE");
// });

// window.Echo.private(`room.1`)
//     .subscribed(function () {
//         console.log("subscribed To Channel");
//     })
//     .listenToAll(function () {
//         console.log("listening to channel");
//     })
//     .listen("NewMessageEvent", (data) => {
//         console.log(data);
//     });

// window.Echo.private("room.1").listen("PushMessage", (e) => {
//     console.log(e);
// });
