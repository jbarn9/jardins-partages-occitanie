document.addEventListener("DOMContentLoaded", () => {
  const eventsCard = document.getElementById("eventsCard");
  const loader = document.getElementById("loader");
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "/google/calendar/coming-soon");
  loader.classList.remove("d-none");
  xhr.onload = function () {
    if (xhr.status === 200) {
      loader.classList.add("d-none");
      eventsCard.innerHTML = xhr.responseText;
      // console.log("ok", xhr.responseText);
    } else {
      console.log(
        "Erreur lors de la récupération des événements : " + xhr.status
      );
    }
  };
  xhr.send();
});
