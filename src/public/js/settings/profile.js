var SettingsProfile = {
  init: function() {

    // Load avatar
    $.ajax({
      url: 'api/user/avatar',
      type: 'GET',
      data: {
        nocache: true
      },
      success: function (response) {

        if (response.success) {

          $('#settingsProfileAvatarImage').attr('src', response.avatar ? response.avatar : '').show();

          setTimeout(function(){
            $('#settingsProfileActionsSuccess').css('opacity', 0);
          }, 5000);

        } else {

          console.log(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });

    // Load profile
    $.ajax({
      url: 'api/user/profile',
      type: 'GET',
      data: {
        nocache: true
      },
      success: function (response) {

        if (response.success) {

          $('#settingsProfileFullName').val(response.profile.fullName ? response.profile.fullName : '');
          $('#settingsProfileLocation').val(response.profile.location ? response.profile.location : '');
          $('#settingsProfileURL').val(response.profile.url ? response.profile.url : '');
          $('#settingsProfileBitMessage').val(response.profile.bitMessage ? response.profile.bitMessage : '');
          $('#settingsProfileTOX').val(response.profile.tox ? response.profile.tox : '');
          $('#settingsProfileBio').val(response.profile.bio ? response.profile.bio : '');

        } else {

          console.log(response.message);

        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  }
}

$(document).ready(function() {

  // Init modules
  ModuleMenu.init('settings');
  ModuleSettings.init('settings');

  // Init page
  SettingsProfile.init();

});