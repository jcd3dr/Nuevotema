// Main JavaScript file
document.addEventListener('DOMContentLoaded', () => {
  smoothScrolling();
  hoverAnimations();
  mobileMenuToggle();
});

function smoothScrolling() {
  const links = document.querySelectorAll('a[href^="#"]:not([href="#"])');

  links.forEach((link) => {
    link.addEventListener('click', (e) => {
      const target = document.querySelector(link.getAttribute('href'));

      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
}

function hoverAnimations() {
  const items = document.querySelectorAll('.hover-animate');

  items.forEach((item) => {
    item.addEventListener('mouseenter', () => {
      item.classList.add('is-hovered');
    });

    item.addEventListener('mouseleave', () => {
      item.classList.remove('is-hovered');
    });
  });
}

function mobileMenuToggle() {
  const nav = document.getElementById('site-navigation');
  const button = document.getElementById('menu-toggle');

  if (nav && button) {
    button.addEventListener('click', () => {
      nav.classList.toggle('is-open');
      const expanded = nav.classList.contains('is-open');
      button.setAttribute('aria-expanded', expanded);
    });
  }
}
