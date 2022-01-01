var ModulePost = {
  init: function(element) {
    ModulePost.loadAvatar(element);
  },
  loadAvatar: function(element) {
    $.ajax({
      url: 'api/user/avatar',
      type: 'GET',
      success: function (response) {

        if (response.success) {

          if (response.avatar) {
            $(element).find('img').attr('src', response.avatar);
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
  add: function() {

    var input  = $('#modulePost > .message > textarea');

    $.ajax({
      url: 'api/post/add',
      type: 'POST',
      data: {
        message: input.val()
      },
      success: function (response) {

        if (response.success) {

          input.val('');

          $(document).trigger('ModulePost.add:success', [response]);

        } else {

          alert(response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  },
}