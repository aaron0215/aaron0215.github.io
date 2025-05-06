const components = [
  { id: 'navbar-container', path: '../components/navbar.html' },
  { id: 'about-container', path: '../components/about.html' },
  { id: 'experience-container', path: '../components/experience.html' },
  { id: 'education-container', path: '../components/education.html' },
  { id: 'skills-container', path: '../components/skills.html' },
  { id: 'projects-container', path: '../components/projects.html' },
  { id: 'certificates-container', path: '../components/certificates.html' },
  { id: 'other-experience-container', path: '../components/other-experience.html' },
  { id: 'contacts-container', path: '../components/contacts.html' }
];

// Wait for jQuery to be fully loaded
$(function() {
  console.log('jQuery ready, starting to load components');
  
  // Load each component
  components.forEach(function(component) {
    loadComponent(component.id, component.path);
  });
  
  // Function to load component with error handling
  function loadComponent(elementId, componentPath) {
    console.log(`Loading ${componentPath} into #${elementId}`);
    
    // First verify the element exists
    if (!$('#' + elementId).length) {
      console.error(`Element #${elementId} not found in the document`);
      return;
    }
    
    // Show loading state
    $('#' + elementId).html(`<div class="text-center p-3">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
      <p class="mt-2">Loading ${elementId}...</p>
    </div>`);
    
    // Load the component using jQuery AJAX
    $.ajax({
      url: componentPath,
      cache: false,  // Disable caching for development
      timeout: 5000, // 5 second timeout
      success: function(data) {
        // Check if we got valid data
        if (!data || data.length === 0) {
          $('#' + elementId).html(`
            <div class="alert alert-warning">
              <strong>Warning:</strong> Empty content received from ${componentPath}
            </div>
          `);
          console.warn(`Empty content received from ${componentPath}`);
          return;
        }
        
        // Insert the component HTML
        $('#' + elementId).html(data);
        console.log(`Successfully loaded ${componentPath}`);
      },
      error: function(xhr, status, error) {
        // Handle errors
        $('#' + elementId).html(`
          <div class="alert alert-danger">
            <strong>Error:</strong> Failed to load component
            <hr>
            <small>${status}: ${error}</small>
          </div>
        `);
        console.error(`Error loading ${componentPath}:`, status, error);
      }
    });
  }
});

// Back to top button functionality
$(function() {
  // Button will appear when user scrolls down 300px
  var scrollThreshold = 300;
  var backToTopBtn = $('#back-to-top-btn');
  
  // Show/hide button based on scroll position
  $(window).scroll(function() {
    if ($(this).scrollTop() > scrollThreshold) {
      backToTopBtn.addClass('btn-show');
    } else {
      backToTopBtn.removeClass('btn-show');
    }
  });
  
  // Smooth scroll to top when button is clicked
  backToTopBtn.click(function() {
    $('html, body').animate({scrollTop: 0}, 800);
    return false;
  });
});