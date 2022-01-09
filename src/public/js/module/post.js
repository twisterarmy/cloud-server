var ModulePost = {
  loadAvatar: function() {
    $.ajax({
      url: 'api/user/avatar',
      type: 'GET',
      success: function (response) {

        if (response.success) {

          if (response.avatar) {
            $('#modulePostAvatar').attr('src', response.avatar).show();
          }

        } else {

          console.log(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
    });
  },
  send: function() {

    $.ajax({
      url: 'api/post/add',
      type: 'POST',
      data: {
        message: $('#modulePostMessage').val()
      },
      success: function (response) {

        if (response.success) {

          $('#modulePostMessage').val('');
          $('#modulePostPreview').hide();
          $('#modulePostPreview .text').html('');

          $(document).trigger('ModulePost.add:success', [response]);

        } else {

          console.log(response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  },
  preview: function() {

    $.ajax({
      url: 'api/post/preview',
      type: 'POST',
      data: {
        message: $('#modulePostMessage').val()
      },
      success: function (response) {

        if (response.success) {

          if (response.format == '') {
            $('#modulePostPreview').removeClass('active');
            $('#modulePostPreview .text').html('');
          } else {
            $('#modulePostPreview').addClass('active');
            $('#modulePostPreview .text').html(response.format);
          }

        } else {

          console.log(response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  },
}

$(document).ready(function() {

  // Init module
  ModulePost.loadAvatar();

  // Event listeners
  $(document).on('ModulePost.add:success', function(/*event, response*/) {
    ModuleFeed.load('#moduleFeed', 1, true);
  });

  $('#modulePostMessage').on('keyup', function() {
    ModulePost.preview();
  });

  $('#modulePostSend').on('click', function() {
    ModulePost.send();
  });
});
