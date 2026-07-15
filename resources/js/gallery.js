import Splide from '@splidejs/splide';
import '@splidejs/splide/css';

function initGalleries() {
    document.querySelectorAll('.mobiliario-galeria[data-splide-root]').forEach((root) => {
        if (root.dataset.splideMounted === 'true') {
            return;
        }

        const mainEl = root.querySelector('[data-splide-main]');
        const thumbsEl = root.querySelector('[data-splide-thumbs]');

        if (!mainEl) {
            return;
        }

        const main = new Splide(mainEl, {
            type: 'fade',
            rewind: true,
            pagination: false,
            height: '28rem',
        });

        if (thumbsEl) {
            const thumbs = new Splide(thumbsEl, {
                fixedWidth: 112,
                fixedHeight: 80,
                gap: 12,
                rewind: true,
                pagination: false,
                isNavigation: true,
                arrows: false,
                cover: true,
                focus: 'center',
                breakpoints: {
                    640: {
                        fixedWidth: 88,
                        fixedHeight: 64,
                    },
                },
            });

            main.sync(thumbs);
            main.mount();
            thumbs.mount();
        } else {
            main.mount();
        }

        root.dataset.splideMounted = 'true';
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGalleries);
} else {
    initGalleries();
}

document.addEventListener('livewire:navigated', initGalleries);
