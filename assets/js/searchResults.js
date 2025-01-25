// ORDER POSTS
function orderPosts() {
  console.log(document.getElementById("order").value);

  const searchResults = document.querySelector("#search_results");
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/search/drive");
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  loader.classList.remove("d-none");
  xhr.onload = function () {
    if (xhr.status === 200) {
      loader.classList.add("d-none");
      searchResults.innerHTML = xhr.responseText;
    } else {
      searchResults.innerHTML =
        "Erreur lors de la récupération des événements : " +
        xhr.status +
        ". Veuillez réessayer ou rafraichir la page.";
    }
  };
  xhr.send(
    "order=" + encodeURIComponent(document.getElementById("order").value)
  );
}

// SEARCH RESULTS
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const iconSearch = document.querySelector(".icon-search");
  searchInput.addEventListener("keyup", function () {
    searchInput.value.length > 0
      ? (iconSearch.innerHTML = "<i class='fa-solid fa-close'></i>")
      : (iconSearch.innerHTML = "<i class='fa-solid fa-search'></i>");
  });
  iconSearch.addEventListener("click", function () {
    const icon = iconSearch.children[0];
    // console.log(icon);
    if (icon.classList.contains("fa-close")) {
      searchInput.value = "";
      iconSearch.innerHTML = "<i class='fa-solid fa-search'></i>";
      displayAllDriveResults();
    }
  });
});

const loader = document.getElementById("loader");
function displayAllDriveResults() {
  const order = document.getElementById("order").value;
  const searchResults = document.querySelector("#search_results");
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/search/drive");
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  loader.classList.remove("d-none");
  xhr.onload = function () {
    if (xhr.status === 200) {
      loader.classList.add("d-none");
      searchResults.innerHTML = xhr.responseText;
    } else {
      searchResults.innerHTML =
        "Erreur lors de la récupération des événements : " +
        xhr.status +
        " - " +
        xhr.responseText;
    }
  };
  xhr.send(
    "keywords=" +
      encodeURIComponent(document.getElementById("searchInput").value) +
      "&order=" +
      encodeURIComponent(order)
  );
}
