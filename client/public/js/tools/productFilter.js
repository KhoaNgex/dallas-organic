function filterCate(add_url, cate_id) {
  window.location.replace(add_url + cate_id);
  return true;
}

$(document).ready(function () {
  window.scrollTo(0, 500);
  myhref = window.location.href;
  if (myhref.includes("cate")) {
    last_pos = myhref.lastIndexOf("/");
    x = document.getElementById(myhref.substring(last_pos + 1));
    x.classList.add("active");
  } else {
    x = document.getElementById("cate-0");
    x.classList.add("active");
  }
});
