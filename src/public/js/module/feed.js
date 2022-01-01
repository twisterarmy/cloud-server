var ModuleFeed = {
  template: {
    feed: {
      item: {
        append: function(feed, userName, time, message, reTwist) {
          if (reTwist === undefined || reTwist.length == 0) {
            var rt = false;
          } else {
            var rt = $(
              $('<div/>', {
                'class': 'retwist'
              }).append(
                $('<div/>', {
                  'class': 'info'
                }).append(
                  $('<a/>', {
                    'href': 'people/' + reTwist.userName
                  }).append(reTwist.userName)
                ).append(
                  $('<span/>', {
                    'class': 'time'
                  }).append(
                    reTwist.time
                  )
                )
              ).append(reTwist.message)
            );
          }
          $(feed).append(
            $('<div/>', {
              'class': 'item',
              'data-username': userName
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
                'class': 'message'
              }).append(
                $('<div/>', {
                  'class': 'info'
                }).append(
                  $('<a/>', {
                    'href': 'people/' + userName
                  }).append(userName)
                ).append(
                  $('<span/>', {
                    'class': 'time'
                  }).append(
                    time
                  )
                )
              ).append(rt).append(
                $('<div/>', {
                  'class': (message != '' ? 'quote' : '')
                }).append(message)
              )
            )
          );
        }
      }
    }
  },
  loadAvatar: function(feed, userName) {
    $.ajax({
      url: 'api/user/avatar',
      type: 'POST',
      data: {
        userName: userName
      },
      success: function (response) {

        if (response.success) {

          if (response.avatar) {
            $(feed).find('div[data-username="' + userName + '"] .avatar img').attr('src', response.avatar);
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
  load: function(feed, reFresh) {
    $.ajax({
      url: 'api/post/get',
      type: 'POST',
      data: {
        userName: $(feed).data('username')
      },
      success: function (response) {
        if (response.success) {

          if (reFresh) {
            $(feed).html('');
          }

          $(response.posts).each(function() {
            ModuleFeed.template.feed.item.append(feed, this.userName, this.time, this.message, this.reTwist);
            ModuleFeed.loadAvatar(feed, this.userName);
          });

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