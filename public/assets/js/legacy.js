document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('active');
            mobileMenuBtn.setAttribute('aria-expanded', String(isOpen));
            mobileMenuBtn.innerHTML = isOpen
                ? '<i class="fas fa-times" aria-hidden="true"></i>'
                : '<i class="fas fa-bars" aria-hidden="true"></i>';
        });

        navLinks.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars" aria-hidden="true"></i>';
            });
        });
    }

    const header = document.getElementById('header');
    if (header) {
        const updateHeader = () => header.classList.toggle('scrolled', window.scrollY > 100);
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            const targetId = anchor.getAttribute('href');
            const targetElement = targetId && targetId !== '#' ? document.querySelector(targetId) : null;
            if (!targetElement) return;
            event.preventDefault();
            window.scrollTo({ top: targetElement.offsetTop - 80, behavior: 'smooth' });
        });
    });

    document.querySelectorAll('[data-post-slider]').forEach((slider) => {
        const track = slider.querySelector('[data-slider-track]');
        const previous = slider.querySelector('[data-slider-prev]');
        const next = slider.querySelector('[data-slider-next]');
        const status = slider.querySelector('[data-slider-status]');
        const controls = slider.querySelector('.post-slider-controls');
        if (!track || !previous || !next) return;

        const items = Array.from(track.children).filter((item) => item.matches('[data-slider-item]'));
        const maximumScroll = () => Math.max(0, track.scrollWidth - track.clientWidth);
        const positions = () => {
            const left = track.getBoundingClientRect().left;
            return items.map((item) => item.getBoundingClientRect().left - left + track.scrollLeft);
        };
        const update = () => {
            const maximum = maximumScroll();
            const canScroll = items.length > 1 && maximum > 1;
            if (controls) controls.hidden = !canScroll;
            previous.hidden = next.hidden = !canScroll;
            if (status) status.hidden = !canScroll;
            previous.disabled = track.scrollLeft <= 1;
            next.disabled = track.scrollLeft >= maximum - 1;

            if (status && items.length) {
                const bounds = track.getBoundingClientRect();
                const visible = items.map((item, index) => ({ bounds: item.getBoundingClientRect(), index }))
                    .filter((item) => item.bounds.right > bounds.left + 1 && item.bounds.left < bounds.right - 1);
                if (visible.length) {
                    const first = visible[0].index + 1;
                    const last = visible[visible.length - 1].index + 1;
                    const text = `${first === last ? first : `${first}–${last}`} of ${items.length}`;
                    if (status.textContent !== text) status.textContent = text;
                }
            }
        };
        const goTo = (position) => track.scrollTo({
            left: Math.max(0, Math.min(position, maximumScroll())),
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
        });
        const move = (direction) => {
            const stops = positions();
            const target = direction > 0
                ? stops.find((position) => position > track.scrollLeft + 2) ?? maximumScroll()
                : stops.reverse().find((position) => position < track.scrollLeft - 2) ?? 0;
            goTo(target);
        };

        previous.addEventListener('click', () => move(-1));
        next.addEventListener('click', () => move(1));
        track.addEventListener('keydown', (event) => {
            if (event.target !== track || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();
            if (event.key === 'Home') goTo(0);
            else if (event.key === 'End') goTo(maximumScroll());
            else move(event.key === 'ArrowRight' ? 1 : -1);
        });
        track.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    });

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const fields = contactForm.querySelectorAll('input, textarea');
            const [name, email, subject, message] = Array.from(fields, (field) => field.value.trim());
            const body = `Hello Sir,\n\nI visited your website.\n\nName: ${name}\nEmail: ${email}\n\nMessage:\n${message}`;
            const mailto = `mailto:Razaqkhan100@gmail.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailto;
        });
    }

    const animatedElements = document.querySelectorAll('.about-image, .vm-box, .product-card');
    if (animatedElements.length) {
        animatedElements.forEach((element) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        });
        const animateOnScroll = () => animatedElements.forEach((element) => {
            if (element.getBoundingClientRect().top < window.innerHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
        animateOnScroll();
        window.addEventListener('scroll', animateOnScroll, { passive: true });
    }
});
