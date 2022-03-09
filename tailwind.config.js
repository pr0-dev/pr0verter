const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "pr0-bg": "#161618",
                "pr0-main": "#EE4D2E",
                "pr0-dark": "#000000",
                "pr0-text": "#888888",
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
