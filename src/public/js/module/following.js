var ModuleFollowing = {
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
                    'src': '/api/image?hash=' + userName,
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
            ).append(
              $('<div/>', {
                'class': 'action'
              }).append(
                $('<i/>', {
                  'class': 'bi bi-x-circle',
                  'title': 'Unfollow',
                  'onclick': 'ModuleFollowing.unFollow(\'' + list + '\', \'' + userName + '\', false)',
                })
              )
            )
          );
        }
      }
    }
  },
  load: function(list, reFresh) {
    $.ajax({
      url: 'api/follow/get',
      type: 'POST',
      data: {},
      success: function (response) {
        if (response.success) {

          if (reFresh) {
            $(list).html('');
          }

          $(response.users).each(function() {
            ModuleFollowing.template.list.item.append(list, this.userName);
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
  unFollow: function(list, userName, reFresh) {
    $.ajax({
      url: 'api/follow/delete',
      type: 'POST',
      data: {
        userName: userName
      },
      success: function (response) {
        if (response.success) {

          if (reFresh) {
            ModuleFollowing.load(list, reFresh);
          }

          $(document).trigger('ModuleFollowing.unFollow:success', [response]);

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