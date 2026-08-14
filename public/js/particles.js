(function () {
    'use strict';
    const CONFIG = {
        minRadius: 0.8, maxRadius: 3.2,
        minSpeed: 0.22, maxSpeed: 1.05,
        colors: ['rgba(245,158,11,', 'rgba(251,191,36,', 'rgba(217,119,6,'],
        connectDistance: 130, connectOpacity: 0.18, glowIntensity: 12
    };

    class Particle {
        constructor(W, H) { this.reset(W, H, true); }
        reset(W, H, randomY = false) {
            this.x = Math.random() * W;
            this.y = randomY ? Math.random() * H : H + 10;
            this.r = CONFIG.minRadius + Math.random() * (CONFIG.maxRadius - CONFIG.minRadius);
            this.speed = CONFIG.minSpeed + Math.random() * (CONFIG.maxSpeed - CONFIG.minSpeed);
            this.vx = (Math.random() - 0.5) * 0.6;
            this.vy = -this.speed;
            this.color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)];
            this.alpha = 0.2 + Math.random() * 0.7;
        }
        update(W, H) {
            this.x += this.vx;
            this.y += this.vy;
            if (this.y < -10) this.reset(W, H, false);
        }
        draw(ctx) {
            ctx.save();
            ctx.shadowColor = this.color + '0.9)';
            ctx.shadowBlur = CONFIG.glowIntensity;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = this.color + this.alpha + ')';
            ctx.fill();
            ctx.restore();
        }
    }

    function init() {
        const canvas = document.getElementById('particles-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let W = canvas.width = window.innerWidth;
        let H = canvas.height = window.innerHeight;
        const particles = Array.from({ length: 120 }, () => new Particle(W, H));

        window.addEventListener('resize', () => {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
        });

        function loop() {
            ctx.clearRect(0, 0, W, H);
            particles.forEach(p => { p.update(W, H); p.draw(ctx); });
            requestAnimationFrame(loop);
        }
        loop();
    }
    document.addEventListener('DOMContentLoaded', init);
})();
