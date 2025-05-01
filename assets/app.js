import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
const ws = new WebSocket('ws://localhost:8080');

ws.onmessage = function(e) {
    const notification = JSON.parse(e.data);
    
    if (notification.type === 'interview_reminder') {
        // Affichez la notification dans l'UI
        const notifCount = document.getElementById('notification-count');
        notifCount.textContent = parseInt(notifCount.textContent) + 1;
        
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.innerHTML += `
            <div class="notification-item">
                <div class="notification-title">${notification.message}</div>
                <div class="notification-time">${notification.time}</div>
            </div>
        `;
    }
};
