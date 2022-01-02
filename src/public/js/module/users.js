var ModuleUsers = {
  template: {
    list: {
      item: {
        append: function(list, userName) {
          $(list).append(
            $('<div/>', {
              'class': 'item' + (userName == $(list).data('username') ? ' active' : '')
            }).append(
              $('<div/>', {
                'class': 'avatar'
              }).append(
                $('<a/>', {
                  'href': 'people/' + userName
                }).append(
                  $('<img/>', {
                    'src': '',
                    'alt': '',
                  })
                )
              )
            ).append(
              $('<div/>', {
                'class': 'info'
              }).append(
                $('<a/>', {
                  'href': 'people/' + userName
                }).append(userName)
              )
            )
          );
        }
      }
    }
  },
  load: function(list, reFresh) {
    $.ajax({
      url: 'api/user/random',
      type: 'POST',
      data: {},
      success: function (response) {
        if (response.success) {

          if (reFresh) {
            $(list).html('');
          }

          $(response.users).each(function() {
            ModuleUsers.template.list.item.append(list, this.userName);
          });

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