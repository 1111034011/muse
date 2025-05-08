const track = document.querySelector('.carousel-track');
const slides = Array.from(track.querySelectorAll('img')); // 🔥重點，改這裡
const dotsContainer = document.querySelector('.dots');

let currentIndex = 2; // 初始顯示第三張

function updateSlides() {
    slides.forEach((slide) => {
        slide.className = '';
    });

    const total = slides.length;
    const getIndex = (offset) => (currentIndex + offset + total) % total;

    slides[getIndex(-2)].classList.add('prev2');
    slides[getIndex(-1)].classList.add('prev');
    slides[getIndex(0)].classList.add('active');
    slides[getIndex(1)].classList.add('next');
    slides[getIndex(2)].classList.add('next2');

    updateDots();
}

function moveNext() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlides();
}

function moveToSlide(index) {
    currentIndex = index;
    updateSlides();
}

function updateDots() {
    const dots = document.querySelectorAll('.dot');
    dots.forEach(dot => dot.classList.remove('active'));
    if (dots[currentIndex]) {
        dots[currentIndex].classList.add('active');
    }
}

function createDots() {
    slides.forEach((slide, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        dot.addEventListener('click', () => moveToSlide(index));
        dotsContainer.appendChild(dot);
    });
}

createDots();
updateSlides();

setInterval(moveNext, 3000);
