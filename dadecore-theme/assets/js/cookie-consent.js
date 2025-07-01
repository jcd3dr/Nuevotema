/**
 * Dadecore Cookie Consent Banner
 */
document.addEventListener('DOMContentLoaded', function () {
    // Check if settings are localized
    if (typeof dadecoreCookieConsent === 'undefined') {
        console.warn('Cookie Consent settings not found. Aborting initialization.');
        return;
    }

    const settings = dadecoreCookieConsent;
    const consentCookieName = 'dadecore_cookie_consent';
    const gtmId = settings.gtmId;

    // DOM Elements
    let bannerElement = null;
    let modalElement = null;

    // Default consent state structure
    const defaultConsents = {
        necessary: true, // Always true
        analytics: settings.categories.analytics ? settings.categories.analytics.defaultEnabled : false,
        marketing: settings.categories.marketing ? settings.categories.marketing.defaultEnabled : false,
    };

    const consentMapping = {
        analytics: 'analytics_storage',
        marketing: 'ad_storage' // Also potentially 'ad_user_data', 'ad_personalization'
                                // For simplicity, mapping marketing to ad_storage.
                                // Functionality & Personalization could be mapped if categories are added.
    };


    function getConsent() {
        const cookie = document.cookie.split('; ').find(row => row.startsWith(consentCookieName + '='));
        if (cookie) {
            try {
                return JSON.parse(decodeURIComponent(cookie.split('=')[1]));
            } catch (e) {
                return null;
            }
        }
        // For broader compatibility and larger storage, check localStorage as primary
        const storedConsent = localStorage.getItem(consentCookieName);
        if (storedConsent) {
            try {
                return JSON.parse(storedConsent);
            } catch(e) {
                return null;
            }
        }
        return null;
    }

    function setConsent(consents, expirationDays = 365) {
        const consentStateToStore = {
            necessary: true, // Always true
            analytics: consents.analytics === true,
            marketing: consents.marketing === true,
            timestamp: new Date().getTime()
        };

        // Store in localStorage (primary)
        localStorage.setItem(consentCookieName, JSON.stringify(consentStateToStore));

        // Set a small cookie for server-side checks or as a fallback indicator
        const date = new Date();
        date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = consentCookieName + "=" + encodeURIComponent(JSON.stringify({ consented: true, ts: consentStateToStore.timestamp })) + ";" + expires + ";path=/;SameSite=Lax";

        updateGtmConsent(consentStateToStore);
    }

    function updateGtmConsent(consents) {
        if (typeof gtag !== 'function' || !settings.consentModeEnabled) {
            // console.log('gtag not available or Consent Mode disabled. Skipping GTM update.');
            return;
        }

        const gtmConsentPayload = {
            // Basic GCM types. functionality_storage, personalization_storage, security_storage can be added if needed
            'analytics_storage': consents.analytics ? 'granted' : 'denied',
            'ad_storage': consents.marketing ? 'granted' : 'denied', // Basic mapping
            // If you have more granular categories that map to GCM types:
            // 'functionality_storage': consents.functionality ? 'granted' : 'denied',
            // 'personalization_storage': consents.personalization ? 'granted' : 'denied',
        };

        // Potentially add ad_user_data and ad_personalization based on marketing consent
        if (settings.consentModeEnabled) { // Check again, just in case
            gtag('consent', 'update', gtmConsentPayload);
            // console.log('GTM Consent Updated:', gtmConsentPayload);

            // If marketing consent is granted, and you need to send ad_user_data, ad_personalization
            // gtag('set', 'u', { 'ads_data_redaction': !consents.marketing }); // Example if using data redaction
        }
    }

    function createBanner() {
        if (!settings.bannerEnabled) return;

        document.body.insertAdjacentHTML('beforeend', `
            <div id="dadecore-cookie-consent-banner">
                <div class="cookie-banner-content">
                    <div class="cookie-banner-text">
                        <p>${settings.bannerText}</p>
                    </div>
                    <div class="cookie-banner-actions">
                        <button id="dadecore-cookie-accept" class="accept">${settings.acceptText}</button>
                        <button id="dadecore-cookie-decline" class="decline">${settings.declineText}</button>
                        <button id="dadecore-cookie-settings" class="settings">${settings.settingsText}</button>
                    </div>
                </div>
            </div>
        `);
        bannerElement = document.getElementById('dadecore-cookie-consent-banner');
        bannerElement.style.display = 'block';

        document.getElementById('dadecore-cookie-accept').addEventListener('click', handleAcceptAll);
        document.getElementById('dadecore-cookie-decline').addEventListener('click', handleDeclineAll);
        document.getElementById('dadecore-cookie-settings').addEventListener('click', openSettingsModal);
    }

    function destroyBanner() {
        if (bannerElement) {
            bannerElement.remove();
            bannerElement = null;
        }
    }

    function handleAcceptAll() {
        const consents = {
            necessary: true,
            analytics: true,
            marketing: true,
            // ... any other categories set to true
        };
        setConsent(consents);
        destroyBanner();
        destroyModal(); // Ensure modal is also closed
    }

    function handleDeclineAll() {
        const consents = {
            necessary: true,
            analytics: false,
            marketing: false,
            // ... any other categories set to false
        };
        setConsent(consents);
        destroyBanner();
        destroyModal(); // Ensure modal is also closed
    }

    function createModal() {
        let categoriesHtml = '';
        for (const key in settings.categories) {
            if (settings.categories.hasOwnProperty(key)) {
                const cat = settings.categories[key];
                const isChecked = (key === 'necessary' || cat.currentConsent === true); // currentConsent will be populated when modal opens
                const isDisabled = key === 'necessary';

                categoriesHtml += `
                    <div class="category">
                        <div class="category-header">
                            <label for="cookie-cat-${key}">${cat.label}</label>
                            <label class="dadecore-cookie-toggle ${isDisabled ? 'disabled' : ''}">
                                <input type="checkbox" id="cookie-cat-${key}" data-category="${key}" ${isChecked ? 'checked' : ''} ${isDisabled ? 'disabled' : ''}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="category-description">
                            ${cat.description}
                        </div>
                    </div>
                `;
            }
        }

        document.body.insertAdjacentHTML('beforeend', `
            <div id="dadecore-cookie-settings-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>${settings.bannerTitle || 'Cookie Settings'}</h3>
                        <button class="modal-close-button" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        ${categoriesHtml}
                    </div>
                    <div class="modal-footer">
                        <button id="dadecore-cookie-save-settings">${settings.saveSettingsText || 'Save Settings'}</button>
                    </div>
                </div>
            </div>
        `);

        modalElement = document.getElementById('dadecore-cookie-settings-modal');
        modalElement.style.display = 'flex';

        modalElement.querySelector('.modal-close-button').addEventListener('click', closeSettingsModal);
        document.getElementById('dadecore-cookie-save-settings').addEventListener('click', handleSaveSettings);

        // Close modal if clicking outside the content
        modalElement.addEventListener('click', function(event) {
            if (event.target === modalElement) {
                closeSettingsModal();
            }
        });
    }

    function destroyModal() {
        if (modalElement) {
            modalElement.remove();
            modalElement = null;
        }
    }

    function openSettingsModal() {
        const currentConsent = getConsent() || defaultConsents; // Use existing or defaults
        // Update settings object with current consent for checkboxes in modal
        for (const key in settings.categories) {
            if (settings.categories.hasOwnProperty(key)) {
                settings.categories[key].currentConsent = currentConsent[key];
            }
        }
        destroyBanner(); // Hide banner when modal is open
        createModal();
    }

    function closeSettingsModal() {
        destroyModal();
        // Re-show banner if no consent has been given yet.
        // If consent was already given and they just opened settings, don't reshow banner.
        if (!getConsent()) {
             createBanner();
        }
    }

    function handleSaveSettings() {
        const newConsents = { necessary: true }; // Necessary is always true
        const checkboxes = modalElement.querySelectorAll('.modal-body input[type="checkbox"]');

        checkboxes.forEach(checkbox => {
            const category = checkbox.dataset.category;
            if (category !== 'necessary') { // Necessary is handled above
                newConsents[category] = checkbox.checked;
            }
        });

        setConsent(newConsents);
        destroyModal();
        // No need to show banner again as settings are saved.
    }

    // --- Initialization ---
    const existingConsent = getConsent();

    if (!settings.bannerEnabled) {
        // If banner is disabled in theme options, but GTM/Consent Mode is on,
        // ensure GTM gets at least the default consent values (which it should on page load from PHP)
        // Or, if there's existing consent, re-apply it for GTM.
        if (existingConsent && settings.consentModeEnabled && typeof gtag === 'function') {
            updateGtmConsent(existingConsent);
        } else if (settings.consentModeEnabled && typeof gtag === 'function') {
            // This ensures GTM is aware of the defaults if no consent has been given and banner is off
            // However, the PHP part in header.php should already do this. This is a fallback.
            const phpDefaults = {
                analytics: settings.phpDefaultConsents.analytics_storage === 'granted',
                marketing: settings.phpDefaultConsents.ad_storage === 'granted'
            };
            updateGtmConsent(phpDefaults);
        }
        return; // Do not proceed to show banner
    }

    if (!existingConsent) {
        createBanner();
    } else {
        // Consent already exists, just ensure GTM is updated if Consent Mode is active
        if (settings.consentModeEnabled && typeof gtag === 'function') {
            updateGtmConsent(existingConsent);
        }
    }
});
