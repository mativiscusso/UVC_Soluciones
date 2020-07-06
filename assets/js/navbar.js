function stickyElement(e) {
  let navbar = document.querySelector(".menu");
  let logoMenuNav = document.querySelector("#logo-menu");
  let scrollValue = window.scrollY;

  if (scrollValue > 100) {
    navbar.classList.add("is-fixed");
    logoMenuNav.classList.add("fixed");
  } else if (scrollValue < 50) {
    navbar.classList.remove("is-fixed");
    logoMenuNav.classList.remove("fixed");
  }
}

window.addEventListener("scroll", stickyElement);
