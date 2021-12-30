var Home = {
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
                  reTwist.userName
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
              'class': 'item'
            }).append(
              $('<div/>', {
                'class': 'avatar'
              }).append(
                $('<img/>', {
                  'src': '/api/image?hash=' + userName,
                  'alt': '',
                })
              )
            ).append(
              $('<div/>', {
                'class': 'message'
              }).append(
                $('<div/>', {
                  'class': 'info'
                }).append(
                  userName
                ).append(
                  $('<span/>', {
                    'class': 'time'
                  }).append(
                    time
                  )
                )
              ).append(rt).append(message)
            )
          );
        }
      }
    }
  },
  post: {
    add: function(feed, input) {
      $.ajax({
        url: 'api/post/add',
        type: 'POST',
        data: {
          message: $(input).val()
        },
        success: function (response) {

          if (response.success) {

            $(input).val('');

            Home.post.get(feed, true);

          } else {
            alert(response.message);
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
      });
    },
    get: function(feed, reFresh) {
      $.ajax({
        url: 'api/post/get',
        type: 'POST',
        data: {},
        success: function (response) {
          if (response.success) {

            if (reFresh) {
              $(feed).html('');
            }

            $(response.posts).each(function() {
              Home.template.feed.item.append(feed, this.userName, this.time, this.message, this.reTwist);
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
}

$(document).ready(function() {
  Home.post.get('#feed', true);
});