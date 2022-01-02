var ModuleFollowing = {
  template: {
    list: {
      item: {
        append: function(list, userName) {
          $(list).append(
            $('<div/>', {
              'class': 'item' + (userName == $(list).data('username') ? ' active' : ''),
              'data-username': userName
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
                $('<div/>', {
                  'class': 'username'
                }).append(
                  $('<a/>', {
                    'href': 'people/' + userName
                  }).append(userName)
                )
              ).append(
                $('<div/>', {
                  'class': 'location'
                })
              ).append(
                $('<div/>', {
                  'class': 'bio'
                })
              ).append(
                $('<div/>', {
                  'class': 'url'
                })
              ).append(
                $('<div/>', {
                  'class': 'tox'
                })
              ).append(
                $('<div/>', {
                  'class': 'bitmessage'
                })
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
  loadProfile: function(list, userName) {
    $.ajax({
      url: 'api/user/profile',
      type: 'GET',
      data: {
        userName: userName
      },
      success: function (response) {

        if (response.success) {

          $(list).find('div[data-username="' + userName + '"] .username > a').html(response.profile.fullName ? response.profile.fullName : response.profile.userName);
          $(list).find('div[data-username="' + userName + '"] .location').html(response.profile.location);
          $(list).find('div[data-username="' + userName + '"] .url').html(response.profile.url == '' ? '' : $('<a/>',{'href':response.profile.url,'class':'bi bi-link','target':'_blank','title':'Website'}));
          $(list).find('div[data-username="' + userName + '"] .bio').html(response.profile.bio);
          $(list).find('div[data-username="' + userName + '"] .bitMessage').html(response.profile.bitMessage == '' ? '' : $('<a/>',{'href':'bitmessage:' + response.profile.bitMessage,'class':'bi bi-send','title':'BitMessage'}));
          $(list).find('div[data-username="' + userName + '"] .tox').html(response.profile.tox == '' ? '' : $('<a/>',{'href':'tox:' + response.profile.tox,'class':'bi bi-chat-square-dots','title':'TOX'}));


        } else {

          console.log(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  },
  loadAvatar: function(list, userName) {
    $.ajax({
      url: 'api/user/avatar',
      type: 'GET',
      data: {
        userName: userName
      },
      success: function (response) {

        if (response.success) {

          if (response.avatar) {
            $(list).find('div[data-username="' + userName + '"] .avatar img').attr('src', response.avatar).show();
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
            ModuleFollowing.loadAvatar(list, this.userName);
            ModuleFollowing.loadProfile(list, this.userName);
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

          console.log(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  },
}