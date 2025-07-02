(function(){
    function updateConsent(granted){
        if (typeof gtag === 'function') {
            gtag('consent', 'update', {
                'ad_storage': granted ? 'granted' : 'denied',
                'analytics_storage': granted ? 'granted' : 'denied',
                'ad_user_data': granted ? 'granted' : 'denied',
                'ad_personalization': granted ? 'granted' : 'denied'
            });
        }
        if(granted){
            var ev = document.createEvent('Event');
            ev.initEvent('cookies-consent-granted', true, true);
            document.dispatchEvent(ev);
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        var stored = localStorage.getItem('cookieConsent');
        if(stored){
            updateConsent(stored === 'granted');
            return;
        }
        var banner = document.getElementById('cookie-banner');
        if(!banner) return;
        banner.style.display = 'block';
        banner.querySelector('.cookie-accept').addEventListener('click', function(){
            banner.style.display = 'none';
            localStorage.setItem('cookieConsent','granted');
            updateConsent(true);
        });
        banner.querySelector('.cookie-decline').addEventListener('click', function(){
            banner.style.display = 'none';
            localStorage.setItem('cookieConsent','denied');
            updateConsent(false);
        });
    });
})();
