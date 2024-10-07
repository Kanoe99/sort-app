/** @type {import('tailwindcss').Config} */
import plugin from 'tailwindcss/plugin';

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                "black": "#060606"
            },
            fontFamily: {
                "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
            },
            fontSize: {
                "2xs": ".625rem" // 10px
            },
            // keyframes: {
            //     dash: {
            //       '0%': { backgroundPosition: '0% 100%' },
            //       '100%': { backgroundPosition: '100% 100%' },
            //     },
            // },
            // animation: {
            //     dash: 'dash 2s linear infinite',
            // },
        },
    },
    plugins: [
        plugin(function({ addComponents }) {
            addComponents({
                '.no-scrollbar::-webkit-scrollbar': {
                    display: 'none', // Chrome, Safari, Edge, and Opera
                },
                '.no-scrollbar': {
                    '-ms-overflow-style': 'none', // Internet Explorer 10+
                    'scrollbar-width': 'none', // Firefox
                },
                '.fade-bottom': {
                    zIndex: '3',
                    position: 'absolute',
                    width: '100%',
                    pointerEvents: 'none', // Allows interaction with scroll content
                },
                '.fade-bottom-gradient': {
                    bottom: '0',
                    height: '120px', // Adjust this as needed
                    background: 'linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgb(0, 0, 0) 100%)',
                },
                '.blur-bottom': {
                    position: 'absolute',
                    bottom: '0',
                    left: '0',
                    right: '0',
                    height: '30px',
                    backdropFilter: 'blur(1px)',
                    backgroundColor: 'rgba(0, 0, 0, 0.2)',
                }
            });
        }),
    ],
};
