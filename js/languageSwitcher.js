function switchLanguage(language) {
    const en = document.getElementById('lang-en');
    const tr = document.getElementById('lang-tr');
    if (language === 'en') {
        en.className = 'block';
        tr.className = 'hidden';
    } else {
        en.className = 'hidden';
        tr.className = 'block';
    }
}

document.getElementById('lang-en-btn').addEventListener('click', () => {
    switchLanguage('en')
})

document.getElementById('lang-tr-btn').addEventListener('click', () => {
    switchLanguage('tr')
})