function addfavorite(url,name) {
   if (document.all) {
      window.external.addFavorite(url,name);
   } else if (window.sidebar) {
      window.sidebar.addPanel(name, url, "");
   }
}