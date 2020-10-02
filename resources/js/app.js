require('./bootstrap');
import Echo from "laravel-echo";
// import Peer from 'simple-peer';

window.io = require('socket.io-client');
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

// function getPermissions(user_stream) {
// let user_stream = null;
// const peer = new Peer({
//     initiator: true,
//     stream: user_stream,
//     trickle: false
// });
// console.log(peer);
// }