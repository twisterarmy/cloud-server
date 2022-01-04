$(document).ready(function() {

  // Init modules
  ModuleMenu.init('/');
  ModuleFeed.load('#moduleFeed', true);
  //ModuleUsers.load('#moduleUsers', true);

  // Event listeners
  $(document).on('ModulePost.add:success', function(/*event, response*/) {
    ModuleFeed.load('#moduleFeed', true);
  });

});