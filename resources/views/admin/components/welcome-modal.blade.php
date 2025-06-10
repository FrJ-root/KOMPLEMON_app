<div id="welcomePopup" class="fixed inset-0 bg-black bg-opacity-90 flex justify-center items-center z-50">
    <div class="welcome-popup-inner text-center">
        <h2 class="text-3xl font-bold text-green-400 mb-4 typing-effect">Welcome Mr. Admin</h2>
        <p class="text-green-300 text-lg mb-6 fade-in-effect">System Initializing...</p>
        <p class="text-gray-500 text-sm fade-in-effect-delay">Access Granted. Enjoy your session.</p>
    </div>
    
    <style>
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        .typing-effect {
            overflow: hidden;
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: 0.15em;
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .fade-in-effect {
            opacity: 0;
            animation: fadeIn 2s ease-in 3.5s forwards;
        }
        
        .fade-in-effect-delay {
            opacity: 0;
            animation: fadeIn 2s ease-in 5.5s forwards;
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .fade-out {
            animation: fadeOut 1s ease-in forwards;
        }
    </style>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const welcomePopup = document.getElementById('welcomePopup');
        welcomePopup.style.display = 'flex';
        
        setTimeout(() => {
            welcomePopup.classList.add('fade-out');
            setTimeout(() => {
                welcomePopup.remove();
            }, 1000);
        }, 8000);
    });
</script>
