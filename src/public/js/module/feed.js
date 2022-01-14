var ModuleFeed = {
  template: {
    feed: {
      item: {
        append: function(feed, item) {
          if (item.reTwist === undefined || item.reTwist.length == 0) {
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
                    'href': 'people/' + item.reTwist.userName
                  }).append(item.reTwist.userName)
                ).append(
                  $('<span/>', {
                    'class': 'time'
                  }).append(
                    item.reTwist.time
                  )
                )
              ).append(item.reTwist.message)
            );
          }
          $(feed).append(
            $('<div/>', {
              'class': 'item',
              'data-user-name': item.userName,
              'data-meta': item.meta
            }).append(
              $('<div/>', {
                'class': 'avatar'
              }).append(
                $('<a/>', {
                  'href': 'people/' + item.userName
                }).append(
                  $('<img/>', {
                    'src': '',
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
                    'href': 'people/' + item.userName
                  }).append(item.userName)
                ).append(
                  $('<span/>', {
                    'class': 'time'
                  }).append(
                    item.time
                  )
                )
              ).append(rt).append(
                $('<div/>', {
                  'class': (item.message != '' ? 'quote' : '')
                }).append(item.message)
              )
            ).append(
              $('<div/>', {
                'class': 'actions'
              }).append(
                $('<span/>', {
                  'class': 'bi bi-reply-fill',
                  'title': 'Reply',
                  'onclick': 'ModuleFeed.reply($(this).closest(\'.item\').data(\'meta\'))'
                })
              ).append(
                $('<span/>', {
                  'class': 'bi bi-quote',
                  'title': 'Quote',
                  'onclick': 'ModuleFeed.retwist($(this).closest(\'.item\').data(\'meta\'))'
                })
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
      type: 'GET',
      data: {
        userName: userName
      },
      success: function (response) {

        if (response.success) {

          if (response.avatar) {
            $(feed).find('div[data-user-name="' + userName + '"] .avatar img').attr('src', response.avatar).show();
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
  load: function(feed, page, reFresh) {
    $.ajax({
      url: 'api/post/get',
      type: 'GET',
      data: {
        page: page,
        userName: $(feed).data('username')
      },
      success: function (response) {
        if (response.success) {

          if (reFresh) {
            $(feed).html('');
          }

          $(response.posts).each(function() {
            ModuleFeed.template.feed.item.append(feed, this);
            ModuleFeed.loadAvatar(feed, this.userName);
          });

          if (response.page > 0) {
            $(feed).append(
              $('<div/>', {
                'class': 'loadMore',
                'onclick': 'ModuleFeed.load(\'' + feed + '\', ' + response.page + ', true)'
              }).text('More')
            );
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