jQuery(document).ready(function($) {
  $(".color-picker").wpColorPicker();

  /**
   * Uploading Images
   */
  var file_frame;

  $.fn.uploadMediaFile = function(button, preview_media) {
    var button_id = button.attr("id");
    var field_id = button_id.replace("_button", "");
    var preview_id = button_id.replace("_button", "_preview");

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $(this).data("uploader_title"),
      button: {
        text: $(this).data("uploader_button_text")
      },
      // only allow images here, no svgs etc
      library: {
        type: ["image/jpg", "image/jpeg", "image/png", "image/gif"]
      },
      multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on("select", function() {
      attachment = file_frame
        .state()
        .get("selection")
        .first()
        .toJSON();
      $("#" + field_id).val(attachment.id);
      if (preview_media) {
        $("#" + preview_id).attr("src", attachment.sizes.thumbnail.url);
      }
    });

    // Finally, open the modal
    file_frame.open();
  };

  $(".image_upload_button").click(function() {
    $.fn.uploadMediaFile($(this), true);
  });

  $(".image_delete_button").click(function() {
    $(this)
      .closest("td")
      .find(".image_data_field")
      .val("");
    $(this)
      .closest("td")
      .find(".image_preview")
      .attr("src", "");
    return false;
  });

  $("[data-required]").each(function() {
    var $this = $(this),
      r = $this.data("required"),
      required = $("#" + $this.data("required")),
      value = $this.data("required-value");

    if (required.find(":radio[value=" + value + "]").is(":checked")) {
      $('[data-required="' + r + '"]')
        .closest("tr")
        .show();
    } else {
      $('[data-required="' + r + '"]')
        .closest("tr")
        .hide();
    }

    required.change(function() {
      if (required.find(":radio[value=" + value + "]").is(":checked")) {
        $('[data-required="' + r + '"]')
          .closest("tr")
          .show();
      } else {
        $('[data-required="' + r + '"]')
          .closest("tr")
          .hide();
      }
    });
  });
});
