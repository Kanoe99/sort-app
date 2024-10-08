document.addEventListener('DOMContentLoaded', () => {
    const slides = document.getElementById('carousel-inner');
    const nextButton = document.getElementById('next-slide');
    const prevButton = document.getElementById('prev-slide');

    let currentIndex = 0;
    const blocksToShow = 3; // Number of blocks to show per slide
    const totalBlocks = slides.children.length;
    const blockWidth = slides.children[0].offsetWidth *
        blocksToShow; // Get the width of each block multiplied by blocksToShow

    // Clone the first few slides for looping
    const cloneCount = Math.min(blocksToShow, totalBlocks); // Clone up to 'blocksToShow' items
    for (let i = 0; i < cloneCount; i++) {
        const clone = slides.children[i].cloneNode(true);
        slides.appendChild(clone); // Append clone to the end
    }

    // Update total blocks after cloning
    const totalClones = slides.children.length;

    // Move to the next slide
    nextButton.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex >= totalClones / blocksToShow) {
            currentIndex = 0; // Reset to the first set of slides
            slides.style.transition = 'none'; // Disable transition for reset
            slides.style.transform = `translateX(0)`;
            setTimeout(() => {
                slides.style.transition =
                    'transform 0.3s ease-in-out'; // Re-enable transition
                currentIndex++; // Move to the next set
                slides.style.transform = `translateX(-${currentIndex * blockWidth}px)`;
            }, 0);
        } else {
            slides.style.transform = `translateX(-${currentIndex * blockWidth}px)`;
        }
    });

    // Move to the previous slide
    prevButton.addEventListener('click', () => {
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = Math.ceil(totalBlocks / blocksToShow) -
                1; // Go to the last set of slides
            slides.style.transition = 'none'; // Disable transition for reset
            slides.style.transform =
                `translateX(-${currentIndex * blockWidth}px)`; // Move to last set
            setTimeout(() => {
                slides.style.transition =
                    'transform 0.3s ease-in-out'; // Re-enable transition
                currentIndex--; // Move to the previous set
                slides.style.transform = `translateX(-${currentIndex * blockWidth}px)`;
            }, 0);
        } else {
            slides.style.transform = `translateX(-${currentIndex * blockWidth}px)`;
        }
    });

    // Optionally, adjust the block width dynamically if the window is resized
    window.addEventListener('resize', () => {
        const newBlockWidth = slides.children[0].offsetWidth * blocksToShow;
        slides.style.transform = `translateX(-${currentIndex * newBlockWidth}px)`;
    });
});