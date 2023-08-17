jQuery(document).ready(function ($) {
  $(".save-post-icon").on("click", function (e) {
    e.preventDefault();
    var postID = $(this).data("post-id");
    var icon = $(this).find("i");

    // AJAX call to save post ID as user meta
    $.ajax({
      type: "POST",
      url: "/wordpress.php/wp-admin/admin-ajax.php",
      data: {
        action: "save_post",
        post_id: postID,
      },
      success: function (response) {
        if (icon.hasClass("fa-regular")) {
          icon.removeClass("fa-regular");
          icon.addClass("fa-solid");
        } else {
          icon.removeClass("fa-solid");
          icon.addClass("fa-regular");
        }
      },
    });
  });
});
