const getLocalStorage = (key) => {
  const spaghettiLocalStorage = localStorage.getItem("spaghetti");

  if (spaghettiLocalStorage) {
    return JSON.parse(spaghettiLocalStorage)[key];
  }

  return null;
};

const setLocalStorage = (key, value) => {
  const spaghettiLocalStorage = localStorage.getItem("spaghetti");

  if (spaghettiLocalStorage) {
    const spaghettiLocalStorageArray = JSON.parse(spaghettiLocalStorage);

    spaghettiLocalStorageArray[key] = value;

    return localStorage.setItem(
      "spaghetti",
      JSON.stringify(spaghettiLocalStorageArray)
    );
  }

  return localStorage.setItem(
    "spaghetti",
    JSON.stringify({ isDarkTheme: value })
  );
};

document
  .querySelector("li.dark-theme-activator")
  .addEventListener("click", () => {
    const dataTheme = document.querySelector("html").getAttribute("data-theme");
    const isDarkTheme = dataTheme === "dark";

    document
      .querySelector("html")
      .setAttribute("data-theme", isDarkTheme ? "light" : "dark");

    setLocalStorage("isDarkTheme", !isDarkTheme);
  });
