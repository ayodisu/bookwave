let userBox = document.querySelector(".header .header-2 .user-box");

document.querySelector("#user-btn").onclick = () => {
  userBox.classList.toggle("active");
  stickyNavbar.classList.remove("active");
};

let stickyNavbar = document.querySelector(".header .header-2 .navbar");

document.querySelector("#menu-btn").onclick = () => {
  stickyNavbar.classList.toggle("active");
  userBox.classList.remove("active");
};

window.onscroll = () => {
  userBox.classList.remove("active");
  stickyNavbar.classList.remove("active");

  if (window.scrollY > 60) {
    document.querySelector(".header .header-2").classList.add("active");
  } else {
    document.querySelector(".header .header-2").classList.remove("active");
  }
};
