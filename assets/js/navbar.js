function stickyElement(e) {
  let navbar = document.querySelector(".menu");
  let logoMenuNav = document.querySelector("#logo-menu");
  let logoMenuInicial = document.querySelector(".logo-menu-fixed");
  let scrollValue = window.scrollY;

  if (scrollValue > 100) {
    navbar.classList.add("is-fixed");
    logoMenuNav.classList.add("fixed");
    logoMenuInicial.style.display = "none";
  } else if (scrollValue < 50) {
    navbar.classList.remove("is-fixed");
    logoMenuNav.classList.remove("fixed");
    logoMenuInicial.style.display = "block";
  }
}

window.addEventListener("scroll", stickyElement);
