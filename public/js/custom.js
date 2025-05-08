// Fix for chart lagging and shaking
document.addEventListener('DOMContentLoaded', function() {
  // Check if Chart.js is loaded
  if (typeof Chart !== 'undefined') {
    // Override Chart.js defaults to improve performance
    Chart.defaults.animation = false; // Disable all animations
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    
    // Add a small delay before rendering charts to ensure DOM is ready
    setTimeout(function() {
      // Find all canvas elements and set fixed dimensions
      document.querySelectorAll('canvas').forEach(function(canvas) {
        if (canvas.parentNode) {
          // Set fixed dimensions to prevent layout shifts
          canvas.style.width = '100%';
          canvas.style.height = '250px';
          canvas.style.minHeight = '250px';
          canvas.style.maxHeight = '250px';
          
          // Apply hardware acceleration
          canvas.style.transform = 'translateZ(0)';
          canvas.style.backfaceVisibility = 'hidden';
          canvas.style.perspective = '1000px';
        }
      });
    }, 100);
  }
});