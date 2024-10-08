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
                },
                // New components for angle-left and angle-right
                '.angle-left': {
                    height: '0.75rem',
                    width: '0.75rem', 
                    borderLeftWidth: '2px', 
                    borderBottomWidth: '2px', 
                    borderColor: 'white', 
                    position: 'absolute',
                    top: '50%', 
                    left: '0.5rem', 
                    cursor: 'pointer',
                    transform: 'translateY(-50%) rotate(45deg)',
                },
                '.angle-right': {
                    height: '0.75rem',
                    width: '0.75rem',
                    borderRightWidth: '2px', 
                    borderBottomWidth: '2px',
                    borderColor: 'white', 
                    position: 'absolute',
                    top: '50%', 
                    right: '0.5rem', 
                    cursor: 'pointer', 
                    transform: 'translateY(-50%) rotate(-45deg)',
                },
            });
        }),
    ],
};
