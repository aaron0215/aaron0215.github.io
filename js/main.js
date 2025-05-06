function loadComponent(elementId, componentPath) {
    fetch(componentPath)
      .then(response => {
        response.text()
      })
      .then(data => {
        document.getElementById(elementId).innerHTML = data;
      })
      .catch(error => console.error('Error loading component:', error));
}
  
  document.addEventListener('DOMContentLoaded', function() {
    // loadComponent('navbar-container', './components/navbar.html');
    loadComponent('about-container', '../components/about.html');
    loadComponent('experience-container', '../components/experience.html');
    // loadComponent('education-container', 'components/education.html');
    // loadComponent('skills-container', 'components/skills.html');
    // loadComponent('projects-container', 'components/projects.html');
    // loadComponent('certificates-container', 'components/certificates.html');
    // loadComponent('other-experience-container', 'components/other-experience.html');
    // loadComponent('contacts-container', 'components/contacts.html');
  });