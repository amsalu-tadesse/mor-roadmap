let inactivityTimeout;

function resetInactivityTimeout() {
    clearTimeout(inactivityTimeout);

    inactivityTimeout = setTimeout(function () {
        window.location.href = '/logout';
    }, 7200000);
}

document.addEventListener('mousemove', resetInactivityTimeout);
document.addEventListener('keydown', resetInactivityTimeout);
document.addEventListener('scroll', resetInactivityTimeout);


resetInactivityTimeout();
