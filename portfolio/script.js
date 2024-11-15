document.addEventListener("DOMContentLoaded", function() {
    const progressBars = document.querySelectorAll('.progress');
    
    progressBars.forEach(function(progressBar) {
        const progress = progressBar.getAttribute('data-progress');
        progressBar.style.width = `${progress}%`;
        progressBar.style.transition = "width 2s ease-in-out";
    });
});
