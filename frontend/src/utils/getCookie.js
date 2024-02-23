function getCookie(cookieName) {
    const cookies = document.cookie.split(';');
    for (const cookie of cookies) {
        const [name, value] = cookie.split('=');

        const trimmedName = name.trim();

        if (trimmedName === cookieName) {
            return decodeURIComponent(value);
        }
    }
}

export default getCookie;