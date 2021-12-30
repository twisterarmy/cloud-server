var ModulePost = {
  add: function() {

    var input = $('#modulePost textarea');

    $.ajax({
      url: 'api/post/add',
      type: 'POST',
      data: {
        message: input.val()
      },
      success: function (response) {

        if (response.success) {

          input.val('');

          $(document).trigger('modulePost.add:success', [response]);

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