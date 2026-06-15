// --- ENREGISTREMENT DU SERVICE WORKER (PWA) ---
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Détecte dynamiquement le dossier racine de l'application à partir du manifest
        const manifestLink = document.querySelector('link[rel="manifest"]');
        const basePath = manifestLink ? manifestLink.getAttribute('href').replace('manifest.json', '') : '/';
        
        navigator.serviceWorker.register(`${basePath}sw.js`)
            .then(reg => console.log('Service Worker enregistré avec succès ! [Scope: ' + reg.scope + ']'))
            .catch(err => console.error('Échec de l\'enregistrement du Service Worker :', err));
    });
}
