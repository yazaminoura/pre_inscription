<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Page Not Found') }}</title>
    <link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}"> <!-- ملف CSS الخاص بك -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3a8a, #60a5fa), url('{{ asset('dist/assets/img/404-cool.jpg') }}') no-repeat center/cover;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-blend-mode: multiply;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .error-container {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            max-width: 700px;
            background: rgba(1, 0, 0, 0.9);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            animation: zoomIn 0.7s ease-out;
        }
        .error-image {
            width: 200px;
            height: 100Px; /* لضمان ظهور الصورة بنسب صحيحة */
            margin-bottom: 25px;
            
            border-radius: 12px;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            display: block;
            object-fit: cover;
            margin-left: 30%
        }
        .error-image:hover {
            transform: scale(1.2) rotate(5deg);
            box-shadow: 0 10px 30px rgba(0, 64, 128, 0.5);
        }
        .message {
            font-size: 40px;         
            text-shadow: 0 0 10px rgba(211, 47, 47, 0.5);
            font-weight: 600;
            color: #ff004d;
            margin-bottom: 20px; 
            animation: glow 1.5s infinite alternate;
            text-shadow: 0 0 10px #ff004d, 0 0 20px #ff004d, 0 0 40px #ff004d;
        }
        .description {
            font-size: 23px;
            color:rgba(0, 128, 255, 0.5);
            margin-bottom: 35px;
            line-height: 1.7;
            font-weight: 400;
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(45deg, #004080, #0284c7);
            color: black;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.4s ease;
            box-shadow: 0 5px 20px rgba(0, 64, 128, 0.5);
            position: relative;
            overflow: hidden;
        }
        .btn-home::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s ease, height 0.5s ease;
        }
        .btn-home:hover::after {
            width: 200px;
            height: 200px;
        }
        .btn-home:hover {
            background: linear-gradient(45deg, #0284c7, #0ea5e9);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 64, 128, 0.7);
        }
        @keyframes zoomIn {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes glow {
            0% { text-shadow: 0 0 10px rgba(211, 47, 47, 0.5); }
            100% { text-shadow: 0 0 20px rgba(211, 47, 47, 0.8); }
        }
        @media (max-width: 600px) {
            .error-container {
                padding: 25px;
                margin: 15px;
            }
            .error-image {
                width: 180px;
                border-radius: 8px;
            }
            .message {
                font-size: 30px;
            }
            .description {
                font-size: 16px;
            }
            .btn-home {
                padding: 12px 30px;
                font-size: 16px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="error-container">
        <img src="{{ asset('dist/assets/img/404_icon.jpg') }}" class="error-image" alt="404 Not Found">
        <div class="message">{{ __('Oops! Page not found') }}</div>
        <div class="description">{{ __('It seems you’ve wandered off the path. Let’s get you back!') }}</div>
        <a href="#" class="btn-home" onclick="goBack()">{{ __('Go Back') }}</a>
    </div>
    <script>
        // دالة للرجوع إلى الصفحة السابقة
        function goBack() {
            // استخدام history.back() للرجوع خطوة للوراء في السجل
            if (history.length > 1) {
                history.back();
            } else {
                // إذا ما فيه سجل، يرجع للصفحة الرئيسية
                window.location.href = '{{ route('candidat.form') }}';
            }
        }

        // تأثير جزيئات خفيف في الخلفية
        const canvas = document.createElement('canvas');
        document.body.appendChild(canvas);
        canvas.style.position = 'absolute';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.zIndex = '0';
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const particlesArray = [];
        const numberOfParticles = 50;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 5 + 1;
                this.speedX = Math.random() * 1 - 0.5;
                this.speedY = Math.random() * 1 - 0.5;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.size > 0.2) this.size -= 0.1;
            }
            draw() {
                ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
                if (particlesArray[i].size <= 0.2) {
                    particlesArray.splice(i, 1);
                    i--;
                    particlesArray.push(new Particle());
                }
            }
            requestAnimationFrame(animate);
        }

        init();
        animate();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>