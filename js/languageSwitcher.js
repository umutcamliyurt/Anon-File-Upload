function switchLanguage(language) {
    const en = document.getElementById('lang-en');
    const tr = document.getElementById('lang-tr');
    if (language === 'en') {
        en.style.display = 'block';
        tr.style.display = 'none';
    } else {
        en.style.display = 'none';
        tr.style.display = 'block';
    }
}