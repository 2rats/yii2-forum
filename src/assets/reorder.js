$(document).ready(() => {
  const animationSpeed = 150;
  const fallbackTolerance = 3;
  const swapThreshold = 0.65;

  const initializeSortable = ($element, options = {}) => {
    $element.sortable({
      animation: animationSpeed,
      fallbackOnBody: true,
      fallbackTolerance: fallbackTolerance,
      swapThreshold: swapThreshold,
      ...options,
    });
  };

  initializeSortable($(".parent-reorder"));

  $(".child-items").each((_, element) => {
    initializeSortable($(element), { group: "shared", multiDrag: true });
  });

  $(".show-button, .hide-button").on("click", function () {
    const $button = $(this);
    $button.toggle();
    $button.siblings().toggle();
    $button.closest(".parent").find(".child-items").parent().toggle();
  });

  const showAlert = (selector) => {
    $(selector).fadeIn();
    setTimeout(() => {
      $(selector).fadeOut("fast");
    }, 2000);
  };

  $("#save").click(() => {
    const sortedData = [];
    const parentOrder = $(".parent-reorder").sortable("toArray");

    // unset first "3wm" because it's not a parent
    const index = parentOrder.indexOf("3wm");
    parentOrder.splice(index, 1);

    $(".child-items").each((index, element) => {
      sortedData.push({
        parentItem: parentOrder[index],
        childItems: $(element).sortable("toArray"),
      });
    });

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
      type: "POST",
      url: window.location.href,
      data: { data: JSON.stringify(sortedData) },
      headers: {
        "X-CSRF-Token": csrfToken,
      },
      success: () => showAlert(".alert-success"),
      error: () => showAlert(".alert-danger"),
    });
  });
});
