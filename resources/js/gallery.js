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

        const pauseInactiveVideos = () => {
            mainEl.querySelectorAll('video').forEach((video) => {
                const slide = video.closest('.splide__slide');

                if (! slide?.classList.contains('is-active')) {
                    video.pause();
                }
            });
        };

        const main = new Splide(mainEl, {
            type: 'fade',
            rewind: true,
            pagination: false,
            height: '28rem',
        });

        main.on('moved', pauseInactiveVideos);
        main.on('move', pauseInactiveVideos);

        if (thumbsEl) {
            const thumbs = new Splide(thumbsEl, {
                fixedWidth: 112,
                fixedHeight: 80,
                gap: 12,
                rewind: true,
                pagination: false,
                isNavigation: true,
                arrows: false,
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

function scheduleInitGalleries() {
    requestAnimationFrame(initGalleries);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGalleries);
} else {
    initGalleries();
}

document.addEventListener('livewire:navigated', initGalleries);

const observeGalleries = () => {
    if (!document.body) {
        return;
    }

    new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            for (const node of mutation.addedNodes) {
                if (!(node instanceof HTMLElement)) {
                    continue;
                }

                if (
                    node.matches('.mobiliario-galeria[data-splide-root]')
                    || node.querySelector('.mobiliario-galeria[data-splide-root]')
                ) {
                    scheduleInitGalleries();
                    return;
                }
            }
        }
    }).observe(document.body, { childList: true, subtree: true });
};

if (document.body) {
    observeGalleries();
} else {
    document.addEventListener('DOMContentLoaded', observeGalleries);
}
