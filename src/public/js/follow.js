$(document).ready(function() {

  // Init modules
  ModuleMenu.init('follow');
  ModuleFollowing.load('#moduleFollowing', true);
  ModuleFeed.load('#moduleFeed', true);

  // Event listeners
  $(document).on('ModuleFollowing.unFollow:success', function(/*event, response*/) {
    ModuleMenu.init('follow');
    ModuleFollowing.load('#moduleFollowing', true);
    ModuleFeed.load('#moduleFeed', true);
  });
});