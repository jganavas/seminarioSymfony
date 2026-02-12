import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('Theme controller connected');
        // Aplicar tema guardado al conectar
        const theme = localStorage.getItem('theme') || 'light';
        this.setTheme(theme);
    }

    toggle() {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    setTheme(theme) {
        const main = document.querySelector('main');
        const header = document.querySelector('header');
        
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.style.backgroundColor = '#111827';
            document.body.style.backgroundColor = '#111827';
            if (main) main.style.backgroundColor = '#111827';
            if (header) header.style.backgroundColor = '#1f2937'; // gray-800
            document.title = '🌙 Modo Oscuro - Mi paginilla';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.backgroundColor = '#f9fafb';
            document.body.style.backgroundColor = '#f9fafb';
            if (main) main.style.backgroundColor = '#f9fafb';
            if (header) header.style.backgroundColor = '#ffffff'; // white
            document.title = '☀️ Modo Claro - Mi paginilla';
        }
        localStorage.setItem('theme', theme);
        console.log('Theme set to:', theme);
        console.log('HTML classes:', document.documentElement.className);
    }
}
