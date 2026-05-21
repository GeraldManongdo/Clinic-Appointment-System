/**
 * Navigation Functions
 * Handles navbar, sidebar, and general navigation
 */

document.addEventListener("DOMContentLoaded", function () {
  initializeNavigation();
});

function initializeNavigation() {
  handleNavbarScroll();
  handleSidebarToggle();
  handleMobileMenu();
  handleActiveLinks();
}

// Handle navbar scroll effects
function handleNavbarScroll() {
  const navbar = document.querySelector(".navbar");
  if (!navbar) return;

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      navbar.classList.add("navbar-scrolled");
    } else {
      navbar.classList.remove("navbar-scrolled");
    }
  });
}

// Handle sidebar toggle (mobile)
function handleSidebarToggle() {
  const sidebarToggle = document.querySelector(".sidebar-toggle");
  const sidebar = document.querySelector(".sidebar");
  const sidebarOverlay = document.querySelector(".sidebar-overlay");

  if (!sidebarToggle || !sidebar) return;

  sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("open");
  });

  // Close sidebar when overlay is clicked
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener("click", () => {
      sidebar.classList.remove("open");
    });
  }

  // Close sidebar when a link is clicked
  const sidebarLinks = sidebar.querySelectorAll("a");
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", () => {
      sidebar.classList.remove("open");
    });
  });
}

// Handle mobile menu
function handleMobileMenu() {
  const navToggle = document.querySelector(".nav-toggle");
  const navMenu = document.querySelector(".nav-menu");

  if (!navToggle || !navMenu) return;

  navToggle.addEventListener("click", () => {
    navMenu.classList.toggle("show");
  });

  // Close menu when a link is clicked
  const navLinks = navMenu.querySelectorAll("a");
  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      navMenu.classList.remove("show");
    });
  });
}

// Handle active navigation links
function handleActiveLinks() {
  const currentPage = getCurrentPage();
  const navItems = document.querySelectorAll(".nav-item, .nav-link");

  navItems.forEach((item) => {
    const href = item.getAttribute("href");
    if (href && href.includes(currentPage)) {
      item.classList.add("active");
    } else {
      item.classList.remove("active");
    }
  });
}

// Get current page
function getCurrentPage() {
  const path = window.location.pathname;
  return path.split("/").pop() || "index.php";
}

// Navigate to page
function navigateTo(page) {
  window.location.href = "/" + page;
}

// Logout function
function logout() {
  if (confirm("Are you sure you want to logout?")) {
    window.location.href = "/public/admin/logout.php";
  }
}

// Export functions
window.clinicNav = {
  initializeNavigation,
  handleNavbarScroll,
  handleSidebarToggle,
  handleMobileMenu,
  handleActiveLinks,
  getCurrentPage,
  navigateTo,
  logout,
};
