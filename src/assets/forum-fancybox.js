$(document).ready(function () {
  $(`.image-group`).each(function () {
    Fancybox.bind(this, "img", {
      groupAll: true,
    });
  });
});
