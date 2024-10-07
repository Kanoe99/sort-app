@php
    $classes = 'flex justify-center overflow-hidden gap-[.3rem] relative';
@endphp

<div {{ $attributes(['class' => $classes]) }} id="carousel">
    <!-- Carousel Container (with overflow-hidden) -->
    <div class="flex !w-[93%] overflow-hidden relative" id="carousel-slides">
        <!-- Slot Content (Blocks) -->
        <div class="flex transition-transform gap-2 duration-300 ease-in-out" id="carousel-inner">
            {{ $slot }}
        </div>
    </div>

    <!-- Left and Right Arrows -->
    <button id="prev-slide" class="bg-white w-4 h-4 absolute top-1/2 left-0 transform -translate-y-1/2">
    </button>
    <button id="next-slide" class="bg-white w-4 h-4 absolute right-0 top-1/2 transform -translate-y-1/2">
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.getElementById('carousel-inner');
        const nextButton = document.getElementById('next-slide');
        const prevButton = document.getElementById('prev-slide');

        let currentIndex = 0;
        const blocksToShow = 2; // Number of blocks to show per slide
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
</script>
