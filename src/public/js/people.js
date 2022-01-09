$(document).ready(function() {

  // Init modules
  ModuleMenu.init('people');
  ModuleFollowing.load('#moduleFollowing', true);
  ModuleFeed.load('#moduleFeed', 1, true);

  // Event listeners
  $(document).on('ModuleFollowing.unFollow:success', function(/*event, response*/) {
    ModuleMenu.init('people');
    ModuleFollowing.load('#moduleFollowing', true);
    ModuleFeed.load('#moduleFeed', 1, true);
  });
});