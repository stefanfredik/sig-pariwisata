/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: '#2563EB',
                secondary: '#7C3AED',
                success: '#10B981',
                warning: '#F59E0B',
                danger: '#EF4444',
            },
        },
    },
    plugins: [],
}
