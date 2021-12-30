var ModuleMenu = {
  init: function(href) {

    $('#moduleMenu a[href="' + href + '"]').addClass('active');

    ModuleMenu.updateFollowTotal();
  },
  updateFollowTotal: function() {
    $.ajax({
      url: 'api/follow/total',
      type: 'POST',
      success: function (response) {
        if (response.success) {

          if (response.total) {
            $('#moduleMenu a[href=follow] span').html(response.total).show();
          }

        } else {

          alert(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
    });
  }
}